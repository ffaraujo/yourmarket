<?php

class Application_Model_ProductMapper {

    protected $prefix = Application_Model_Product::PREFIX;
    protected $_dbTable;
    private $realDelMode = false;
    private static $_imgSizes = array();

    public function Application_Model_ProductMapper() {
        $systemMapper = new Application_Model_SystemMapper();
        $delMode = $systemMapper->findConfig('delete-mode');
        $this->realDelMode = $delMode->getValue();
    }

    /**
     * Sets the DB Table for this Mapper
     * 
     * @param String $dbTable String of matching DB Table class
     * @return Application_Model_ProductMapper
     */
    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * Gets the DB Table for this Mapper
     * 
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Product');
        }
        return $this->_dbTable;
    }

    /**
     * Save or Update the object in DB
     * 
     * @param Application_Model_Product $object An object to save
     * @return Int $id Or NULL if get error
     */
    public function save(Application_Model_Product $object) {
        $data = array(
            $this->prefix . 'id' => $object->getId(),
            $this->prefix . 'name' => $object->getName(),
            $this->prefix . 'user' => $object->getUser(),
            $this->prefix . 'create_date' => $object->getCreateDate(),
            $this->prefix . 'active' => $object->getActive(),
        );

        $id = $object->getId();
        if (empty($id)) {
            unset($data['id']);
            $id = $this->getDbTable()->insert($data);
            return $id;
        } else {
            $this->getDbTable()->update($data, array($this->prefix . 'id = ?' => $id));
            return $id;
        }
    }

    /**
     * Get data in DB and converts to object
     * 
     * @param Int $id ID to search
     * @param Boolean $onlyActive define if searches only when status active > 0 or not
     * @param String $where define a where clausule to more specific searches
     * @return Application_Model_Product
     */
    public function find($id, $onlyActive = false, $where = false) {
        $object = new Application_Model_Product();
        if (!$where && ($id != 0)) {
            $result = $this->getDbTable()->find($id);
            if (0 == count($result))
                return NULL;

            $row = $result->current();
        } else {
            $row = $this->getDbTable()->fetchRow($where);
            if (empty($row))
                return NULL;
        }

        $status = ($onlyActive) ? 1 : 0;
        if ($row->prd_active < $status)
            return NULL;

        $object->setId($row->prd_id);
        $object->setName($row->prd_name);
        $object->setUser($row->prd_user);
        $object->setCreateDate($row->prd_create_date);
        $object->setActive($row->prd_active);
        return $object;
    }

    /**
     * Get data in DB and converts to a set of objects
     * 
     * @param Boolean $onlyActive define if searches only when status active > 0 or not
     * @param String $where define a where clausule to more specific searches
     * @param String|Array $order define order to get data
     * @param Int $limit define max number of objects retrieved
     * @return Array
     */
    public function fetchAll($onlyActive = false, $where = false, $order = false, $limit = false) {
        $select = $this->getDbTable()->select();
        if ($onlyActive) {
            $select->where($this->prefix . 'active >= 1');
        } else {
            $select->where($this->prefix . 'active >= 0');
        }
        if ($where) {
            $select->where($where);
        }
        if ($order) {
            $select->order($order);
        } else {
            $select->order($this->prefix . 'id ASC');
        }
        if ($limit) {
            $select->limit($limit);
        }
        $resultSet = $this->getDbTable()->fetchAll($select);

        $objects = array();
        foreach ($resultSet as $row) {
            $object = new Application_Model_Product();
            $object->setId($row->prd_id);
            $object->setName($row->prd_name);
            $object->setUser($row->prd_user);
            $object->setCreateDate($row->prd_create_date);
            $object->setActive($row->prd_active);

            $objects[] = $object;
        }
        return $objects;
    }

    /**
     * Get data in DB and converts to a set of objects with pagination
     * 
     * @param Int $itemPerPage define how many items by page
     * @param Int $currentPage define in which page you are
     * @param Boolean $onlyActive define if searches only when status active > 0 or not
     * @param Array $options may define where, order and limit clauses
     * @return Zend_Paginator
     */
    public function fetchAllPages($itemPerPage, $currentPage, $onlyActive = false, $options = array()) {
        if (empty($options)) {
            $objectSet = $this->fetchAll($onlyActive);
        } else {
            $objectSet = $this->fetchAll($onlyActive, $options['where'], $options['order'], $options['limit']);
        }

        $pages = Zend_Paginator::factory($objectSet);
        $pages->setItemCountPerPage($itemPerPage);
        $pages->setCurrentPageNumber($currentPage);

        return $pages;
    }

    /**
     * Delete a register by ID
     * 
     * @param Int $id Object ID
     * @return NULL
     */
    public function delete($id) {
        if (!empty($id)) {
            //$this->deleteImages($id);
            if ($this->realDelMode) {
                $this->getDbTable()->delete($this->prefix . 'id = ' . $id);
            } else {
                $this->getDbTable()->update(array($this->prefix . 'active' => -1), $this->prefix . 'id = ' . $id);
            }
        }
    }

    /**
     * Delete images from a register
     * 
     * @param Int $id Object ID
     * @return boolean
     */
    public function deleteImages($id) {
        if (!empty($id)) {
            $object = $this->find($id);
            if (!empty($object)) {
                $file = $object->getImage();
                $path = PATH_UPLOAD . 'users/';
                if (file_exists($path . $file)) {
                    unlink($path . $file);

                    if (!empty(self::$_imgSizes)) {
                        foreach (self::$_imgSizes as $size) {
                            if ($size['w'] || $size['h']) {
                                $path_thumb = $path . '/' . $size['w'] . '_' . $size['h'] . '/';
                                if (file_exists($path_thumb . $file)) {
                                    unlink($path_thumb . $file);
                                }
                            }

                            if (array_key_exists('thumbs', $size)) {
                                foreach ($size['thumbs'] as $size_t) {
                                    $path_t = $path_thumb . '/' . $size_t['w'] . '_' . $size_t['h'] . '/';
                                    if (file_exists($path_t . $file)) {
                                        unlink($path_t . $file);
                                    }
                                }
                            }
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * Return Image Sizes for this class
     * 
     * @return Array
     */
    public static function getImageSizes() {
        return self::$_imgSizes;
    }

}

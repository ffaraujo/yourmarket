<?php

class Application_Model_ShoppingMapper {

    protected $prefix = Application_Model_Shopping::PREFIX;
    protected $_dbTable;
    private $realDelMode = false;
    private static $_imgSizes = array();

    public function Application_Model_ShoppingMapper() {
        $systemMapper = new Application_Model_SystemMapper();
        $delMode = $systemMapper->findConfig('delete-mode');
        $this->realDelMode = $delMode->getValue();
    }

    /**
     * Sets the DB Table for this Mapper
     * 
     * @param String $dbTable String of matching DB Table class
     * @return Application_Model_ShoppingMapper
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
            $this->setDbTable('Application_Model_DbTable_Shopping');
        }
        return $this->_dbTable;
    }

    /**
     * Save or Update the object in DB
     * 
     * @param Application_Model_Shopping $object An object to save
     * @return Int $id Or NULL if get error
     */
    public function save(Application_Model_Shopping $object) {
        $data = array(
            $this->prefix . 'id' => $object->getId(),
            $this->prefix . 'date' => $object->getDate(),
            $this->prefix . 'value' => $object->getValue(),
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
     * Save or Update an item in DB
     * 
     * @param Array $values An array with data to save
     * @return Int $id Or NULL if get error
     */
    public function saveItem(array $values) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $result = $db->insert('products_has_shopping', $values);

        return $result;
    }

    /**
     * Get data in DB and converts to object
     * 
     * @param Int $id ID to search
     * @param Boolean $onlyActive define if searches only when status active > 0 or not
     * @param String $where define a where clausule to more specific searches
     * @return Application_Model_Shopping
     */
    public function find($id, $onlyActive = false, $where = false) {
        $object = new Application_Model_Shopping();
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
        if ($row->shp_active < $status)
            return NULL;

        $object->setId($row->shp_id);
        $object->setDate($row->shp_date);
        $object->setValue($row->shp_value);
        $object->setUser($row->shp_user);
        $object->setCreateDate($row->shp_create_date);
        $object->setActive($row->shp_active);
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
            $object = new Application_Model_Shopping();
            $object->setId($row->shp_id);
            $object->setDate($row->shp_date);
            $object->setValue($row->shp_value);
            $object->setUser($row->shp_user);
            $object->setCreateDate($row->shp_create_date);
            $object->setActive($row->shp_active);

            $objects[] = $object;
        }
        return $objects;
    }

    /**
     * Get data in DB and converts to a set of objects
     * 
     * @param Int $shId shopping Id to search
     * @param String|Array $order define order to get data
     * @return Array
     */
    public function fetchAllItens($shId = 0) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from('products_has_shopping')
                ->join('products', 'prd_id = phs_product_id')
                ->where('phs_shopping_id = ?', $shId);

        $select->order('prd_name ASC');
        $resultSet = $db->fetchAll($select);

        return $resultSet;
    }

    /**
     * Get data in DB and converts to a set of objects
     * 
     * @param Int $prId product Id to search
     * @param String|Array $order define order to get data
     * @param String $where define where clause to get data
     * @return Array
     */
    public function fetchItemsById($prId, $minDate, $maxDate, $order) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from('products_has_shopping')
                ->join('shopping', $this->prefix . 'id = phs_shopping_id', $this->prefix . 'date')
                ->join('products', 'prd_id = phs_product_id', 'prd_name')
                ->where('phs_product_id = ?', $prId);

        if (!empty($minDate) && !empty($maxDate)) {
            $select->where($this->prefix . 'date >= ?', $minDate)
                    ->where($this->prefix . 'date <= ?', $maxDate);
        }

        if ($order) {
            $select->order($order);
        } else {
            $select->order($this->prefix . 'date ASC');
        }
        $resultSet = $db->fetchAll($select);

        return $resultSet;
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
        if ((!empty($id)) && ($id != 1)) {
            //$this->deleteImages($id);
            if ($this->realDelMode) {
                $this->getDbTable()->delete($this->prefix . 'id = ' . $id);
            } else {
                $this->getDbTable()->update(array($this->prefix . 'active' => -1), $this->prefix . 'id = ' . $id);
            }
        }
    }

    /**
     * Delete a register by ID
     * 
     * @param Int $sid Shopping ID
     * @param Int $pid Product ID
     * @return NULL
     */
    public function deleteItem($sid, $pid) {
        if ((!empty($sid)) && (!empty($pid))) {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->delete('products_has_shopping', "phs_product_id = $pid AND phs_shopping_id = $sid");
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

    public function getNumberOfItens($shId) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from('products_has_shopping', array('phs_shopping_id', "phs_quantity as qtd"))
                ->where('phs_shopping_id = ?', $shId);
        $resources = $db->fetchAll($select);
        if (!empty($resources)) {
            $sum = 0;
            foreach ($resources as $reg) {
                if (($reg['qtd'] < 1) || (substr($reg['qtd'], strpos($reg['qtd'], '.'), 3) != '000'))
                    $sum += 1;
                else
                    $sum += $reg['qtd'];
            }
            return $sum;
        } else {
            return 0;
        }
    }

    public function getTotal($shId) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from('products_has_shopping', array('phs_shopping_id', "phs_to_value as total"))
                ->where('phs_shopping_id = ?', $shId);
        $resources = $db->fetchAll($select);
        if (!empty($resources)) {
            $sum = 0;
            foreach ($resources as $reg) {
                $sum += $reg['total'];
            }
            return $sum;
        } else {
            return 0;
        }
    }

}

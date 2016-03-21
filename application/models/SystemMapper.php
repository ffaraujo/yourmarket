<?php

class Application_Model_SystemMapper {

    protected $prefix = Application_Model_System::PREFIX;
    protected $_dbTable;
    private $realDelMode = false;

    /**
     * Sets the DB Table for this Mapper
     * 
     * @param String $dbTable String of matching DB Table class
     * @return Application_Model_SystemMapper
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
            $this->setDbTable('Application_Model_DbTable_System');
        }
        return $this->_dbTable;
    }

    /**
     * Save or Update the object in DB
     * 
     * @param Application_Model_System $object An object to save
     * @return Int $id Or NULL if get error
     */
    public function save(Application_Model_System $object) {
        $data = array(
            $this->prefix . 'id' => $object->getId(),
            $this->prefix . 'name' => $object->getName(),
            $this->prefix . 'value' => $object->getValue(),
            $this->prefix . 'desc' => $object->getDesc(),
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
     * @return Application_Model_System
     */
    public function find($id, $onlyActive = false, $where = false) {
        $object = new Application_Model_System();

        if (!$where && ($id != 0)) {
            $result = $this->getDbTable()->find($id);
            if (0 == count($result)) {
                return NULL;
            }

            $row = $result->current();
        } else {
            $row = $this->getDbTable()->fetchRow($where);
        }

        $status = ($onlyActive) ? 1 : 0;
        if ($row->sys_active < $status)
            return NULL;

        $object->setId($row->sys_id);
        $object->setName($row->sys_name);
        $object->setDesc($row->sys_desc);
        $object->setValue($row->sys_value);
        $object->setActive($row->sys_active);
        return $object;
    }

    public function findConfig($config) {
        $object = new Application_Model_System();

        $select = $this->getDbTable()->select()->where($this->prefix . "name LIKE ?", $config);
        $row = $this->getDbTable()->fetchRow($select);

        if (empty($row)) {
            return NULL;
        }

        $object->setId($row->sys_id);
        $object->setName($row->sys_name);
        $object->setDesc($row->sys_desc);
        $object->setValue($row->sys_value);
        $object->setActive($row->sys_active);
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
            $object = new Application_Model_System();

            $object->setId($row->sys_id);
            $object->setName($row->sys_name);
            $object->setDesc($row->sys_desc);
            $object->setValue($row->sys_value);
            $object->setActive($row->sys_active);

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
            if ($this->realDelMode) {
                $this->getDbTable()->delete($this->prefix . 'id = ' . $id);
            } else {
                $this->getDbTable()->update(array($this->prefix . 'active' => -1), $this->prefix . 'id = ' . $id);
            }
        }
    }

}

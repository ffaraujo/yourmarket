<?php

class Application_Model_AuditorLogMapper {

    protected $prefix = Application_Model_AuditorLog::PREFIX;
    protected $_dbTable;
    private $realDelMode = false;

    /**
     * Sets the DB Table for this Mapper
     * 
     * @param String $dbTable String of matching DB Table class
     * @return Application_Model_AuditorLogMapper
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
            $this->setDbTable('Application_Model_DbTable_Auditorship');
        }
        return $this->_dbTable;
    }

    /**
     * Save or Update the object in DB
     * 
     * @param Application_Model_AuditorLog $object An object to save
     * @return Int $id Or NULL if get error
     */
    public function save(Application_Model_AuditorLog $object) {
        $data = array(
            $this->prefix . 'id' => $object->getId(),
            $this->prefix . 'user' => $object->getUser(),
            $this->prefix . 'action' => $object->getAction(),
            $this->prefix . 'controller' => $object->getController(),
            $this->prefix . 'object_id' => $object->getObjectId(),
            $this->prefix . 'insert_date' => $object->getInsertDate(),
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
     * @param String $where define a where clausule to more specific searches
     * @return Application_Model_AuditorLog
     */
    public function find($id, $where = false) {
        $object = new Application_Model_AuditorLog();

        if (!$where && ($id != 0)) {
            $result = $this->getDbTable()->find($id);
            if (0 == count($result)) {
                return NULL;
            }

            $row = $result->current();
        } else {
            $row = $this->getDbTable()->fetchRow($where);
        }

        $object->setId($row->aud_id);
        $object->setUser($row->aud_user);
        $object->setAction($row->aud_action);
        $object->setController($row->aud_controller);
        $object->setObjectId($row->aud_object_id);
        $object->setInsertDate($row->aud_insert_date);
        return $object;
    }

    /**
     * Get data in DB and converts to a set of objects
     * 
     * @param String $where define a where clausule to more specific searches
     * @param String|Array $order define order to get data
     * @param Int $limit define max number of objects retrieved
     * @return Array
     */
    public function fetchAll($where = false, $order = false, $limit = false) {
        $select = $this->getDbTable()->select();
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
            $object = new Application_Model_AuditorLog();

            $object->setId($row->aud_id);
            $object->setUser($row->aud_user);
            $object->setAction($row->aud_action);
            $object->setController($row->aud_controller);
            $object->setObjectId($row->aud_object_id);
            $object->setInsertDate($row->aud_insert_date);

            $objects[] = $object;
        }
        return $objects;
    }

    /**
     * Get data in DB and converts to a set of objects with pagination
     * 
     * @param Int $itemPerPage define how many items by page
     * @param Int $currentPage define in which page you are
     * @param Array $options may define where, order and limit clauses
     * @return Zend_Paginator
     */
    public function fetchAllPages($itemPerPage, $currentPage, $options = array()) {
        if (empty($options)) {
            $objectSet = $this->fetchAll();
        } else {
            $objectSet = $this->fetchAll($options['where'], $options['order'], $options['limit']);
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
            $this->getDbTable()->delete($this->prefix . 'id = ' . $id);
        }
    }

}

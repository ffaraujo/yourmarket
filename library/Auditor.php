<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auditoria
 *
 * @author fabio.araujo
 */
class Auditor {

    private $db;
    private $mode;

    public function saveLog($user, $action, $controller, $object = NULL) {
        $sysMapper = new Application_Model_SystemMapper();
        $aud_config = $sysMapper->findConfig('log-mode');
        $this->mode = $aud_config->getValue();

        switch ($this->mode) {
            case 'db':
                $this->saveLogDb($user, $action, $controller, $object);
                break;
            case 'text':
            default:
                $this->saveLogText($user, $action, $controller, $object);
                break;
        }
    }

    public function saveLogDb($user, $action, $controller, $object) {
        $db = $this->db = Zend_Db_Table::getDefaultAdapter();

        $db->beginTransaction();
        try {
            $date = Zend_Date::now();
            $data = array(
                'aud_user' => $user,
                'aud_action' => $action,
                'aud_controller' => $controller,
                'aud_object_id' => $object,
                'aud_insert_date' => $date->get('yyyy-MM-dd HH:mm:ss'),
            );
            $db->insert('auditorship', $data);
            $db->commit();
            return true;
        } catch (Exception $exc) {
            $db->rollBack();
            return false;
        }
    }

    public function saveLogText($user, $action, $controller, $object) {
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/auditorship.log');
        $logger = new Zend_Log($writer);
        $logger->setTimestampFormat('Y-m-d H:i:s');

        $message = "u:$user;a:$action;l:$controller;o:$object;";
        $logger->log($message, Zend_Log::DEBUG);
    }

}

?>

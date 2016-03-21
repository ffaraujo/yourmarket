<?php

class AuditorshipController extends GeneralController {

    public function init() {
        parent::init();
        $this->_iniUrl = '/system';
        $this->setLayout('layout-menu');

        if (!$this->_logon->getLogged()) {
            $this->_redirect('/logon');
        } else {
            $this->_logon->refreshExpirationTime();
        }

        $this->view->user = $this->_logon->getUser();
        $this->view->logged = $this->_logon->getLogged();
    }

    public function indexAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'R')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), '/');
        }

        $sysMapper = new Application_Model_SystemMapper();
        $aud_obj = $sysMapper->findConfig('log-mode');
        $aud_config = $aud_obj->getValue();

        if ($aud_config == 'db') {
            $mapper = new Application_Model_AuditorLogMapper();
            if ($this->_hasParam('order')) {
                $order = $this->defineListOrder();
            } else {
                $order = Application_Model_AuditorLog::PREFIX . 'insert_date DESC';
            }
            $this->view->d_order = $this->_getParam('d', 'desc');
            $this->view->log = $mapper->fetchAllPages(10, $this->_getParam('pag', 1), array('order' => $order, 'limit' => false, 'where' => false));
        } else {
            $handle = fopen(APPLICATION_PATH . '/../logs/auditorship.log', 'r');
            $log = "";
            while (!feof($handle)) {
                $buffer = fgets($handle, 8192);
                $log .= $buffer . '<br />';
                unset($buffer);
            }
            $this->view->log = $log;
        }

        $this->view->aud_config = $aud_config;
    }

    private function defineListOrder() {
        switch ($this->_getParam('order')) {
            case 'id':
                $order = Application_Model_AuditorLog::PREFIX . 'id ';
                break;
            case 'user':
                $order = Application_Model_AuditorLog::PREFIX . 'user ';
                break;
            case 'controller':
                $order = Application_Model_AuditorLog::PREFIX . 'controller ';
                break;
            case 'action':
                $order = Application_Model_AuditorLog::PREFIX . 'action ';
                break;
            case 'date':
            default:
                $order = Application_Model_AuditorLog::PREFIX . 'insert_date ';
                break;
        }
        if ($this->_hasParam('d')) {
            switch ($this->_getParam('d')) {
                case 'desc':
                    $order .= 'DESC';
                    break;
                case 'asc':
                default:
                    $order .= 'ASC';
                    break;
            }
        } else {
            $order .= 'ASC';
        }
        return $order;
    }

}

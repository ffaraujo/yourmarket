<?php

class ShoppingItensController extends GeneralController {

    public function init() {
        $this->_imgSizes = Application_Model_ShoppingMapper::getImageSizes();

        parent::init();
        if ($this->_hasParam('sid')) {
            $this->_iniUrl = '/shopping-itens/index/sid/' . $this->_getParam('sid');
        } else {
            $this->_iniUrl = '/shopping';
        }
        $this->setLayout('layout');

        if (!$this->_logon->getLogged()) {
            $this->_redirect('/logon');
        } else {
            $this->_logon->refreshExpirationTime();
        }

        $this->view->user = $this->_logon->getUser();
        $this->view->logged = $this->_logon->getLogged();

        $this->_auditor = new Auditor();
    }

    public function indexAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'R')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), '/');
        }

        $shoppingMapper = new Application_Model_ShoppingMapper();
        $select = "phs_shopping_id = " . $this->_getParam('sid');
        /*if ($this->_hasParam('order')) {
            $order = $this->defineListOrder();
        } else {
            $order = Application_Model_Shopping::PREFIX . 'date DESC';
        }
        $this->view->d_order = $this->_getParam('d', 'asc');*/
        $this->view->shopping = $shoppingMapper->fetchAllItens(false, $select, $order);
    }

    public function insertAction() {
        // action body
    }

    public function activateAction() {
        // action body
    }

    public function deleteAction() {
        // action body
    }

}

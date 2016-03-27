<?php

class ShoppingController extends GeneralController {

    public function init() {
        $this->_imgSizes = Application_Model_ShoppingMapper::getImageSizes();

        parent::init();
        $this->_iniUrl = '/shopping';
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
        $select = NULL;
        if ($this->_hasParam('order')) {
            $order = $this->defineListOrder();
        } else {
            $order = Application_Model_Shopping::PREFIX . 'date DESC';
        }
        $this->view->d_order = $this->_getParam('d', 'asc');
        $this->view->shopping = $shoppingMapper->fetchAll(false, $select, $order);
        $this->view->mapper = $shoppingMapper;
    }

    public function insertAction() {
        if ($this->_hasParam('id')) {
            if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'U')) {
                $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
            }
        } else {
            if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'C')) {
                $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
            }
        }

        $request = $this->getRequest();
        $form = new Application_Form_Shopping();
        $mapper = new Application_Model_ShoppingMapper();

        if ($this->_hasParam('id')) {
            /* @var $db_object Application_Model_Shopping */
            $db_object = $mapper->find($this->_getParam('id'));
            if (!empty($db_object)) {
                $db_object->setFormDate();
                $form = new Application_Form_Shopping(NULL, true, $db_object->getId());
                $form->populate($db_object->toArray());
            }
        }

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $date = Zend_Date::now();
                $shopping = new Application_Model_Shopping($form->getValues());
                $id = $shopping->getId();
                if (empty($id)) {
                    $shopping->setCreateDate($date->get('yyyy-MM-dd HH:mm:ss', 'pt_BR'));
                    $shopping->setUser($this->_logon->getUser()->getId());
                    $shopping->setActive(1);
                } else {
                    $shopping->setCreateDate($db_object->getCreateDate());
                    $shopping->setUser($db_object->getUser());
                    $shopping->setActive($db_object->getActive());
                }

                $shopping->setDbDate();
                $result_id = $mapper->save($shopping);

                $this->_auditor->saveLog($this->_logon->getUser()->getId(), $this->getRequest()->getActionName(), $this->getRequest()->getControllerName(), $result_id);
                $this->addFlashMessage(array('Dados cadastrados com sucesso.', SUCCESS), $this->_iniUrl);
            }
        }

        $this->view->form = $form;
    }

    public function activateAction() {
        // action body
    }

    public function deleteAction() {
        // action body
    }

}

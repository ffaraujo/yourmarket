<?php

class ShoppingItensController extends GeneralController {

    public function init() {
        $this->_imgSizes = Application_Model_ShoppingMapper::getImageSizes();

        parent::init();
        if ($this->_hasParam('shopping')) {
            $this->_iniUrl = '/shopping-itens/index/shopping/' . $this->_getParam('shopping');
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
        $shId = $this->_getParam('shopping');
        $this->view->itens = $shoppingMapper->fetchAllItens($shId);
    }

    public function insertAction() {
        /*if ($this->_hasParam('id')) {
            if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'U')) {
                $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
            }
        } else {
            if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'C')) {
                $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
            }
        }*/

        $request = $this->getRequest();
        $form = new Application_Form_ShoppingItem();
        $mapper = new Application_Model_ShoppingMapper();

        if ($this->_hasParam('id')) {
            /* @var $db_object Application_Model_Shopping */
            $db_object = $mapper->find($this->_getParam('id'));
            if (!empty($db_object)) {
                $db_object->setFormDate();
                $form = new Application_Form_ShoppingItem(NULL, true, $db_object->getId());
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

    public function deleteAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'D')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
        }

        $mapper = new Application_Model_ShoppingMapper();

        if ($this->_hasParam('shopping') && $this->_hasParam('product')) {
            $mapper->deleteItem($this->_getParam('shopping'), $this->_getParam('product'));
        }
        $this->_auditor->saveLog($this->_logon->getUser()->getId(), $this->getRequest()->getActionName(), $this->getRequest()->getControllerName(), $this->_getParam('shopping') . '---' . $this->_getParam('product'));
        $this->addFlashMessage(array('Registro excluído com sucesso.', SUCCESS), $this->_iniUrl);
    }

}

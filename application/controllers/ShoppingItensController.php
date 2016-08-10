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
        if(!$this->_hasParam('shopping')) {
            $this->addFlashMessage(array('Informe uma feira.', ERROR), $this->_iniUrl);
        }

        $shoppingMapper = new Application_Model_ShoppingMapper();
        $shId = $this->_getParam('shopping');
        $this->view->itens = $shoppingMapper->fetchAllItens($shId);
        $this->view->sid = $shId;
    }

    public function insertAction() {
        // @TODO DO AN EDIT FEATURE :/
        if ($this->_hasParam('id')) {
            if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'U')) {
                $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
            }
        } else {
            if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'C')) {
                $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
            }
        }
        if(!$this->_hasParam('shopping')) {
            $this->addFlashMessage(array('Informe uma feira.', ERROR), $this->_iniUrl);
        }

        $request = $this->getRequest();
        $form = new Application_Form_ShoppingItem();
        $mapper = new Application_Model_ShoppingMapper();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                /* @TODO se nao houver valor total, calcular automaticamente */
                $values = $form->getValues();
                $values['phs_shopping_id'] = $this->_getParam('shopping');

                $result_id = $mapper->saveItem($values);

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

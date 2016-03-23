<?php

class ProductController extends GeneralController {

    public function init() {
        $this->_imgSizes = Application_Model_ProductMapper::getImageSizes();

        parent::init();
        $this->_iniUrl = '/product';
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

        $productsMapper = new Application_Model_ProductMapper();
        $select = NULL;
        if ($this->_hasParam('order')) {
            $order = $this->defineListOrder();
        } else {
            $order = Application_Model_Product::PREFIX . 'name ASC';
        }
        $this->view->d_order = $this->_getParam('d', 'asc');
        $this->view->products = $productsMapper->fetchAll(false, $select, $order);
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
        $form = new Application_Form_Product();
        $mapper = new Application_Model_ProductMapper();

        if ($this->_hasParam('id')) {
            /* @var $db_object Application_Model_Product */
            $db_object = $mapper->find($this->_getParam('id'));
            if (!empty($db_object)) {
                $form = new Application_Form_Product(NULL, true, $db_object->getId());
                $form->populate($db_object->toArray());
            }
        }

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $date = Zend_Date::now();
                $product = new Application_Model_Product($form->getValues());
                $id = $product->getId();
                if (empty($id)) {
                    $product->setCreateDate($date->get('yyyy-MM-dd HH:mm:ss', 'pt_BR'));
                    $product->setUser($this->_logon->getUser()->getId());
                    $product->setActive(1);
                } else {
                    $product->setCreateDate($db_object->getCreateDate());
                    $product->setUser($db_object->getUser());
                    $product->setActive($db_object->getActive());
                }

                $result_id = $mapper->save($product);

                $this->_auditor->saveLog($this->_logon->getUser()->getId(), $this->getRequest()->getActionName(), $this->getRequest()->getControllerName(), $result_id);
                $this->addFlashMessage(array('Dados cadastrados com sucesso.', SUCCESS), $this->_iniUrl);
            }
        }

        $this->view->form = $form;
    }

    public function activateAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'A')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
        }

        $mapper = new Application_Model_ProductMapper();

        if ($this->_hasParam('id')) {
            $row = $mapper->find($this->_getParam('id'));
            if (!empty($row)) {
                if ($row->getId() != 1) {
                    $status = ($row->getActive()) ? 0 : 1;
                    $row->setActive($status);
                    $mapper->save($row);
                }
            }
        }
        $this->_auditor->saveLog($this->_logon->getUser()->getId(), $this->getRequest()->getActionName(), $this->getRequest()->getControllerName(), $row->getId());
        $this->addFlashMessage(array('Alterado com sucesso', SUCCESS), $this->_iniUrl);
    }

    public function deleteAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'D')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
        }

        $mapper = new Application_Model_ProductMapper();

        if ($this->_hasParam('id')) {
            $row = $mapper->find($this->_getParam('id'));
            if (!empty($row)) {
                $mapper->delete($row->getId());
            }
        }
        $this->_auditor->saveLog($this->_logon->getUser()->getId(), $this->getRequest()->getActionName(), $this->getRequest()->getControllerName(), $row->getId());
        $this->addFlashMessage(array('Registro excluído com sucesso.', SUCCESS), $this->_iniUrl);
    }

    private function defineListOrder() {
        switch ($this->_getParam('order')) {
            case 'id':
                $order = Application_Model_Product::PREFIX . 'id ';
                break;
            case 'name':
            default:
                $order = Application_Model_Product::PREFIX . 'name ';
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

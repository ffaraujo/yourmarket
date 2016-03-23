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
        // action body
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

}

<?php

class SystemController extends GeneralController {

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
        
        $this->_auditor = new Auditor();
    }

    public function indexAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'U')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), '/');
        }

        $form = new Application_Form_System();
        $request = $this->getRequest();
        $mapper = new Application_Model_SystemMapper();

        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                unset($data['csrf']);
                unset($data['OK']);
                foreach ($data as $key => $value) {
                    $config = $mapper->findConfig(str_replace('_', '-', $key));
                    $config->setValue($value);
                    $mapper->save($config);
                    unset($config);
                }
                $this->_auditor->saveLog($this->_logon->getUser()->getId(),'update',$this->getRequest()->getControllerName(),999);
                $this->addFlashMessageDirect(array('Configurações salvas com sucesso.', SUCCESS));
            }
        }

        $this->view->form = $form;
    }

}

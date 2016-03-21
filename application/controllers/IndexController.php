<?php

class IndexController extends GeneralController {

    public function init() {
        parent::init();
        $this->_iniUrl = '/';
    }

    public function indexAction() {
        //your code here...
    }

    public function logonAction() {
        if ($this->_logon->getLogged()) {
            $this->_redirect($this->_iniUrl);
        }

        $form = new Application_Form_Logon();
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $login = $this->_getParam(Application_Model_User::PREFIX . 'email');
                $pass = $this->_getParam(Application_Model_User::PREFIX . 'password');

                if (!$login or !$pass) {
                    $this->addFlashMessageDirect(array('Preencha os dados corretamente.', ERROR));
                } else {
                    $result = $this->_logon->doLogin($login, $pass);
                    if ($result) {
                        $this->_redirect($this->_iniUrl);
                    } else {
                        $this->addFlashMessageDirect(array('Usuário ou senha inválidos.', ERROR));
                    }
                }
            } else {
                $this->addFlashMessageDirect(array('Erro na validação.', ERROR));
            }
        }
        $this->view->form = $form;
    }

    public function logoutAction() {
        $this->_logon->doLogout();
        $this->_redirect('/logon');
    }

}

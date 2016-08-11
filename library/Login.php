<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of login
 *
 * @author fabio.araujo
 */
class Login {

    protected $_auth;
    protected $identity;
    protected $user = NULL;
    protected $logged = false;

    public function init() {
        // SETTING UP RESTRICTED AREA
        $this->_auth = Zend_Auth::getInstance();
        $this->_auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Restricted'));

        if ($this->_auth->hasIdentity()) {
            $this->identity = $this->_auth->getIdentity();

            /* ---- DEFINE HERE YOUR USERS MODULE ---- */
            $usersMapper = new Application_Model_UserMapper();
            $key = Application_Model_User::PREFIX . 'id';
            
            $this->user = $usersMapper->find($this->identity->$key);
            $this->logged = true;
            return true;
        } else {
            $this->logged = false;
            return false;
        }
    }

    public function doLogin($login, $pass) {
        $auth_session = new Zend_Session_Namespace('Zend_Auth_Restricted');
        $sysMapper = new Application_Model_SystemMapper();
        $exp_config = $sysMapper->findConfig('expiration-time');
        $auth_session->setExpirationSeconds($exp_config->getValue()); //default value: 1h, 3600 secs

        $authAdapter = new Zend_Auth_Adapter_DbTable(
                Zend_Db_Table::getDefaultAdapter(), //db adapter
                'users', // table
                'use_email', // login
                'use_password', //pass
                'SHA1(?)' //pass rule
        );

        // Set the input credential values (e.g., from a login form)
        $authAdapter->setIdentity($login)->setCredential($pass);

        // Perform the authentication query, saving the result
        $result = $this->_auth->authenticate($authAdapter);

        if ($result->isValid()) {
            $user = $authAdapter->getResultRowObject();

            $this->_auth->getStorage()->write($user);
            $user = $this->_auth->getIdentity();

            if ($user->use_active < 1) {
                $this->_auth->clearIdentity();
                return false;
            }
            $this->logged = true;
            $db = Zend_Db_Table::getDefaultAdapter();
            $data = Zend_Date::now();
            $db->update('users', array('use_last_access' => $data->get('yyyy-MM-dd HH:mm:ss')), 'use_id = ' . $user->use_id);
            return true;
        } else {
            return false;
        }
    }

    public function doLogout() {
        $this->_auth->clearIdentity();
        $this->logged = false;
        $this->identity = NULL;
        $this->user = NULL;
    }

    public function getUser() {
        return $this->user;
    }

    public function getLogged() {
        return $this->logged;
    }

    public function refreshExpirationTime() {
        //return false;
        $sysMapper = new Application_Model_SystemMapper();
        $exp_config = $sysMapper->findConfig('expiration-time');
        $auth_session = new Zend_Session_Namespace('Zend_Auth_Restricted');
        $auth_session->setExpirationSeconds($exp_config->getValue()); //default value: 1h, 3600 secs
        return true;
    }

    public function checksSuperUser() {
        if ($this->getLogged()) {
            if ($this->getUser()->getRole() < 3) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}

?>

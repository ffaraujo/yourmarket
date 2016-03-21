<?php

class AccessController extends GeneralController {

    public function init() {
        parent::init();
        $this->_iniUrl = '/access';
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

        $db = Zend_Db_Table::getDefaultAdapter();
        $result = array();
        $total = 0;
        $role = $this->_getParam('role', $this->_logon->getUser()->getRole());

        $select = $db->select()
                ->from('acl_access', array('acc_resource', 'COUNT(*) as qty'))
                ->group('acc_resource')
                ->where('acc_role = ?', $role);
        $resources = $db->fetchAll($select);

        foreach ($resources as $r) {
            $select = $db->select()
                    ->from('acl_access')
                    ->join('acl_resources', 'acc_resource = res_id', 'res_desc')
                    ->join('acl_roles', 'acc_role = rol_id', 'rol_desc')
                    ->join('acl_privileges', 'acc_privilege = pri_id', 'pri_desc')
                    ->where('acc_role = ?', $role)
                    ->where('acc_resource = ?', $r['acc_resource'])
                    ->order(array('acc_role ASC', 'acc_resource ASC', 'acc_privilege ASC'));
            $result[$r['acc_resource']]['rows'] = $db->fetchAll($select);
            $result[$r['acc_resource']]['qty'] = $r['qty'];
            $total += $r['qty'];
        }
        
        $userMapper = new Application_Model_UserMapper();
        $roles = $userMapper->getUserRoles($this->_logon->getUser()->getRole());
        
        $this->view->total = $total;
        $this->view->results = $result;
        $this->view->roles = $roles;
        $this->view->role = $role;
    }

    public function changeAccessAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'U')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), '/');
        }
        if (!$this->_hasParam('resource') || !$this->_hasParam('privilege') || !$this->_hasParam('role')) {
            $this->addFlashMessage(array('Os parâmetros informados são incorretos.', ERROR), '/');
        }
        if ($this->_getParam('role') < $this->_logon->getUser()->getRole()) {
            $this->addFlashMessage(array('Operação não permitida', ERROR), '/');
        }

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()->from('acl_access')
                ->where('acc_resource LIKE ?', $this->_getParam('resource'))
                ->where('acc_role LIKE ?', $this->_getParam('role'))
                ->where('acc_privilege LIKE ?', $this->_getParam('privilege'));
        $result = $db->fetchRow($select);
        if (empty($result)) {
            $this->addFlashMessage(array('Item não existe na lista de acesso.', ERROR), '/');
        }
        
        $value = ($result['acc_allow'] == 0) ? 1 : 0;
        $db->update('acl_access', array('acc_allow' => $value), "acc_resource LIKE '{$this->_getParam('resource')}' AND acc_role LIKE '{$this->_getParam('role')}' AND acc_privilege LIKE '{$this->_getParam('privilege')}'");
        $this->addFlashMessage(array('Permissão alterada com sucesso.', SUCCESS), $this->_iniUrl . '/index/role/' . $this->_getParam('role'));
    }

}

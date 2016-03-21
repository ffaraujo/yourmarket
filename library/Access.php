<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Access
 *
 * @author fabio.araujo
 */
class Access {

    protected $user;

    function __construct($user) {
        $this->user = $user;
    }

    public function isAllowed($resource, $privilege) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from('acl_access')
                ->join('acl_resources', 'res_id = acc_resource')
                ->where('res_desc = ?', $resource)
                ->where('acc_privilege = ?', $privilege)
                ->where('acc_role = ?', $this->user->getRole());
        $result = $db->fetchRow($select);
        
        if (empty($result)) {
            return false;
        } else {
            if ($result['acc_allow'] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

}

?>

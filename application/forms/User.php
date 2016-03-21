<?php

class Application_Form_User extends Zend_Form {

    private $pre = Application_Model_User::PREFIX;
    private $elementDecorators = array('ViewHelper', 'Errors', 'Label');
    private $path = PATH_UPLOAD;

    public function setPath() {
        $this->path .= 'users';
    }

    function __construct($options = NULL, $generateHash = true, $id = 0) {
        parent::__construct($options);
        if ($generateHash) {
            $hash = new Zend_Form_Element_Hash('csrf');
            $hash->setDecorators($this->elementDecorators);
            $hash->setIgnore(true);
            $this->addElement($hash);
        }
        $this->customInit($id);
    }

    public function customInit($edit_id) {
        $this->setPath();

        $this->setMethod('post')->setEnctype('multipart/form-data')->setAttrib('class', 'form-back');
        $this->setTranslator(Zend_Registry::get('translate'));

        $id = new Zend_Form_Element_Hidden($this->pre . 'id');
        $id->setRequired(false)->removeDecorator('Label')->removeDecorator('HtmlTag');

        $name = new Zend_Form_Element_Text($this->pre . 'name');
        $name->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(0, 80))
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                    'maxlength' => '80',
                ))
                ->setLabel('Nome:')
                ->setRequired(true);

        $email = new Zend_Form_Element_Text($this->pre . 'email');
        $email->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(5, 150))
                ->addValidator('EmailAddress')
                ->addValidator(new Zend_Validate_Db_NoRecordExists(
                        array('table' => 'users', 'field' => $this->pre . 'email', 'exclude' => array('field' => $this->pre . 'id', 'value' => $edit_id))
                        ), true)
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                    'maxlength' => '150',
                ))
                ->setLabel('Email:')
                ->setRequired(true);

        $pass = new Zend_Form_Element_Password($this->pre . 'password');
        $pass->addFilters(array('StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(6, 15))
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                    'maxlength' => '15',
                ))
                ->setLabel('Senha:')
                ->setRequired(false);

        $pass_c = new Zend_Form_Element_Password('pass_confirm');
        $pass_c->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                ))
                ->setLabel('Confirmar Senha:')
                ->setIgnore(true)
                ->setRequired(false);

        $userMapper = new Application_Model_UserMapper();
        $rolesRows = $userMapper->getUserRoles(2);
        $roles = array();
        foreach ($rolesRows as $role) {
            $roles[$role['rol_id']] = $role['rol_desc'];
        }
        $role = new Zend_Form_Element_Select($this->pre . 'role', array('multiOptions' => $roles));
        $role->setLabel('Tipo:')
                ->setAttrib('class', '')
                ->setDecorators($this->elementDecorators)
                ->setRequired(true);

        if (!file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }
        $image = new Zend_Form_Element_File($this->pre . 'image');
        $image->setLabel('Arquivo:')
                ->addValidator('Extension', false, 'jpg,png,gif')
                ->removeDecorator('HtmlTag')
                ->setDestination($this->path)
                ->setRequired(false);

        if ($image->getFileName()) {
            $arrayExt = explode(".", $image->getFileName());
            $ext = end($arrayExt);
            $newFileName = date('Ymdhisu') . mt_rand(1000, 9999) . "." . $ext;
            $image->addFilter("Rename", array("target" => $this->path . '/' . $newFileName, "overwrite" => true));
        }

        $submit = new Zend_Form_Element_Submit('OK');
        $submit->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => 'ok-button',
                ))
                ->setRequired(true)
                ->removeDecorator('Label');

        $this->addElements(array($id, $name, $email, $pass, $pass_c, $role, $image, $submit));
    }

}

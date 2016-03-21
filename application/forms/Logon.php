<?php

class Application_Form_Logon extends Zend_Form {

    private $pre = Application_Model_User::PREFIX;
    private $elementDecorators = array('ViewHelper', 'Errors', 'Label');

    function __construct($options = NULL, $generateHash = true) {
        parent::__construct($options);
        if ($generateHash) {
            $hash = new Zend_Form_Element_Hash('csrf');
            $hash->setDecorators($this->elementDecorators);
            $hash->setIgnore(true);
            $this->addElement($hash);
        }
    }

    public function init() {
        $this->setMethod('post')->setAttrib('class', 'form-logon');
        $this->setTranslator(Zend_Registry::get('translate'));

        $login = new Zend_Form_Element_Text($this->pre . 'email');
        $login->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(5, 150))
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
                ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('OK');
        $submit->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => 'ok-button',
                ))
                ->setRequired(true)
                ->removeDecorator('Label');

        $this->addElements(array($login, $pass, $submit));
    }

}

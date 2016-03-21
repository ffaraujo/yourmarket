<?php

class Application_Form_System extends Zend_Form {

    private $pre = Application_Model_User::PREFIX;
    private $elementDecorators = array('ViewHelper', 'Errors', 'Label', 'Description');

    function __construct($options = NULL, $generateHash = true) {
        parent::__construct($options);
        if ($generateHash) {
            $hash = new Zend_Form_Element_Hash('csrf');
            $hash->setDecorators($this->elementDecorators);
            $hash->setIgnore(true);
            $this->addElement($hash);
        }
        $this->customInit();
    }

    public function customInit() {
        $this->setMethod('post')->setEnctype('multipart/form-data')->setAttrib('class', 'form-back');
        $this->setTranslator(Zend_Registry::get('translate'));
        
        $systemMapper = new Application_Model_SystemMapper();
        $configs = $systemMapper->fetchAll(false);
        
        foreach ($configs as $config) {
            $element = new Zend_Form_Element_Text(str_replace('-', '_', $config->getName()));
            $element->addValidator('NotEmpty')
                    ->setDecorators($this->elementDecorators)
                    ->setAttribs(array(
                        'style' => '',
                        'class' => '',
                    ))
                    ->setLabel(str_replace('-', '_', $config->getName()) . ':')
                    ->setValue($config->getValue())
                    ->setDescription($config->getDesc())
                    ->setRequired(true);
            $this->addElement($element);
            unset($element);
        }
        
        $submit = new Zend_Form_Element_Submit('OK');
        $submit->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => 'ok-button',
                ))
                ->setRequired(true)
                ->removeDecorator('Label');
        $this->addElement($submit);
    }

}

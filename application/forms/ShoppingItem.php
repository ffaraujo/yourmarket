<?php

class Application_Form_ShoppingItem extends Zend_Form {

    private $pre = 'phs_';
    private $elementDecorators = array('ViewHelper', 'Errors', 'Label');
    private $path = PATH_UPLOAD;

    public function setPath() {
        $this->path .= 'shopping-itens';
    }

    function __construct($options = NULL, $generateHash = false, $id = 0) {
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

        $id = new Zend_Form_Element_Hidden($this->pre . 'shopping_id');
        $id->setRequired(false)->removeDecorator('Label')->removeDecorator('HtmlTag');

        $item = new Zend_Form_Element_Text($this->pre . 'product_id');
        $item->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(0, 80))
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                    'id' => $this->pre . 'product_id',
                    'maxlength' => '80',
                ))
                ->setLabel('Item:')
                ->setRequired(true);
        
        $qtde = new Zend_Form_Element_Text($this->pre . 'quantity');
        $qtde->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(0, 5))
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                    'maxlength' => '5',
                ))
                ->setLabel('Quantidade:')
                ->setRequired(true);

        $value = new Zend_Form_Element_Text($this->pre . 'un_value');
        $value->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(0, 7))
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                    'maxlength' => '7',
                ))
                ->setLabel('Valor Unitário:')
                ->setRequired(true);
        
        $total = new Zend_Form_Element_Text($this->pre . 'to_value');
        $total->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(0, 7))
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                    'maxlength' => '7',
                ))
                ->setLabel('Valor Total:')
                ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('OK');
        $submit->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => 'ok-button',
                ))
                ->setRequired(true)
                ->removeDecorator('Label');

        $this->addElements(array($id, $item, $qtde, $value, $total, $submit));
    }

}

<?php

class Application_Form_Product extends Zend_Form {

    private $pre = Application_Model_Product::PREFIX;
    private $elementDecorators = array('ViewHelper', 'Errors', 'Label');
    private $path = PATH_UPLOAD;

    public function setPath() {
        $this->path .= 'product';
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

      /*if (!file_exists($this->path)) {
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
        }*/

        $submit = new Zend_Form_Element_Submit('OK');
        $submit->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => 'ok-button',
                ))
                ->setRequired(true)
                ->removeDecorator('Label');

        $this->addElements(array($id, $name, $submit));
    }

}

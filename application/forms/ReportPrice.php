<?php

class Application_Form_ReportPrice extends Zend_Form {

    private $elementDecorators = array('ViewHelper', 'Errors', 'Label');

    function __construct($options = NULL, $generateHash = false) {
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

        $now = Zend_Date::now();

        // INICIAL DATE
        $iniDate = new Zend_Form_Element_Text('inidate');
        $iniDate->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(0, 10))
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                    'maxlength' => '10',
                ))
                ->setLabel('Data Inicial:')
                ->setValue('01/01/2016')
                ->setRequired(true);

        // FINAL DATE
        $endDate = new Zend_Form_Element_Text('enddate');
        $endDate->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(0, 10))
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                    'maxlength' => '10',
                ))
                ->setLabel('Data Final:')
                ->setValue($now->get('dd/MM/yyyy'))
                ->setRequired(true);

        // PRODUCTS ARRAY
        $productMapper = new Application_Model_ProductMapper();
        $productsRows = $productMapper->fetchAll(false, false, 'prd_name ASC');
        $products = array(
            0 => '--- ESCOLHER PRODUTO ---',
        );
        foreach ($productsRows as $product) {
            $products[$product->getId()] = $product->getName();
        }

        // ITEM 1
        $item1 = new Zend_Form_Element_Select('item1', array('multiOptions' => $products));
        $item1->setLabel('Item:')
                ->setAttrib('class', '')
                ->setDecorators($this->elementDecorators)
                ->setRequired(true);

        // ITEM 2
        $item2 = new Zend_Form_Element_Select('item2', array('multiOptions' => $products));
        $item2->setLabel('Item:')
                ->setAttrib('class', '')
                ->setDecorators($this->elementDecorators)
                ->setRequired(true);
        // ITEM 3
        $item3 = new Zend_Form_Element_Select('item3', array('multiOptions' => $products));
        $item3->setLabel('Item:')
                ->setAttrib('class', '')
                ->setDecorators($this->elementDecorators)
                ->setRequired(true);
        // ITEM 4
        $item4 = new Zend_Form_Element_Select('item4', array('multiOptions' => $products));
        $item4->setLabel('Item:')
                ->setAttrib('class', '')
                ->setDecorators($this->elementDecorators)
                ->setRequired(true);
        // ITEM 5
        $item5 = new Zend_Form_Element_Select('item5', array('multiOptions' => $products));
        $item5->setLabel('Item:')
                ->setAttrib('class', '')
                ->setDecorators($this->elementDecorators)
                ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('OK');
        $submit->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => 'ok-button',
                ))
                ->setRequired(true)
                ->removeDecorator('Label');

        $this->addElements(array($iniDate, $endDate, $item1, $item2, $item3, $item4, $item5, $submit));
    }

}

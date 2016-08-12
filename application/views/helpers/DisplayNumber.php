<?php

class Zend_View_Helper_DisplayNumber {

    public function DisplayNumber($number) {
        return number_format($number, 2, ',', '.');
    }

}

?>

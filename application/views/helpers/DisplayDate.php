<?php

class Zend_View_Helper_DisplayDate {

    public function DisplayDate($date, $partial, $type) {
        $dateObj = new Zend_Date($date, $partial);
        switch ($type) {
            case 'form':
                $date = $dateObj->get('dd/MM/yyyy');
                break;
            case 'db':
                $date = $dateObj->get('yyyy-MM-dd');
                break;
            default:
                break;
        }
        return $date;
    }

}

?>

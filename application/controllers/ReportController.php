<?php

class ReportController extends GeneralController {

    public function init() {
        parent::init();
        $this->_iniUrl = '/report';
        $this->setLayout('layout');

        if (!$this->_logon->getLogged()) {
            $this->_redirect('/logon');
        } else {
            $this->_logon->refreshExpirationTime();
        }

        $this->view->user = $this->_logon->getUser();
        $this->view->logged = $this->_logon->getLogged();
    }

    public function indexAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'R')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), '/');
        }

        $request = $this->getRequest();
        $formReportPrice = new Application_Form_ReportPrice();
        $formReportPrice->setAction($request->getBaseUrl() . '/report/price');

        // @TODO put datepicker in the right forms
        $this->view->formPrice = $formReportPrice;
    }

    public function priceAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'R')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), '/');
        }

        $form = new Application_Form_ReportPrice();
        $request = $this->getRequest();
        $shoppingMapper = new Application_Model_ShoppingMapper();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $data = $form->getValues();
                // @TODO implementar calculo da moda
                $tableData = array();
                $valueData = array();
                $statsData = array(
                    'avg' => array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0),
                    'min' => array(1 => 99999, 2 => 99999, 3 => 99999, 4 => 99999, 5 => 99999),
                    'max' => array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0),
                    'mod' => array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0),
                    'name' => array(1 => 'Item 1', 2 => 'Item 2', 3 => 'Item 3', 4 => 'Item 4', 5 => 'Item 5'),
                );

                for ($i = 1; $i <= 5; $i++) {
                    if (!empty($data['item' . $i])) {
                        $items = $shoppingMapper->fetchItemsById($data['item' . $i], $this->setDbDate($data['inidate']), $this->setDbDate($data['enddate']), 'shp_date ASC');
                        $d1 = $d2 = 0;
                        foreach ($items as $item) {
                            if ($item['phs_to_value'] < $statsData['min'][$i])
                                $statsData['min'][$i] = $item['phs_to_value'];
                            if ($item['phs_to_value'] > $statsData['max'][$i])
                                $statsData['max'][$i] = $item['phs_to_value'];
                            $d1 += $item['phs_to_value'];
                            $d2++;
                            $tableData[$item['shp_date']][$i] = number_format($item['phs_to_value'], 2, ',', '.');
                            $statsData['name'][$i] = $item['prd_name'];
                            $value = number_format($item['phs_to_value'], 2, ',', '.');
                            if (isset($valueData[$i][$value])) {
                                $valueData[$i][$value] += 1;
                            } else {
                                $valueData[$i][$value] = 1;
                            }
                        }
                        arsort($valueData[$i]);
                        $statsData['mod'][$i] = key($valueData[$i]);
                        $statsData['avg'][$i] = number_format(round($d1 / $d2, 2, PHP_ROUND_HALF_UP), 2, ',', '.');
                    }
                }
                ksort($tableData);
                $this->view->tableData = $tableData;
                $this->view->statsData = $statsData;
            }
        }
    }

    private function setDbDate($date) {
        $fdate = new Zend_Date($date, 'dd/MM/yyyy');
        return $fdate->get('yyyy-MM-dd');
    }

    private function setFormDate($date) {
        $fdate = new Zend_Date($date, 'yyyy-MM-dd');
        return $fdate->get('dd/MM/yyyy');
    }

}

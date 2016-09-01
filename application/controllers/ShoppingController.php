<?php

class ShoppingController extends GeneralController {

    public function init() {
        $this->_imgSizes = Application_Model_ShoppingMapper::getImageSizes();

        parent::init();
        $this->_iniUrl = '/shopping';
        $this->setLayout('layout');

        if (!$this->_logon->getLogged()) {
            $this->_redirect('/logon');
        } else {
            $this->_logon->refreshExpirationTime();
        }

        $this->view->user = $this->_logon->getUser();
        $this->view->logged = $this->_logon->getLogged();

        $this->_auditor = new Auditor();
    }

    public function indexAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'R')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), '/');
        }

        $shoppingMapper = new Application_Model_ShoppingMapper();
        $select = NULL;
        if ($this->_hasParam('order')) {
            $order = $this->defineListOrder();
        } else {
            $order = Application_Model_Shopping::PREFIX . 'date DESC';
        }
        $this->view->d_order = $this->_getParam('d', 'asc');
        $this->view->shopping = $shoppingMapper->fetchAll(false, $select, $order);
        $this->view->mapper = $shoppingMapper;
    }

    public function insertAction() {
        if ($this->_hasParam('id')) {
            if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'U')) {
                $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
            }
        } else {
            if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'C')) {
                $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
            }
        }

        $request = $this->getRequest();
        $form = new Application_Form_Shopping();
        $mapper = new Application_Model_ShoppingMapper();

        if ($this->_hasParam('id')) {
            /* @var $db_object Application_Model_Shopping */
            $db_object = $mapper->find($this->_getParam('id'));
            if (!empty($db_object)) {
                $db_object->setFormDate();
                $form = new Application_Form_Shopping(NULL, true, $db_object->getId());
                $form->populate($db_object->toArray());
            }
        }

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $date = Zend_Date::now();
                $shopping = new Application_Model_Shopping($form->getValues());
                $id = $shopping->getId();
                if (empty($id)) {
                    $shopping->setCreateDate($date->get('yyyy-MM-dd HH:mm:ss', 'pt_BR'));
                    $shopping->setUser($this->_logon->getUser()->getId());
                    $shopping->setActive(1);
                } else {
                    $shopping->setCreateDate($db_object->getCreateDate());
                    $shopping->setUser($db_object->getUser());
                    $shopping->setActive($db_object->getActive());
                }

                $shopping->setDbDate();
                $result_id = $mapper->save($shopping);

                $this->_auditor->saveLog($this->_logon->getUser()->getId(), $this->getRequest()->getActionName(), $this->getRequest()->getControllerName(), $result_id);
                $this->addFlashMessage(array('Dados cadastrados com sucesso.', SUCCESS), $this->_iniUrl);
            }
        }

        $this->view->form = $form;
    }

    public function activateAction() {
        // @TODO develop activate shopping feature
        // action body
    }

    public function deleteAction() {
        // @TODO develop delete shopping feature
        // action body
    }

    public function populateAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'P')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
        }

        try {
            echo '<pre>';
            $shoppingMapper = new Application_Model_ShoppingMapper();
            $productMapper = new Application_Model_ProductMapper();

            $now = date('d-m-Y H:i:s');

            $files = $this->readDir();
            $ff = 1; $debug = false; $word = 'MISTU';  $fDebug = 'C:\wamp\www\yourmarket\public/upload/cc-20160715.csv'; //29FF
            foreach ($files as $file) {
                if ($ff++ > 50)
                    break;
                if (($file != 'cc-20160715.csv') && $debug) //29FF
                    continue;
                
                set_time_limit(180);
                $file = PATH_UPLOAD . $file;
                //$file = PATH_UPLOAD . 'cc-20160315.csv';
                $nowZ = new Zend_Date($now, 'dd-MM-yyyy HH:mm:ss');
                echo $file . ' [' . $now . ']<br /><hr /><br />';
                echo '<table>
					<tr align="center">
					<td>NOME;</td><td>QTDE;</td><td>P. UNI;</td><td>P. TOTAL;</td>
					</tr>';
                $handle = fopen($file, 'r');

                unset($lines);
                $lines = array();
                while (!feof($handle)) {
                    $buffer = fgets($handle, 8192);
                    $lines[] = explode(';', $buffer);
                }

                $sum = 0.0;
                $i = 0;
                $shoppingDate = new Zend_Date($lines[0][0], 'dd/MM/yy');

                array_shift($lines);
                $row = $shoppingMapper->fetchAll(false, "shp_date LIKE '" . $shoppingDate->get('yyyy-MM-dd') . "'");
                if (empty($row[0])) {
                    $shopping = new Application_Model_Shopping();
                    $shopping->setDate($shoppingDate->get('yyyy-MM-dd'));
                    $shopping->setValue(0);
                    $shopping->setUser($this->_logon->getUser()->getId());
                    $shopping->setCreateDate($nowZ->get('yyyy-MM-dd HH:mm:ss'));
                    $shopping->setActive(1);
                    $shoppingId = $shoppingMapper->save($shopping);
                    $shopping->setId($shoppingId);
                } else {
                    $shopping = $row[0];
                    $shoppingId = $shopping->getId();
                }

                foreach ($lines as $line) {
                    if (empty($line[0]))
                        continue;

                    $results = $productMapper->fetchAll(true, "prd_name LIKE '" . substr(trim($line[0]), 0, 5) . "%'", array("prd_name ASC", "prd_id ASC"));
                    if (empty($results)) {
                        $product = new Application_Model_Product();
                        $product->setName(trim($line[0]));
                        $product->setCreateDate($nowZ->get('yyyy-MM-dd HH:mm:ss'));
                        $product->setActive(1);
                        $product->setUser($this->_logon->getUser()->getId());
                        $productMapper->save($product);
                        $rank = 100;
                    } else {
                        if (count($results) == 1) {
                            $product = $results[0];
                            $rank = $this->verifyName(trim($line[0]), $product->getName());
                        } else {
                            $rank = 0;
                            foreach ($results as $result) {
                                if ($result->getName() == trim($line[0])) {
                                    $rank = 100;
                                    $product = $result;
                                    break;
                                } else {
                                    $number = $this->verifyName(trim($line[0]), $result->getName());
                                    if ((substr(trim($line[0]), 0, 5) == $word) && $debug && ($file == $fDebug)) { //29FF
                                        echo 'trim line: ' . trim($line[0]).', result get name: '.$result->getName()."\n";
                                        echo 'number: ' . $number . ' - rank: ' . $rank . "\n";
                                    }
                                    if ($number > $rank) {
                                        $product = $result;
                                        $rank = $number;
                                    }
                                }
                            }
                            if (substr(trim($line[0]), 0, 5) == $word && $debug && ($file == $fDebug)) { //29FF
                                echo '$line:';
                                var_dump($line);
                                echo '$product:';
                                var_dump($product);
                                echo '$results:';
                                var_dump($results);
                            }
                        }
                    }

                    $itemData = array(
                        'phs_product_id' => $product->getId(),
                        'phs_shopping_id' => $shoppingId,
                        'phs_quantity' => str_replace(',', '.', $line[1]),
                        'phs_un_value' => str_replace(',', '.', $line[2]),
                        'phs_to_value' => str_replace(',', '.', $line[3]),
                    );
                    
                    if (substr(trim($line[0]), 0, 5) == $word && $debug && ($file == $fDebug)) { //29FF
                        echo '$itemData:';
                        var_dump($itemData);
                    }

                    $shoppingMapper->saveItem($itemData);
                    $sum += floatval(str_replace(',', '.', $line[3]));

                    if ($i % 2)
                        $cor = 'navy';
                    else
                        $cor = 'blue';
                    if($rank < 60)
                        $cor = 'goldenrod';
                    echo '<tr style="color:' . $cor . '; text-align:center; font-weight:bold;"><td colspan="3">' . trim($line[0]) . ';</td><td>(' . $rank . '%)</td><td>'.$product->getName().'</td></tr>';

                    unset($itemData);
                    $i++;
                }
                $shopping->setValue($sum);
                $shoppingMapper->save($shopping);

                echo '<tr style="color:green; text-align:center; font-weight:bold;"><td colspan="4">' . $i . ' registros cadastrados!</td></tr>';
                echo '</table>';
                fclose($handle);
            }
            echo '</pre>';
            exit('SUCESSO');
        } catch (Exception $e) {
            echo '<strong style="color:red;">message:' . $e->getMessage() . '</strong><br />';
            echo '<p style="color:red;">line:' . $e->getLine() . '</p><br />';
            echo '<p style="color:red;">code:' . $e->getCode() . '</p><br />';
            echo '<p style="color:red;">file:' . $e->getFile() . '</p><br />';
            echo '$product: ';
            var_dump($product);
            echo '$itemData: ';
            var_dump($itemData);
            echo '$line: ';
            var_dump($line);
            echo '$lines: ';
            var_dump($lines);
            echo '<p style="color:red;">' . $e->getTraceAsString() . '</p><br />';
            exit('ERRO');
        }
        exit('FIM');
    }

    private function verifyName($strA, $strB) {
        if ($strA == $strB) {
            $n = 100;
        } else {
            $n = 0;
            $lengA = strlen($strA);

            for ($i = 0; $i < $lengA; $i++) {
                if (substr($strA, $i, 1) == substr($strB, $i, 1))
                    $n = round((($i + 1) / $lengA) * 100, 0, PHP_ROUND_HALF_UP);
                else
                    break;
            }
        }
        return $n;
    }

    public function readDir() {
        $dir = PATH_UPLOAD . '';

        $ponteiro = opendir($dir);

        $itens = array();

        while ($nome_itens = readdir($ponteiro)) {
            if (strpos($nome_itens, '.csv') !== false)
                $itens[] = $nome_itens;
        }
        sort($itens);

        return $itens;
    }
    
    private function defineListOrder() {
        switch ($this->_getParam('order')) {
            case 'id':
                $order = Application_Model_Shopping::PREFIX . 'id ';
                break;
            case 'value':
                $order = Application_Model_Shopping::PREFIX . 'value ';
                break;
            case 'date':
            default:
                $order = Application_Model_Shopping::PREFIX . 'date ';
                break;
        }
        if ($this->_hasParam('d')) {
            switch ($this->_getParam('d')) {
                case 'desc':
                    $order .= 'DESC';
                    break;
                case 'asc':
                default:
                    $order .= 'ASC';
                    break;
            }
        } else {
            $order .= 'ASC';
        }
        return $order;
    }


}

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
        /*if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'P')) {
          $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
        }*/
        try {
            echo '<pre>';
            $shoppingMapper = new Application_Model_ShoppingMapper();
            $productMapper  = new Application_Model_ProductMapper();

            $now = date('d-m-Y H:i:s');
            $file = PATH_UPLOAD . 'cc-20160315.csv';
			$nowZ = new Zend_Date($now, 'dd-MM-yyyy HH:mm:ss');
            echo $file . ' [' . $now . ']<br /><hr /><br />';
            echo '<table>
                <tr align="center">
                <td>NOME;</td><td>QTDE;</td><td>P. UNI;</td><td>P. TOTAL;</td>
                </tr>';
            $handle = fopen($file, 'r');

            while (!feof($handle)) {
                $buffer = fgets($handle, 8192);
                $lines[] = explode(';', $buffer);
            }
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
            $db->beginTransaction();

            $i = 0;
            $k = 0;
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
                $shoppingMapper->save($shopping);
            } else {
                $shopping = $row[0];
            }

            foreach ($lines as $line) {
				$results = $productMapper->fetchAll(true,"prd_name LIKE '" . trim(substr($line[0],0,5)) . "%'",array("prd_name ASC","prd_id ASC"));
				if(empty($results)) {
					$product = new Application_Model_Product();
					$product->setName(trim($line[0]));
					$product->setCreateDate($nowZ->get('yyyy-MM-dd HH:mm:ss'));
					$product->setActive(1);
					$product->setUser($this->_logon->getUser()->getId());
					$productMapper->save($product);
				} else {
					if(count($results) == 1) {
						$product = $result;
					} else {
						$rank = 0;
						foreach($results as $result) {
							if($result->getName() == trim($line[0])) {
								$product = $result;
								break;
							} else {
								$number = verifyName(trim($line[0]),$result->getName());
								if ($number > $rank) {
									$product = $result;
									$rank = $number;
								}
							}
						}
					}
				}

				if ($i % 2)
					$cor = 'navy';
				else
					$cor = 'blue';
				echo '<tr style="color:' . $cor . '; text-align:center; font-weight:bold;"><td colspan="3">' . trim($line[0]) . ';</td><td>Criado!</td></tr>';
				$shoppingMapper->save($row);
				unset($row);
				$i++;
            }

            echo '<tr style="color:green; text-align:center; font-weight:bold;"><td colspan="4">' . $i . ' registros cadastrados!</td></tr>';
            echo '<tr style="color:red; text-align:center; font-weight:bold;"><td colspan="4">' . $k . ' registros nao-cadastrados!</td></tr>';
            echo '</table>';
            $db->commit();
            fclose($handle);
            //var_dump($lines);
            echo '</pre>';
            exit('SUCESSO');
        } catch (Exception $e) {
            $db->rollBack();
            echo '<strong style="color:red;">' . $e->getMessage() . '</strong><br />';
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
			
			// TEST FOR 20%
			$x = round($lengA / 4, 0, PHP_ROUND_HALF_UP);
			$aTest = substr(trim($strA),0,$x);
			$bTest = substr(trim($strB),0,$x);
			if ($aTest == $bTest)
				$n = 20;
			// TEST FOR 50%
			$x = round($lengA / 2, 0, PHP_ROUND_HALF_DOWN);
			$aTest = substr(trim($strA),0,$x);
			$bTest = substr(trim($strB),0,$x);
			if ($aTest == $bTest)
				$n = 50;
			// TEST FOR 80%
			$x = round(($lengA * 8) / 10, 0, PHP_ROUND_HALF_DOWN);
			$aTest = substr(trim($strA),0,$x);
			$bTest = substr(trim($strB),0,$x);
			if ($aTest == $bTest)
				$n = 80;
		}
		return $n;
	}

}

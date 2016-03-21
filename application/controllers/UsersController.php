<?php

class UsersController extends GeneralController {

    public function init() {
        $this->_imgSizes = Application_Model_UserMapper::getImageSizes();

        parent::init();
        $this->_iniUrl = '/users';
        $this->setLayout('layout-menu');

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

        $usersMapper = new Application_Model_UserMapper();
        $select = Application_Model_User::PREFIX . 'role >= ' . $this->_logon->getUser()->getRole();
        if ($this->_hasParam('order')) {
            $order = $this->defineListOrder();
        } else {
            $order = Application_Model_User::PREFIX . 'name ASC';
        }
        $this->view->d_order = $this->_getParam('d', 'asc');
        $this->view->users = $usersMapper->fetchAll(false, $select, $order);
    }

    public function activateAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'A')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
        }

        $mapper = new Application_Model_UserMapper();

        if ($this->_hasParam('id')) {
            $row = $mapper->find($this->_getParam('id'));
            if (!empty($row)) {
                if ($row->getId() != 1) {
                    $status = ($row->getActive()) ? 0 : 1;
                    $row->setActive($status);
                    $mapper->save($row);
                }
            }
        }
        $this->_auditor->saveLog($this->_logon->getUser()->getId(), $this->getRequest()->getActionName(), $this->getRequest()->getControllerName(), $row->getId());
        $this->addFlashMessage(array('Alterado com sucesso', SUCCESS), $this->_iniUrl);
    }

    public function insertAction() {
        if ($this->_hasParam('id')) {
            if ($this->_getParam('id') != $this->_logon->getUser()->getId()) {
                if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'U')) {
                    $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
                }
            }
        } else {
            if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'C')) {
                $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
            }
        }

        $request = $this->getRequest();
        $form = new Application_Form_User();
        $mapper = new Application_Model_UserMapper();

        if ($this->_hasParam('id')) {
            /* @var $db_object Application_Model_User */
            $db_object = $mapper->find($this->_getParam('id'));
            if (!empty($db_object)) {
                $form = new Application_Form_User(NULL, true, $db_object->getId());
                $form->populate($db_object->toArray());
            }
        }

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $date = Zend_Date::now();
                $user = new Application_Model_User($form->getValues());
                $id = $user->getId();
                if (empty($id)) {
                    $user->setInsertDate($date->get('yyyy-MM-dd HH:mm:ss', 'pt_BR'));
                    $user->setParent($this->_logon->getUser()->getId());
                    $user->setActive(1);
                } else {
                    $user->setInsertDate($db_object->getInsertDate());
                    $user->setParent($db_object->getParent());
                    $user->setActive($db_object->getActive());
                }
                $pass = $user->getPassword();
                if (empty($pass)) {
                    $user->setPassword($db_object->getPassword());
                } else {
                    $user->setPassword(sha1($user->getPassword()));
                }
                $img = $user->getImage();
                if (!empty($id)) {
                    if (empty($img)) {
                        $user->setImage($db_object->getImage());
                        $img = $user->getImage();
                    } else {
                        $mapper->deleteImages($id);
                    }
                }

                $result_id = $mapper->save($user);

                $this->_auditor->saveLog($this->_logon->getUser()->getId(), $this->getRequest()->getActionName(), $this->getRequest()->getControllerName(), $result_id);
                if (empty($img)) {
                    $this->addFlashMessage(array('Dados cadastrados com sucesso.', SUCCESS), $this->_iniUrl);
                } else {
                    $this->_redirect($this->_iniUrl . '/crop/id/' . $result_id);
                }
            }
        }

        $this->view->form = $form;
    }

    public function deleteAction() {
        if (!$this->_access->isAllowed($this->getRequest()->getControllerName(), 'D')) {
            $this->addFlashMessage(array('Permissão Negada.', ERROR), $this->_iniUrl);
        }

        $mapper = new Application_Model_UserMapper();

        if ($this->_hasParam('id')) {
            $row = $mapper->find($this->_getParam('id'));
            if (!empty($row)) {
                $mapper->delete($row->getId());
            }
        }
        $this->_auditor->saveLog($this->_logon->getUser()->getId(), $this->getRequest()->getActionName(), $this->getRequest()->getControllerName(), $row->getId());
        $this->addFlashMessage(array('Registro excluído com sucesso.', SUCCESS), $this->_iniUrl);
    }

    public function cropAction() {
        $this->_helper->layout->setLayout('layout');

        if (!$this->_hasParam('id')) {
            $this->addFlashMessage(array('Erro ao informar registro.', ERROR), $this->_iniUrl);
        } else {
            $mapper = new Application_Model_UserMapper();
            $user = $mapper->find($this->_getParam('id', 0));
            if (empty($user)) {
                $this->addFlashMessage(array('Erro ao informar registro.', ERROR), $this->_iniUrl);
            }
        }

        $this->view->image = $file = $user->getImage();
        $path = PATH_UPLOAD . $this->getRequest()->getControllerName() . '/';
        $image_str = $path . $file;

        if (file_exists($image_str)) {
            $image = new m2brimagem($image_str);
            if ($image->valida() == "OK") {
                if ($image->getLargura() > 800) {
                    $image->redimensiona(800);
                    $image->grava($image_str);
                }
                if ($image->getAltura() > 800) {
                    $image->redimensiona(NULL, 800);
                    $image->grava($image_str);
                }
                $crop = false;
                foreach ($this->_imgSizes as $size) {
                    $dest = $path . $size['w'] . '_' . $size['h'] . '/';
                    if ($size['type'] != 'jcrop') {
                        $image->redimensiona($size['w'], $size['h'], $size['type'], array(0, 0, 0));
                        $image->grava($dest . $file);
                    } else {
                        $crop = true;
                        $this->view->size = $size;
                        if (!file_exists($dest . $file)) {
                            $image->redimensiona($size['w'], $size['h'], 'crop');
                            $image->grava($dest . $file);
                        }
                        if ($this->getRequest()->isPost()) {
                            $data = $this->getRequest()->getPost();
                            $x1 = $data['x1'];
                            $y1 = $data['y1'];
                            $w = $data['w'];
                            $h = $data['h'];
                            $this->crop($x1, $y1, $w, $h, $w, $h, $image_str, $dest . $file);
                            $resized_img = new m2brimagem($dest . $file);
                            if ($resized_img->valida() == 'OK') {
                                $resized_img->redimensiona($size['w'], $size['h'], 'crop');
                                $resized_img->grava($dest . $file);
                            }
                        }
                    }
                    if (!empty($size['thumbs'])) {
                        $resized_img = new m2brimagem($dest . $file);
                        if ($resized_img->valida() == 'OK') {
                            foreach ($size['thumbs'] as $thumb_size) {
                                $thumb_dest = $dest . '/' . $thumb_size['w'] . '_' . $thumb_size['h'] . '/';
                                $resized_img->redimensiona($thumb_size['w'], $thumb_size['h'], $thumb_size['type'], array(0, 0, 0));
                                $resized_img->grava($thumb_dest . $file);
                            }
                        }
                    }
                }

                if ($this->getRequest()->isPost() || !$crop) {
                    $this->addFlashMessage(array('Registro adicionado com sucesso', SUCCESS), $this->_iniUrl);
                }
            } else {
                $this->addFlashMessage(array('Erro ao gerar imagens.', ERROR), $this->_iniUrl);
            }
        } else {
            $this->addFlashMessage(array('Imagen informada não existe.', ERROR), $this->_iniUrl);
        }
    }

    private function defineListOrder() {
        switch ($this->_getParam('order')) {
            case 'id':
                $order = Application_Model_User::PREFIX . 'id ';
                break;
            case 'email':
                $order = Application_Model_User::PREFIX . 'email ';
                break;
            case 'name':
            default:
                $order = Application_Model_User::PREFIX . 'name ';
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

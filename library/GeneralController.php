<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeneralController
 *
 * @author fabio.araujo
 */
require_once 'Zend/Controller/Action.php';

class GeneralController extends Zend_Controller_Action {

    protected $_iniUrl;
    protected $_logon;
    protected $_access;
    protected $_translate;
    protected $_auditor;
    protected $_navigation;
    protected $_flashMessenger;
    protected $_imgSizes = array();

    /*
     * @TODO implementar modo offline
     */
    
    public function init() {
        $this->setDbCollation();

        $this->_logon = new Login();
        $this->_logon->init();

        $this->_access = new Access($this->_logon->getUser());

        $this->defineLanguage();

        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->messages = $this->_flashMessenger->getMessages();

        $this->setNavigation();
        $this->createFolders();
    }

    protected function addFlashMessage(array $msg, $redirect = false) {
        $this->_flashMessenger->addMessage($msg);

        if ($redirect) {
            $this->_redirect($redirect);
        }
    }

    protected function setLayout($layout = 'main') {
        $this->_helper->layout->setLayout($layout);
    }

    protected function disableLayout() {
        $this->_helper->layout->disableLayout();
    }

    protected function addFlashMessageDirect(array $msg) {
        $this->view->messages = array($msg);
    }

    protected function setDbCollation() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->query("SET NAMES utf8");
        $db->query("SET CHARACTER SET utf8");
        $db->query("SET COLLATION_CONNECTION = utf8_general_ci");
    }

    protected function defineLanguage() {
        $sysMapper = new Application_Model_SystemMapper();
        $langConf = $sysMapper->findConfig('multi-language');

        if (!empty($langConf)) {
            $directory = APPLICATION_PATH . '/../languages/';
            $items = array();
            // open directory
            $pointer = opendir($directory);

            // mount the vectors with items found
            while ($item_name = readdir($pointer)) {
                if ($item_name != "." && $item_name != "..") {
                    $items[] = $item_name;
                }
            }

            foreach ($items as $item) {
                $pointer = opendir($directory . $item);

                while ($item_name = readdir($pointer)) {
                    if ($item_name != "." && $item_name != "..") {
                        if (empty($this->_translate)) {
                            $this->_translate = new Zend_Translate('array', $directory . $item . '/' . $item_name, $item);
                        } else {
                            $this->_translate->addTranslation($directory . $item . '/' . $item_name, $item);
                        }
                    }
                }
            }

            if ($this->_hasParam('language')) {
                if ($this->_translate->isAvailable($this->_getParam('language'))) {
                    $this->_translate->setLocale($this->_getParam('language'));
                    $this->view->linguagemSelecionada = $this->_getParam('language');
                }
            } elseif ($this->_translate->isAvailable("pt_BR")) {
                $this->_translate->setLocale('pt_BR');
            }

            Zend_Form::setDefaultTranslator($this->_translate);
            $this->view->translate = $this->_translate;
        }
    }

    public function setNavigation() {
        $this->_navigation = new Zend_Navigation(array(
            array(
                'label' => 'HOME',
                'id' => 'bc-home',
                'title' => 'HOME',
                'order' => 1,
                'controller' => 'index',
                'action' => 'index',
                'pages' => array(
                    array(
                        'label' => strtoupper($this->getRequest()->getControllerName()),
                        'id' => 'bc-' . $this->getRequest()->getControllerName(),
                        'title' => strtoupper($this->getRequest()->getControllerName()),
                        'order' => 2,
                        'controller' => $this->getRequest()->getControllerName(),
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'label' => strtoupper($this->getRequest()->getActionName()),
                                'id' => 'bc-' . $this->getRequest()->getActionName(),
                                'title' => strtoupper($this->getRequest()->getActionName()),
                                'order' => 3,
                                'controller' => $this->getRequest()->getControllerName(),
                                'action' => $this->getRequest()->getActionName(),
                            ),
                        ),
                    ),
                ),
            ),
        ));
        $this->view->getHelper('navigation')->setContainer($this->_navigation);
    }

    private function createFolders() {
        if (!empty($this->_imgSizes)) {
            $dir = PATH_UPLOAD . $this->getRequest()->getControllerName();
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            foreach ($this->_imgSizes as $size) {
                if ($size['w'] || $size['h']) {
                    $dir_thumb = $dir . '/' . $size['w'] . '_' . $size['h'];

                    if (!file_exists($dir_thumb)) {
                        mkdir($dir_thumb, 0755, true);
                    }

                    if (array_key_exists('thumbs', $size)) {
                        foreach ($size['thumbs'] as $size_t) {
                            $dir_t = $dir_thumb . '/' . $size_t['w'] . '_' . $size_t['h'];
                            if (!file_exists($dir_t)) {
                                mkdir($dir_t, 0755, true);
                            }
                        }
                    }
                }
            }
        }
    }

    public function crop($src_x, $src_y, $src_w, $src_h, $dest_w, $dest_h, $source, $dest) {
        $pathinfo = pathinfo($source);
        $type = $pathinfo['extension'];
        switch ($type) {
            case 'gif':
                $src_img = imagecreatefromgif($source);
                break;
            case 'jpg':
                $src_img = imagecreatefromjpeg($source);
                break;
            case 'png':
                $src_img = imagecreatefrompng($source);
                break;
        }

        $dest_img = imagecreatetruecolor($dest_w, $dest_h);

        imagecopyresampled($dest_img, $src_img, 0, 0, $src_x, $src_y, $dest_w, $dest_h, $src_w, $src_h);

        switch ($type) {
            case 'gif':
                imagegif($dest_img, $dest);
                break;
            case 'jpg':
                imagejpeg($dest_img, $dest, 100);
                break;
            case 'png':
                imagepng($dest_img, $dest);
                break;
            default:
                break;
        }
    }
    
    protected function getIdUrl($url) {
        if (strrpos($url, '-'))
            $v = 1;
        else
            $v = 0;
        $id = substr($url, strrpos($url, '-') + $v, strlen($url));

        return Zend_Filter_Int::filter($id);
    }

}

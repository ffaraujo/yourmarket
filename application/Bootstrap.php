<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initRouter() {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/router.ini', 'production');

        $router->addConfig($config, 'routes');
    }

    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }

}

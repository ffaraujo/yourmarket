<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of System
 *
 * @author fabio.araujo
 */
class System {

    protected $configs = array();

    public function init() {
        $mapper = new Application_Model_SystemMapper();
        $configs = $mapper->fetchAll();
        foreach ($configs as $config) {
            $this->configs[$config->getName()] = $config->getValue();
        }
    }

    public function getConfig($config) {
        if (array_key_exists($config, $this->configs)) {
            return $this->configs[$config];
        } else {
            return NULL;
        }
    }

}

?>

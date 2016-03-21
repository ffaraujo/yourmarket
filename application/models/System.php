<?php

class Application_Model_System {
    const PREFIX = 'sys_';
    
    protected $sys_id;
    protected $sys_name;
    protected $sys_value;
    protected $sys_desc;
    protected $sys_active;
    
    function __construct(Array $data = array()) {
        if (is_array($data) && !empty($data)) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
    }
    
    function toArray() {
        return get_object_vars($this);
    }
    
    public function getId() {
        return $this->sys_id;
    }

    public function setId($sys_id) {
        $this->sys_id = $sys_id;
    }

    public function getName() {
        return $this->sys_name;
    }

    public function setName($sys_name) {
        $this->sys_name = $sys_name;
    }
    
    public function getValue() {
        return $this->sys_value;
    }

    public function setValue($sys_value) {
        $this->sys_value = $sys_value;
    }

    public function getDesc() {
        return $this->sys_desc;
    }

    public function setDesc($sys_desc) {
        $this->sys_desc = $sys_desc;
    }
    
    public function getActive() {
        return $this->sys_active;
    }

    public function setActive($sys_active) {
        $this->sys_active = $sys_active;
    }
}

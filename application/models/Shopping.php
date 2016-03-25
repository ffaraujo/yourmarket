<?php

class Application_Model_Shopping {

    const PREFIX = 'shp_';

    protected $shp_id;
    protected $shp_date;
    protected $shp_value;
    protected $shp_create_date;
    protected $shp_user;
    protected $shp_active;

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
        return $this->shp_id;
    }

    public function getDate() {
        return $this->shp_date;
    }

    public function getValue() {
        return $this->shp_value;
    }

    public function getCreateDate() {
        return $this->shp_create_date;
    }

    public function getUser() {
        return $this->shp_user;
    }

    public function getActive() {
        return $this->shp_active;
    }

    public function setId($shp_id) {
        $this->shp_id = $shp_id;
    }

    public function setDate($shp_date) {
        $this->shp_date = $shp_date;
    }

    public function setValue($shp_value) {
        $this->shp_value = $shp_value;
    }

    public function setCreateDate($shp_create_date) {
        $this->shp_create_date = $shp_create_date;
    }

    public function setUser($shp_user) {
        $this->shp_user = $shp_user;
    }

    public function setActive($shp_active) {
        $this->shp_active = $shp_active;
    }

}

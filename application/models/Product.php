<?php

class Application_Model_Product {

    const PREFIX = 'prd_';

    protected $prd_id;
    protected $prd_name;
    protected $prd_create_date;
    protected $prd_user;
    protected $prd_active;

    function __construct(Array $data = array()) {
        if (is_array($data) && !empty($data)) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function getId() {
        return $this->prd_id;
    }

    public function getName() {
        return $this->prd_name;
    }

    public function getCreateDate() {
        return $this->prd_create_date;
    }

    public function getUser() {
        return $this->prd_user;
    }

    public function getActive() {
        return $this->prd_active;
    }

    public function setId($prd_id) {
        $this->prd_id = $prd_id;
    }

    public function setName($prd_name) {
        $this->prd_name = $prd_name;
    }

    public function setCreateDate($prd_create_date) {
        $this->prd_create_date = $prd_create_date;
    }

    public function setUser($prd_user) {
        $this->prd_user = $prd_user;
    }

    public function setActive($prd_active) {
        $this->prd_active = $prd_active;
    }

}

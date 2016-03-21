<?php

class Application_Model_User {

    const PREFIX = 'use_';

    protected $use_id;
    protected $use_name;
    protected $use_email;
    protected $use_password;
    protected $use_role;
    protected $use_image;
    protected $use_parent;
    protected $use_insert_date;
    protected $use_last_access;
    protected $use_active;

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
        return $this->use_id;
    }

    public function setId($use_id) {
        $this->use_id = $use_id;
    }

    public function getName() {
        return $this->use_name;
    }

    public function setName($use_name) {
        $this->use_name = $use_name;
    }

    public function getEmail() {
        return $this->use_email;
    }

    public function setEmail($use_email) {
        $this->use_email = $use_email;
    }

    public function getPassword() {
        return $this->use_password;
    }

    public function setPassword($use_password) {
        $this->use_password = $use_password;
    }

    public function getRole() {
        return $this->use_role;
    }

    public function setRole($use_role) {
        $this->use_role = $use_role;
    }

    public function getParent() {
        return $this->use_parent;
    }

    public function setParent($use_parent) {
        $this->use_parent = $use_parent;
    }

    public function getInsertDate() {
        return $this->use_insert_date;
    }

    public function setInsertDate($use_insert_date) {
        $this->use_insert_date = $use_insert_date;
    }

    public function getLastAccess() {
        return $this->use_last_access;
    }

    public function setLastAccess($use_last_access) {
        $this->use_last_access = $use_last_access;
    }

    public function getActive() {
        return $this->use_active;
    }

    public function setActive($use_active) {
        $this->use_active = $use_active;
    }

    public function getImage() {
        return $this->use_image;
    }

    public function setImage($use_image) {
        $this->use_image = $use_image;
    }

}

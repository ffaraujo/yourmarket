<?php

class Application_Model_AuditorLog {

    const PREFIX = 'aud_';

    protected $aud_id;
    protected $aud_user;
    protected $aud_action;
    protected $aud_controller;
    protected $aud_object_id;
    protected $aud_insert_date;

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
        return $this->aud_id;
    }

    public function getUser() {
        return $this->aud_user;
    }

    public function getAction() {
        return $this->aud_action;
    }

    public function getController() {
        return $this->aud_controller;
    }

    public function getObjectId() {
        return $this->aud_object_id;
    }

    public function getInsertDate() {
        return $this->aud_insert_date;
    }

    public function setId($aud_id) {
        $this->aud_id = $aud_id;
    }

    public function setUser($aud_user) {
        $this->aud_user = $aud_user;
    }

    public function setAction($aud_action) {
        $this->aud_action = $aud_action;
    }

    public function setController($aud_controller) {
        $this->aud_controller = $aud_controller;
    }

    public function setObjectId($aud_object_id) {
        $this->aud_object_id = $aud_object_id;
    }

    public function setInsertDate($aud_insert_date) {
        $this->aud_insert_date = $aud_insert_date;
    }

}

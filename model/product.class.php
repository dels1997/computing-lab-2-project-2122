<?php

class Product {
    protected $id, $id_user, $name, $description, $price;
    
    function __construct ($id, $id_user, $name, $description, $price) {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }

    public function __set ($property, $value) {
        if (property_exists ($this, $property))
            $this->$property = $value;
        return $this;
    }

    public function __get ($property) {
        if (property_exists ($this, $property))
            return $this->$property;
    }

};


?>
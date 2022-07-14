<?php

class Sale {
    protected $id, $id_product, $id_user, $rating, $comment;
    
    function __construct ($id, $id_product, $id_user, $rating, $comment) {
        $this->id = $id;
        $this->id_product = $id_product;
        $this->id_user = $id_user;
        $this->rating = $rating; 
        $this->comment = $comment;
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
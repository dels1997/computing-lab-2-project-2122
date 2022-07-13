<?php
class User
{
    protected $id, $username, $password_hash, $email, $registration_sequence, $has_registered, $admin;

    function __construct($id, $username, $password_hash, $email, $registration_sequence, $has_registered, $admin)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password_hash = $password_hash;
        $this->email = $email;
        $this->registration_sequence = $registration_sequence;
        $this->has_registered = $has_registered;
        $this->admin = $admin;
    }

    function __get($property)
    {
        if(property_exists($this, $property))
            return $this->$property;
    }

    function __set($property, $value)
    {
        if(property_exists($this, $property))
            $this->$property = $value;

        return $this;
    }
};

?>
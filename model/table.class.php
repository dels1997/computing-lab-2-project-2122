<?php
class Table
{
    protected $id, $id_user, $o2_table, $co2_table;

    function __construct($id, $id_user, $o2_table, $co2_table)
    {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->o2_table = $o2_table;
        $this->co2_table = $co2_table;
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
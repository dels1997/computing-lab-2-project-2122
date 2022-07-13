<?php
class Training
{
    protected $id, $id_user, $type, $duration, $date;

    function __construct($id, $id_user, $type, $duration, $date)
    {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->type = $type;
        $this->duration = $duration;
        $this->date = $date;
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
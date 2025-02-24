<?php

class actividad{

    private $id;
    //descripción corta y larga
    private $descC;
    private $descL;

    //Constructor
    public function __construct($id, $descC, $descL)
    {
        $this->id = $id;
        $this->descC = $descC;
        $this->descL = $descL;
    }

    // Funciones SET 
    public function setID($id)
    {
        $this->id = $id;
    }
    public function setDescripcionCorta($descC)
    {
        $this->descC = $descC;
    }
    public function setDescripcionLarga($descL)
    {
        $this->descL = $descL;
    }

    // Funciones GET
    public function getID()
    {
        return $this->id;
    }
    public function getDescripcionCorta()
    {
        return $this->descC;
    }
    public function getDescripcionLarga()
    {
        return $this->descL;
    }

    public function __toString()
    {
        $cadena = "ACTIVIDAD NRO " . $this->getID()
            . ": " . $this->getDescripcionCorta()
            . " | " . $this->getDescripcionLarga()
            . "\n";
        return $cadena;
    }
}
?>


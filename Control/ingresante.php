<?php

class ingresante
{

    private $dni;
    private $tipoDNI;
    private $nombre;
    private $apellido;
    private $legajo;
    private $correo;

    //Constructor
    public function __construct($dni, $tipoDNI, $nombre, $apellido, $legajo, $correo)
    {
        $this->dni = $dni;
        $this->tipoDNI = $tipoDNI;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->legajo = $legajo;
        $this->correo = $correo;
    }

    // Funciones SET 
    public function setDni($dni)
    {
        $this->dni = $dni;
    }
    public function setTipoDNI($tipoDNI)
    {
        $this->tipoDNI = $tipoDNI;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function setLegajo($legajo)
    {
        $this->legajo = $legajo;
    }
    public function setCorreo($correo)
    {
        $this->correo = $correo;
    }

    // Funciones GET
    public function getDni()
    {
        return $this->dni;
    }
    public function getTipoDNI()
    {
        return $this->tipoDNI;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function getLegajo()
    {
        return $this->legajo;
    }
    public function getCorreo()
    {
        return $this->correo;
    }

    //función to string para visualizar los datos del ingresante

    public function __toString()
    {
        $cadena = "\n"
            ."DATO DEL INGRESANTE: " . $this->getNombre()
            . " " . $this->getApellido()
            . " | " . $this->getTipoDNI()
            . " " . $this->getDni()
            . " | " . $this->getLegajo()
            . " | " . $this->getCorreo();
        return $cadena;
    }
}

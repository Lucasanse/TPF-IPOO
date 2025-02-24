<?php
include_once 'actividad.php';

class modulo
{

    private $id;
    private $descripcion;
    private $horarioInicio;
    private $horarioCierre;
    private $fechaInicio;
    private $fechaFin;
    private $topeInscripciones;
    private $costo;
    private $obj_actividad;
    private $cantidadDeInscriptos;

    // Constructor
    public function __construct($id, $descripcion, $horarioInicio, $horarioCierre, $fechaInicio, $fechaFin, $topeInscripciones, $costo, $obj_actividad)
    {
        $this->id = $id;
        $this->descripcion = $descripcion;
        $this->horarioInicio = $horarioInicio;
        $this->horarioCierre = $horarioCierre;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->topeInscripciones = $topeInscripciones;
        $this->costo = $costo;
        $this->obj_actividad = $obj_actividad;
        $this->cantidadDeInscriptos = 0;
    }


    // Getter y Setter para el id
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter y Setter para la descripcion
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    // Getter y Setter para horario de inicio 
    public function getHorarioInicio()
    {
        return $this->horarioInicio;
    }

    public function setHorarioInicio($horarioInicio)
    {
        $this->horarioInicio = $horarioInicio;
    }

    // Getter y Setter para horario de cierre
    public function getHorarioCierre()
    {
        return $this->horarioCierre;
    }

    public function setHorarioCierre($horarioCierre)
    {
        $this->horarioCierre = $horarioCierre;
    }

    // Getter y Setter para fecha de inicio
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    // Getter y Setter para fecha final 
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }

    // Getter y Setter para el tope de inscripciones
    public function getTopeInscripciones()
    {
        return $this->topeInscripciones;
    }

    public function setTopeInscripciones($topeInscripciones)
    {
        $this->topeInscripciones = $topeInscripciones;
    }

    // Getter y Setter para el costo
    public function getCosto()
    {
        return $this->costo;
    }

    public function setCosto($costo)
    {
        $this->costo = $costo;
    }

    // Getter y Setter para la actividad
    public function getObj_actividad()
    {
        return $this->obj_actividad;
    }

    public function setObj_actividad($obj_actividad)
    {
        $this->obj_actividad = $obj_actividad;
    }

    public function getcantidadDeInscriptos()
    {
        return $this->cantidadDeInscriptos;
    }
    //se añade un valor a la variable de cantidad de inscriptos actuales 
    //Retorna verdadero o falso, si el módulo está completo de personas, devuelve falso. 
    public function sumarUnInscripto(){
        $res = true;
        if($this -> cantidadDeInscriptos < $this ->topeInscripciones){
            $this -> cantidadDeInscriptos ++;
        } else {
            $res = false;
        }
        return $res;
    }
    //resta un valor a la variable de cantidad de inscriptos actuales 
    public function restarUnInscripto(){
        $res = true;
        if($this -> cantidadDeInscriptos != 0){
            $this -> cantidadDeInscriptos --;
        } else {
            $res = false;
        }
        return $res;
    }

    //4. Implementar y redefinir (donde corresponda) el método darCostoMódulo que retorna el importe final correspondiente a la inscripción de ese módulo.

    public function darCostoModulo(){
        return $this->costo;
    }

    public function esModuloEnLinea(){
        return false;
    }

    //mostrar el modulo solamente con su numero y descripción 
    public function toStringBreve(){
        $cadena ="MÓDULO ID " . $this->getId()
            . ": " . $this->getDescripcion();
        return $cadena;
    } 

    //función to string para visualizar los datos del módulo
    public function __toString()
    {
        $cadena = "\n"
            ."DATO DEL MÓDULO NRO " . $this->getId()
            . ": " . $this->getDescripcion()
            . " | de " . $this->getHorarioInicio()
            . " hasta las " . $this->getHorarioCierre()
            . " | desde el " . $this->getFechaInicio()
            . " hasta el " . $this->getFechaFin()
            . " | Tope: " . $this->getTopeInscripciones()
            . " | Costo: " . $this->getCosto()
            . " | " . $this->getObj_actividad();
            
        return $cadena;
    }
}

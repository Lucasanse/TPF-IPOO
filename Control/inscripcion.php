<?php
include_once 'modulo.php';
include_once 'en_linea.php';
include_once 'ingresante.php';

class inscripcion
{
    private $id;
    private $fecha;
    private $costoFinal;
    private $col_objModulo;
    private $ingresante;

    //constructor 
    public function __construct($id, $fecha, $col_objModulo, $ingresante)
    {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->col_objModulo = $col_objModulo;
        $this->ingresante = $ingresante;
        $this->costoFinal = $this->darCostoInscripcion();
    }

    // Métodos set y get para $id
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }


    // Métodos set y get para $fecha
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    // Métodos set y get para $costoFinal
    public function setCostoFinal($costoFinal)
    {
        $this->costoFinal = $costoFinal;
    }

    public function getCostoFinal()
    {
        return $this->costoFinal;
    }

    // Métodos set y get para $col_objModulo
    public function setColObjModulo($col_objModulo)
    {
        $this->col_objModulo = $col_objModulo;
    }

    public function getColObjModulo()
    {
        return $this->col_objModulo;
    }

    // Métodos set y get para $ingresante
    public function setIngresante($ingresante)
    {
        $this->ingresante = $ingresante;
    }

    public function getIngresante()
    {
        return $this->ingresante;
    }

    //crea una cadena con una lista de los modulos incorporados a la inscripción
    public function listarModulos()
    {
        $cadena = "";
        $modulos = $this->getColObjModulo();
        foreach ($modulos as $modulo) {
            $cadena .= "\n" . "------" . $modulo->toStringBreve();
        }
        return $cadena;
    }

    //actualiza el costo de la inscripcion teniendo en cuenta los modulos. 
    public function darCostoInscripcion()
    {
        $montoFinal = 0;
        $modulos = $this->getColObjModulo();
        foreach ($modulos as $modulo) {
            $montoFinal += $modulo->darCostoModulo();
        }
        $this->setCostoFinal($montoFinal);
        return $montoFinal;
    }

    //metodo que, dado un módulo, corrobora de que su actividad no se repita con otra de otro modulo cargado en la inscripción
    //si es true, se repiten.
    public function actividadRepetida($obj_modulo)
    {
        $respuesta = true;
        $actividades = [];
        $modulos = $this->getColObjModulo();
        foreach ($modulos as $modulo) {
            array_push($actividades, $modulo->getiDActividad());
        }
        array_push($actividades, $obj_modulo->getiDActividad());

        if (count($actividades) === count(array_unique($actividades))) {
            $respuesta = false;
        }
        return $respuesta;
    }

    //función que verifica si existe el módulo pasado por parametro dentro del arreglo de modulos
    //si es verdadero, el modulo pasado por parametro ya existe en el arreglo de modulos
    public function existeModulo($obj_modulo)
    {
        $res = true;
        $modulos = $this->getColObjModulo();
        array_push($modulos, $obj_modulo);
        //si no hay elementos repetidos una vez que agregamos el modulo, quiere decir que no contiene dicho modulo
        if (count($modulos) === count(array_unique($modulos))) {
            $res = false;
        }
        return $res;
    }

    //función que verifica si existe una actividad pasada por parametro dentro del arreglo de modulos
    //si es verdadero, la actividad pasada por parametro ya existe en alguno de los módulos
    public function existeActividad($obj_actividad)
    {
        $modulos = $this->getColObjModulo();
        foreach ($modulos as $modulo){
            if ($modulo -> getObj_actividad() == $obj_actividad){
                return true;
            }
        }
        return false;
    }

    public function extraerActividades (){
        $res = [];
        foreach ($this -> col_objModulo as $modulo){
            array_push($res, $modulo -> getObj_actividad());
        }
        return $res;

    }


    //añade un modulo corroborando que su actividad no se repita con otra de la colección de módulos
    // verifica tambien de que el modulo no este previamente cargado en la inscripcion y que haya cupo de inscriptos

    public function añadirModulo($obj_modulo)
    {
        $res = false;
        if ($this->actividadRepetida($obj_modulo)) {
            echo "Se está añadiendo un módulo repetido o la actividad del modulo añadido se repite con la actividad de otro módulo\n";
        } else {
            if ($obj_modulo->sumarUnInscripto()) {
                $this->col_objModulo[] = $obj_modulo;
                //se actualiza el costo de la inscripción
                $this->darCostoInscripcion();
                $res = true;
            } else {
                echo "El módulo elegido ya está lleno\n";
            }
        }

        return $res;
    }

    public function eliminarModulo($obj_modulo){
        $res = false;
        foreach ($this -> col_objModulo as $indice => $modulo) {
            if ($modulo == $obj_modulo) {
                //se elimina el elemento, se quita un cupo en el modulo y se vuelve a calcular el precio de la inscripción
                unset($this -> col_objModulo[$indice]); 
                $modulo -> restarUnInscripto();
                $this -> darCostoInscripcion();
                $res = true;
            }
        }
        // Reindexa el arreglo
        $this->col_objModulo = array_values($this-> col_objModulo); 
        return $res;
    }


    public function __toString()
    {
        $cadena = "\n\n"
            . "DATO DE LA INSCRIPCION " . $this->getId()
            . " | Fecha: " . $this->getFecha()
            . " " . $this->getIngresante()
            . "\n Modulos: " . $this->listarModulos()
            . "\n Costo: " . $this->getCostoFinal();
        return $cadena;
    }
}

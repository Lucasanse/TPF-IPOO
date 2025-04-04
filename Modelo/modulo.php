<?php
include_once 'actividad.php';
include_once 'baseDatos.php';

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
    private $cantidadDeInscriptos;
    private $mensajeoperacion;
    private $obj_actividad;

    // Constructor
    public function __construct()
    {
        $this->id = 0;
        $this->descripcion = "";
        $this->horarioInicio = "";
        $this->horarioCierre = "";
        $this->fechaInicio = "";
        $this->fechaFin = "";
        $this->topeInscripciones = "";
        $this->costo = "";
        $this->obj_actividad = "";
        $this->cantidadDeInscriptos = 0;
    }

    public function cargar($id, $descripcion, $horarioInicio, $horarioCierre, $fechaInicio, $fechaFin, $topeInscripciones, $costo, $obj_actividad)
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


    // metodos set

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setHorarioInicio($horarioInicio)
    {
        $this->horarioInicio = $horarioInicio;
    }


    public function setHorarioCierre($horarioCierre)
    {
        $this->horarioCierre = $horarioCierre;
    }

    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }

    public function setTopeInscripciones($topeInscripciones)
    {
        $this->topeInscripciones = $topeInscripciones;
    }

    public function setCosto($costo)
    {
        $this->costo = $costo;
    }

    public function setObj_actividad($obj_actividad)
    {
        $this->obj_actividad = $obj_actividad;
    }

    public function setmensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function setCantidadInscriptos($cantidadDeInscriptos)
    {
        $this->cantidadDeInscriptos = $cantidadDeInscriptos;
    }
    //Metodos get

    public function getId()
    {
        return $this->id;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getHorarioInicio()
    {
        return $this->horarioInicio;
    }

    public function getHorarioCierre()
    {
        return $this->horarioCierre;
    }

    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    public function getTopeInscripciones()
    {
        return $this->topeInscripciones;
    }

    public function getCosto()
    {
        return $this->costo;
    }

    public function getObj_actividad()
    {
        return $this->obj_actividad;
    }

    public function getmensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function getcantidadDeInscriptos()
    {
        return $this->cantidadDeInscriptos;
    }

    /**
     * funcion que devuelve un boolean en caso de que la gente inscripta sea igual a al tome de ingresantes
     * @return boolean si está lleno
     */
    public function estaLleno()
    {
        return $this->getCantidadDeInscriptos() >= $this->getTopeInscripciones();
    }


    /**
     * se añade un valor a la variable de cantidad de inscriptos actuales 
     * @return boolean si el módulo está completo de personas, devuelve falso. 
     */
    public function sumarUnInscripto()
    {
        $res = true;
        if (!$this->estaLleno()) {
            $this->setCantidadInscriptos($this->getcantidadDeInscriptos() + 1);
        } else {
            $res = false;
        }
        //se modifica el nuevo valor en la base de datos;
        $this->modificar();
        return $res;
    }
    //resta un valor a la variable de cantidad de inscriptos actuales 
    public function restarUnInscripto()
    {
        $res = true;
        if ($this->getCantidadDeInscriptos() != 0) {
            $this->setCantidadInscriptos($this->getcantidadDeInscriptos() - 1);
        } else {
            $res = false;
        }
        //se modifica el nuevo valor en la base de datos;
        $this->modificar();
        return $res;
    }

    //4. Implementar y redefinir (donde corresponda) el método darCostoMódulo que retorna el importe final correspondiente a la inscripción de ese módulo.

    public function darCostoModulo()
    {
        return $this->getCosto();
    }

    public function esModuloEnLinea()
    {
        return false;
    }

    //Función que muestra el modulo solamente con su numero y descripción 
    public function toStringBreve()
    {
        $cadena = "MÓDULO ID " . $this->getId()
            . ": " . $this->getDescripcion();
        return $cadena;
    }

    //función to string para visualizar los datos del módulo
    public function __toString()
    {
        $cadena = "\n"
            . "DATO DEL MÓDULO NRO " . $this->getId()
            . ": " . $this->getDescripcion()
            . " | de " . $this->getHorarioInicio()
            . " hasta las " . $this->getHorarioCierre()
            . " | desde el " . $this->getFechaInicio()
            . " hasta el " . $this->getFechaFin()
            . " | Tope: " . $this->getTopeInscripciones()
            . " | Costo: " . $this->getCosto()
            . " | Inscriptos actuales: " . $this->getcantidadDeInscriptos()
            . "\n" . $this->getObj_actividad();

        return $cadena;
    }

    /**
     * Función para insertar un módulo a la base de datos 
     * @return boolean
     */
    public function insertar()
    {
        $base = new baseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO Modulo (descripcion, horarioInicio, horarioCierre, fechaInicio, fechaFin, topeInscripciones, costo, cantidadDeInscriptos, actividad_id)
				VALUES ('" . $this->getDescripcion() . "','" . $this->getHorarioInicio() . "','" . $this->getHorarioCierre() . "','" . $this->getFechaInicio() .
            "','" . $this->getFechaFin() . "'," . $this->getTopeInscripciones() . "," . $this->getCosto() . "," . 0 . "," . $this->getObj_actividad()->getID() . ")";

        if ($base->Iniciar()) {
            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setID($id);
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    /**
     * Función para buscar un objeto en la base de datos 
     * @param id del modulo a buscar
     * @return boolean si se encontró el modulo teniendo en cuenta el id 
     */
    public function Buscar($id)
    {
        $base = new BaseDatos();
        $consultaPersona = "Select * from modulo where id=" . $id;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPersona)) {
                if ($row2 = $base->Registro()) {
                    $this->setID($id);
                    $this->setDescripcion($row2['descripcion']);
                    $this->setHorarioInicio($row2['horarioInicio']);
                    $this->setHorarioCierre($row2['horarioCierre']);
                    $this->setFechaInicio($row2['fechaInicio']);
                    $this->setFechaFin($row2['fechaFin']);
                    $this->setTopeInscripciones($row2['topeInscripciones']);
                    $this->setCosto($row2['costo']);
                    $this->setCantidadInscriptos($row2['cantidadDeInscriptos']);

                    $obj_actividad= new actividad();
                    $obj_actividad -> buscar($row2['actividad_id']);

                    $this->setObj_actividad($obj_actividad);
                    $resp = true;
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    /**
     * Función que devuelve un arreglo de todos los objetos de esta clase referenciados en la base de datos 
     * @param condicion
     * @return array 
     */
    public function listar($condicion = "")
    {
        $arreglo = null;
        $base = new BaseDatos();
        $consulta = "Select * from modulo ";
        if ($condicion != "") {
            $consulta = $consulta . ' where ' . $condicion;
        }
        $consulta .= " order by id ";
        //echo $consulta;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $arreglo = array();
                while ($row2 = $base->Registro()) {
                    $id = $row2['id'];
                    $desc = $row2['descripcion'];
                    $hi = $row2['horarioInicio'];
                    $hc = $row2['horarioCierre'];
                    $fi = $row2['fechaInicio'];
                    $ff = $row2['fechaFin'];
                    $topeI = $row2['topeInscripciones'];
                    $costo = $row2['costo'];
                    $cantInscriptos = $row2['cantidadDeInscriptos'];

                    $obj_actividad = new actividad();
                    $obj_actividad ->Buscar($row2['actividad_id']);

                    $modulo = new modulo();
                    $modulo->cargar($id, $desc, $hi, $hc, $fi, $ff, $topeI, $costo, $obj_actividad);
                    $modulo->setCantidadInscriptos($cantInscriptos);
                    array_push($arreglo, $modulo);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arreglo;
    }

    /**
     * Añade alguna modificacion a la base de datos en caso de que el objeto haya sido modificado
     * @return boolean 
     */
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE modulo 
                           SET descripcion='" . $this->getDescripcion() . "',horarioInicio='" . $this->getHorarioInicio() .
            "',horarioCierre='" . $this->getHorarioCierre() . "',fechaInicio='" . $this->getFechaInicio() .
            "',fechaFin='" . $this->getFechaFin() . "',topeInscripciones=" . $this->getTopeInscripciones() .
            ",costo=" . $this->getCosto() . ",cantidadDeInscriptos=" . $this->getcantidadDeInscriptos() .
            ",actividad_id=" . $this->getObj_actividad()->getID() . " WHERE id=" . $this->getID();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    /**
     * Elimina el objeto en la base de datos
     * @return boolean 
     */
    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM modulo WHERE id=" . $this->getID();
            if ($base->Ejecutar($consultaBorra)) {
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }
}

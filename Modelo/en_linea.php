<?php

include_once 'baseDatos.php';

class enLinea extends modulo
{
    private $linkLlamada;
    private $bonificacion;


    // Constructor
    public function __construct()
    {
        parent::__construct();
        $this->linkLlamada = "";
        $this->bonificacion = 0;
    }

    public function cargar($id, $descripcion, $horarioInicio, $horarioCierre, $fechaInicio, $fechaFin, $topeInscripciones, $costo, $obj_actividad, $linkLlamada = null, $bonificacion = null)
    {
        parent::cargar($id, $descripcion, $horarioInicio, $horarioCierre, $fechaInicio, $fechaFin, $topeInscripciones, $costo, $obj_actividad);
        $this->linkLlamada = $linkLlamada;
        $this->bonificacion = $bonificacion;
    }

    // Getter y Setter para linkLlamada
    public function getLinkLlamada()
    {
        return $this->linkLlamada;
    }

    public function setLinkLlamada($linkLlamada)
    {
        $this->linkLlamada = $linkLlamada;
    }

    // Getter y Setter para bonificacion
    public function getBonificacion()
    {
        return $this->bonificacion;
    }

    public function setBonificacion($bonificacion)
    {
        $this->bonificacion = $bonificacion;
    }

    //4. Implementar y redefinir (donde corresponda) el método darCostoMódulo que retorna el importe final correspondiente a la inscripción de ese módulo.
    public function darCostoModulo()
    {
        $costo = parent::darCostoModulo();
        $costoConDescuento = $costo - (($costo * $this->getBonificacion()) / 100);
        return $costoConDescuento;
    }

    public function esModuloEnLinea()
    {
        return true;
    }

    /**
     * Función para insertar un módulo a la base de datos 
     * @return boolean
     */
    public function insertar()
    {
        $resp = false;
        $base = new baseDatos();
        if (parent::insertar()) {
            $consultaInsertar = "INSERT INTO enLinea (id, linkLlamada, bonificacion)
			VALUES (" . $this->getId() . ", '" . $this->getLinkLlamada() . "'," . $this->getBonificacion() . " )";
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaInsertar)) {
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
    /**
     * Función para buscar un objeto en la base de datos 
     * @param id del modulo a buscar
     * @return boolean si se encontró el modulo teniendo en cuenta el id 
     */
    public function Buscar($id)
    {
        $base = new BaseDatos();
        $resp = false;
        if (parent::Buscar($id)) {
            $consulta = "Select * from enLinea where id=" . $id;
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consulta)) {
                    if ($row2 = $base->Registro()) {
                        $this->setLinkLlamada($row2['linkLlamada']);
                        $this->setBonificacion($row2['bonificacion']);
                        $resp = true;
                    }
                } else {
                    $this->setmensajeoperacion($base->getError());
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        }
        return $resp;
    }

    /**
     * Añade alguna modificacion a la base de datos en caso de que el objeto haya sido modificado
     * @return boolean 
     */
    public function modificar()
    {
        $resp = false;
        if (parent::modificar()) {
            $base = new BaseDatos();
            $consultaModifica = "UPDATE enLinea 
                               SET linkLlamada='" . $this->getLinkLlamada() . "',bonificacion=" . $this->getBonificacion() .
                " WHERE id=" . $this->getID();

            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaModifica)) {
                    $resp =  true;
                } else {
                    $this->setmensajeoperacion($base->getError());
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        }

        return $resp;
    }

    public function __toString()
    {
        $cadena = parent::__toString();
        $cadena .= " | link de llamada: " . $this->getLinkLlamada()
            . " | Bonificación: " . $this->getBonificacion()
            . "% | MODULO EN LINEA";
        return $cadena;
    }
}

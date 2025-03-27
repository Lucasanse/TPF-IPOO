<?php
include_once 'modulo.php';
include_once 'en_linea.php';
include_once 'ingresante.php';
include_once '../Modelo/baseDatos.php';

class inscripcion
{
    private $id;
    private $fecha;
    private $costoFinal;
    private $obj_ingresante;
    private $col_objModulo;
    private $mensajeoperacion;

    //constructor 
    public function __construct()
    {
        $this->id = 0;
        $this->fecha = "";
        $this->obj_ingresante = "";
        $this->col_objModulo = [];
        $this->costoFinal = 0;
    }

    public function cargar($id, $fecha, $obj_ingresante)
    {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->obj_ingresante = $obj_ingresante;
        $this->col_objModulo = $this->extraerModulos();
        if ($this->col_objModulo == null) {
            $this->costoFinal = 0;
            $this->modificar();
        } else {
            $this->costoFinal = $this->darCostoInscripcion();
        }
    }

    // Métodos set 
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function setCostoFinal($costoFinal)
    {
        $this->costoFinal = $costoFinal;
    }

    public function setObj_ingresante($obj_ingresante)
    {
        $this->obj_ingresante = $obj_ingresante;
    }
    public function setCol_objModulo($col_objModulo)
    {
        $this->col_objModulo = $col_objModulo;
    }
    public function setmensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    // Métodos get
    public function getId()
    {
        return $this->id;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getCostoFinal()
    {
        return $this->costoFinal;
    }
    public function getObj_ingresante()
    {
        return $this->obj_ingresante;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeoperacion;
    }
    public function getCol_objModulo()
    {
        return $this->col_objModulo;
    }



    /**
     * crea una cadena con una lista de los modulos incorporados a la inscripción
     */
    public function listarModulos()
    {
        $cadena = "";
        $modulos = $this->getCol_objModulo();
        foreach ($modulos as $modulo) {
            $cadena .= "\n" . "------" . $modulo->toStringBreve();
        }
        return $cadena;
    }
    /**
     * actualiza el costo de la inscripcion teniendo en cuenta los modulos. 
     * @return double
     */
    
    public function darCostoInscripcion()
    {
        $montoFinal = 0;
        $modulos = $this->getCol_objModulo();
        foreach ($modulos as $modulo) {
            $montoFinal += $modulo->darCostoModulo();
        }
        $this->setCostoFinal($montoFinal);
        $this->modificar();
        return $montoFinal;
    }

    /**
     * metodo que, dado un módulo, corrobora de que su actividad no se repita con otra de otro modulo cargado en la inscripción
     * si es true, se repiten.
     * @param unknown $obj_modulo
     * @return boolean
     */
    
    public function actividadRepetida($obj_modulo)
    {
        $respuesta = true;
        $actividades = [];
        $modulos = $this->getCol_objModulo();
        foreach ($modulos as $modulo) {
            array_push($actividades, $modulo->getObj_actividad());
        }
        array_push($actividades, $obj_modulo->getObj_actividad());

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
        $modulos = $this->getCol_objModulo();
        array_push($modulos, $obj_modulo);
        //si no hay elementos repetidos una vez que agregamos el modulo, quiere decir que no contiene dicho modulo
        if (count($modulos) === count(array_unique($modulos))) {
            $res = false;
        }
        return $res;
    }

    /**
     * Función que verifica si existe una actividad pasada por parametro dentro del arreglo de modulos
     * Si es verdadero, la actividad pasada por parametro ya existe en alguno de los módulos
     * @return boolean
     */
    public function existeActividad($obj_actividad)
    {
        $modulos = $this->getCol_objModulo();
        foreach ($modulos as $modulo) {
            if ($modulo->getObj_actividad() == $obj_actividad) {
                return true;
            }
        }
        return false;
    }

    /**
     *  Función que extrae las actividades correspondientes a todos los modulos de la inscripción
     * @return array
     * */
    public function extraerActividades()
    {
        $res = [];
        foreach ($this->getCol_objModulo() as $modulo) {
            array_push($res, $modulo->getObj_actividad());
        }
        return $res;
    }

    /**
     * añade un modulo corroborando que su actividad no se repita con otra de la colección de módulos
     * verifica tambien de que el modulo no este previamente cargado en la inscripcion y que haya cupo de inscriptos
     * @param obj_modulo
     * @return boolean
     */
    public function añadirModulo($obj_modulo)
    {
        $res = false;
        $base = new baseDatos();
        if ($this->actividadRepetida($obj_modulo)) {
            echo "Se está añadiendo un módulo repetido o la actividad del modulo añadido se repite con la actividad de otro módulo\n";
        } else {
            //se verifica si no está lleno el modulo
            if (!$obj_modulo->estaLleno()) {
                //se inserta el dato en la base de datos
                //Se inserta en la tabla Inscripcion_Modulo, si no hay errores, se actualiza el precio de la inscripción 
                $consultaInsertar = "INSERT INTO Inscripcion_Modulo (inscripcion_id, modulo_id) 
				                     VALUES (" . $this->getId() . "," . $obj_modulo->getId() . ")";
                if ($base->Iniciar()) {
                    if ($base->Ejecutar($consultaInsertar)) {
                        $obj_modulo->sumarUnInscripto();
                        $this->setCol_objModulo($this->extraerModulos());
                        $this->darCostoInscripcion();
                    } else {
                        $this->setmensajeoperacion($base->getError());
                    }
                } else {
                    $this->setmensajeoperacion($base->getError());
                }

                //se actualiza el costo de la inscripción

                $res = true;
            } else {
                echo "El módulo elegido ya está lleno\n";
            }
        }

        return $res;
    }

    /**
     * Elimina el objeto modulo de la inscripción
     * Elimina la relación en la base de datos 
     * @return boolean
     */
    public function eliminarModulo($obj_modulo)
    {
        $res = false;
        $base = new baseDatos();
        foreach ($this->getCol_objModulo() as $modulo) {
            if ($modulo == $obj_modulo) {
                //se elimina el elemento, se quita un cupo en el modulo y se vuelve a calcular el precio de la inscripción
                $consultaEliminar = "DELETE FROM Inscripcion_Modulo WHERE inscripcion_id =" . $this->getId() . " AND modulo_id =" . $obj_modulo->getID();
                if ($base->Iniciar()) {
                    if ($base->Ejecutar($consultaEliminar)) {
                        $modulo->restarUnInscripto();
                        $this->darCostoInscripcion();
                        $this->setCol_objModulo($this->extraerModulos());
                        $res = true;
                    } else {
                        $this->setmensajeoperacion($base->getError());
                    }
                } else {
                    $this->setmensajeoperacion($base->getError());
                }
            }
        }
        // Reindexa el arreglo
        $this->col_objModulo = array_values($this->col_objModulo);
        return $res;
    }

    /**
     * funcion que evalua en la base de datos la tabla inscripcion_modulo 
     * @return array con los objetos modulos relacionados a la inscripción
     */
    public function extraerModulos()
    {
        $base = new BaseDatos();
        $modulos = [];
        $consulta = "SELECT M.id FROM Modulo M JOIN Inscripcion_Modulo IM ON M.id = IM.modulo_id WHERE IM.inscripcion_id =" . $this->getId();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row2 = $base->Registro()) {
                    $id = $row2['id'];
                    $modulo = new enLinea();
                    //si el modulo no está en linea 
                    if (!$modulo->buscar($id)) {
                        $modulo = new modulo();
                        $modulo->buscar($id);
                    }
                    array_push($modulos, $modulo);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $modulos;
    }

    /**
     * Función para insertar un módulo a la base de datos 
     * @return boolean
     */
    public function insertar()
    {
        $base = new baseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO inscripcion (fecha, costoFinal, dni, tipoDni) 
				VALUES ('" . $this->getFecha() . "','" . $this->getCostoFinal() . "','" .
            $this->getObj_ingresante() -> getDni(). "','" . $this->getObj_ingresante() -> getTipoDNI(). "')";

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
     * Función que devuelve un arreglo de todos los objetos de esta clase referenciados en la base de datos 
     * @param condicion
     * @return array 
     */
    public function listar($condicion = "")
    {
        $arreglo = null;
        $base = new BaseDatos();

        if ($condicion != "") {
            $consulta = $condicion;
        } else {
            $consulta = "Select * from inscripcion ";
        }
        $consulta .= " order by id ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $arreglo = array();
                while ($row2 = $base->Registro()) {
                    $id = $row2['id'];
                    $fecha = $row2['fecha'];
                    $dni = $row2['dni'];
                    $tipodni = $row2['tipoDni'];

                    $obj_ingresante = new ingresante();
                    $obj_ingresante-> Buscar($dni,$tipodni);

                    $inscripcion = new inscripcion();
                    $inscripcion->cargar($id, $fecha, $obj_ingresante);
                    array_push($arreglo, $inscripcion);
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
     * Función para buscar un objeto en la base de datos 
     * @param id del modulo a buscar
     * @return boolean si se encontró el modulo teniendo en cuenta el id 
     */
    public function Buscar($id)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM inscripcion WHERE id=" . $id;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $this->setID($id);
                    $this->setFecha($row2['fecha']);

                    $obj_ingresante = new ingresante();
                    $obj_ingresante-> Buscar($row2['dni'],$row2['tipoDni']);

                    $this->setObj_ingresante($obj_ingresante);
                    $this->setCol_objModulo($this->extraerModulos());
                    $this->setCostoFinal($this->darCostoInscripcion());
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
     * Añade alguna modificacion a la base de datos en caso de que el objeto haya sido modificado
     * @return boolean 
     */
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE inscripcion 
                           SET fecha='" . $this->getFecha() . "',costoFinal=" . $this->getCostoFinal() .
            ",dni='" . $this->getObj_ingresante()->getDni() . "',tipoDni='" . $this->getObj_ingresante()->getTipoDNI() .
            "' WHERE id=" . $this->getID();

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
        $modulos = $this->getCol_objModulo();
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM inscripcion WHERE id=" . $this->getID();
            if ($base->Ejecutar($consultaBorra)) {
                //se restan inscriptos a los modulos que estaban asociados a la inscripción
                foreach ($modulos as $modulo) {
                    $modulo->restarUnInscripto();
                }
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }


    public function __toString()
    {
        $cadena = "\n\n"
            . "DATO DE LA INSCRIPCION " . $this->getId()
            . " | Fecha: " . $this->getFecha()
            . " | Costo: $" . $this->getCostoFinal()
            . $this->getObj_ingresante()
            . "\n Modulos inscriptos: " . $this->listarModulos();

        return $cadena;
    }
}

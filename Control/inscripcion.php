<?php
include_once 'modulo.php';
include_once 'en_linea.php';
include_once 'ingresante.php';
include_once "BaseDatos.php";

class inscripcion
{
    private $id;
    private $fecha;
    private $costoFinal;
    private $dniIngresante;
    private $tipoDNI;
    private $mensajeoperacion;
    private $col_objModulo;

    //constructor 
    public function __construct($id, $fecha, $dniIngresante, $tipoDNI)
    {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->dniIngresante = $dniIngresante;
        $this->tipoDNI = $tipoDNI;
        $this->col_objModulo = $this->extraerModulos();
        if ($this->col_objModulo == null) {
            $this->costoFinal = 0;
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

    public function setdniIngresante($dniIngresante)
    {
        $this->dniIngresante = $dniIngresante;
    }
    public function setTipoDNI($tipoDNI)
    {
        $this->tipoDNI = $tipoDNI;
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
    public function getdniIngresante()
    {
        return $this->dniIngresante;
    }
    public function getTipoDNI()
    {
        return $this->tipoDNI;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeoperacion;
    }
    public function getCol_objModulo()
    {
        return $this->col_objModulo;
    }




    //crea una cadena con una lista de los modulos incorporados a la inscripción
    public function listarModulos()
    {
        $cadena = "";
        $modulos = $this->getCol_objModulo();
        foreach ($modulos as $modulo) {
            $cadena .= "\n" . "------" . $modulo->toStringBreve();
        }
        return $cadena;
    }

    //actualiza el costo de la inscripcion teniendo en cuenta los modulos. 
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

    //metodo que, dado un módulo, corrobora de que su actividad no se repita con otra de otro modulo cargado en la inscripción
    //si es true, se repiten.
    public function actividadRepetida($obj_modulo)
    {
        $respuesta = true;
        $actividades = [];
        $modulos = $this->getCol_objModulo();
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
        $modulos = $this->getCol_objModulo();
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
        $modulos = $this->getCol_objModulo();
        foreach ($modulos as $modulo) {
            if ($modulo->getObj_actividad() == $obj_actividad) {
                return true;
            }
        }
        return false;
    }

    public function extraerActividades()
    {
        $res = [];
        foreach ($this->getCol_objModulo() as $modulo) {
            array_push($res, $modulo->getObj_actividad());
        }
        return $res;
    }


    //añade un modulo corroborando que su actividad no se repita con otra de la colección de módulos
    // verifica tambien de que el modulo no este previamente cargado en la inscripcion y que haya cupo de inscriptos

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
                        $this->darCostoInscripcion();
                        $this->setCol_objModulo($this->extraerModulos());
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

    //funcion que evalua en la base de datos la tabla inscripcion_modulo y devuelve un arreglo con los objetos modulos
    public function extraerModulos()
    {
        $base = new BaseDatos();
        $modulos = [];
        $consulta = "SELECT M.id FROM Modulo M JOIN Inscripcion_Modulo IM ON M.id = IM.modulo_id WHERE IM.inscripcion_id =" . $this->getId();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row2 = $base->Registro()) {
                    $id = $row2['id'];
                    $modulo = new enLinea(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                    //si el modulo no está en linea 
                    if (!$modulo->buscar($id)) {
                        $modulo = new modulo(0, 0, 0, 0, 0, 0, 0, 0, 0);
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

    public function insertar()
    {
        $base = new baseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO inscripcion (fecha, costoFinal, dni, tipoDni) 
				VALUES ('" . $this->getFecha() . "','" . $this->getCostoFinal() . "','" .
            $this->getdniIngresante() . "','" . $this->getTipoDNI() . "')";

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

                    $inscripcion = new inscripcion($id, $fecha, $dni, $tipodni);
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
                    $this->setdniIngresante($row2['dni']);
                    $this->setTipoDNI($row2['tipoDni']);
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


    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE inscripcion 
                           SET fecha='" . $this->getFecha() . "',costoFinal=" . $this->getCostoFinal() .
            ",dni='" . $this->getdniIngresante() . "',tipoDni='" . $this->getTipoDNI() .
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
                    echo $this;
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
            . " | Ingresante con DNI: " . $this->getTipoDNI() . " " . $this->getdniIngresante()
            . " | Costo: $" . $this->getCostoFinal()
            . "\n Modulos: " . $this->listarModulos();

        return $cadena;
    }
}

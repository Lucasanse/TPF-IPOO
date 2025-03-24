<?php
include_once '../Modelo/baseDatos.php';

class ingresante
{

    private $dni;
    private $tipoDNI;
    private $nombre;
    private $apellido;
    private $legajo;
    private $correo;
    private $mensajeoperacion;

    //Constructor
    public function __construct()
    {
        $this->dni = "";
        $this->tipoDNI = "";
        $this->nombre = "";
        $this->apellido = "";
        $this->legajo = "";
        $this->correo = "";
    }

    public function cargar($dni, $tipoDNI, $nombre, $apellido, $legajo, $correo)
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
    public function setmensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
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
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
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
            $consulta = "Select * from ingresante ";
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $arreglo = array();
                while ($row2 = $base->Registro()) {
                    $dni = ($row2['dni']);
                    $tipoDNI = ($row2['tipoDni']);
                    $nombre = ($row2['nombre']);
                    $apellido = ($row2['apellido']);
                    $legajo = ($row2['legajo']);
                    $correo = ($row2['correo']);

                    $ingresante = new ingresante();
                    $ingresante->cargar($dni, $tipoDNI, $nombre, $apellido, $legajo, $correo);
                    array_push($arreglo, $ingresante);
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
    public function Buscar($dni, $tipoDni)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * from ingresante where dni='" . $dni . "' AND tipoDni='" . $tipoDni . "'";
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $this->setdni($dni);
                    $this->setTipoDNI($tipoDni);
                    $this->setNombre($row2['nombre']);
                    $this->setApellido($row2['apellido']);
                    $this->setLegajo($row2['legajo']);
                    $this->setCorreo($row2['correo']);
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

    public function estaInscripto()
    {
        $base = new BaseDatos();
        $consulta = "SELECT COUNT(*) AS total FROM Inscripcion  where dni='" . $this->getDni() . "' AND tipoDni='" . $this->getTipoDNI() . "'";
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    if ($row2['total'] > 0) {
                        $resp = true;
                    }
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
     * Función para insertar un módulo a la base de datos 
     * @return boolean
     */
    public function insertar()
    {
        $base = new baseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO Ingresante (dni, tipoDni, nombre, apellido, legajo, correo) 
				VALUES ('" . $this->getDni() . "','" . $this->getTipoDNI() . "','" . $this->getNombre() . "','" .
            $this->getApellido() . "','" . $this->getLegajo() . "','" . $this->getCorreo() . "')";


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

    /**
     * Añade alguna modificacion a la base de datos en caso de que el objeto haya sido modificado
     * @return boolean 
     */
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE ingresante 
                           SET nombre='" . $this->getNombre() . "',apellido='" . $this->getApellido() .
            "',legajo='" . $this->getLegajo() . "',correo='" . $this->getCorreo() .
            "' WHERE dni='" . $this->getDni() . "' AND tipoDni='" . $this->getTipoDNI() . "'";
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
            $consultaBorra = "DELETE FROM ingresante WHERE dni='" . $this->getDni() . "' AND tipoDni='" . $this->getTipoDNI() . "'";
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

    //función to string para visualizar los datos del ingresante

    public function __toString()
    {
        $cadena = "\n"
            . "DATO DEL INGRESANTE: " . $this->getNombre()
            . " " . $this->getApellido()
            . " | " . $this->getTipoDNI()
            . " " . $this->getDni()
            . " | " . $this->getLegajo()
            . " | " . $this->getCorreo();
        return $cadena;
    }
}

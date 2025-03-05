<?php
include_once "BaseDatos.php";

class actividad
{

    private $id;
    //descripción corta y larga
    private $descC;
    private $descL;
    private $mensajeoperacion;

    //Constructor
    public function __construct()
    {
        $this->id = "";
        $this->descC = "";
        $this->descL = "";
    }

    //constructor para cargar datos
    public function cargar($id, $descC, $descL){
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
    public function setmensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
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
    public function getmensajeoperacion()
    {
        return $this->mensajeoperacion;
    }


    public function __toString()
    {
        $cadena = "ACTIVIDAD NRO " . $this->getID()
            . ": " . $this->getDescripcionCorta()
            . " | " . $this->getDescripcionLarga()
            . "\n";
        return $cadena;
    }

      /**
     * Función que inserta una actividad en la base de datos
     * @return boolean si se logró insertar correctamente
     */
    public function insertar()
    {
        $base = new baseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO actividad (descripcionCorta, descripcionLarga) 
				VALUES ('" . $this->getDescripcionCorta() . "','" . $this->getDescripcionLarga() . "')";


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
     * Función que busca si existe una actividad con ese ID en la base de datos
     * Si se encuentra, se le asigna al objeto los valores del ID encontrado
     * @param int $id de la actividad
     * @return boolean si se encontró
     */
    public function Buscar($id){
		$base=new BaseDatos();
		$consulta="Select * from actividad where id=".$id;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consulta)){
				if($row2=$base->Registro()){
				    $this->setID($id);
					$this->setDescripcionCorta($row2['descripcionCorta']);
					$this->setDescripcionLarga($row2['descripcionLarga']);
					$resp= true;
				}				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }		
		 return $resp;
	}	


 /**
     * Función que lista en un arreglo las actividades de la base de datos 
     * @param string $condición 
     * @return array con las actividades
     */
    public function listar($condicion=""){
	    $arreglo = null;
		$base=new BaseDatos();
        if ($condicion != "") {
            $consulta = $condicion;
        } else {
            $consulta = "Select * from actividad ";
            
        }
        $consulta .= " order by id ";
        
		if($base->Iniciar()){
			if($base->Ejecutar($consulta)){				
				$arreglo= array();
				while($row2=$base->Registro()){
				    $id=$row2['id'];
					$descripcionCorta=$row2['descripcionCorta'];
					$descripcionLarga=$row2['descripcionLarga'];
				
					$actividad=new actividad();
                    $actividad -> cargar($id, $descripcionCorta, $descripcionLarga);
					array_push($arreglo,$actividad);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arreglo;
	}
    /**
     * Función que modifica algún atributo del objeto en la base de datos  
     * @return boolean en caso si se modificó con exito
     */
    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE actividad 
                           SET descripcionCorta='".$this->getDescripcionCorta()."',descripcionLarga='".$this->getDescripcionLarga().
                           "' WHERE id=".$this->getID();
						   
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}

    /**
     * Función que elimina el objeto en la base de datos 
     * @return boolean en caso si se eliminó con exito
     */
    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM actividad WHERE id=".$this->getID();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());
					
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}
}

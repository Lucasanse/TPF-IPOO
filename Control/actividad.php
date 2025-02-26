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

    public function listar($condicion=""){
	    $arreglo = null;
		$base=new BaseDatos();
		$consulta="Select * from actividad ";
		if ($condicion!=""){
		    $consulta=$consulta.' where '.$condicion;
		}
		$consulta.=" order by id ";
		//echo $consulta;
		if($base->Iniciar()){
			if($base->Ejecutar($consulta)){				
				$arreglo= array();
				while($row2=$base->Registro()){
				    $id=$row2['id'];
					$descripcionCorta=$row2['descripcionCorta'];
					$descripcionLarga=$row2['descripcionLarga'];
				
					$actividad=new actividad($id, $descripcionCorta, $descripcionLarga);
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

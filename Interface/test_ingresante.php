<?php
include_once '../Control/ingresante.php';
include_once '../Control/modulo.php';
include_once '../Control/en_linea.php';
include_once '../Control/inscripcion.php';
include_once '../Control/actividad.php';


// ------------ variables donde se almacenan los datos ---------------
$actividades = [];
$modulos = [];
$inscripciones = [];
$ingresantes = [];

// ------------ funciones ---------------


//función que muestra por pantalla el menú
function menu()
{
    echo "\n------ MENÚ ------\n";
    echo "1. ABM actividades\n";
    echo "2. ABM módulos\n";
    echo "3. ABM inscripciones\n";
    echo "4. Visualizar las inscripciones realizadas\n";
    echo "5. Visualizar las inscripciones realizadas a un modulo determinado\n";
    echo "6. Visualziar las inscripciones ralizadas a una actividad determinada\n";
    echo "7. Registros que poseen el mismo DNI y aparecen mas de una vez dado un módulo\n";
    echo "8. Visualizar la informacion de todas las actividades que se inscribió un ingresante\n";
    echo "9. Visualizar las actividades \n";
    echo "10. Visualizar los modulos \n";
    echo "0. Terminar programa\n";
    echo "Seleccione una opción: ";
}

//alta de una actividad, da como respuesta un booleano. Si es falso, es porque ya existe el ID. 
function altaActividad()
{
    global $actividades;
    $res = false;
    echo "Ingrese una descripción corta para la actividad:\n";
    $descC = trim(fgets(STDIN));
    echo "Ingrese una descripción larga para la actividad:\n";
    $descL = trim(fgets(STDIN));
    $obj_actividad = new actividad(0, $descC, $descL);
    $obj_actividad ->insertar();
    //se agrega la actividad al arreglo de actividades
    array_push($actividades, $obj_actividad);
    echo "Se añadió : \n" . $obj_actividad . "\n";
    $res = true;

    return $res;
}
//función que da de alta un modulo, ingresa por parametro una variable booleana que indica si se trata de un modulo en linea o no
//si el parametro de entrada es true, ingresa se da de alta un modulo en linea. 
function altaModulo($enLinea, $id)
{
    global $modulos, $actividades;
    $res = false;
    echo "Ingrese el ID de la actividad correspondiente al módulo: ";
    $idActividad = trim(fgets(STDIN));
    $obj_actividad = buscarID($actividades, $idActividad);
    if ($obj_actividad != null) {
        echo "Ingrese una descripción para el módulo: \n";
        $descM = trim(fgets(STDIN));
        echo "Ingrese el horario de inicio del módulo: \n";
        $horarioIni = trim(fgets(STDIN));
        echo "Ingrese el horario de cierre del módulo: \n";
        $horarioCierre = trim(fgets(STDIN));
        echo "Ingrese la fecha de inicio del módulo: \n";
        $fechaI = trim(fgets(STDIN));
        echo "Ingrese la fecha de finalización del módulo: \n";
        $fechaF = trim(fgets(STDIN));
        echo "Ingrese el tope de inscripciones: \n";
        $tope = trim(fgets(STDIN));
        echo "Ingrese el costo del módulo: \n";
        $costo = trim(fgets(STDIN));
        if ($enLinea) {
            echo "Ingrese el link de llamada para el módulo en linea: \n";
            $link = trim(fgets(STDIN));
            echo "Ingrese el % de bonificación \n";
            $bonificacion = trim(fgets(STDIN));
            $obj_modulo = new enLinea($id, $descM, $horarioIni, $horarioCierre, $fechaI, $fechaF, $tope, $costo, $obj_actividad, $link, $bonificacion);
        } else {
            $obj_modulo = new modulo($id, $descM, $horarioIni, $horarioCierre, $fechaI, $fechaF, $tope, $costo, $obj_actividad);
        }
        array_push($modulos, $obj_modulo);
        echo "Se añadió con exito: " . $obj_modulo . "\n";
        $res = true;
    } else {
        echo "No existe actividad con ese ID\n";
    }
    return $res;
}



function altaInscripcion($id)
{
    global $inscripciones, $ingresantes;
    echo "Ingrese el DNI de la persona que se va a inscribir ";
    $dni = trim(fgets(STDIN));
    //verificamos de que exista el estudiante y que no esté previamente inscripto
    $obj_ingresante = buscarDNI($ingresantes, $dni);
    if ($obj_ingresante != null && !yaEstaiInscripto($obj_ingresante)) {
        echo "Ingrese una fecha de realización: \n";
        $fecha = trim(fgets(STDIN));
        $obj_inscripcion = new inscripcion($id, $fecha, [], $obj_ingresante);
        //se añaden los módulos
        añadirModuloAUnaInscripción($obj_inscripcion);
        //añadimos la inscripcion al arreglo de inscripciones
        array_push($inscripciones, $obj_inscripcion);
        echo "se añadió: " . $obj_inscripcion . "\n";
        $res = true;
    } else {
        echo "El ingresante no existe o ya está inscripto\n";
    }
    return $res;
}



//funcion que busca una actividad y la modifica. Devuelve booleano, si es falso es porque no encontró la actividad
//NO se puede modificar el ID
function modificacionActividad($obj_actividad)
{
    $res = true;
    echo "¿Que desea modificar de la actividad " . $obj_actividad->getId() . "?\n";
    echo "1. Descripción corta \n";
    echo "2. Descripción larga \n";
    echo "0. salir\n";
    echo "Seleccione una opción: ";
    $opcion = trim(fgets(STDIN));
    switch ($opcion) {
        case 1:
            echo "Ingrese la nueva descripcion corta: \n";
            $dato = trim(fgets(STDIN));
            $obj_actividad->setDescripcionCorta($dato);
            break;

        case 2:
            echo "Ingrese la nueva descripcion larga: \n";
            $dato = trim(fgets(STDIN));
            $obj_actividad->setDescripcionLarga($dato);
            break;

        default:
            break;
    }
    $res = $obj_actividad -> modificar();
    return $res;
}

function modificacionModulo($enLinea,  $obj_modulo)
{
    global $actividades;
    $res = false;
    //verifica si coincide el tipo de modulo elegido con el pasado por parámetro
    if ($enLinea == $obj_modulo->esModuloEnLinea()) {
        $res = true;
        echo "¿Que desea modificar del modulo " . $obj_modulo->getId() . "?\n";
        echo "1. Descripción \n";
        echo "2. Horario de inicio \n";
        echo "3. Horario de fin \n";
        echo "4. Fecha de inicio \n";
        echo "5. Fecha de fin \n";
        echo "6. Tope de inscripciones \n";
        echo "7. Costo \n";
        echo "8. La actividad \n";
        if ($enLinea) {
            echo "9. Link de llamada \n";
            echo "10. La bonificación \n";
        }
        echo "0. salir\n";
        echo "Seleccione una opción: ";
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                echo "Ingrese la nueva descripcion: \n";
                $dato = trim(fgets(STDIN));
                $obj_modulo->setDescripcion($dato);
                break;

            case 2:
                echo "Ingrese la nueva hora de inicio: \n";
                $dato = trim(fgets(STDIN));
                $obj_modulo->setHorarioInicio($dato);
                break;
            case 3:
                echo "Ingrese la nueva hora de fin: \n";
                $dato = trim(fgets(STDIN));
                $obj_modulo->setHorarioCierre($dato);
                break;
            case 4:
                echo "Ingrese la nueva fecha de inicio: \n";
                $dato = trim(fgets(STDIN));
                $obj_modulo->setFechaInicio($dato);
                break;
            case 5:
                echo "Ingrese la nueva fecha de fin: \n";
                $dato = trim(fgets(STDIN));
                $obj_modulo->setFechaFin($dato);
                break;
            case 6:
                echo "Ingrese el nuevo tope de inscripciones : \n";
                $dato = trim(fgets(STDIN));
                $obj_modulo->setTopeInscripciones($dato);
                break;
            case 7:
                echo "Ingrese el nuevo costo: \n";
                $dato = trim(fgets(STDIN));
                $obj_modulo->setCosto($dato);
                break;
            case 8:
                echo "Ingrese el ID de la actividad nueva: \n";
                $dato = trim(fgets(STDIN));
                $obj_actividad = buscarID($actividades, $dato);
                if ($obj_actividad != null) {
                    $obj_modulo->setObj_actividad($obj_actividad);
                } else {
                    echo "La actividad con ID " . $dato . " no existe\n";
                }
                break;
            case 9:
                if ($enLinea) {
                    echo "Ingrese el nuevo link de llamada: \n";
                    $dato = trim(fgets(STDIN));
                    $obj_modulo->setLinkLlamada($dato);
                } else {
                    echo "Opción válida solo para módulos en línea\n";
                }
                break;
            case 10:
                if ($enLinea) {
                    echo "Ingrese la nueva bonificación: \n";
                    $dato = trim(fgets(STDIN));
                    $obj_modulo->setBonificacion($dato);
                } else {
                    echo "Opción válida solo para módulos en línea\n";
                }
                break;
            case 0:
                break;
            default:
                echo "Opción no válida\n";
                break;
        }
    }

    return $res;
}

//funcion para modificar los datos de la inscripcion
//No se puede cambiar su ID ni su persona inscripta
function modificacionInscripcion($obj_inscripcion)
{
    $res = true;
    echo "¿Que desea modificar de la inscripción " . $obj_inscripcion->getId() . "?\n";
    echo "1. Fecha de inscripción \n";
    echo "2. Añadir modulos \n";
    echo "3. Eliminar módulos \n";
    echo "0. salir\n";
    echo "Seleccione una opción: ";
    $opcion = trim(fgets(STDIN));
    switch ($opcion) {
        case 1:
            echo "Ingrese la nueva fecha: \n";
            $dato = trim(fgets(STDIN));
            $obj_inscripcion->setFecha($dato);
            break;
        case 2:
            añadirModuloAUnaInscripción($obj_inscripcion);
            break;
        case 3:
            eliminarModuloAUnaInscripcion($obj_inscripcion);
            break;
        case 0:
            break;
        default:
            echo "No es una opción válida";
            break;
    }

    return $res;
}
//funcion que da de baja una actividad
//como un modulo requiere de una actividad, si se elimina una actividad se eliminan los modulos que estén asociadas a ellas
function bajaActividad($obj_actividad)
{
    $res = $obj_actividad -> eliminar();
    return $res;
}

//funcion que da de baja un modulo
//se elimina para todas las inscripciones 
function bajaModulo($obj_modulo)
{
    global $modulos, $inscripciones;
    $res = false;
    foreach ($modulos as $indice => $modulo) {
        if ($modulo == $obj_modulo) {
            //se elimina el elemento del arreglo principal
            unset($modulos[$indice]);
            // Reindexa el arreglo
            $modulos = array_values($modulos);
            //se elimina el módulo en las inscripciones que lo contengan
            foreach ($inscripciones as $inscripcion) {
                $inscripcion->eliminarModulo($obj_modulo);
            }
            $res = true;
        }
    }
    echo "Se eliminó: " . $obj_modulo;
    return $res;
}

//funcion que da de baja una inscripción
function bajaInscripcion($obj_inscripcion)
{
    global $inscripciones;
    $res = false;
    //se busca la posición del arreglo donde esté la inscripción
    foreach ($inscripciones as $indice => $inscripcion) {
        if ($inscripcion == $obj_inscripcion) {
            //se elimina el elemento
            unset($inscripciones[$indice]);
            // Reindexa el arreglo
            $inscripciones = array_values($inscripciones);
            $res = true;
        }
    }
    echo "Se eliminó: " . $obj_inscripcion;

    return $res;
}

//menu que nos permite dar de alta, baja o modificar una actividad
function abmActividad()
{
    global $actividades;
    echo "¿Qué desea hacer?\n";
    echo "1. Dar de alta una actividad \n";
    echo "2. Dar de baja una actividad \n";
    echo "3. Modificar una actividad  \n";
    echo "0. salir\n";
    echo "Seleccione una opción: ";
    $opcion = trim(fgets(STDIN));
    switch ($opcion) {
        case 1:
                if (altaActividad()) {
                    echo "Se añadio correctamente la actividad\n";
                } else {
                    echo "No se añadió correctamente la actividad\n";
                }
            break;
        case 2:
            echo "Ingrese el ID de la actividad: ";
            $id = trim(fgets(STDIN));
            //Busca ID por la funcion buscar ID, si no lo encuentra va a devolver null
            $obj = new actividad(0,"","");
            $obj_actividad = buscarID2($obj, $id);
            if ($obj_actividad != null) {
                if (bajaActividad($obj_actividad)) {
                    echo "Se eliminó correctamente la actividad \n";
                } else {
                    echo "No se eliminó correctamente la actividad\n";
                }
            } else {
                echo "No se encuentra ID de la actividad\n";
            };
            break;
        case 3:
            echo "Ingrese el ID de la actividad: ";
            $id = trim(fgets(STDIN));
            //Busca ID por la funcion buscar ID, si no lo encuentra va a devolver null
            $obj = new actividad(0,"","");
            $obj_actividad = buscarID2($obj, $id);
            if ($obj_actividad != null) {
                if (modificacionActividad($obj_actividad)) {
                    echo "Se modifico correctamente la actividad\n";
                } else {
                    echo "No se pudo modificar la actividad";
                }
            } else {
                echo "No se encuentra ID de la actividad\n";
            };
            break;
        case 0:
            break;
        default:
            echo "No existe esa opción";
    }
}
//funcion para dar de alta, baja y modificacion de modulos comunes y en linea 
function abmModulo()
{
    global $modulos;
    //Variable que indica si estamos trabajando con un modulo en linea o no
    $enLinea = false;
    echo "¿Qué desea hacer?\n";
    echo "1. Dar de alta un módulo \n";
    echo "2. Dar de alta un módulo en linea\n";
    echo "3. Modificar un modulo  \n";
    echo "4. Modificar un modulo en linea  \n";
    echo "5. dar de baja un modulo (común o en linea) \n";
    echo "0. salir\n";
    $opcion = trim(fgets(STDIN));
    echo "Ingrese el ID del modulo: ";
    $id = trim(fgets(STDIN));
    //si el usuario eligió la opcion 2 o 4, trabajará en un módulo en línea
    if ($opcion == 2 || $opcion == 4) {
        $enLinea = true;
    }
    //Busca ID por la funcion buscar ID, si no lo encuentra va a devolver null (si es null, es que no existe)
    $obj_modulo = buscarID($modulos, $id);
    switch ($opcion) {
        case 1:
        case 2:
            if ($obj_modulo == null) {
                if (altaModulo($enLinea, $id)) {
                    echo "Se añadio correctamente el módulo\n";
                } else {
                    echo "No se pudo dar de alta el módulo\n";
                };
            } else {
                echo "El ID del modulo que deseas ingresar ya existe\n";
            }
            break;
        case 3:
        case 4:
            if ($obj_modulo != null) {
                if (modificacionModulo($enLinea, $obj_modulo)) {
                    echo "Se modifico correctamente el módulo \n";
                } else {
                    echo "No se pudo modificar el módulo. No se encontró el ID o el modulo seleccionado era un módulo en linea\n";
                }
            } else {
                echo "El ID del modulo que deseas ingresar no existe\n";
            }
            break;
        case 5:
            if ($obj_modulo != null) {
                if (bajaModulo($obj_modulo)) {
                    echo "Se eliminó con exito\n";
                } else {
                    echo "No se pudo eliminar el módulo\n";
                };
            } else {
                echo "El ID del modulo que deseas ingresar no existe\n";
            }
            break;
        case 0:
            break;
        default:
            echo "No existe esa opción";
    }
}

function abmAInscripcion()
{
    global $inscripciones;
    echo "¿Qué desea hacer?\n";
    echo "1. Dar de alta una inscripción \n";
    echo "2. Dar de baja una inscripción \n";
    echo "3. Modificar una inscripción  \n";
    echo "0. salir\n";
    echo "Seleccione una opción: ";
    $opcion = trim(fgets(STDIN));
    echo "Ingrese el ID de la inscripción: ";
    $id = trim(fgets(STDIN));
    $obj_inscripcion = buscarID($inscripciones, $id);
    switch ($opcion) {
        case 1:
            if ($obj_inscripcion == null) {
                if (altaInscripcion($id)) {
                    echo "Se añadio correctamente la inscripción\n";
                } else {
                    echo "No se pudo dar de alta la inscripción\n";
                }
            } else {
                echo "Ya existe una inscripción con ese ID \n";
            }

            break;
        case 2:
            if ($obj_inscripcion != null) {
                if (bajaInscripcion($obj_inscripcion)) {
                    echo "Se eliminó correctamente la inscripción\n";
                } else {
                    echo "No se pudo realizar la inscripción\n";
                }
            } else {
                echo "No existe una inscripción con ese ID \n";
            }
            break;
        case 3:
            if ($obj_inscripcion != null) {
                if (modificacionInscripcion($obj_inscripcion)) {
                    echo "Se modifico correctamente la inscripción\n";
                } else {
                    echo "No se pudo modificar la inscripcion\n";
                }
            } else {
                echo "No existe una inscripción con ese ID \n";
            }
            break;
        case 0:
            break;
        default:
            echo "No existe esa opción";
    }
}

//Funcion que dado un arreglo y un ID retorna el objeto con ese ID
//En caso de no encontrarlo retorna null
//Las clases que funcionan con buscar ID: actividad, en_linea, inscripcion y modulo 
function buscarID($arreglo, $id)
{
    foreach ($arreglo as $objeto) {
        if ($objeto->getId() == $id) {
            return $objeto;
        }
    }
    return null;
}

function buscarID2($obj, $id)
{
    $arreglo = $obj -> listar();
    foreach ($arreglo as $objeto) {
        if ($objeto->getId() == $id) {
            return $objeto;
        }
    }
    return null;
}

//Funcion que dado un arreglo busca una persona teniendo en cuenta su DNI
//En caso de no encontrarlo retorna null
//Las clases que funcionan con buscar DNI: ingresante
function buscarDNI($arreglo, $dni)
{
    foreach ($arreglo as $objeto) {
        if ($objeto->getDni() == $dni) {
            return $objeto;
        }
    }
    return null;
}

//funcion que muestra por pantalla todas las inscripciones correspondiente a un ID de un modulo dado por el usuario
function inscripcionesPorModulo()
{
    global $modulos, $inscripciones;
    echo " Ingresar ID del módulo: \n";
    $id = trim(fgets(STDIN));
    $moduloElegido = buscarID($modulos, $id);
    if ($moduloElegido != null) {
        echo "Visualización de de incripciones realizadas al modulo con ID: " . $id . "\n";
        foreach ($inscripciones as $inscripcion) {
            if ($inscripcion->existeModulo($moduloElegido)) {
                echo $inscripcion . "\n";
            }
        }
    } else {
        echo "no se encontró el módulo";
    }
}
// funcion que dada una actividad que seleccione el usuario va a devolver todas las inscripciones relacionadas a esa actvidad
function inscripcionesPorActividad()
{
    global $actividades, $inscripciones;
    echo " Ingresar ID de la actividad: \n";
    $id = trim(fgets(STDIN));
    $obj_actividad = buscarID($actividades, $id);
    if ($obj_actividad != null) {
        echo "Visualización de de incripciones realizadas con la actividad: " . $obj_actividad . "\n";
        foreach ($inscripciones as $inscripcion) {
            if ($inscripcion->existeActividad($obj_actividad)) {
                echo $inscripcion . "\n";
            }
        }
    } else {
        echo "no se encontró la actividad";
    }
}


//función que verifica si un ingresante ya está inscripto
function yaEstaiInscripto($obj_ingresante)
{
    global $inscripciones;
    foreach ($inscripciones as $inscripcion) {
        if ($inscripcion->getIngresante() == $obj_ingresante) {
            return true;
        }
    }
    return false;
}

//funcion que añade un modulo a una inscripcion pasada por parametro
function añadirModuloAUnaInscripción($obj_inscripcion)
{
    global $modulos;
    $id = 1;
    while ($id != 0) {
        echo " Ingresar ID del módulo que quieras agregar\n";
        echo " Si no queres agregar, seleccioná 0: ";
        $id = trim(fgets(STDIN));
        if ($id != 0) {
            $moduloElegido = buscarID($modulos, $id);
            if ($moduloElegido != null) {
                if ($obj_inscripcion->añadirModulo($moduloElegido)) {
                    echo "Modulo añadido\n";
                }
            }
        }
    }
}
//funcion que elimina un modulo a una inscripcion pasada por parametro
function eliminarModuloAUnaInscripcion($obj_inscripcion)
{
    global $modulos;
    $id = 1;
    while ($id != 0) {
        echo " Ingresar ID del módulo que quieras eliminar\n";
        echo " Si no queres eliminar más, seleccioná 0: ";
        $id = trim(fgets(STDIN));
        if ($id != 0) {
            $moduloElegido = buscarID($modulos, $id);
            if ($moduloElegido != null) {
                if ($obj_inscripcion->eliminarModulo($moduloElegido)) {
                    echo "Modulo eliminado\n";
                } else {
                    echo "No se pudo eliminar el módulo porque no está\n";
                }
            }
        }
    }
}
//funcion que al buscar un ingresante, devuelve todas las actividades en las que está inscripto
function actividadesPorIngresantes(){
    global $inscripciones, $ingresantes;
    $res = [];
    echo " Ingresar el DNI del estudiante: \n";
    $dni = trim(fgets(STDIN));
    $ingresanteElegido = buscarDNI($ingresantes, $dni);
    if ($ingresanteElegido != null) {
        foreach($inscripciones as $inscripcion){
            if ($inscripcion -> getIngresante() == $ingresanteElegido){
                $res = $inscripcion -> extraerActividades();
                break;
            }
        }
    } else {
        echo "No se encuentra ingresante con ese DNI";
    }
    return $res;
}


// ----------- PRECARGA DE DATOS -------------

// CARGA DE INGRESANTES
$obj_ingresante1 = new ingresante(40994956, "DNI", "Lucas", "San Segundo", "FAI - 1921", "Lucas@gmail.com");
$obj_ingresante2 = new ingresante(37930123, "DNI", "Claudia", "Maglieto", "FAI - 1122", "Claudia@gmail.com");
$obj_ingresante3 = new ingresante(40333333, "Libreta", "Wilson", "Condori", "FAI - 2000", "Wilson@gmail.com");
$obj_ingresante4 = new ingresante(12345678, "DNI", "Carmen", "Gonzalez", "FAI - 2332", "Carmen@gmail.com");
array_push($ingresantes, $obj_ingresante1, $obj_ingresante2, $obj_ingresante3, $obj_ingresante4);


// CARGA DE ACTIVIDADES

$obj_actividad1 = new actividad(1, "Curso matemáticas", "Descripción larga");
$obj_actividad2 = new actividad(2, "Taller de robótica", "Descripción larga");
$obj_actividad3 = new actividad(3, "Charla de liderazgo", "Descripción larga");
$obj_actividad4 = new actividad(4, "Curso de álgebra", "Descripción larga");
array_push($actividades, $obj_actividad1, $obj_actividad2, $obj_actividad3, $obj_actividad4);


// CARGA DE MODULOS

$obj_modulo1 = new modulo(1, "Matematica", "10:30", "13:00", "01/05/2025", "01/08/2025", 4, 1200, $obj_actividad1);
$obj_modulo2 = new modulo(2, "Robotica", "11:30", "14:30", "01/05/2025", "01/08/2025", 20, 1200, $obj_actividad2);
$obj_modulo3 = new modulo(3, "Lideres", "12:30", "17:10", "01/05/2025", "01/08/2025", 1, 1200, $obj_actividad3);


// CARGA DE MODULOS EN LINEA 

$obj_enLinea1 = new enLinea(4, "Matematica en linea", "10:30", "13:00", "01/05/2025", "01/08/2025", 20, 1200, $obj_actividad1, "www.google.com", 20);
$obj_enLinea2 = new enLinea(5, "Robotica en linea", "13:30", "15:00", "03/05/2025", "03/08/2025", 20, 1200, $obj_actividad2, "www.google.com", 20);

//CREACION DE ARREGLOS DE MODULOS 
array_push($modulos, $obj_enLinea1, $obj_enLinea2, $obj_modulo1, $obj_modulo2, $obj_modulo3);


// CARGA DE INSCRIPCIONES 
$obj_inscripcion1 = new inscripcion(1, "01/03/25", [], $obj_ingresante1);
$obj_inscripcion1->añadirModulo($obj_enLinea1);
$obj_inscripcion1->añadirModulo($obj_modulo2);

$obj_inscripcion2 = new inscripcion(2, "02/03/25", [], $obj_ingresante2);
$obj_inscripcion2->añadirModulo($obj_enLinea2);
$obj_inscripcion2->añadirModulo($obj_modulo1);

$obj_inscripcion3 = new inscripcion(3, "03/03/25", [], $obj_ingresante3);
$obj_inscripcion3->añadirModulo($obj_modulo1);
$obj_inscripcion3->añadirModulo($obj_modulo2);
$obj_inscripcion3->añadirModulo($obj_modulo3);

//arreglo de inscripciones
array_push($inscripciones, $obj_inscripcion1, $obj_inscripcion2, $obj_inscripcion3);



// ------------------------ MENU PRINCIPAL ------------------------------

while (true) {

    // Mostramos el menú
    menu();

    // Leemos la opción del usuario
    $opcion = trim(fgets(STDIN));
    echo "\n";

    // Evaluamos la opción con switch
    switch ($opcion) {
        case '1':
            abmActividad();
            break;
        case '2':
            abmModulo();
            break;
        case '3':
            abmAInscripcion();
            break;
        case '4':
            echo " ------- Inscripciones: ----------\n";
            foreach ($inscripciones as $inscripcion) {
                echo $inscripcion . "\n";
            }
            break;
        case '5':
            inscripcionesPorModulo();
            break;
        case '6':
            inscripcionesPorActividad();
            break;
        case '7':

            break;
        case '8':
            $arregloActividades = actividadesPorIngresantes();
            if(!empty($arregloActividades)){
                echo "Las actividades que está inscripto el alumno son: \n";
                foreach ($arregloActividades as $actividad){
                    echo $actividad;
                }
            }
            break;
        case '9':
            $objActividad = new actividad(0,"","");
            $actividades = $objActividad -> listar();
            echo " ------- Actividades: ----------\n";
            foreach ($actividades as $actividad) {
                echo $actividad . "\n";
            }
            break;
        case '10':
            echo " ------- Modulos: ----------\n";
            foreach ($modulos as $modulo) {
                echo $modulo . "\n";
            }
            break;
        case '11':
            $obj_actividad1 -> setDescripcionCorta("CARMEN");
            $obj_actividad1 ->modificar();
            break;

        case '0':
            echo "Saliendo del programa...\n";
            exit;

        default:
            echo "Opción no válida. Intente de nuevo.\n";
    }
}


//abm modulos e inscripciones 

//Buscar dado un módulo todos aquellos registros que poseen el mismo DNI y aparecen mas de una vez



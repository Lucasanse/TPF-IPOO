<?php
include_once '../Control/ingresante.php';
include_once '../Control/modulo.php';
include_once '../Control/en_linea.php';
include_once '../Control/inscripcion.php';
include_once '../Control/actividad.php';



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
    $obj_actividad->insertar();
    //se agrega la actividad al arreglo de actividades
    array_push($actividades, $obj_actividad);
    echo "Se añadió : \n" . $obj_actividad . "\n";
    $res = true;

    return $res;
}
//función que da de alta un modulo, ingresa por parametro una variable booleana que indica si se trata de un modulo en linea o no
//si el parametro de entrada es true, ingresa se da de alta un modulo en linea. 
function altaModulo($enLinea)
{
    $res = false;
    echo "Ingrese el ID de la actividad correspondiente al módulo: ";
    $idActividad = trim(fgets(STDIN));
    $obj_actividad = new actividad(0, "", "");
    if ($obj_actividad->Buscar($idActividad)) {
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
            $obj_modulo = new enLinea(0, $descM, $horarioIni, $horarioCierre, $fechaI, $fechaF, $tope, $costo, $idActividad, $link, $bonificacion);
            $obj_modulo->insertar();
        } else {
            $obj_modulo = new modulo(0, $descM, $horarioIni, $horarioCierre, $fechaI, $fechaF, $tope, $costo, $idActividad);
            $obj_modulo->insertar();
        }
        echo "Se añadió con exito: " . $obj_modulo . "\n";
        $res = true;
    } else {
        echo "No existe actividad con ese ID\n";
    }
    return $res;
}



function altaInscripcion()
{
    $res = false;
    echo "Ingrese el DNI de la persona que se va a inscribir ";
    $dni = trim(fgets(STDIN));
    echo "Ingrese el Tipo de DNI de la persona que se va a inscribir ";
    $tipoDni = trim(fgets(STDIN));
    //verificamos de que exista el estudiante y que no esté previamente inscripto
    $obj_ingresante = new ingresante(0, 0, 0, 0, 0, 0);
    if ($obj_ingresante->buscar($dni, $tipoDni) && !$obj_ingresante->estaInscripto()) {
        echo "Ingrese una fecha de realización: \n";
        $fecha = trim(fgets(STDIN));
        $obj_inscripcion = new inscripcion(0, $fecha, $dni, $tipoDni);
        //añadimos la inscripcion a la base de dtos
        if ($obj_inscripcion->insertar()) {
            //se añaden los módulos
            añadirModuloAUnaInscripción($obj_inscripcion);
            echo "se añadió: " . $obj_inscripcion . "\n";
            $res = true;
        }
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
    $res = $obj_actividad->modificar();
    return $res;
}

function modificacionModulo($enLinea,  $obj_modulo)
{
    $res = false;
    //verifica si coincide el tipo de modulo elegido con el pasado por parámetro
    if ($enLinea == $obj_modulo->esModuloEnLinea()) {
        echo "¿Que desea modificar del modulo " . $obj_modulo->getId() . "?\n";
        echo "1. Descripción \n";
        echo "2. Horario de inicio \n";
        echo "3. Horario de fin \n";
        echo "4. Fecha de inicio \n";
        echo "5. Fecha de fin \n";
        echo "6. Tope de inscripciones \n";
        echo "7. Costo \n";
        if ($enLinea) {
            echo "8. Link de llamada \n";
            echo "9. La bonificación \n";
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
                if ($enLinea) {
                    echo "Ingrese el nuevo link de llamada: \n";
                    $dato = trim(fgets(STDIN));
                    $obj_modulo->setLinkLlamada($dato);
                } else {
                    echo "Opción válida solo para módulos en línea\n";
                }
                break;
            case 9:
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
        if ($obj_modulo->modificar()) {
            $res = true;
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
    
    if ($obj_inscripcion->modificar()) {
        $res = true;
    }

    return $res;
}
//funcion que da de baja una actividad
//como un modulo requiere de una actividad, si se elimina una actividad se eliminan los modulos que estén asociadas a ellas
function bajaActividad($obj_actividad)
{
    $res = $obj_actividad->eliminar();
    return $res;
}

//funcion que da de baja un modulo
//se elimina para todas las inscripciones 
function bajaModulo($obj_modulo)
{
    $res = false;
    if ($obj_modulo->eliminar()) {
        $res = true;
        echo "Se eliminó: " . $obj_modulo;
    }
    return $res;
}

//funcion que da de baja una inscripción
function bajaInscripcion($obj_inscripcion)
{
    $res = false;
    if ($obj_inscripcion->eliminar()) {
        $res = true;
        echo "Se eliminó: " . $obj_inscripcion . "\n";
    }
    return $res;
}

//menu que nos permite dar de alta, baja o modificar una actividad
function abmActividad()
{
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
            //Busca ID por la funcion buscar ID, si no lo encuentra va a devolver falso
            $obj_actividad = new actividad(0, "", "");
            if ($obj_actividad->Buscar($id)) {
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
            //Busca ID por la funcion buscar ID, si no lo encuentra va a devolver falso
            $obj_actividad = new actividad(0, "", "");
            if ($obj_actividad->Buscar($id)) {
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
    //si el usuario eligió la opcion 2 o 4, trabajará en un módulo en línea
    if ($opcion == 2 || $opcion == 4) {
        $enLinea = true;
    }
    //Busca ID por la funcion buscar ID, si no lo encuentra va a devolver null (si es null, es que no existe)
    if ($enLinea) {
        $obj_modulo = new enLinea(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    } else {
        $obj_modulo = new modulo(0, 0, 0, 0, 0, 0, 0, 0, 0);
    }
    switch ($opcion) {
        case 1:
        case 2:
            if (altaModulo($enLinea)) {
                echo "Se añadio correctamente el módulo\n";
            } else {
                echo "No se pudo dar de alta el módulo\n";
            };
            break;
        case 3:
        case 4:
            echo "Ingrese el ID del modulo: ";
            $id = trim(fgets(STDIN));
            if ($obj_modulo->buscar($id)) {
                if (modificacionModulo($enLinea, $obj_modulo)) {
                    echo "Se modifico correctamente el módulo \n";
                } else {
                    echo "No se pudo modificar el módulo.\n";
                }
            } else {
                if ($enLinea) {
                    echo " El modulo seleccionado no era un modulo en linea o";
                }
                echo " El modulo seleccionado no existe \n";
            }
            break;
        case 5:
            echo "Ingrese el ID del modulo: ";
            $id = trim(fgets(STDIN));
            if ($obj_modulo->buscar($id)) {
                if (bajaModulo($obj_modulo)) {
                    echo "\nSe eliminó con exito\n";
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

    echo "¿Qué desea hacer?\n";
    echo "1. Dar de alta una inscripción \n";
    echo "2. Dar de baja una inscripción \n";
    echo "3. Modificar una inscripción  \n";
    echo "0. salir\n";
    echo "Seleccione una opción: ";
    $opcion = trim(fgets(STDIN));
    $obj_inscripcion = new inscripcion(0, 0, 0, 0);

    switch ($opcion) {
        case 1:
            if (altaInscripcion()) {
                echo "Se añadio correctamente la inscripción\n";
            } else {
                echo "No se pudo dar de alta la inscripción\n";
            }
            break;
        case 2:
            echo "Ingrese el ID de la inscripción: ";
            $id = trim(fgets(STDIN));
            if ($obj_inscripcion ->buscar($id)) {
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
            echo "Ingrese el ID de la inscripción: ";
            $id = trim(fgets(STDIN));
            if ($obj_inscripcion ->buscar($id)) {
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
    echo " Ingresar ID del módulo: ";
    $id = trim(fgets(STDIN));
    $moduloElegido = new modulo(0,0,0,0,0,0,0,0,0);
    if ($moduloElegido ->Buscar($id)) {
        $cadena =  "SELECT I.id, I.fecha, I.costoFinal, I.dni, I.tipoDni
                    FROM Inscripcion I
                    JOIN Inscripcion_Modulo IM ON I.id = IM.inscripcion_id
                    WHERE IM.modulo_id =". $id;
        $inscripcion = new inscripcion(0,0,0,0);
        $arreglo = $inscripcion -> listar($cadena);
        if(empty($arreglo)){
            echo "El modulo ".$id." no tiene inscripciones asociadas\n";
        } else {
            echo "Visualización de de incripciones realizadas al modulo con ID: " . $id . "\n";
            foreach ($arreglo as $inscripcion) {
                echo $inscripcion;
            }
        }
        
    } else {
        echo "no se encontró el módulo";
    }
}
// funcion que dada una actividad que seleccione el usuario va a devolver todas las inscripciones relacionadas a esa actvidad
function inscripcionesPorActividad()
{
    echo " Ingresar ID de la actividad: \n";
    $id = trim(fgets(STDIN));
    $obj_actividad = new actividad(0,0,0,0);
    if ($obj_actividad ->Buscar($id)) {
        echo "Visualización de de incripciones realizadas con la actividad: " . $obj_actividad . "\n";
        $cadena = "SELECT I.id, I.fecha, I.dni, I.tipoDni
                    FROM Inscripcion I
                    JOIN Inscripcion_Modulo IM ON I.id = IM.inscripcion_id
                    JOIN Modulo M ON IM.modulo_id = M.id
                    JOIN Actividad A ON M.actividad_id = A.id
                    WHERE A.id = ". $id;
        $inscripcion = new inscripcion(0,0,0,0);
        $arreglo = $inscripcion -> listar($cadena);
        if(empty($arreglo)){
            echo "la actividad ".$id." no tiene inscripciones asociadas\n";
        } else {
            echo "Visualización de de inscripciones realizadas a la actividad con ID: " . $id . "\n";
            foreach ($arreglo as $inscripcion) {
                echo $inscripcion;
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

    $id = 1;
    $moduloElegido = new modulo(0, 0, 0, 0, 0, 0, 0, 0, 0);
    while ($id != 0) {
        echo " Ingresar ID del módulo que quieras agregar\n";
        echo " Si no queres agregar, seleccioná 0: ";
        $id = trim(fgets(STDIN));
        if ($id != 0) {
            if ($moduloElegido->buscar($id)) {
                if ($obj_inscripcion->añadirModulo($moduloElegido)) {
                    echo "Modulo añadido\n";
                }
            } else {
                echo "No existe el módulo elegido";
            }
        }
    }
}
//funcion que elimina un modulo a una inscripcion pasada por parametro
function eliminarModuloAUnaInscripcion($obj_inscripcion)
{
    $id = 1;
    $moduloElegido = new modulo(0, 0, 0, 0, 0, 0, 0, 0, 0);
    while ($id != 0) {
        echo " Ingresar ID del módulo que quieras eliminar\n";
        echo " Si no queres eliminar más, seleccioná 0: ";
        $id = trim(fgets(STDIN));
        if ($id != 0) {
            if ($moduloElegido->buscar($id)) {
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
function actividadesPorIngresantes()
{
    global $inscripciones, $ingresantes;
    $res = [];
    echo " Ingresar el DNI del estudiante: \n";
    $dni = trim(fgets(STDIN));
    $ingresanteElegido = buscarDNI($ingresantes, $dni);
    if ($ingresanteElegido != null) {
        foreach ($inscripciones as $inscripcion) {
            if ($inscripcion->getIngresante() == $ingresanteElegido) {
                $res = $inscripcion->extraerActividades();
                break;
            }
        }
    } else {
        echo "No se encuentra ingresante con ese DNI";
    }
    return $res;
}



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
            $obj = new inscripcion(0, 0, 0, 0);
            $inscripciones = $obj->listar();
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
            if (!empty($arregloActividades)) {
                echo "Las actividades que está inscripto el alumno son: \n";
                foreach ($arregloActividades as $actividad) {
                    echo $actividad;
                }
            }
            break;
        case '9':
            $objActividad = new actividad(0, "", "");
            $actividades = $objActividad->listar();
            echo " ------- Actividades: ----------\n";
            foreach ($actividades as $actividad) {
                echo $actividad . "\n";
            }
            break;
        case '10':
            echo " ------- Modulos: ----------\n";
            $objModulo = new modulo(0, 0, 0, 0, 0, 0, 0, 0, 0);
            $modulos = $objModulo->listar();
            foreach ($modulos as $modulo) {
                echo $modulo . "\n";
            }
            break;
        case '11':
            $obj = new inscripcion(0, "2025-10-12", "99887766", "Cédula");
            $objM = new modulo(0, 0, 0, 0, 0, 0, 0, 0, 0);
            $objI = new ingresante(0, 0, 0, 0, 0, 0);

             

            break;

        case '0':
            echo "Saliendo del programa...\n";
            exit;

        default:
            echo "Opción no válida. Intente de nuevo.\n";
    }
}




//hacer todas las consultas finales 7,8, 9 Y 10
//checkear comentarios y modificar con los params y eso


//Buscar dado un módulo todos aquellos registros que poseen el mismo DNI y aparecen mas de una vez

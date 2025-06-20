<?php
include_once 'BaseDatos.php';
include_once 'Empresa.php';
include_once 'ResponsableV.php';
include_once 'Pasajero.php';
include_once 'Viaje.php';

class TestViaje{

    public function gestionarEmpresa(){

        echo"
    \n+================================+\n" .
    "|      Gestionar  Empresas       |\n" .
    "¦================================¦\n" .
    "|1.- Ingresar nueva empresa      |\n" .
    "|2.- Modificar empresa           |\n" .
    "|3.- Eliminar empresa            |\n" .
    "|Seleccione una opción: ";


        $opcion = trim(fgets(STDIN));

        if ($opcion == 1){
            $objEmpresa = new Empresa();
            echo "Nombre: ";
            $nombre= trim(fgets(STDIN));
            echo "Direccion: ";
            $direccion = trim(fgets(STDIN));
            $objEmpresa->cargar(0,$nombre,$direccion);
            if($objEmpresa->Insertar()){
                echo "Empresa ingresada con exito. ID; ". $objEmpresa->getIdempresa(). "\n";
            } else {
                echo "ERROR: ". $objEmpresa->getMensajeoperacion(). "\n";
            }      
    } elseif ($opcion == 2){
            $objeEmpresa = new Empresa();
            echo "Ingrese ID de la empresa a modificar: ";
            $id = trim(fgets(STDIN));
            if ( $objEmpresa->Buscar($id)){
                echo "Nombre actual: " . $objEmpresa->getEnombre() . "\n Nuevo nombre: ";
                $nombre = trim(fgets(STDIN));
                echo "Direccion actual: " . $objEmpresa->getEdireccion() . "\n Nueva direccion:";
                $direccion = trim(fgets(STDIN));
                $objEmpresa->cargar($id, $nombre, $direccion);

                if ($objEmpresa->Modificar()){
                    echo "Empresa modificada correctamente.\n";
                } else{
                    echo "ERROR:". $objEmpresa->getMensajeoperacion(). \n";
                }
            } else {
                echo "Empresa no encotrada.\n";
            }
    }  elseif ($opcion == 3) {
        $objEmpresa = new Empresa();
        echo "Ingrese ID de la empresa a eliminar: ";
        $id = trim(fgets(STDIN));
        if ($objEmpresa->Buscar($id)) {
            if ($objEmpresa->Eliminar()) {
                echo "Empresa eliminada correctamente.\n";
            } else {
                echo "Error: " . $objEmpresa->getMensajeoperacion() . "\n";
            }
        } else {
            echo "Empresa no encontrada.\n";
        }

    } else {
        echo "Opción inválida.\n";
    }

            public function gestionarResponsable() {
    echo
    "\n+================================+\n" .
    "|    Gestionar de Responsables   |\n" .
    "¦================================¦\n" .
    "|1.- Ingresar nuevo responsable  |\n" . 
    "|2.- Modificar responsable       |\n" . 
    "|3.- Eliminar responsable        |\n" . 
    "|Seleccione una opción: ";

    $opcion = trim(fgets(STDIN));

    if ($opcion == 1) {
        $objResponsable = new ResponsableV();
        echo "Nombre: ";
        $nombre = trim(fgets(STDIN));
        echo "Apellido: ";
        $apellido = trim(fgets(STDIN));
        echo "Número de licencia: ";
        $numLicencia = trim(fgets(STDIN));

        $objResponsable->cargarResponsable($nombre, $apellido, 0, $numLicencia);
        if ($objResponsable->insertar()) {
            echo "Responsable ingresado con éxito. Número de empleado asignado: " . $objResponsable->getRnumeroempleado() . "\n";
        } else {
            echo "Error: " . $objResponsable->getmensajeoperacion() . "\n";
        }

    } elseif ($opcion == 2) {
        echo "Número de empleado a modificar: ";
        $numEmpleado = trim(fgets(STDIN));
        $objResponsable = ResponsableV::Buscar($numEmpleado);

        if ($objResponsable) {
            echo "Número de licencia actual: " . $objResponsable->getRnumerolicencia() . "\nNuevo número de licencia: ";
            $numLicencia = trim(fgets(STDIN));
            echo "Nombre actual: " . $objResponsable->getNombre() . "\nNuevo nombre: ";
            $nombre = trim(fgets(STDIN));
            echo "Apellido actual: " . $objResponsable->getApellido() . "\nNuevo apellido: ";
            $apellido = trim(fgets(STDIN));

            $objResponsable->cargarResponsable($nombre, $apellido, $numEmpleado, $numLicencia);
            if ($objResponsable->modificar()) {
                echo "Responsable modificado con éxito.\n";
            } else {
                echo "Error: " . $objResponsable->getmensajeoperacion() . "\n";
            }

        } else {
            echo "Responsable no encontrado.\n";
        }

    } elseif ($opcion == 3) {
        echo "Número de empleado a eliminar: ";
        $numEmpleado = trim(fgets(STDIN));
        $objResponsable = ResponsableV::Buscar($numEmpleado);

        if ($objResponsable) {
            if ($objResponsable->eliminar()) {
                echo "Responsable eliminado con éxito.\n";
            } else {
                echo "Error: " . $objResponsable->getmensajeoperacion() . "\n";
            }
        } else {
            echo "Responsable no encontrado.\n";
        }

    } else {
        echo "Opción inválida.\n";
    }
}

public function gestionarViaje() {
    echo  
    "\n+================================+\n" .
    "|       Gestionar   Viajes       |\n" .
    "¦================================¦\n" .
    "|1. Ingresar nuevo viaje         |\n" . 
    "|2. Modificar viaje existente    |\n" . 
    "|3. Eliminar viaje               |\n" .
    "|Seleccione una opción:";

    $opcion = trim(fgets(STDIN));

    if ($opcion == 1) {
        $objViaje = new Viaje();

        echo "Destino: ";
        $destino = trim(fgets(STDIN));
        echo "Cantidad máxima de pasajeros: ";
        $cantMax = trim(fgets(STDIN));
        echo "Costo del viaje: ";
        $costo = trim(fgets(STDIN));
        echo "ID de la empresa: ";
        $idEmpresa = trim(fgets(STDIN));
        echo "Número de empleado responsable: ";
        $nroResponsable = trim(fgets(STDIN));

        $objEmpresa = new Empresa();
        $objResponsable = ResponsableV::Buscar($nroResponsable);

        if (!$objEmpresa->Buscar($idEmpresa)) {
            echo "Empresa no encontrada.\n";
        } elseif (!$objResponsable) {
            echo "Responsable no encontrado.\n";
        } else {
            $objViaje->cargar(0, $destino, $cantMax, $objEmpresa->getIdempresa(), $objResponsable, $costo);
            if ($objViaje->insertar()) {
                echo "Viaje ingresado con éxito. ID: " . $objViaje->getIdviaje() . "\n";
            } else {
                echo "Error: " . $objViaje->getmensajeoperacion() . "\n";
            }
        }

    } elseif ($opcion == 2) {
        echo "ID del viaje a modificar: ";
        $idViaje = trim(fgets(STDIN));
        $objViaje = new Viaje();

        if ($objViaje->Buscar($idViaje)) {
            echo "Destino actual: " . $objViaje->getVdestino() . "\nNuevo destino: ";
            $destino = trim(fgets(STDIN));
            echo "Cantidad máxima actual: " . $objViaje->getVcantmaxpasajeros() . "\nNueva cantidad: ";
            $cantMax = trim(fgets(STDIN));
            echo "Costo actual: " . $objViaje->getVimporte() . "\nNuevo costo: ";
            $costo = trim(fgets(STDIN));
            echo "ID nueva empresa: ";
            $idEmpresa = trim(fgets(STDIN));
            echo "Nuevo número de empleado responsable: ";
            $nroResponsable = trim(fgets(STDIN));

            $objEmpresa = new Empresa();
            $objResponsable = ResponsableV::Buscar($nroResponsable);

            if (!$objEmpresa->Buscar($idEmpresa)) {
                echo "Empresa no encontrada.\n";
            } elseif (!$objResponsable) {
                echo "Responsable no encontrado.\n";
            } else {
                $objViaje->cargar($idViaje, $destino, $cantMax, $objEmpresa->getIdempresa(), $objResponsable, $costo);
                if ($objViaje->modificar()) {
                    echo "Viaje modificado con éxito.\n";
                } else {
                    echo "Error: " . $objViaje->getmensajeoperacion() . "\n";
                }
            }

        } else {
            echo "Viaje no encontrado.\n";
        }

    } elseif ($opcion == 3) {
        echo "ID del viaje a eliminar: ";
        $idViaje = trim(fgets(STDIN));
        $objViaje = new Viaje();

        if ($objViaje->Buscar($idViaje)) {
            if ($objViaje->eliminar()) {
                echo "Viaje eliminado con éxito.\n";
            } else {
                echo "Error: " . $objViaje->getmensajeoperacion() . "\n";
            }
        } else {
            echo "Viaje no encontrado.\n";
        }

    } else {
        echo "Opción inválida.\n";
    }
}

        
}

$objTest = new TestViajes();

do {
    echo "\n" .
        "+================================+\n" .
        "|         MENU PRINCIPAL         |\n" .
        "¦================================¦\n" .
        "|1.- Gestión de Empresas         |\n" . 
        "|2.- Gestión de Responsables     |\n" . 
        "|3.- Gestión de Viajes           |\n" . 
        "|0.- Salir                       |\n" . 
        "¦================================|\n" .
        "|Seleccione una opción:";

    $opcion = trim(fgets(STDIN));

    if ($opcion == 1) {
        $objTest->gestionarEmpresa();
    } elseif ($opcion == 2) {
        $objTest->gestionarResponsable();
    } elseif ($opcion == 3) {
        $objTest->gestionarViaje();
    } elseif ($opcion == 0) {
        echo "Saliendo.\n";
    } else {
        echo "Opción inválida.\n";
    }

} while ($opcion != 0);



?>

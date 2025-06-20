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
            
        
}

// PRUEBAS
testCrearEmpresa();
testModificarEmpresa();
testCrearViaje();
testModificarViaje();
testEliminarViaje();
testEliminarEmpresa();
?>

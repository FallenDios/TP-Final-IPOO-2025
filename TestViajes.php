<?php

include_once 'Empresa.php';
include_once 'Responsable.php';
include_once 'Pasajero.php';
include_once 'Viaje.php';
include_once 'ViajePasajero.php';

do{
    
    mostrarMenu();
    $opcion = trim(fgets(STDIN));
    switch($opcion){
        
        case 1:
        
            echo "Ingrese el nombre de la empresa: \n";
            $nombreE = trim(fgets(STDIN));
            echo "Ingrese la direccion de la empresa: \n";
            $direccionE = trim(fgets(STDIN));
            $nuevaEmpresa = new Empresa();
            $nuevaEmpresa->cargar($nombreE, $direccionE);
            if($nuevaEmpresa->insertar()){
                echo "La empresa a sido registrada con exito. \n";
            }else{
                echo $nuevaEmpresa->getMensajeOperacion() . "\n";
            }
        break;
        
        case 2:
        
            echo "Ingrese el id de la empresa cuyos datos desea modificar: \n";
            $idE = trim(fgets(STDIN));
            $empresa = new Empresa();
            if($empresa->buscar($idE)){
                echo "La empresa existe en el sistema. Ingrese los nuevos datos. \n";
                echo "Nuevo nombre: \n";
                $nuevoNombre = trim(fgets(STDIN));
                echo "Nueva direccion: \n";
                $nuevaDireccion = trim(fgets(STDIN));
                $empresa->setNombre($nuevoNombre);
                $empresa->setDireccion($nuevaDireccion);
                $empresa->modificar();
                echo "Los datos de la Empresa fueron modificados con exito.\n";
            }else{
                echo $empresa->getMensajeOperacion() . "\n";
            }
        break;
        
        case 3:
        
            echo "Ingrese el id de la empresa cuyos datos desea eliminar: \n";
            $idE = trim(fgets(STDIN));
            $empresa = new Empresa();
            if($empresa->buscar($idE)){
                $viaje=new Viaje();
                $viajes = $viaje->listar();
                $i=0;
                $viajeAsociado=false;
                
                while ($i < count($viajes) && !$viajeAsociado) {
                    $unaEmpresa = $viajes[$i]->getEmpresa();
                    $idEmpresa=$unaEmpresa->getIdEmpresa();
                    if ($idEmpresa == $idE) {
                        $viajeAsociado=true;
                    }
                    $i++;
                }
                if ($viajeAsociado) {
                    echo "No se puede eliminar la empresa porque tiene viajes asociados.\n";
                }else {
                    if($empresa->eliminar()){
                        echo "La empresa fue eliminada de la BD con éxito. \n";
                    }else{
                        echo $empresa->getMensajeOperacion() . "\n";
                    }
                }
            }else{
                echo $empresa->getMensajeOperacion() . "\n";
            }
        break;
   
        case 4:
            
            echo "Ingrese el destino del viaje: \n";
            $destinoV = trim(fgets(STDIN));
            echo "Ingrese la capacidad máxima de pasajeros: \n";
            $cantMaxP = trim(fgets(STDIN));
            echo "Ingrese el importe: \n";
            $importeV = trim(fgets(STDIN));
            do{
            echo "Ingrese el id de la empresa que proporciona el viaje: \n";
            $idE = trim(fgets(STDIN));
            $empresa = new Empresa();
        
            if($empresa->buscar($idE)){
                $seguir = true;
            }else{
                echo "La empresa que menciona no existe en la BD, intente otra vez. \n";
                $seguir = false;
            }
                
            }while(!$seguir);
            
            do{
            echo "Ingrese el id del Responsable del viaje: \n";
            $idR = trim(fgets(STDIN));
            $responsable = new Responsable();
    
            if($responsable->buscar($idR)){
                $seguir = true;
            }else{
                echo "El responsable que menciona no existe en la BD, intente otra vez \n";
                $seguir = false;
            }
            
            }while(!$seguir);
            
            $viaje = new Viaje();
            $viaje->cargar($destinoV,$cantMaxP,$empresa,$responsable,$importeV);
            if($viaje->insertar()){
                echo "El viaje ha sido registrado con exito \n";
            }else{
                echo $viaje->getMensajeOperacion();
            }
        break;
        
        case 5:

            echo "Ingrese el id del viaje cuyos datos quiere modificar: \n";
            $idV = trim(fgets(STDIN));
            $viaje = new Viaje();

            if($viaje->buscar($idV)){
                echo "El viaje existe en la BD, ingrese los nuevos datos. \n";
                echo "Ingrese el destino: \n";
                $destinoV = trim(fgets(STDIN));
                echo "Ingrese la cantidad maxima de pasajeros: \n";
                $cantMaxP = trim(fgets(STDIN));
                echo "Ingrese el importe: \n";
                $importeV = trim(fgets(STDIN));
              
                do{
                    echo "Ingrese el id del Responsable del viaje: \n";
                    $idR = trim(fgets(STDIN));
                    $responsable = new Responsable();
    
                if($responsable->buscar($idR)){
                    $seguir = true;
                }else{
                    echo "El responsable que menciona no existe en la BD, intente otra vez \n";
                    $seguir = false;
                }
                }while(!$seguir);   
                
                do{
                echo "Ingrese el id de la empresa que proporciona el viaje: \n";
                $idE = trim(fgets(STDIN));
                $empresa = new Empresa();
            
                if($empresa->buscar($idE)){
                    $seguir = true;
                }else{
                    echo "La empresa que menciona no existe en la BD, intente otra vez. \n";
                    $seguir = false;
                }
                }while(!$seguir);

                $viaje->setDestino($destinoV);
                $viaje->setCantMaxPasajeros($cantMaxP);
                $viaje->setImporte($importeV);
                $viaje->setEmpresa($empresa);
                $viaje->setResponsable($responsable);
                if($viaje->modificar()){
                    echo "Los datos fueron modificados con éxito. \n";
                }else{
                    echo $viaje->getMensajeOperacion() . "\n";
                }
                    
            }else{
                echo $viaje->getMensajeOperacion();
            }
        break;
        
        case 6:
            echo "Ingrese el id del viaje cuyos datos desea eliminar: \n";
            $idV = trim(fgets(STDIN));
            $viaje = new Viaje();
            if($viaje->buscar($idV)){
                $viajePasajero=new ViajePasajero();
                $lista=$viajePasajero->listar("viaje",$idV);
                if (count($lista) > 0) {
                    echo "No se puede eliminar porque el viaje tiene pasajeros asociados.";
                }else {
                    if ($viaje->eliminar()) {
                        echo "Viaje eliminado con exito.\n";
                    }else {
                        echo $viaje->getMensajeOperacion().".\n";
                    }
                }
            }else {
                echo $viaje->getMensajeOperacion().".\n";
            }
        break;

         case 7:
      echo "Ingrese el documento del pasajero\n";
      $documentoP = trim(fgets(STDIN));
      echo "Ingrese el nombre del pasajero\n";
      $nombreP = trim(fgets(STDIN));
      echo "Ingrese el apellido del pasajero\n";
      $apellidoP = trim(fgets(STDIN));
      echo "Ingrese el telefono del pasajero\n";
      $telefonoP = trim(fgets(STDIN));

      $seguir = false; // inicializa correctamente
      $pasajeroP = new Pasajero();

      do {
        echo "Ingrese el id del viaje que quiere realizar\n";
        $idViajeP = trim(fgets(STDIN));
        $viajeP = new Viaje();

        if ($viajeP->buscar($idViajeP)) {
          $seguir = true;
        } else {
          echo "El viaje de id: $idViajeP no existe, pruebe de nuevo\n";
        }
      } while(!$seguir);

      // validez ok, ahora cargo datos
      $pasajeroP->inicializarPasajero($documentoP, $nombreP, $apellidoP, $telefonoP);



      if ($pasajeroP->insertar()) {
        $relacion = new ViajePasajero();
        $relacion->cargar($viajeP, $pasajeroP);
        if ($relacion->insertar()) {
          echo "El pasajero ha sido insertado con exito\n";
        } else {
          echo $relacion->getMensajeOperacion() . "\n";
        }
      } else {
        echo $pasajeroP->getMensajeOperacion() . "\n";
      }
      break;

        case 8:
            
            echo "Ingrese el id del pasajero cuyos datos desea modificar: \n";
            $idP = trim(fgets(STDIN));
            $pasajero = new Pasajero();
            if($pasajero->buscar($idP)){
                echo "El pasajero existe en el sistema. Ingrese los nuevos datos. \n";
                echo "Nuevo nombre: \n";
                $nuevoNombre = trim(fgets(STDIN));
                echo "Nuevo documento: \n";
                $nuevoDocumento = trim(fgets(STDIN));
                echo "Nuevo apellido: \n";
                $nuevoApellido = trim(fgets(STDIN));
                echo "Nuevo telefono: \n";
                $nuevoTelefono = trim(fgets(STDIN));
                $pasajero->setNombre($nuevoNombre);
                $pasajero->setNumeroDocumento($nuevoDocumento);
                $pasajero->setApellido($nuevoApellido);
                $pasajero->setTelefono($nuevoTelefono);
                $pasajero->modificar();
            }else{
                echo $pasajero->getMensajeOperacion() . "\n";
            }

        break;

        case 9:
            
            echo "Ingrese el id del pasajero cuyos datos desea eliminar: \n";
            $idP = trim(fgets(STDIN));
            $pasajero = new Pasajero();
            if($pasajero->buscar($idP)){
                $viajePasajero = new ViajePasajero();
                $lista = $viajePasajero->listar("pasajero",$idP);
                if (count($lista) > 0) {
                    echo "El pasajero tiene viajes asociados. Desea eliminarlo igual?(si/no): ";
                    $resp=strtolower(trim(fgets(STDIN)));
                    if ($resp=="si") {
                        foreach ($lista as $unRegistro) {
                            $unRegistro->eliminar();
                        }
                        
                        if($pasajero->eliminar()){
                            echo "El pasajero fue eliminado con éxito.\n";
                        }else{
                            echo $pasajero->getMensajeOperacion()."\n";
                        }
                    }else {
                        echo "Eliminacion cancelada.\n";
                    }
                }else {
                    if($pasajero->eliminar()){
                        echo "El pasajero fue eliminado con éxito.\n";
                    }else{
                        echo $pasajero->getMensajeOperacion()."\n";
                    }
                }
            }else{
                echo $pasajero->getMensajeOperacion()."\n";
            }

        break;    
        
        case 10:

            echo "Ingrese el numero de licencia del Responsable \n";
            $numLicenciaR = trim(fgets(STDIN));
            echo "Ingrese el nombre del responsable \n";
            $nombreR = trim(fgets(STDIN));
            echo "Ingrese el apellido del responsable \n";
            $apellidoR = trim(fgets(STDIN));
            $responsableR = new Responsable();
            $responsableR->inicializarResponsable($numLicenciaR, $nombreR, $apellidoR);

            
            if($responsableR->insertar()){
                echo "El responsable ha sido insertado con exito \n";
            }else{
                echo $responsableR->getMensajeOperacion();
            }
        break;

        case 11:
      echo "Ingrese el id del responsable cuyos datos desea modificar:\n";
      $idR = trim(fgets(STDIN));
      $responsableR = new Responsable();
      if ($responsableR->buscar($idR)) {
        echo "Ingrese el nuevo nombre:\n";
        $nuevoNombre = trim(fgets(STDIN));
        echo "Ingrese la nueva licencia:\n";
        $nuevaLicencia = trim(fgets(STDIN));
        echo "Ingrese el nuevo apellido:\n";
        $nuevoApellido = trim(fgets(STDIN));
        $responsableR->setNombre($nuevoNombre);
        $responsableR->setNumeroLicencia($nuevaLicencia);
        $responsableR->setApellido($nuevoApellido);
        $responsableR->modificar();
        echo "Responsable modificado con éxito.\n";
      } else {
        echo $responsableR->getMensajeOperacion() . "\n";
      }
      break;

        case 12:
            
            echo "Ingrese el id del responsable cuyos datos desea eliminar: \n";
            $idR = trim(fgets(STDIN));
            $responsableR = new Responsable();
            if($responsableR->buscar($idR)){
                $viaje=new Viaje();
                $lista=$viaje->listar();
                $viajeAsociado=false;
                $i=0;
                while ($i < count($lista) && !$viajeAsociado) {
                    $idResponsable=$lista[$i]->getResponsable()->getNumeroEmpleado();
                    if ($idResponsable==$idR) {
                        $viajeAsociado=true;
                    }
                    $i++;
                }
                if ($viajeAsociado) {
                    echo "El responsable no se puede eliminar porque tiene viajes asociados.";
                }else {
                    if($responsableR->eliminar()){
                        echo "El responsable fue eliminado de la BD con éxito. \n";
                    }else{
                        echo $responsableR->getMensajeOperacion() . "\n";
                    }
                }
            }else{
                echo $responsableR->getMensajeOperacion() . "\n";
            }

        break;
        




    }
}while($opcion != 0 && $opcion != 13);

function mostrarMenu(){
    
    echo "\n *-------------------------------------------------* \n";
    echo "1) Ingresar datos de una nueva Empresa. \n";
    echo "2) Modificar datos de una Empresa existente. \n";
    echo "3) Eliminar los datos de una Empresa existente. \n";
    echo "*-------------------------------------------------* \n";
    echo "4) Ingresar datos de un nuevo Viaje. \n";
    echo "5) Modificar datos de un Viaje existente. \n";
    echo "6) Eliminar los datos de un Viaje existente. \n";
    echo "*-------------------------------------------------* \n";
    echo "7) Ingresar datos de un nuevo Pasajero. \n";
    echo "8) Modificar datos de un Pasajero existente. \n";
    echo "9) Eliminar los datos de un Pasajero existente. \n";
    echo "*-------------------------------------------------* \n";
    echo "10) Ingresar datos de un nuevo Responsable. \n";
    echo "11) Modificar datos de un Responsable existente. \n";
    echo "12) Eliminar los datos de un Responsable existente. \n";
    echo "13) Salir.\n";
    echo "*-------------------------------------------------* \n";

}
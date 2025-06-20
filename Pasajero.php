<?php
include_once 'BaseDatos.php';
include_once 'empresa.php';
include_once 'persona.php';
include_once 'viaje.php';

class Pasajero extends Persona {
    private $pdocumento;
    private $idviaje;
    private $telefono;

    public function __construct($nombre = "", $apellido = "", $pdocumento = 0, $telefono = "", $idviaje = 0) {
        parent::__construct($nombre, $apellido);
        $this->pdocumento = $pdocumento;
        $this->idviaje = $idviaje;
        $this->telefono = $telefono;
    }

    public function cargarPasajero($pdocumento, $nombre, $apellido, $telefono, $idviaje) {
        $this->setPdocumento($pdocumento);
        parent::setNombre($nombre);
        parent::setApellido($apellido);
        $this->setTelefono($telefono);
        $this->setIdviaje($idviaje);
    }


    public function getPdocumento() { return $this->pdocumento; }
    public function getIdviaje() { return $this->idviaje; }
    public function getTelefono() { return $this->telefono; }
    public function setPdocumento($pdocumento) { $this->pdocumento = $pdocumento; }
    public function setIdviaje($idviaje) { $this->idviaje = $idviaje; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }

    public function __toString() {
        return parent::__toString() .
            "=== Pasajero ===\n" .
            "Documento: " . $this->getPdocumento() . "\n" .
            "Teléfono: " . $this->getTelefono() . "\n" .
            "ID Viaje: " . $this->getIdviaje() . "\n";
    }


    public function Buscar($pdocumento){
        $base=new BaseDatos();
        $consultaPasajero = "SELECT * FROM pasajero WHERE pdocumento = " . intval($pdocumento);
        $resp= false;
        if($base->Iniciar()){
            if($base->Ejecutar($consultaPasajero)){
                if($row2=$base->Registro()){					
                    $this->setPdocumento($pdocumento);
                    parent::setNombre($row2['pnombre']);
                    parent::setApellido($row2['papellido']);
                    $this->setTelefono($row2['ptelefono']);
                    $this->setIdviaje($row2['idviaje']);
                    $resp= true;
                }
            }
        }else{
            parent::setMensajeoperacion("Pasajero->Buscar: ".$base->getError());
        }
        return $resp;
    }

    public static function listar($condicion = ""){
        $arregloPasajeros = null;
        $base = new BaseDatos();
        $consultaPasajero = "SELECT * FROM pasajero ";
        if ($condicion != "") {
            $consultaPasajero .= ' WHERE ' . $condicion;
        }
        $consultaPasajero .= " ORDER BY pdocumento ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                $arregloPasajeros = array();
                while ($row2 = $base->Registro()) {
                    $objPasajero = new Pasajero();
                    $objPasajero->cargarPasajero($row2['pdocumento'], $row2['pnombre'], $row2['papellido'], $row2['ptelefono'], $row2['idviaje']);
                    array_push($arregloPasajeros, $objPasajero);
                }
            } else {
                parent::setMensajeoperacion("Pasajero->listar: ".$base->getError());
            }
        } else {
            parent::setMensajeoperacion("Pasajero->listar: ".$base->getError());
        }
        return $arregloPasajeros;
    }

    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) 
                             VALUES (" . $this->getPdocumento() . ", '" . parent::getNombre() . "', '" . parent::getApellido() . "', '" . $this->getTelefono() . "', " . $this->getIdviaje() . ")";
        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($consultaInsertar)) {
                $resp = true;
            } else {
                parent::setMensajeoperacion("Pasajero->insertar: ".$base->getError());
            }
        } else {
            parent::setMensajeoperacion("Pasajero->insertar: ".$base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaModificar = "UPDATE pasajero SET pnombre='" . parent::getNombre() . "', papellido='" . parent::getApellido() . "', ptelefono='" . $this->getTelefono() . "', idviaje=" . $this->getIdviaje() . " WHERE pdocumento=" . $this->getPdocumento();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModificar)) {
                $resp = true;
            } else {
                parent::setMensajeoperacion("Pasajero->modificar: ".$base->getError());
            }
        } else {
            parent::setMensajeoperacion("Pasajero->modificar: ".$base->getError());
        }
        return $resp;
    }   

    public function eliminar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaEliminar = "DELETE FROM pasajero WHERE pdocumento=" . $this->getPdocumento();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEliminar)) {
                $resp = true;
            } else {
                parent::setMensajeoperacion("Pasajero->eliminar: ".$base->getError());
            }
        } else {
            parent::setMensajeoperacion("Pasajero->eliminar: ".$base->getError());
        }
        return $resp;

}

}



?>

<?php

include_once "BaseDatos.php";
include_once "Viaje.php";
include_once "Pasajero.php";

class ViajePasajero {
    // Atributos privados
    private $objViaje;             // Delegación: referencia a un objeto Viaje
    private $objPasajero;          // Delegación: referencia a un objeto Pasajero
    private $mensajeOperacion;     // Guarda mensajes de error o estado

    // Constructor: inicializa los atributos
    public function __construct($objViaje = null, $objPasajero = null) {
        $this->objViaje = $objViaje;
        $this->objPasajero = $objPasajero;
        $this->mensajeOperacion = "";
    }

    // Método cargar: inicializa los atributos mediante set
    public function cargar($objViaje, $objPasajero) {
        $this->setObjViaje($objViaje);
        $this->setObjPasajero($objPasajero);
    }

    // Getters
    public function getObjViaje() { return $this->objViaje; }
    public function getObjPasajero() { return $this->objPasajero; }
    public function getMensajeOperacion() { return $this->mensajeOperacion; }

    // Setters
    public function setObjViaje($objViaje) { $this->objViaje = $objViaje; }
    public function setObjPasajero($objPasajero) { $this->objPasajero = $objPasajero; }
    public function setMensajeOperacion($mensajeOperacion) { $this->mensajeOperacion = $mensajeOperacion; }

    // Método __toString: representa el vínculo
    public function __toString() {
        $idViaje = $this->getObjViaje() ? $this->getObjViaje()->getIdViaje() : "Sin viaje";
        $idPasajero = $this->getObjPasajero() ? $this->getObjPasajero()->getIdPasajero() : "Sin pasajero";

        return "ID Viaje: $idViaje\nID Pasajero: $idPasajero\n";
    }

    // Inserta una relación viaje-pasajero en la tabla intermedia
    public function insertar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        $idViaje = $this->getObjViaje()->getIdViaje();
        $idPasajero = $this->getObjPasajero()->getIdPasajero();

        if ($baseDatos->Iniciar()) {
            $consultaInsertar = "INSERT INTO viaje_pasajero(idviaje, idpasajero) VALUES($idViaje, $idPasajero)";
            if ($baseDatos->Ejecutar($consultaInsertar)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Devuelve una colección de objetos ViajePasajero según un criterio
    public function listar($tipo = "", $id = "") {
        $baseDatos = new BaseDatos();
        $coleccion = [];
        $condicion = "";

        // Determina condición según tipo
        if ($tipo == "viaje") {
            $condicion = "WHERE idviaje = $id";
        } elseif ($tipo == "pasajero") {
            $condicion = "WHERE idpasajero = $id";
        }

        $consulta = "SELECT * FROM viaje_pasajero $condicion";

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                while ($fila = $baseDatos->Registro()) {
                    $viaje = new Viaje();
                    $viaje->setIdViaje($fila['idviaje']); // Se evita recursividad innecesaria

                    $pasajero = new Pasajero();
                    $pasajero->buscar($fila['idpasajero']);

                    $viajePasajero = new ViajePasajero();
                    $viajePasajero->cargar($viaje, $pasajero);
                    $coleccion[] = $viajePasajero;
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $coleccion;
    }

    // Elimina la relación viaje-pasajero
    public function eliminar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        $idViaje = $this->getObjViaje()->getIdViaje();
        $idPasajero = $this->getObjPasajero()->getIdPasajero();

        if ($baseDatos->Iniciar()) {
            $consultaEliminar = "DELETE FROM viaje_pasajero WHERE idviaje = $idViaje AND idpasajero = $idPasajero";
            if ($baseDatos->Ejecutar($consultaEliminar)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Lista pasajeros asociados a un id de viaje
    public function listarPasajerosPorIdViaje($idViaje) {
        $baseDatos = new BaseDatos();
        $colPasajeros = [];

        $consulta = "SELECT p.* FROM pasajero p 
                     JOIN viaje_pasajero vp ON p.idpasajero = vp.idpasajero
                     WHERE vp.idviaje = $idViaje";

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                while ($fila = $baseDatos->Registro()) {
                    $objPasajero = new Pasajero();
                    $objPasajero->setIdPasajero($fila['idpasajero']);
                    $objPasajero->setNumeroDocumento($fila['pdocumento']);
                    $objPasajero->setNombre($fila['pnombre']);
                    $objPasajero->setApellido($fila['papellido']);
                    $objPasajero->setTelefono($fila['ptelefono']);
                    $colPasajeros[] = $objPasajero;
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $colPasajeros;
    }

}

?>

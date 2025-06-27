<?php
include_once "BaseDatos.php"; // Se incluye la clase que maneja la conexión a la base de datos

class Pasajero {
    // Atributos privados
    private $idPasajero;
    private $documento;
    private $nombre;
    private $apellido;
    private $telefono;
    private $mensajeOperacion;

    // Constructor: inicializa los atributos con valores por defecto
    public function __construct() {
        $this->idPasajero = 0;
        $this->nombre = "";
        $this->apellido = "";
        $this->documento = "";
        $this->telefono = "";
        $this->mensajeOperacion = "";
    }

    // Método para cargar valores usando métodos set
    public function cargar($unDocumento, $unNombre, $unApellido, $unTelefono) {
        $this->setNumeroDocumento($unDocumento);
        $this->setNombre($unNombre);
        $this->setApellido($unApellido);
        $this->setTelefono($unTelefono);
    }

    // Métodos de acceso (getters)
    public function getIdPasajero() { return $this->idPasajero; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getNumeroDocumento() { return $this->documento; }
    public function getTelefono() { return $this->telefono; }
    public function getMensajeOperacion() { return $this->mensajeOperacion; }

    // Métodos de modificación (setters)
    public function setIdPasajero($idPasajero) { $this->idPasajero = $idPasajero; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setNumeroDocumento($numeroDocumento) { $this->documento = $numeroDocumento; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setMensajeOperacion($mensajeOperacion) { $this->mensajeOperacion = $mensajeOperacion; }

    // Método mágico __toString que devuelve una representación textual del objeto
    public function __toString() {
        return
        "ID Pasajero: " . $this->getIdPasajero() . "\n" .
        "Nombre: " . $this->getNombre() . "\n" .
        "Apellido: " . $this->getApellido() . "\n" .
        "Numero de Documento: " . $this->getNumeroDocumento() . "\n" .
        "Telefono: " . $this->getTelefono() . "\n";
    }

    // Método que busca un pasajero por ID en la base de datos
    public function buscar($idPasajero) {
        $baseDatos = new BaseDatos();
        $respuesta = false;
        $consulta = "SELECT * FROM pasajero WHERE idpasajero = " . $idPasajero;

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                if ($fila = $baseDatos->Registro()) {
                    // Se cargan los datos al objeto
                    $this->setIdPasajero($fila['idpasajero']);
                    $this->setNombre($fila['pnombre']);
                    $this->setApellido($fila['papellido']);
                    $this->setNumeroDocumento($fila['pdocumento']);
                    $this->setTelefono($fila['ptelefono']);
                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion("No existe Pasajero con ese ID.");
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
        return $respuesta;
    }

    // Método que inserta un pasajero en la base de datos
    public function insertar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaInsertar = "INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono) 
            VALUES('" . $this->getNumeroDocumento() . "', '" . $this->getNombre() . "', '" . $this->getApellido() . "', " . $this->getTelefono() . ")";
            $id = $baseDatos->devuelveIDInsercion($consultaInsertar);

            if ($id != null) {
                $this->setIdPasajero($id); // Asigna el ID generado
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
        return $respuesta;
    }

    // Método que modifica los datos de un pasajero existente
    public function modificar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaModificar = "UPDATE pasajero SET pdocumento = '" . $this->getNumeroDocumento() . "', pnombre = '" . $this->getNombre() .
            "', papellido = '" . $this->getApellido() . "', ptelefono = " . $this->getTelefono() .
            " WHERE idpasajero = " . $this->getIdPasajero();

            if ($baseDatos->Ejecutar($consultaModificar)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
        return $respuesta;
    }

    // Método que elimina un pasajero de la base de datos
    public function eliminar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaEliminar = "DELETE FROM pasajero WHERE idpasajero = " . $this->getIdPasajero();
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

    // Método que devuelve un arreglo con todos los pasajeros de la base
    public function listar() {
        $baseDatos = new BaseDatos();
        $coleccionPasajeros = [];
        $consulta = "SELECT * FROM pasajero";

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                while ($fila = $baseDatos->Registro()) {
                    $pasajero = new Pasajero();
                    // Se cargan los datos del pasajero
                    $pasajero->cargar(
                        $fila['pdocumento'], 
                        $fila['pnombre'], 
                        $fila['papellido'], 
                        $fila['ptelefono']
                    );
                    $pasajero->setIdPasajero($fila['idpasajero']);
                    $coleccionPasajeros[] = $pasajero;
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
        return $coleccionPasajeros;
    }
}

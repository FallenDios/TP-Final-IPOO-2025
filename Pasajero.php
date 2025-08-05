<?php

include_once "BaseDatos.php";
include_once "Persona.php";

class Pasajero extends Persona {
    // Atributos privados propios de Pasajero
    private $idPasajero;
    private $documento;
    private $telefono;
    private $mensajeOperacion;

    // Constructor: inicializa atributos y llama al constructor de Persona
    public function __construct() {
        parent::__construct();
        $this->idPasajero = 0;
        $this->documento = "";
        $this->telefono = "";
        $this->mensajeOperacion = "";
    }

    // Inicializa los datos específicos del pasajero
    public function inicializarPasajero($documento, $nombre, $apellido, $telefono) {
        $this->setNumeroDocumento($documento);
        $this->setTelefono($telefono);
        parent::cargar($nombre, $apellido); // Inicializa nombre y apellido desde Persona
    }

    // Getters
    public function getIdPasajero() { return $this->idPasajero; }
    public function getNumeroDocumento() { return $this->documento; }
    public function getTelefono() { return $this->telefono; }
    public function getMensajeOperacion() { return $this->mensajeOperacion; }

    // Setters
    public function setIdPasajero($idPasajero) { $this->idPasajero = $idPasajero; }
    public function setNumeroDocumento($documento) { $this->documento = $documento; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setMensajeOperacion($mensaje) { $this->mensajeOperacion = $mensaje; }

    // Representación del objeto como string
    public function __toString() {
        return "ID Pasajero: " . $this->getIdPasajero() . "\n" .
               "Documento: " . $this->getNumeroDocumento() . "\n" .
               "Telefono: " . $this->getTelefono() . "\n" .
               parent::__toString(); // Muestra nombre y apellido
    }

    // Busca pasajero por su ID
    public function buscar($idPasajero) {
        $base = new BaseDatos();
        $respuesta = false;
        $consulta = "SELECT pa.*, pe.nombre, pe.apellido ".
                    "FROM pasajero pa JOIN persona pe ON pa.idpersona = pe.idpersona ".
                    "WHERE pa.idpasajero = $idPasajero";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($fila = $base->Registro()) {
                    $this->setIdPasajero($fila['idpasajero']);
                    $this->setNumeroDocumento($fila['pdocumento']);
                    $this->setTelefono($fila['ptelefono']);
                    $this->setNombre($fila['nombre']);
                    $this->setApellido($fila['apellido']);
                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion("No se encontr\xF3 pasajero.");
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $respuesta;
    }

    // Inserta una nueva persona y pasajero
    public function insertar() {
        $base = new BaseDatos();
        $respuesta = false;

        if ($base->Iniciar()) {
            $consultaPersona = "INSERT INTO persona(nombre, apellido) ".
                               "VALUES('" . $this->getNombre() . "', '" . $this->getApellido() . "')";
            $idPersona = $base->devuelveIDInsercion($consultaPersona);

            if ($idPersona != null) {
                $consultaPasajero = "INSERT INTO pasajero(pdocumento, ptelefono, idpersona) ".
                                    "VALUES('" . $this->getNumeroDocumento() . "', '" . $this->getTelefono() . "', $idPersona)";
                $id = $base->devuelveIDInsercion($consultaPasajero);
                if ($id != null) {
                    $this->setIdPasajero($id);
                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion($base->getError());
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $respuesta;
    }

    // Modifica datos en ambas tablas
    public function modificar() {
        $base = new BaseDatos();
        $respuesta = false;

        if ($base->Iniciar()) {
            $consultaPersona = "UPDATE persona SET nombre = '" . $this->getNombre() .
                               "', apellido = '" . $this->getApellido() .
                               "' WHERE idpersona = (SELECT idpersona FROM pasajero WHERE idpasajero = " . $this->getIdPasajero() . ")";

            $modificoPersona = $base->Ejecutar($consultaPersona);

            $consultaPasajero = "UPDATE pasajero SET pdocumento = '" . $this->getNumeroDocumento() .
                                 "', ptelefono = '" . $this->getTelefono() .
                                 "' WHERE idpasajero = " . $this->getIdPasajero();

            $modificoPasajero = $base->Ejecutar($consultaPasajero);

            if ($modificoPersona && $modificoPasajero) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $respuesta;
    }

    // Elimina de ambas tablas
    // Elimina al pasajero y luego a la persona asociada
public function eliminar() {
    $base = new BaseDatos();
    $respuesta = false;

    if ($base->Iniciar()) {
        // 1. Eliminar relaciones en viaje_pasajero (si existen)
        $consultaViajePasajero = "DELETE FROM viaje_pasajero WHERE idpasajero = " . $this->getIdPasajero();
        $base->Ejecutar($consultaViajePasajero); // No afecta si no hay relaciones

        // 2. Eliminar de la tabla pasajero
        $consultaPasajero = "DELETE FROM pasajero WHERE idpasajero = " . $this->getIdPasajero();
        if ($base->Ejecutar($consultaPasajero)) {

            // 3. Finalmente eliminar de la tabla persona
            $consultaPersona = "DELETE FROM persona WHERE idpersona = (
                SELECT idpersona FROM pasajero WHERE idpasajero = " . $this->getIdPasajero() . "
            )";

            // Alternativa: recuperar el idpersona antes, ya que luego de borrar pasajero no existirá
            $consultaIdPersona = "SELECT idpersona FROM pasajero WHERE idpasajero = " . $this->getIdPasajero();
            if ($base->Ejecutar($consultaIdPersona)) {
                if ($fila = $base->Registro()) {
                    $idPersona = $fila['idpersona'];
                    $consultaPersona = "DELETE FROM persona WHERE idpersona = $idPersona";

                    if ($base->Ejecutar($consultaPersona)) {
                        $respuesta = true;
                    } else {
                        $this->setMensajeOperacion($base->getError());
                    }
                }
            }

        } else {
            $this->setMensajeOperacion($base->getError());
        }
    } else {
        $this->setMensajeOperacion($base->getError());
    }

    return $respuesta;
}

    // Lista todos los pasajeros
    public function listar() {
        $base = new BaseDatos();
        $coleccion = [];
        $consulta = "SELECT pa.*, pe.nombre, pe.apellido FROM pasajero pa JOIN persona pe ON pa.idpersona = pe.idpersona";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($fila = $base->Registro()) {
                    $pasajero = new Pasajero();
                    $pasajero->inicializarPasajero(
                        $fila['pdocumento'],
                        $fila['nombre'],
                        $fila['apellido'],
                        $fila['ptelefono']
                    );
                    $pasajero->setIdPasajero($fila['idpasajero']);
                    $coleccion[] = $pasajero;
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $coleccion;
    }
}

?>

<?php

include_once "BaseDatos.php";
include_once "Persona.php";

class Responsable extends Persona {
    // Atributos privados
    private $numeroEmpleado; // Clave primaria en la tabla responsable
    private $numeroLicencia; // Número de licencia
    private $mensajeOperacion; // Para guardar errores de operación

    // Constructor: inicializa todo, incluyendo el padre
    public function __construct() {
        parent::__construct(); // Llama al constructor de Persona
        $this->numeroEmpleado = 0;
        $this->numeroLicencia = 0;
        $this->mensajeOperacion = "";
    }

    // Inicializa con datos usando setters
    public function inicializarResponsable($numeroLicencia, $nombre, $apellido) {
        $this->setNumeroLicencia($numeroLicencia);
        parent::cargar($nombre, $apellido); // Método heredado
    }

    // Getters
    public function getNumeroEmpleado() { return $this->numeroEmpleado; }
    public function getNumeroLicencia() { return $this->numeroLicencia; }
    public function getMensajeOperacion() { return $this->mensajeOperacion; }

    // Setters
    public function setNumeroEmpleado($numeroEmpleado) { $this->numeroEmpleado = $numeroEmpleado; }
    public function setNumeroLicencia($numeroLicencia) { $this->numeroLicencia = $numeroLicencia; }
    public function setMensajeOperacion($mensajeOperacion) { $this->mensajeOperacion = $mensajeOperacion; }

    // Representación en texto del objeto
    public function __toString() {
        return "Numero de Empleado: " . $this->getNumeroEmpleado() . "\n" .
               "Numero de Licencia: " . $this->getNumeroLicencia() . "\n" .
               parent::__toString(); // Agrega nombre y apellido
    }

    // Buscar responsable por ID, incluyendo persona
    public function buscar($numeroEmpleado) {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        $consulta = "SELECT r.*, p.nombre, p.apellido
                     FROM responsable r 
                     JOIN persona p ON r.idpersona = p.idpersona
                     WHERE r.rnumeroempleado = " . $numeroEmpleado;

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                if ($fila = $baseDatos->Registro()) {
                    $this->setNumeroEmpleado($fila['rnumeroempleado']);
                    $this->setNumeroLicencia($fila['rnumerolicencia']);
                    $this->setNombre($fila['nombre']);
                    $this->setApellido($fila['apellido']);
                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion("No se encontró responsable con ese ID.");
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Inserta una persona y luego un responsable
    public function insertar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            // Inserta primero en la tabla persona
            $consultaPersona = "INSERT INTO persona(nombre, apellido)
                                VALUES('" . $this->getNombre() . "', '" . $this->getApellido() . "')";
            $idPersona = $baseDatos->devuelveIDInsercion($consultaPersona);

            if ($idPersona != null) {
                // Luego inserta en responsable usando ese ID
                $consultaResponsable = "INSERT INTO responsable(rnumerolicencia, idpersona)
                                        VALUES('" . $this->getNumeroLicencia() . "', $idPersona)";
                $id = $baseDatos->devuelveIDInsercion($consultaResponsable);

                if ($id != null) {
                    $this->setNumeroEmpleado($id);
                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion($baseDatos->getError());
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Modifica tanto en persona como en responsable
    public function modificar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            // Primero actualiza los datos en persona
            $consultaPersona = "UPDATE persona 
                                SET nombre = '" . $this->getNombre() . "', apellido = '" . $this->getApellido() . "'
                                WHERE idpersona = (SELECT idpersona FROM responsable WHERE rnumeroempleado = " . $this->getNumeroEmpleado() . ")";

            $modificoPersona = $baseDatos->Ejecutar($consultaPersona);

            // Luego actualiza los datos en responsable
            $consultaResponsable = "UPDATE responsable 
                                    SET rnumerolicencia = '" . $this->getNumeroLicencia() . "'
                                    WHERE rnumeroempleado = " . $this->getNumeroEmpleado();

            $modificoResponsable = $baseDatos->Ejecutar($consultaResponsable);

            if ($modificoPersona && $modificoResponsable) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }

        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

  
   // Elimina primero de responsable y luego de persona
public function eliminar() {
    $baseDatos = new BaseDatos();
    $respuesta = false;

    if ($baseDatos->Iniciar()) {
        // 1. Obtener el idpersona asociado a este responsable
        $consultaIdPersona = "SELECT idpersona FROM responsable WHERE rnumeroempleado = " . $this->getNumeroEmpleado();
        $idPersona = null;

        if ($baseDatos->Ejecutar($consultaIdPersona)) {
            if ($fila = $baseDatos->Registro()) {
                $idPersona = $fila['idpersona'];
            }
        }

        // 2. Eliminar de la tabla responsable
        $consultaResponsable = "DELETE FROM responsable WHERE rnumeroempleado = " . $this->getNumeroEmpleado();
        if ($baseDatos->Ejecutar($consultaResponsable)) {
            // 3. Eliminar de la tabla persona (una vez desvinculado)
            $consultaPersona = "DELETE FROM persona WHERE idpersona = $idPersona";
            if ($baseDatos->Ejecutar($consultaPersona)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

    } else {
        $this->setMensajeOperacion($baseDatos->getError());
    }

    return $respuesta;
}
    // Lista todos los responsables
    public function listar() {
        $baseDatos = new BaseDatos();
        $coleccionResponsable = [];
        $consulta = "SELECT r.*, p.nombre, p.apellido
                     FROM responsable r 
                     JOIN persona p ON r.idpersona = p.idpersona";

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                while ($fila = $baseDatos->Registro()) {
                    $responsable = new Responsable();
                    $responsable->inicializarResponsable(
                        $fila['rnumerolicencia'],
                        $fila['nombre'],
                        $fila['apellido']
                    );
                    $responsable->setNumeroEmpleado($fila['rnumeroempleado']);
                    $coleccionResponsable[] = $responsable;
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $coleccionResponsable;
    }
}

?>

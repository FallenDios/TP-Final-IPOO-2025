<?php

include_once "BaseDatos.php";
include_once "Persona.php";

class Responsable extends Persona {
    // Atributos privados
    private $numeroEmpleado; // ID del responsable, clave primaria
    private $numeroLicencia; // Número de licencia del responsable
    private $mensajeOperacion; // Mensaje usado para registrar errores o estados

    // Constructor: inicializa los atributos con valores por defecto
    public function __construct() {
        parent::__construct(); // Llama al constructor de la clase padre (Persona)
        $this->numeroEmpleado = 0;
        $this->numeroLicencia = 0;
        $this->mensajeOperacion = "";
    }

    // Método específico para inicializar Responsable (evita redefinir cargar de Persona)
    public function inicializarResponsable($numeroLicencia, $nombre, $apellido) {
        $this->setNumeroLicencia($numeroLicencia);
        parent::cargar($nombre, $apellido); // Carga nombre y apellido usando el método de Persona
    }

    // Métodos de acceso (getters)
    public function getNumeroEmpleado() { return $this->numeroEmpleado; }
    public function getNumeroLicencia() { return $this->numeroLicencia; }
    public function getMensajeOperacion() { return $this->mensajeOperacion; }

    // Métodos de modificación (setters)
    public function setNumeroEmpleado($numeroEmpleado) { $this->numeroEmpleado = $numeroEmpleado; }
    public function setNumeroLicencia($numeroLicencia) { $this->numeroLicencia = $numeroLicencia; }
    public function setMensajeOperacion($mensajeOperacion) { $this->mensajeOperacion = $mensajeOperacion; }

    // Método mágico __toString que devuelve una representación del objeto
    public function __toString() {
        return
            "Numero de Empleado: " . $this->getNumeroEmpleado() . "\n" .
            "Numero de Licencia: " . $this->getNumeroLicencia() . "\n" .
            parent::__toString(); // Llama al __toString de Persona para nombre y apellido
    }

    // Busca un responsable por su número de empleado en la BD
    public function buscar($numeroEmpleado) {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        $consulta = "SELECT * FROM responsable WHERE rnumeroempleado = " . $numeroEmpleado;
        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                $fila = $baseDatos->Registro();
                if ($fila) {
                    $this->setNumeroEmpleado($fila['rnumeroempleado']);
                    $this->setNumeroLicencia($fila['rnumerolicencia']);
                    $this->setNombre($fila['rnombre']);
                    $this->setApellido($fila['rapellido']);
                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion("No existe Responsable con ese ID.");
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Inserta un nuevo responsable en la BD
    public function insertar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaInsertar = "INSERT INTO responsable(rnumerolicencia, rnombre, rapellido)
                VALUES('" . $this->getNumeroLicencia() . "', '" . $this->getNombre() . "', '" . $this->getApellido() . "')";
            $id = $baseDatos->devuelveIDInsercion($consultaInsertar);

            if ($id != null) {
                $this->setNumeroEmpleado($id);
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Modifica los datos del responsable en la BD
    public function modificar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaModificar = "UPDATE responsable SET rnumerolicencia = '" . $this->getNumeroLicencia() .
                "', rnombre = '" . $this->getNombre() .
                "', rapellido = '" . $this->getApellido() .
                "' WHERE rnumeroempleado = " . $this->getNumeroEmpleado();

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

    // Devuelve una colección con todos los responsables registrados en la BD
    public function listar() {
        $baseDatos = new BaseDatos();
        $coleccionResponsable = [];
        $consulta = "SELECT * FROM responsable";

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                while ($fila = $baseDatos->Registro()) {
                    $responsable = new Responsable();
                    $responsable->inicializarResponsable(
                        $fila['rnumerolicencia'],
                        $fila['rnombre'],
                        $fila['rapellido']
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

    // Elimina el responsable de la base de datos
public function eliminar() {
    $baseDatos = new BaseDatos();
    $respuesta = false;

    // Prepara la consulta DELETE
    $consulta = "DELETE FROM responsable WHERE rnumeroempleado = " . $this->getNumeroEmpleado();

    // Ejecuta la operación
    if ($baseDatos->Iniciar()) {
        if ($baseDatos->Ejecutar($consulta)) {
            $respuesta = true;
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
    } else {
        $this->setMensajeOperacion($baseDatos->getError());
    }

    return $respuesta;
}

}

?>

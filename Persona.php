<?php

include_once "BaseDatos.php";

class Persona {
    // Atributos privados
    private $idPersona;
    private $nombre;
    private $apellido;
    private $mensajeOperacion;

    // Constructor
    public function __construct() {
        $this->idPersona = 0;
        $this->nombre = "";
        $this->apellido = "";
        $this->mensajeOperacion = "";
    }

    // Métodos de acceso
    public function getIdPersona() { return $this->idPersona; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getMensajeOperacion() { return $this->mensajeOperacion; }

    // Métodos de modificación
    public function setIdPersona($idPersona) { $this->idPersona = $idPersona; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setMensajeOperacion($mensajeOperacion) { $this->mensajeOperacion = $mensajeOperacion; }

    // Carga nombre y apellido usando setters
    public function cargar($nombre, $apellido) {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }

    public function __toString() {
        return "Nombre: " . $this->getNombre() . "\n" .
               "Apellido: " . $this->getApellido() . "\n";
    }

    // Buscar persona por ID
    public function buscar($idPersona) {
        $baseDatos = new BaseDatos();
        $respuesta = false;
        $consulta = "SELECT * FROM persona WHERE idpersona = " . $idPersona;

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                if ($fila = $baseDatos->Registro()) {
                    $this->setIdPersona($fila['idpersona']);
                    $this->setNombre($fila['nombre']);
                    $this->setApellido($fila['apellido']);
                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion("No se encontró persona con ese ID.");
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Insertar nueva persona
    public function insertar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaInsertar = "INSERT INTO persona(nombre, apellido) VALUES('" .
                $this->getNombre() . "', '" . $this->getApellido() . "')";
            $id = $baseDatos->devuelveIDInsercion($consultaInsertar);

            if ($id != null) {
                $this->setIdPersona($id);
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Modificar una persona existente
    public function modificar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaModificar = "UPDATE persona SET nombre = '" . $this->getNombre() .
                "', apellido = '" . $this->getApellido() .
                "' WHERE idpersona = " . $this->getIdPersona();

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

    // Eliminar una persona
    public function eliminar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaEliminar = "DELETE FROM persona WHERE idpersona = " . $this->getIdPersona();
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

    // Listar todas las personas
    public function listar() {
        $baseDatos = new BaseDatos();
        $coleccion = [];
        $consulta = "SELECT * FROM persona";

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                while ($fila = $baseDatos->Registro()) {
                    $obj = new Persona();
                    $obj->setIdPersona($fila['idpersona']);
                    $obj->setNombre($fila['nombre']);
                    $obj->setApellido($fila['apellido']);
                    $coleccion[] = $obj;
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $coleccion;
    }
}

?>

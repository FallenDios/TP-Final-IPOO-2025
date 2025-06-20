<?php

include_once "BaseDatos.php";

class Persona {
    private $nombre;
    private $apellido;
    private $mensajeOperacion;

    public function __construct($nombre = "", $apellido = "") {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
    }


    public function cargar($nombre, $apellido) {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }

    public function __toString() {
        return "Nombre: " . $this->getNombre() . "\n" .
               "Apellido: " . $this->getApellido() . "\n";
    }


    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getMensajeOperacion() { return $this->mensajeOperacion; }

    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setMensajeOperacion($mensajeOperacion) {$this->mensajeOperacion = $mensajeOperacion;}
}


?>

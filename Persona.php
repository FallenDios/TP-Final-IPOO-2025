<?php

include_once "BaseDatos.php";

// Clase Persona: representa una persona con nombre y apellido.
// Es una clase base que puede ser extendida por otras como Pasajero.
class Persona {
    // Atributos privados
    private $nombre;
    private $apellido;
    private $mensajeOperacion; //  para registrar mensajes de error u operación

    //  inicializa los atributos con valores opcionales
    public function __construct($nombre = "", $apellido = "") {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
    }

    //  cargar: asigna nombre y apellido usando los métodos set
    public function cargar($nombre, $apellido) {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }

    // __toString: retorna una representación textual del objeto
    public function __toString() {
        return "Nombre: " . $this->getNombre() . "\n" .
               "Apellido: " . $this->getApellido() . "\n";
    }

    // Métodos de acceso (getters)
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getMensajeOperacion() { return $this->mensajeOperacion; }

    // Métodos de modificación (setters)
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setMensajeOperacion($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }
}

?>

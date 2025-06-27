<?php

include_once "BaseDatos.php"; // Se incluye la clase que maneja la conexión a la base de datos

class Persona {
    // Atributos privados
    private $nombre;
    private $apellido;
    

    // Constructor: inicializa los atributos con valores opcionales
    public function __construct($nombre = "", $apellido = "") {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        
    }

    // Método cargar: asigna nombre y apellido usando los métodos set
    public function cargar($nombre, $apellido) {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }

    // Método __toString: retorna una representación textual del objeto
    public function __toString() {
        return "Nombre: " . $this->getNombre() . "\n" .
               "Apellido: " . $this->getApellido() . "\n";
    }

    // Métodos de acceso (getters)
    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }


    // Métodos de modificación (setters)
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }


}

?>

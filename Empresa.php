<?php 
include_once "BaseDatos.php"; // Se incluye la clase que gestiona la conexión y operaciones con la base de datos.

class Empresa {
    // Atributos privados de la clase
    private $idempresa;
    private $nombre;
    private $direccion;
    private $mensajeOperacion; // Para registrar mensajes de error o estado de operación

    // Constructor: inicializa los atributos con valores por defecto
    public function __construct() {
        $this->idempresa = 0;
        $this->nombre = "";
        $this->direccion = "";
        $this->mensajeOperacion = "";
    }

    // Método para cargar valores a los atributos usando los métodos set
    public function cargar($unNombre, $unaDireccion) {
        $this->setNombre($unNombre);
        $this->setDireccion($unaDireccion);
    }

    // Métodos de acceso (getters)
    public function getIdEmpresa() { return $this->idempresa; }
    public function getNombre() { return $this->nombre; }
    public function getDireccion() { return $this->direccion; }
    public function getMensajeOperacion() { return $this->mensajeOperacion; }

    // Métodos de modificación (setters)
    public function setIdEmpresa($idempresa) { $this->idempresa = $idempresa; }
    public function setNombre($enombre) { $this->nombre = $enombre; }
    public function setDireccion($edireccion) { $this->direccion = $edireccion; }
    public function setMensajeOperacion($mensajeOperacion) { $this->mensajeOperacion = $mensajeOperacion; }

    // Método mágico __toString: retorna una cadena con los datos de la empresa
    public function __toString() {
        return
        "ID Empresa: " . $this->getIdEmpresa() . "\n" .
        "Nombre: " . $this->getNombre() . "\n" .
        "Direccion: " . $this->getDireccion() . "\n"; 
    }

    // Método que busca una empresa por ID en la base de datos
    public function buscar($idEmpresa) {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        $consulta = "SELECT * FROM empresa WHERE idempresa = " . $idEmpresa;
        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                if ($fila = $baseDatos->Registro()) {
                    // Se setean los atributos con los valores obtenidos
                    $this->setIdEmpresa($fila['idempresa']);
                    $this->setNombre($fila['enombre']);
                    $this->setDireccion($fila['edireccion']);
                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion("No existe empresa con ese ID.");
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
        return $respuesta;
    }

    // Método que inserta un nuevo registro en la tabla empresa
    public function insertar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaInsertar = "INSERT INTO empresa(enombre, edireccion) 
            VALUES('" . $this->getNombre() . "', '" . $this->getDireccion() . "')";
            $id = $baseDatos->devuelveIDInsercion($consultaInsertar);

            if ($id != null) {
                $this->setIdEmpresa($id); // Se asigna el ID generado por la base
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
        return $respuesta;
    }

    // Método que actualiza los datos de una empresa existente
    public function modificar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaModificar = "UPDATE empresa SET enombre='" . $this->getNombre() . "', edireccion='" . $this->getDireccion() .
            "' WHERE idempresa=" . $this->getIdEmpresa();
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

    // Método que elimina una empresa de la base de datos
    public function eliminar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaEliminar = "DELETE FROM empresa WHERE idempresa=" . $this->getIdEmpresa();
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
}

<?php
class BaseDatos {
    private $conexion;
    private $base;
    private $usuario;
    private $clave;
    private $host;

    public function __construct() {
        $this->base = "bdviajes";
        $this->usuario = "root";
        $this->clave = "";
        $this->host = "localhost";
    }

    public function Iniciar() {
        $exito = false;
        $conexion = mysqli_connect($this->host, $this->usuario, $this->clave, $this->base);
        if ($conexion) {
            $this->conexion = $conexion;
            $exito = true;
        }
        return $exito;
    }

    public function Ejecutar($consulta) {
        $resp = false;
        if ($this->conexion) {
            $resp = mysqli_query($this->conexion, $consulta);
        }
        return $resp;
    }

    public function Registro($consulta) {
        $resultado = false;
        if ($this->conexion) {
            $resultado = mysqli_query($this->conexion, $consulta);
        }
        return $resultado;
    }

    public function devuelveIDInsercion() {
    $id = null;
    if ($this->conexion) {
        $id = mysqli_insert_id($this->conexion);
    }
    return $id;
}

}
?>

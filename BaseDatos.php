<?php
// Clase BaseDatos: encapsula la conexión y operaciones básicas con una base de datos MySQL
class BaseDatos {
    // Atributos privados para almacenar los parámetros de conexión y el enlace
    private $conexion;
    private $base;
    private $usuario;
    private $clave;
    private $host;

    // inicializa los parámetros de conexión a la base de datos
    public function __construct() {
        $this->base = "bdviajes";    // Nombre de la base de datos
        $this->usuario = "root";     // Usuario de acceso
        $this->clave = "";           // Clave del usuario (vacía por defecto)
        $this->host = "localhost";   // Servidor de la base de datos
    }

    // Iniciar: intenta establecer la conexión con la base de datos
    // Retorna true si la conexión fue exitosa, false en caso contrario
    public function Iniciar() {
        $exito = false;
        $conexion = mysqli_connect($this->host, $this->usuario, $this->clave, $this->base);
        if ($conexion) {
            $this->conexion = $conexion;
            $exito = true;
        }
        return $exito;
    }

    // Ejecutar: ejecuta una consulta SQL (INSERT, UPDATE o DELETE)
    // Retorna el resultado de la ejecución o false si falla
    public function Ejecutar($consulta) {
        $resp = false;
        if ($this->conexion) {
            $resp = mysqli_query($this->conexion, $consulta);
        }
        return $resp;
    }

    // Registro: ejecuta una consulta SELECT y retorna el resultado
    // Retorna el resultado del mysqli_query para ser recorrido posteriormente
    public function Registro($consulta) {
        $resultado = false;
        if ($this->conexion) {
            $resultado = mysqli_query($this->conexion, $consulta);
        }
        return $resultado;
    }

    // devuelveIDInsercion: retorna el ID generado por una inserción con autoincrement
    public function devuelveIDInsercion() {
        $id = null;
        if ($this->conexion) {
            $id = mysqli_insert_id($this->conexion);
        }
        return $id;
    }


    // Devuelve el último error de la conexión
public function getError() {
    return mysqli_error($this->conexion);
}
}
?>

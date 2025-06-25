<?php 
include_once "BaseDatos.php";

// Clase Empresa: representa una entidad del sistema con sus atributos y operaciones
class Empresa {

    // Atributos privados
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeoperacion; // usado para almacenar mensajes de error

    // permite inicializar el objeto con valores opcionales
    public function __construct($idempresa = 0, $enombre = "", $edireccion = "") {
        $this->idempresa = $idempresa;
        $this->enombre = $enombre;
        $this->edireccion = $edireccion;
    }

    // cargar: setea todos los atributos del objeto usando los métodos set
    public function cargar($idempresa, $enombre, $edireccion){
        $this->setIdempresa($idempresa);
        $this->setEnombre($enombre);
        $this->setEdireccion($edireccion);
    }

    // Métodos de acceso (getters)
    public function getIdempresa() { return $this->idempresa; }
    public function getEnombre() { return $this->enombre; }
    public function getEdireccion() { return $this->edireccion; }
    public function getmensajeoperacion() { return $this->mensajeoperacion; }

    // Métodos de modificación (setters)
    public function setIdempresa($idempresa): void { $this->idempresa = $idempresa; }
    public function setEnombre($enombre): void { $this->enombre = $enombre; }
    public function setEdireccion($edireccion): void { $this->edireccion = $edireccion; }
    public function setmensajeoperacion($mensajeoperacion) { $this->mensajeoperacion = $mensajeoperacion; }

    //__toString: retorna una representación textual del objeto Empresa
    public function __toString() {
        return "ID Empresa: " . $this->getIdempresa() . "\n" .
               "Nombre Empresa: " . $this->getEnombre() . "\n" .
               "Dirección Empresa: " . $this->getEdireccion() . "\n";
    }

    // Buscar: busca una empresa por ID en la base de datos
    public function Buscar($idempresa){
        $base = new BaseDatos();
        $consultaEmpresa = "Select * from empresa where idempresa = " . $idempresa;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                // Se espera una única fila de resultado
                if ($row2 = $base->Registro()) {
                    $this->setIdempresa($idempresa);
                    $this->setEnombre($row2['enombre']);
                    $this->setEdireccion($row2['edireccion']);
                    $resp = true;
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    //listar: devuelve un arreglo de objetos Empresa que cumplen una condición opcional
    public function listar($condicion = ""){
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaEmpresas = "Select * from empresa";
        if ($condicion != "") {
            $consultaEmpresas .= ' where ' . $condicion;
        }
        $consultaEmpresas .= " order by enombre";
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresas)) {
                $arregloEmpresa = array();
                // Se recorren todas las filas retornadas para crear los objetos Empresa
                while ($row2 = $base->Registro()) {
                    $idempresa = $row2['idempresa'];
                    $enombre = $row2['enombre'];
                    $edireccion = $row2['edireccion'];
                    $emp = new Empresa($idempresa, $enombre, $edireccion);
                    array_push($arregloEmpresa, $emp);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloEmpresa;
    }

    // insertar: agrega una nueva empresa a la base de datos
    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO empresa(enombre, edireccion) 
            VALUES (
                '".$this->getEnombre()."',
                '".$this->getEdireccion()."'
            )";
        if ($base->Iniciar()) {
            if ($idInsertado = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setIdempresa($idInsertado);
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    //modificar: actualiza los datos de una empresa en la base
    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE empresa SET 
            enombre = '".$this->getEnombre()."', 
            edireccion = '".$this->getEdireccion()."' 
            WHERE idempresa = ".$this->getIdempresa();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    //eliminar: elimina la empresa de la base de datos
    public function eliminar(){
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorrar = "DELETE FROM empresa WHERE idempresa = " . $this->getIdempresa();
            if ($base->Ejecutar($consultaBorrar)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

}
?>

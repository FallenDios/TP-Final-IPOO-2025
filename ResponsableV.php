<?php
include_once "BaseDatos.php";
include_once "persona.php";

class ResponsableV extends Persona {

    private $rnumeroempleado;
    private $rnumerolicencia;   

    public function __construct($nombre = "", $apellido = "", $rnumeroempleado = 0, $rnumerolicencia = "") {
        parent::__construct($nombre, $apellido); 
        $this->rnumeroempleado = $rnumeroempleado;
        $this->rnumerolicencia = $rnumerolicencia;
    }


    public function cargarResponsable($nombre, $apellido, $rnumeroempleado, $rnumerolicencia){
        $this->cargar($nombre, $apellido);
        $this->setRnumeroempleado($rnumeroempleado);
        $this->setRnumerolicencia($rnumerolicencia);
    }

    public function getRnumeroempleado() {return $this->rnumeroempleado;}
    public function getRnumerolicencia() {return $this->rnumerolicencia;}

    public function setRnumeroempleado($rnumeroempleado): void {$this->rnumeroempleado = $rnumeroempleado;}
    public function setRnumerolicencia($rnumerolicencia): void {$this->rnumerolicencia = $rnumerolicencia;}


    public function __toString()
    {
        return 
                "Responsable:\n" .
                parent::__toString() . 
                "Número Empleado: " . $this->getRnumeroempleado() . "\n" .
                "Número Licencia: " . $this->getRnumerolicencia() . "\n";
    }

    public static function Listar($condicion = ""){
        $arregloResponsable = null;
        $base = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable";
        if ($condicion != "") {
            $consultaResponsable .= ' WHERE ' . $condicion;
        }
        $consultaResponsable .= " ORDER BY rnumeroempleado ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                $arregloResponsable = array();
                while ($row2 = $base->Registro()) {
                    $objResponsable = new ResponsableV();
                    $objResponsable->cargarResponsable($row2['rnombre'], $row2['rapellido'], $row2['rnumeroempleado'], $row2['rnumerolicencia']);
                    array_push($arregloResponsable, $objResponsable);
                }
            } else {
                throw new Exception("Error al listar responsables: " . $base->getError());
            }
        } else {
            throw new Exception("Error al iniciar la base de datos: " . $base->getError());
        }
        return $arregloResponsable;

}
    public static function Buscar($rnumeroempleado){
        $base = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable WHERE rnumeroempleado=" . $rnumeroempleado;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                if ($row2 = $base->Registro()) {
                    $objResponsable = new ResponsableV();
                    $objResponsable->cargarResponsable($row2['rnombre'], $row2['rapellido'], $row2['rnumeroempleado'], $row2['rnumerolicencia']);
                    $resp = $objResponsable;
                }
            } else {
                throw new Exception("Error al buscar responsable: " . $base->getError());
            }
        } else {
            throw new Exception("Error al iniciar la base de datos: " . $base->getError());
        }
        return $resp;
}




    public function Insertar(){
        $base=new BaseDatos();
        $resp= false;
        $consultaInsertar="INSERT INTO responsable(rnumeroempleado, rnumerolicencia, rnombre, rapellido) 
                   VALUES (".$this->getRnumeroempleado().", '".$this->getRnumerolicencia()."', '".parent::getNombre()."', '".parent::getApellido()."')";

            if ($base->Iniciar()) {
        $idInsertado = $base->devuelveIDInsercion($consultaInsertar); // ejecuta y devuelve ID
        if ($idInsertado !== null) {
            $this->setRnumeroempleado($idInsertado);
            $resp = true;
        } else {
            parent::setMensajeoperacion("Responsable no insertado: " . $base->getError());
        }
    } else {
        parent::setMensajeoperacion("Error al iniciar conexión.");
    }

        
        return $resp;
    }

    public function modificar(){
        $base=new BaseDatos();
        $resp= false;
        $consultaModificar="UPDATE responsable SET rnumerolicencia='".$this->getRnumerolicencia()."', rnombre='".parent::getNombre()."', rapellido='".parent::getApellido()."' WHERE rnumeroempleado=".$this->getRnumeroempleado();
        if($base->Iniciar()){
            if($base->Ejecutar($consultaModificar)){
                $resp= true;
            } else {
                parent::setMensajeoperacion("Responsable no modificado");
            }
        } else {
            parent::setMensajeoperacion("Responsable no modificado");
        }
        return $resp;
    }
    public function eliminar(){
        $base=new BaseDatos();
        $resp= false;
        $consultaEliminar="DELETE FROM responsable WHERE rnumeroempleado=".$this->getRnumeroempleado();
        if($base->Iniciar()){
            if($base->Ejecutar($consultaEliminar)){
                $resp= true;
            } else {
                parent::setMensajeoperacion("Responsable no eliminado");
            }
        } else {
            parent::setMensajeoperacion("Responsable no eliminado");
        }
        return $resp;
    }
}



?>

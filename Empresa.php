<?php
include_once "BaseDatos.php";

class Empresa{

    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeoperacion;

    public function __construct($idempresa = 0, $enombre = "", $edireccion = "") {
    $this->idempresa = $idempresa;
    $this->enombre = $enombre;
    $this->edireccion = $edireccion;
}
    
    public function cargar($idempresa, $enombre, $edireccion){
        $this->setIdempresa($idempresa);
        $this->setEnombre($enombre);
        $this->setEdireccion($edireccion);
    }

	public function getIdempresa() {return $this->idempresa;}

	public function getEnombre() {return $this->enombre;}

	public function getEdireccion() {return $this->edireccion;}

	public function setIdempresa( $idempresa): void {$this->idempresa = $idempresa;}

	public function setEnombre( $enombre): void {$this->enombre = $enombre;}

	public function setEdireccion( $edireccion): void {$this->edireccion = $edireccion;}

    public function setmensajeoperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getmensajeoperacion() {
        return $this->mensajeoperacion;
    }


	public function __toString()
    {
        return "ID Empresa: " . $this->getIdempresa() . "\n" .
               "Nombre Empresa: " . $this->getEnombre() . "\n" .
               "Dirección Empresa: " . $this->getEdireccion() . "\n";
    }

    public function Buscar($idempresa){
        $base=new BaseDatos();
        $consultaEmpresa="Select * from empresa where idempresa=".$idempresa;
        $resp= false;
        if($base->Iniciar()){
            if($base->Ejecutar($consultaEmpresa)){
                if($row2=$base->Registro()){					
                    $this->setIdempresa($idempresa);
                    $this->setEnombre($row2['enombre']);
                    $this->setEdireccion($row2['edireccion']);
                    $resp= true;
                }				
            }	else {
                $this->setmensajeoperacion($base->getError());
            }
        }	else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function listar($condicion=""){
        $arregloEmpresa = null;
        $base=new BaseDatos();
        $consultaEmpresas="Select * from empresa ";
        ///si hay condicion como parametro, se agrega where.
        if ($condicion!=""){
            $consultaEmpresas=$consultaEmpresas.' where '.$condicion;
        }
        /// concatena order
        $consultaEmpresas .= " order by enombre ";
        /// verifica que inicie la DB y verifica que se ejecute correctamente la conslta
        if($base->Iniciar()){
            if($base->Ejecutar($consultaEmpresas)){				
                
                $arregloEmpresa= array();
                /// recorre todas empresas y las guarda en un array para despues retornarlo.
                while($row2=$base->Registro()){
                    $idempresa=$row2['idempresa'];
                    $enombre=$row2['enombre'];
                    $edireccion=$row2['edireccion'];    
                    $emp=new Empresa($idempresa, $enombre, $edireccion);
                    array_push($arregloEmpresa,$emp);
                }
            }	
            else {
                $this->setmensajeoperacion($base->getError());
            }
        }	else {
            $this->setmensajeoperacion($base->getError());
        }		
        return $arregloEmpresa;
    }   

    public function insertar(){
        $base=new BaseDatos();
        $resp= false;
        $consultaInsertar="INSERT INTO empresa(enombre, edireccion) 
        VALUES (
        '".$this->getEnombre()."',
        '".$this->getEdireccion()."'
        )";

        if($base->Iniciar()){
            if($idInsertado=$base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdempresa($idInsertado);
                $resp= true;
            }	else {
                $this->setmensajeoperacion($base->getError());
            }
        }	else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp= false;
        $base=new BaseDatos();
        $consultaModifica="UPDATE empresa SET enombre='".$this->getEnombre()."', edireccion='".$this->getEdireccion()."' WHERE idempresa=".$this->getIdempresa();
        if($base->Iniciar()){
            if($base->Ejecutar($consultaModifica)){
                $resp= true;
            }	else {
                $this->setmensajeoperacion($base->getError());
            }
        }	else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }   

    public function eliminar(){
        $base=new BaseDatos();
        $resp= false;
        if($base->Iniciar()){
            $consultaBorrar="DELETE FROM empresa WHERE idempresa=".$this->getIdempresa();
            if($base->Ejecutar($consultaBorrar)){
                $resp= true;
            }	else {
                $this->setmensajeoperacion($base->getError());
            }
        }	else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

}


?>

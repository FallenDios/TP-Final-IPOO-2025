<?php
class Empresa {
    private $idempresa;
    private $enombre;
    private $edireccion;

    public function __construct($idempresa = null, $enombre = "", $edireccion = "") {
        $this->idempresa = $idempresa;
        $this->enombre = $enombre;
        $this->edireccion = $edireccion;
    }

    // Métodos de acceso
    public function getIdempresa() {
        return $this->idempresa;
    }

    public function setIdempresa($idempresa) {
        $this->idempresa = $idempresa;
    }

    public function getEnombre() {
        return $this->enombre;
    }

    public function setEnombre($enombre) {
        $this->enombre = $enombre;
    }

    public function getEdireccion() {
        return $this->edireccion;
    }

    public function setEdireccion($edireccion) {
        $this->edireccion = $edireccion;
    }

    public function __toString() {
        return "ID Empresa: " . $this->getIdempresa() .
            "\nNombre: " . $this->getEnombre() .
            "\nDirección: " . $this->getEdireccion();
    }

    // ORM

    public function insertar() {
        $base = new BaseDatos();
        $exito = false;
        $consulta = "INSERT INTO empresa (enombre, edireccion) VALUES ('" . $this->getEnombre() . "', '" . $this->getEdireccion() . "')";
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $id = $base->devuelveIDInsercion();
                if ($id !== null) {
                    $this->setIdempresa($id);
                    $exito = true;
                }
            }
        }

        return $exito;
    }

    public function modificar() {
        $base = new BaseDatos();
        $exito = false;
        $consulta = "UPDATE empresa SET enombre = '" . $this->getEnombre() . "', edireccion = '" . $this->getEdireccion() . "' WHERE idempresa = " . $this->getIdempresa();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $exito = true;
            }
        }

        return $exito;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $exito = false;
        $consulta = "DELETE FROM empresa WHERE idempresa = " . $this->getIdempresa();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $exito = true;
            }
        }

        return $exito;
    }

    public static function buscar($condicion = "") {
        $colEmpresas = [];
        $base = new BaseDatos();
        $consulta = "SELECT * FROM empresa";
        if ($condicion != "") {
            $consulta .= " WHERE " . $condicion;
        }

        if ($base->Iniciar()) {
            $res = $base->Registro($consulta);
            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $objEmpresa = new Empresa($row['idempresa'], $row['enombre'], $row['edireccion']);
                    $colEmpresas[] = $objEmpresa;
                }
            }
        }

        return $colEmpresas;
    }
}
?>

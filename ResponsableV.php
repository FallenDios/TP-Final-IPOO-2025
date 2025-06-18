<?php
class ResponsableV {
    private $rnumeroempleado;
    private $rnumerolicencia;
    private $rnombre;
    private $rapellido;

    public function __construct($rnumeroempleado = null, $rnumerolicencia = "", $rnombre = "", $rapellido = "") {
        $this->rnumeroempleado = $rnumeroempleado;
        $this->rnumerolicencia = $rnumerolicencia;
        $this->rnombre = $rnombre;
        $this->rapellido = $rapellido;
    }

    // Métodos de acceso
    public function getRnumeroempleado() {
        return $this->rnumeroempleado;
    }

    public function setRnumeroempleado($rnumeroempleado) {
        $this->rnumeroempleado = $rnumeroempleado;
    }

    public function getRnumerolicencia() {
        return $this->rnumerolicencia;
    }

    public function setRnumerolicencia($rnumerolicencia) {
        $this->rnumerolicencia = $rnumerolicencia;
    }

    public function getRnombre() {
        return $this->rnombre;
    }

    public function setRnombre($rnombre) {
        $this->rnombre = $rnombre;
    }

    public function getRapellido() {
        return $this->rapellido;
    }

    public function setRapellido($rapellido) {
        $this->rapellido = $rapellido;
    }

    public function __toString() {
        return "Empleado #: " . $this->getRnumeroempleado() .
               "\nLicencia: " . $this->getRnumerolicencia() .
               "\nNombre: " . $this->getRnombre() .
               "\nApellido: " . $this->getRapellido();
    }

    // ORM
    public function insertar() {
        $base = new BaseDatos();
        $exito = false;
        $consulta = "INSERT INTO responsable (rnumerolicencia, rnombre, rapellido) VALUES ('" . $this->getRnumerolicencia() . "', '" . $this->getRnombre() . "', '" . $this->getRapellido() . "')";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $id = $base->devuelveIDInsercion();
                if ($id !== null) {
                    $this->setRnumeroempleado($id);
                    $exito = true;
                }
            }
        }

        return $exito;
    }

    public function modificar() {
        $base = new BaseDatos();
        $exito = false;
        $consulta = "UPDATE responsable SET rnumerolicencia = '" . $this->getRnumerolicencia() .
                    "', rnombre = '" . $this->getRnombre() .
                    "', rapellido = '" . $this->getRapellido() .
                    "' WHERE rnumeroempleado = " . $this->getRnumeroempleado();

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
        $consulta = "DELETE FROM responsable WHERE rnumeroempleado = " . $this->getRnumeroempleado();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $exito = true;
            }
        }

        return $exito;
    }

    public static function buscar($condicion = "") {
        $colResponsables = [];
        $base = new BaseDatos();
        $consulta = "SELECT * FROM responsable";
        if ($condicion != "") {
            $consulta .= " WHERE " . $condicion;
        }

        if ($base->Iniciar()) {
            $res = $base->Registro($consulta);
            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $obj = new ResponsableV($row['rnumeroempleado'], $row['rnumerolicencia'], $row['rnombre'], $row['rapellido']);
                    $colResponsables[] = $obj;
                }
            }
        }

        return $colResponsables;
    }
}
?>

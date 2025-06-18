<?php
class Pasajero {
    private $pdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $idviaje;

    public function __construct($pdocumento = "", $pnombre = "", $papellido = "", $ptelefono = "", $idviaje = null) {
        $this->pdocumento = $pdocumento;
        $this->pnombre = $pnombre;
        $this->papellido = $papellido;
        $this->ptelefono = $ptelefono;
        $this->idviaje = $idviaje;
    }

    // Métodos de acceso
    public function getPdocumento() {
        return $this->pdocumento;
    }

    public function setPdocumento($pdocumento) {
        $this->pdocumento = $pdocumento;
    }

    public function getPnombre() {
        return $this->pnombre;
    }

    public function setPnombre($pnombre) {
        $this->pnombre = $pnombre;
    }

    public function getPapellido() {
        return $this->papellido;
    }

    public function setPapellido($papellido) {
        $this->papellido = $papellido;
    }

    public function getPtelefono() {
        return $this->ptelefono;
    }

    public function setPtelefono($ptelefono) {
        $this->ptelefono = $ptelefono;
    }

    public function getIdviaje() {
        return $this->idviaje;
    }

    public function setIdviaje($idviaje) {
        $this->idviaje = $idviaje;
    }

    public function __toString() {
        return "Documento: " . $this->getPdocumento() .
               "\nNombre: " . $this->getPnombre() .
               "\nApellido: " . $this->getPapellido() .
               "\nTeléfono: " . $this->getPtelefono() .
               "\nID Viaje: " . $this->getIdviaje();
    }

    // ORM
    public function insertar() {
        $base = new BaseDatos();
        $exito = false;
        $consulta = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) VALUES ('" .
                    $this->getPdocumento() . "', '" .
                    $this->getPnombre() . "', '" .
                    $this->getPapellido() . "', '" .
                    $this->getPtelefono() . "', '" .
                    $this->getIdviaje() . "')";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $exito = true;
            }
        }

        return $exito;
    }

    public function modificar() {
        $base = new BaseDatos();
        $exito = false;
        $consulta = "UPDATE pasajero SET pnombre = '" . $this->getPnombre() .
                    "', papellido = '" . $this->getPapellido() .
                    "', ptelefono = '" . $this->getPtelefono() .
                    "', idviaje = '" . $this->getIdviaje() .
                    "' WHERE pdocumento = '" . $this->getPdocumento() . "'";

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
        $consulta = "DELETE FROM pasajero WHERE pdocumento = '" . $this->getPdocumento() . "'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $exito = true;
            }
        }

        return $exito;
    }

    public static function buscar($condicion = "") {
        $colPasajeros = [];
        $base = new BaseDatos();
        $consulta = "SELECT * FROM pasajero";
        if ($condicion != "") {
            $consulta .= " WHERE " . $condicion;
        }

        if ($base->Iniciar()) {
            $res = $base->Registro($consulta);
            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $obj = new Pasajero($row['pdocumento'], $row['pnombre'], $row['papellido'], $row['ptelefono'], $row['idviaje']);
                    $colPasajeros[] = $obj;
                }
            }
        }

        return $colPasajeros;
    }
}
?>

<?php
class Viaje {
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $vimporte;
    private $objResponsable;
    private $objEmpresa;
    private $colPasajeros;

    public function __construct($idviaje = null, $vdestino = "", $vcantmaxpasajeros = 0, $vimporte = 0, $objResponsable = null, $objEmpresa = null, $colPasajeros = []) {
        $this->idviaje = $idviaje;
        $this->vdestino = $vdestino;
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
        $this->vimporte = $vimporte;
        $this->objResponsable = $objResponsable;
        $this->objEmpresa = $objEmpresa;
        $this->colPasajeros = $colPasajeros;
    }

    // Métodos de acceso
    public function getIdviaje() {
        return $this->idviaje;
    }

    public function setIdviaje($idviaje) {
        $this->idviaje = $idviaje;
    }

    public function getVdestino() {
        return $this->vdestino;
    }

    public function setVdestino($vdestino) {
        $this->vdestino = $vdestino;
    }

    public function getVcantmaxpasajeros() {
        return $this->vcantmaxpasajeros;
    }

    public function setVcantmaxpasajeros($vcantmaxpasajeros) {
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
    }

    public function getVimporte() {
        return $this->vimporte;
    }

    public function setVimporte($vimporte) {
        $this->vimporte = $vimporte;
    }

    public function getObjResponsable() {
        return $this->objResponsable;
    }

    public function setObjResponsable($objResponsable) {
        $this->objResponsable = $objResponsable;
    }

    public function getObjEmpresa() {
        return $this->objEmpresa;
    }

    public function setObjEmpresa($objEmpresa) {
        $this->objEmpresa = $objEmpresa;
    }

    public function getColPasajeros() {
        return $this->colPasajeros;
    }

    public function setColPasajeros($colPasajeros) {
        $this->colPasajeros = $colPasajeros;
    }

    public function __toString() {
        $texto = "ID Viaje: " . $this->getIdviaje() .
                 "\nDestino: " . $this->getVdestino() .
                 "\nCant. Máxima: " . $this->getVcantmaxpasajeros() .
                 "\nImporte: $" . $this->getVimporte() .
                 "\n\nResponsable:\n" . $this->getObjResponsable() .
                 "\n\nEmpresa:\n" . $this->getObjEmpresa() .
                 "\n\nPasajeros:\n";

        foreach ($this->getColPasajeros() as $pasajero) {
            $texto .= "\n" . $pasajero . "\n";
        }

        return $texto;
    }

    // ORM
    public function insertar() {
        $base = new BaseDatos();
        $exito = false;

        $consulta = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) VALUES ('" .
            $this->getVdestino() . "', '" . $this->getVcantmaxpasajeros() . "', '" . $this->getObjEmpresa()->getIdempresa() . "', '" . $this->getObjResponsable()->getRnumeroempleado() . "', '" . $this->getVimporte() . "')";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $id = $base->devuelveIDInsercion();
                if ($id !== null) {
                    $this->setIdviaje($id);
                    $exito = true;
                }
            }
        }

        return $exito;
    }

    public function modificar() {
        $base = new BaseDatos();
        $exito = false;

        $consulta = "UPDATE viaje SET vdestino = '" . $this->getVdestino() .
                    "', vcantmaxpasajeros = '" . $this->getVcantmaxpasajeros() .
                    "', idempresa = '" . $this->getObjEmpresa()->getIdempresa() .
                    "', rnumeroempleado = '" . $this->getObjResponsable()->getRnumeroempleado() .
                    "', vimporte = '" . $this->getVimporte() .
                    "' WHERE idviaje = " . $this->getIdviaje();

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

    //eliminamos a  los pasajeros relacionados
    $colPasajeros = Pasajero::buscar("idviaje = " . $this->getIdviaje());
    foreach ($colPasajeros as $pasajero) {
        $pasajero->eliminar();
    }

    //eliminamos el viaje
    $consulta = "DELETE FROM viaje WHERE idviaje = " . $this->getIdviaje();

    if ($base->Iniciar()) {
        if ($base->Ejecutar($consulta)) {
            $exito = true;
        }
    }

    return $exito;
}

    public static function buscar($condicion = "") {
        $colViajes = [];
        $base = new BaseDatos();
        $consulta = "SELECT * FROM viaje";
        if ($condicion != "") {
            $consulta .= " WHERE " . $condicion;
        }

        if ($base->Iniciar()) {
            $res = $base->Registro($consulta);
            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $objEmpresa = Empresa::buscar("idempresa=" . $row['idempresa'])[0];
                    $objResponsable = ResponsableV::buscar("rnumeroempleado=" . $row['rnumeroempleado'])[0];
                    $colPasajeros = Pasajero::buscar("idviaje=" . $row['idviaje']);

                    $objViaje = new Viaje($row['idviaje'], $row['vdestino'], $row['vcantmaxpasajeros'], $row['vimporte'], $objResponsable, $objEmpresa, $colPasajeros);
                    $colViajes[] = $objViaje;
                }
            }
        }

        return $colViajes;
    }
}
?>

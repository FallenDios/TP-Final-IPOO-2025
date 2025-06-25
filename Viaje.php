<?php

// Clase Viaje: representa un viaje con empresa, responsable, pasajeros, destino e importe
class Viaje {
    // Atributos privados
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $objempresa;           // Delegación: contiene un objeto Empresa
    private $objresponsableV;      // Delegación: contiene un objeto ResponsableV
    private $colObjPasajeros;      // Colección de objetos Pasajero
    private $vimporte;
    private $mensajeoperacion;     // Almacena errores u operaciones

    // Constructor: inicializa todos los atributos
    public function __construct() {
        $this->idviaje = "";
        $this->vdestino = "";
        $this->vcantmaxpasajeros = "";
        $this->objempresa = new Empresa(); // delegación
        $this->objresponsableV = new ResponsableV(); // delegación
        $this->colObjPasajeros = [];
        $this->vimporte = "";
        $this->mensajeoperacion = "";
    }

    //  cargar: asigna valores a los atributos, y usa delegación para buscar Empresa
    public function cargar($idviaje, $vdestino, $vcantmaxpasajeros, $idempresa, $objResponsableV, $vimporte) {
        $empresa = new Empresa();
        if (is_object($idempresa) && get_class($idempresa) === 'Empresa') {
            $idempresa = $idempresa->getIdempresa(); // se extrae el ID si se pasa un objeto
        }
        $empresa->Buscar($idempresa); // delegación

        $this->setIdviaje($idviaje);
        $this->setVdestino($vdestino);
        $this->setVcantmaxpasajeros($vcantmaxpasajeros);
        $this->setObjempresa($empresa);
        $this->setObjresponsableV($objResponsableV);
        $this->setColObjPasajeros([]); // lista vacía de pasajeros
        $this->setVimporte($vimporte);
    }

    // Getters
    public function getIdviaje() { return $this->idviaje; }
    public function getVdestino() { return $this->vdestino; }
    public function getVcantmaxpasajeros() { return $this->vcantmaxpasajeros; }
    public function getObjempresa() { return $this->objempresa; }
    public function getObjresponsableV() { return $this->objresponsableV; }
    public function getColObjPasajeros() { return $this->colObjPasajeros; }
    public function getVimporte() { return $this->vimporte; }
    public function getMensajeoperacion() { return $this->mensajeoperacion; }

    // Setters
    public function setIdviaje($idviaje) { $this->idviaje = $idviaje; }
    public function setVdestino($vdestino) { $this->vdestino = $vdestino; }
    public function setVcantmaxpasajeros($vcantmaxpasajeros) { $this->vcantmaxpasajeros = $vcantmaxpasajeros; }
    public function setObjempresa($objempresa) { $this->objempresa = $objempresa; }
    public function setObjresponsableV($objresponsableV) { $this->objresponsableV = $objresponsableV; }
    public function setColObjPasajeros($colObjPasajeros) { $this->colObjPasajeros = $colObjPasajeros; }
    public function setVimporte($vimporte) { $this->vimporte = $vimporte; }
    public function setMensajeoperacion($mensajeoperacion) { $this->mensajeoperacion = $mensajeoperacion; }

    // Método Buscar: busca un viaje por ID y carga sus datos
    public function Buscar($id) {
        $base = new BaseDatos();
        $consulta = "Select * from viaje where idviaje=" . $id;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $empresa = new Empresa();
                    $empresa->Buscar($row2['idempresa']);

                    $responsable = new ResponsableV();
                    $responsable->Buscar($row2['rnumeroempleado']);

                    $this->cargar($row2['idviaje'], $row2['vdestino'], $row2['vcantmaxpasajeros'], $empresa, $responsable, $row2['vimporte']);
                    $resp = true;
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    // Método listar: retorna todos los viajes opcionalmente filtrados por una condición
    public function listar($condicion = "") {
        $arregloViajes = null;
        $base = new BaseDatos();
        $consulta = "Select * from viaje";
        if ($condicion != "") {
            $consulta .= ' where ' . $condicion;
        }
        $consulta .= " order by idviaje ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $arregloViajes = [];
                while ($row2 = $base->Registro()) {
                    $viaje = new Viaje();

                    $empresa = new Empresa();
                    $empresa->Buscar($row2['idempresa']);

                    $responsable = new ResponsableV();
                    $responsable->Buscar($row2['rnumeroempleado']);

                    $viaje->cargar($row2['idviaje'], $row2['vdestino'], $row2['vcantmaxpasajeros'], $empresa, $responsable, $row2['vimporte']);

                    array_push($arregloViajes, $viaje);
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $arregloViajes;
    }

    // Método Insertar: inserta el viaje en la base de datos y actualiza su ID
    public function Insertar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar =  "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte)
            VALUES (
                '".$this->getVdestino()."',
                '".$this->getVcantmaxpasajeros()."',
                '".$this->getObjempresa()->getIdempresa()."',
                '".$this->getObjresponsableV()->getRnumeroempleado()."',
                '".$this->getVimporte()."'
            )";
        if ($base->Iniciar()) {
            $idInsertado = $base->devuelveIDInsercion($consultaInsertar);
            if ($idInsertado) {
                $this->setIdviaje($idInsertado);
                $resp = true;
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getERROR());
        }
        return $resp;
    }

    // Método Modificar: actualiza los datos del viaje en la base
    public function Modificar() {
        $resp = false;
        $base = new BaseDatos();
        $idEmpresa = $this->getObjempresa()->getIdempresa();
        $rnum = $this->getObjresponsableV()->getRnumeroempleado();
        $consultaModifica = "UPDATE viaje SET 
            vdestino = '{$this->getVdestino()}',
            vcantmaxpasajeros = {$this->getVcantmaxpasajeros()},
            idempresa = {$idEmpresa},
            rnumeroempleado = {$rnum},
            vimporte = {$this->getVimporte()}
            WHERE idviaje = {$this->getIdviaje()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    // Método Eliminar: elimina el viaje de la base de datos
    public function Eliminar() {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM viaje WHERE idviaje=" . $this->getIdviaje();
            if ($base->Ejecutar($consultaBorra)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $resp;
    }

    // Método __toString: devuelve los datos del viaje como string
    public function __toString() {
        return "ID del viaje: " . $this->getIdviaje() . "\n" .
            "Destino: " . $this->getVdestino() . "\n" .
            "Cantidad máxima de pasajeros: " . $this->getVcantmaxpasajeros() . "\n" .
            "Importe: $" . $this->getVimporte() . "\n" .
            "Empresa: " . $this->getObjempresa()->getIdempresa() . "\n" .
            "Responsable: " . $this->getObjresponsableV()->getRnumeroempleado() . "\n";
    }
}
?>

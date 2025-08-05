<?php

include_once "BaseDatos.php";
include_once "Empresa.php";
include_once "Responsable.php";
include_once "ViajePasajero.php";
include_once "Pasajero.php";

class Viaje {
    // Atributos privados de la clase
    private $idViaje; // Identificador del viaje
    private $destino; // Destino del viaje
    private $cantMaxPasajeros; // Capacidad máxima
    private $objEmpresa; // Objeto Empresa (delegación)
    private $objResponsable; // Objeto Responsable (delegación)
    private $importe; // Costo del viaje
    private $mensajeOperacion; // Mensaje para registrar errores o estados
    private $colObjPasajeros; // Colección de objetos Pasajero asociados al viaje

    // Constructor
    public function __construct($objEmpresa = null, $objResponsable = null) {
        $this->idViaje = 0;
        $this->destino = "";
        $this->cantMaxPasajeros = 0;
        $this->objEmpresa = $objEmpresa;
        $this->objResponsable = $objResponsable;
        $this->importe = 0;
        $this->mensajeOperacion = "";
        $this->colObjPasajeros = [];
    }

    // Método cargar: asigna los valores de los parámetros a los atributos
    public function cargar($unDestino, $unaCant, $unaEmpresa, $unResponsable, $unImporte) {
        $this->setDestino($unDestino);
        $this->setCantMaxPasajeros($unaCant);
        $this->setEmpresa($unaEmpresa);
        $this->setResponsable($unResponsable);
        $this->setImporte($unImporte);
    }

    // Métodos de acceso (getters)
    public function getIdViaje() { return $this->idViaje; }
    public function getDestino() { return $this->destino; }
    public function getCantMaxPasajeros() { return $this->cantMaxPasajeros; }
    public function getEmpresa() { return $this->objEmpresa; }
    public function getResponsable() { return $this->objResponsable; }
    public function getImporte() { return $this->importe; }
    public function getMensajeOperacion() { return $this->mensajeOperacion; }
    public function getColObjPasajeros() { return $this->colObjPasajeros; }

    // Métodos de modificación (setters)
    public function setIdViaje($idviaje) { $this->idViaje = $idviaje; }
    public function setDestino($destino) { $this->destino = $destino; }
    public function setCantMaxPasajeros($cantmaxpasajeros) { $this->cantMaxPasajeros = $cantmaxpasajeros; }
    public function setEmpresa($objEmpresa) { $this->objEmpresa = $objEmpresa; }
    public function setResponsable($objResponsable) { $this->objResponsable = $objResponsable; }
    public function setImporte($importe) { $this->importe = $importe; }
    public function setMensajeOperacion($mensajeOperacion) { $this->mensajeOperacion = $mensajeOperacion; }
    public function setColObjPasajeros($colObjPasajeros) { $this->colObjPasajeros = $colObjPasajeros; }

    // Método __toString: devuelve una representación del objeto
    public function __toString() {
        $pasajerosTexto = "";
        foreach ($this->getColObjPasajeros() as $pasajero) {
            $pasajerosTexto .= $pasajero->__toString() . "\n";
        }

        return "ID Viaje: " . $this->getIdViaje() . "\n" .
               "Empresa: " . $this->getEmpresa()->getIdEmpresa() . "\n" .
               "Destino: " . $this->getDestino() . "\n" .
               "Cantidad Maxima de Pasajeros: " . $this->getCantMaxPasajeros() . "\n" .
               "Responsable: " . $this->getResponsable()->getNombre() . " " . $this->getResponsable()->getApellido() . "\n" .
               "Importe: " . $this->getImporte() . "\n" .
               "Pasajeros:\n" . $pasajerosTexto;
    }

    // Método buscar: busca un viaje por ID y carga todos los datos incluyendo empresa, responsable y pasajeros
    public function buscar($idviaje) {
        $baseDatos = new BaseDatos();
        $respuesta = false;
        $consulta = "SELECT * FROM viaje WHERE idviaje = " . $idviaje;

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                if ($fila = $baseDatos->Registro()) {
                    $empresa = new Empresa();
                    $empresa->buscar($fila['idempresa']);

                    $responsable = new Responsable();
                    $responsable->buscar($fila['rnumeroempleado']);

                    $this->setIdViaje($fila['idviaje']);
                    $this->setDestino($fila['vdestino']);
                    $this->setCantMaxPasajeros($fila['vcantmaxpasajeros']);
                    $this->setEmpresa($empresa);
                    $this->setResponsable($responsable);
                    $this->setImporte($fila['vimporte']);

                    // Carga la colección de pasajeros asociados
                    $objViajePasajero = new ViajePasajero();
                    $this->setColObjPasajeros($objViajePasajero->listarPasajerosPorIdViaje($idviaje));

                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion("No existe viaje con ese ID.");
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Método insertar: inserta un nuevo viaje en la base de datos
    public function insertar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaInsertar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte)
                VALUES('" . $this->getDestino() . "', '" . $this->getCantMaxPasajeros() . "', " .
                $this->getEmpresa()->getIdEmpresa() . ", " . $this->getResponsable()->getNumeroEmpleado() . ", '" .
                $this->getImporte() . "')";

            $id = $baseDatos->devuelveIDInsercion($consultaInsertar);
            if ($id != null) {
                $this->setIdViaje($id);
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Método modificar: actualiza los datos de un viaje existente
    public function modificar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaModificar = "UPDATE viaje SET vdestino = '" . $this->getDestino() .
                "', vcantmaxpasajeros = " . $this->getCantMaxPasajeros() .
                ", idempresa = " . $this->getEmpresa()->getIdEmpresa() .
                ", rnumeroempleado = " . $this->getResponsable()->getNumeroEmpleado() .
                ", vimporte = " . $this->getImporte() .
                " WHERE idviaje = " . $this->getIdViaje();

            if ($baseDatos->Ejecutar($consultaModificar)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Método eliminar: borra un viaje de la base de datos
    public function eliminar() {
        $baseDatos = new BaseDatos();
        $respuesta = false;

        if ($baseDatos->Iniciar()) {
            $consultaEliminar = "DELETE FROM viaje WHERE idviaje = " . $this->getIdViaje();
            if ($baseDatos->Ejecutar($consultaEliminar)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
    }

    // Método listar: devuelve un arreglo de objetos Viaje cargados desde la base
    public function listar() {
        $baseDatos = new BaseDatos();
        $coleccionViajes = [];
        $consulta = "SELECT * FROM viaje";

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consulta)) {
                while ($fila = $baseDatos->Registro()) {
                    $empresa = new Empresa();
                    $empresa->buscar($fila['idempresa']);

                    $responsable = new Responsable();
                    $responsable->buscar($fila['rnumeroempleado']);

                    $viaje = new Viaje();
                    $viaje->cargar(
                        $fila['vdestino'],
                        $fila['vcantmaxpasajeros'],
                        $empresa,
                        $responsable,
                        $fila['vimporte']
                    );
                    $viaje->setIdViaje($fila['idviaje']);

                    // Carga los pasajeros asociados al viaje
                    $objViajePasajero = new ViajePasajero(); 
                    $viaje->setColObjPasajeros($objViajePasajero->listarPasajerosPorIdViaje($fila['idviaje']));


                    $coleccionViajes[] = $viaje;
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $coleccionViajes;
    }
}

?>

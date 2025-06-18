<?php
include_once 'BaseDatos.php';
include_once 'Empresa.php';
include_once 'ResponsableV.php';
include_once 'Pasajero.php';
include_once 'Viaje.php';

function testCrearEmpresa() {
    $objEmpresa = new Empresa(null, "Viajes Patagonia", "Av. Argentina 1234");
    if ($objEmpresa->insertar()) {
        echo "Empresa creada:\n" . $objEmpresa . "\n\n";
    }
}

function testModificarEmpresa() {
    $colEmpresas = Empresa::buscar("enombre='Viajes Patagonia'");
    if (count($colEmpresas) > 0) {
        $objEmpresa = $colEmpresas[0];
        $objEmpresa->setEdireccion("Av. Olascoaga 555");
        if ($objEmpresa->modificar()) {
            echo "Empresa modificada:\n" . $objEmpresa . "\n\n";
        }
    }
}

function testEliminarEmpresa() {
    $colEmpresas = Empresa::buscar("enombre='Viajes Patagonia'");
    if (count($colEmpresas) > 0) {
        $objEmpresa = $colEmpresas[0];

        // Eliminar viajes asociados
        $viajes = Viaje::buscar("idempresa = " . $objEmpresa->getIdempresa());
        foreach ($viajes as $objViaje) {
            // Eliminar pasajeros de cada viaje
            $colPasajeros = Pasajero::buscar("idviaje = " . $objViaje->getIdviaje());
            foreach ($colPasajeros as $pasajero) {
                $pasajero->eliminar();
            }
            // Eliminar viaje
            $objViaje->eliminar();
        }

        // Ahora eliminar la empresa
        if ($objEmpresa->eliminar()) {
            echo "Empresa eliminada.\n\n";
        }
    }
}


function testCrearViaje() {
    $colEmpresas = Empresa::buscar("enombre='Viajes Patagonia'");
    if (count($colEmpresas) > 0) {
        $objEmpresa = $colEmpresas[0];

        // Crear responsable
        $objResp = new ResponsableV(null, 9988, "Laura", "Sanchez");
        $objResp->insertar();

        // Eliminar pasajeros si ya existen (evita error de clave duplicada)
        $existe1 = Pasajero::buscar("pdocumento = '30111222'");
        if (count($existe1) > 0) {
            $existe1[0]->eliminar();
        }

        $existe2 = Pasajero::buscar("pdocumento = '30444333'");
        if (count($existe2) > 0) {
            $existe2[0]->eliminar();
        }

        // Creamos pasajeros
        $objPasajero1 = new Pasajero("30111222", "Ana", "Paz", "2994567890", null);
        $objPasajero2 = new Pasajero("30444333", "Luis", "Marquez", "2991234567", null);

        // Creamos viaje
        $objViaje = new Viaje(null, "Bariloche", 10, 15000.5, $objResp, $objEmpresa, []);
        if ($objViaje->insertar()) {
            $objPasajero1->setIdviaje($objViaje->getIdviaje());
            $objPasajero2->setIdviaje($objViaje->getIdviaje());
            $objPasajero1->insertar();
            $objPasajero2->insertar();

            $viajes = Viaje::buscar("idviaje=" . $objViaje->getIdviaje());
            echo "Viaje creado:\n" . $viajes[0] . "\n\n";
        }
    }
}


function testModificarViaje() {
    $viajes = Viaje::buscar("vdestino='Bariloche'");
    if (count($viajes) > 0) {
        $objViaje = $viajes[0];
        $objViaje->setVdestino("San Martín de los Andes");
        if ($objViaje->modificar()) {
            echo "Viaje modificado:\n" . $objViaje . "\n\n";
        }
    }
}

function testEliminarViaje() {
    $viajes = Viaje::buscar("vdestino='San Martín de los Andes'");
    if (count($viajes) > 0) {
        $objViaje = $viajes[0];
        if ($objViaje->eliminar()) {
            echo "Viaje eliminado.\n\n";
        }
    }
}

// PRUEBAS
testCrearEmpresa();
testModificarEmpresa();
testCrearViaje();
testModificarViaje();
testEliminarViaje();
testEliminarEmpresa();
?>

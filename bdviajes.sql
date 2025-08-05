-- Primero se crea PERSONA
CREATE TABLE persona (
    idpersona BIGINT AUTO_INCREMENT,
    nombre VARCHAR(150),
    apellido VARCHAR(150),
    PRIMARY KEY (idpersona)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Luego EMPRESA
CREATE TABLE empresa (
    idempresa BIGINT AUTO_INCREMENT,
    enombre VARCHAR(150),
    edireccion VARCHAR(150),
    PRIMARY KEY (idempresa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Luego RESPONSABLE (que usa persona)
CREATE TABLE responsable (
    rnumeroempleado BIGINT AUTO_INCREMENT,
    rnumerolicencia BIGINT,
    idpersona BIGINT NOT NULL,
    PRIMARY KEY (rnumeroempleado),
    FOREIGN KEY (idpersona) REFERENCES persona(idpersona)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Luego VIAJE (que usa empresa y responsable)
CREATE TABLE viaje (
    idviaje BIGINT AUTO_INCREMENT,
    vdestino VARCHAR(150),
    vcantmaxpasajeros INT,
    idempresa BIGINT,
    rnumeroempleado BIGINT,
    vimporte FLOAT,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa(idempresa)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (rnumeroempleado) REFERENCES responsable(rnumeroempleado)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Luego PASAJERO (que usa persona)
CREATE TABLE pasajero (
    idpasajero BIGINT AUTO_INCREMENT PRIMARY KEY,
    pdocumento BIGINT,
    ptelefono VARCHAR(100),
    idpersona BIGINT,
    FOREIGN KEY (idpersona) REFERENCES persona(idpersona)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Por último, la tabla intermedia
CREATE TABLE viaje_pasajero (
    idviaje BIGINT,
    idpasajero BIGINT,
    PRIMARY KEY (idviaje, idpasajero),
    FOREIGN KEY (idviaje) REFERENCES viaje(idviaje)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (idpasajero) REFERENCES pasajero(idpasajero)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

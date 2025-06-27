CREATE TABLE empresa (
    idempresa BIGINT AUTO_INCREMENT,
    enombre VARCHAR(150),
    edireccion VARCHAR(150),
    PRIMARY KEY (idempresa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE responsable (
    rnumeroempleado BIGINT AUTO_INCREMENT,
    rnumerolicencia BIGINT,
    rnombre VARCHAR(150),
    rapellido VARCHAR(150),
    PRIMARY KEY (rnumeroempleado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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

CREATE TABLE pasajero (
    idpasajero BIGINT AUTO_INCREMENT,
    pdocumento VARCHAR(15),
    pnombre VARCHAR(150),
    papellido VARCHAR(150),
    ptelefono VARCHAR(20),
    PRIMARY KEY (idpasajero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE viaje_pasajero (
    idviaje BIGINT,
    idpasajero BIGINT,
    PRIMARY KEY (idviaje, idpasajero),
    FOREIGN KEY (idviaje) REFERENCES viaje(idviaje)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (idpasajero) REFERENCES pasajero(idpasajero)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

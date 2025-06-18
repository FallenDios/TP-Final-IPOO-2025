
DROP DATABASE IF EXISTS bdviajes;
CREATE DATABASE bdviajes;
USE bdviajes;

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
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (rnumeroempleado) REFERENCES responsable(rnumeroempleado)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE pasajero (
    pdocumento VARCHAR(15),
    pnombre VARCHAR(150),
    papellido VARCHAR(150),
    ptelefono INT,
    idviaje BIGINT,
    PRIMARY KEY (pdocumento),
    FOREIGN KEY (idviaje) REFERENCES viaje(idviaje)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

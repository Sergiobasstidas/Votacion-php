CREATE DATABASE votacion;

\c votacion

CREATE TABLE regiones (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE comunas (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    region_id INT REFERENCES regiones(id)
);

CREATE TABLE candidatos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE votos (
    id SERIAL PRIMARY KEY,
    nombre_apellido VARCHAR(100) NOT NULL,
    alias VARCHAR(100) NOT NULL,
    rut VARCHAR(12) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    region_id INT REFERENCES regiones(id),
    comuna_id INT REFERENCES comunas(id),
    candidato_id INT REFERENCES candidatos(id),
    enterado_por VARCHAR(255) NOT NULL
);

-- Datos de ejemplo para regiones, comunas y candidatos
INSERT INTO regiones (nombre) VALUES ('Region 1'), ('Region 2');
INSERT INTO comunas (nombre, region_id) VALUES ('Comuna 1-1', 1), ('Comuna 1-2', 1), ('Comuna 2-1', 2), ('Comuna 2-2', 2);
INSERT INTO candidatos (nombre) VALUES ('Candidato 1'), ('Candidato 2');

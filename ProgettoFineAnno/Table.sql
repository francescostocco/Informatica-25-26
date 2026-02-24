CREATE DATABASE SitoWebRecensioniTuristiche;

USE SitoWebRecensioniTuristiche;

CREATE TABLE Utenti(
    IdUtente INT(4) AUTO_INCREMENT,
    Nome VARCHAR(20),
    Cognome VARCHAR(20),
    DataNascita DATE,
    Email VARCHAR(30) NOT NULL,
    PasswordUtente VARCHAR(15) NOT NULL,
    PRIMARY KEY(IdUtente)
)

CREATE TABLE Amministratori(
    IdUtente INT(4) NOT NULL,
    CodiceAccesso VARCHAR(10) NOT NULL,
    DataNomina DATE,
    PRIMARY KEY(IdUtente),
    Foreign Key (IdUtente) REFERENCES Utenti(IdUtente)
)

CREATE TABLE Proprietari(
    IdUtente INT(4) NOT NULL,
    NomeAttività VARCHAR(30),
    SedeLegale VARCHAR(30),
    PartitaIVA INT(11) NOT NULL,
    Telefono INT(10),
    IdProprietario INT(3) AUTO_INCREMENT,
    PRIMARY KEY(IdProprietario),
    Foreign Key (IdUtente) REFERENCES Utenti(IdUtente)
)

CREATE TABLE TipoLocalità(
    IdTipoLocalità INT(5) AUTO_INCREMENT, 
    TipoLocalità VARCHAR(50),
    PRIMARY KEY(IdTipoLocalità)
)

CREATE TABLE Strutture(
    CodStruttura INT(6) AUTO_INCREMENT,
    NomeStruttura VARCHAR(40),
    Descrizione VARCHAR(300),
    Indirizzo VARCHAR(40),
    Città VARCHAR(30),
    IdTipoLocalità INT(5) NOT NULL,
    IdProprietario INT(3) NOT NULL,
    PRIMARY KEY(CodStruttura),
    Foreign Key (IdTipoLocalità) REFERENCES TipoLocalità(IdTipoLocalità),
    Foreign Key (IdProprietario) REFERENCES Proprietari(IdProprietario)
)

CREATE TABLE FotoStrutture(
    IdFoto INT(3) AUTO_INCREMENT,
    UrlFoto VARCHAR(1000), 
    CodStruttura INT(6) NOT NULL,
    Foreign Key (CodStruttura) REFERENCES Strutture(CodStruttura)
)

CREATE TABLE Alberghi(
    CodStruttura INT(6) NOT NULL,
    Catena VARCHAR(30),
    NumeroCamere INT(3),
    NumeroStelle INT(1),
    PRIMARY KEY(COdStruttura),
    Foreign Key (CodStruttura) REFERENCES Strutture(CodStruttura)
)

CREATE TABLE B&B(
    CodStruttura INT(6) NOT NULL,
    Categoria BOOLEAN,
    NumeroCamere INT(3),
    ColazioneInclusa BOOLEAN,
    PRIMARY KEY(CodStruttura),
    Foreign Key (CodStruttura) REFERENCES Strutture(CodStruttura)
)

CREATE TABLE CaseVacanze(
    CodStruttura INT(6) NOT NULL,
    NumPostiLetto INT(3),
    Superficie INT(5),
    NumBagni INT(3),
    AnimaliAmmessi BOOLEAN,
    PRIMARY KEY(CodStruttura),
    Foreign Key (CodStruttura) REFERENCES Strutture(CodStruttura)
)

CREATE TABLE Recensioni(
    IdRecensione INT(5) AUTO_INCREMENT,
    IdUtente INT(4) NOT NULL,
    NumStelle INT(1) NOT NULL,
    Titolo VARCHAR(20),
    Commento VARCHAR(300),
    CodStruttura INT(6) NOT NULL,
    PRIMARY KEY(IdRecensione),
    Foreign Key (IdUtente) REFERENCES Utenti(IdUtente),
    Foreign Key (CodStruttura) REFERENCES Strutture(CodStruttura)
)

INSERT INTO Utenti (Nome, Cognome, DataNascita, Email, PasswordUtente)
VALUES ('Mario', 'Rossi', '2006-05-10', 'mario.rossi@test.it', 'Password123');

DROP TABLE IF EXISTS EkkateTableNDocumentArea;
DROP TABLE IF EXISTS EkkateTableNDocumentManicipality;
DROP TABLE IF EXISTS EkkateTableNDocumentSettlement;

DROP TABLE IF EXISTS EkkateTableNManicipalityAreaNumber;
DROP TABLE IF EXISTS EkkateTableNManicipalitySettlement;
DROP TABLE IF EXISTS EkkateTableNSettlementTSB;
DROP TABLE IF EXISTS EkkateTableNSettlementKind;


DROP TABLE IF EXISTS EkkateTableNSettlement;
DROP TABLE IF EXISTS EkkateTableNManicipality;
DROP TABLE IF EXISTS EkkateTableNArea;
DROP TABLE IF EXISTS EkkateTableNTSB;
DROP TABLE IF EXISTS EkkateTableNDocument;
DROP TABLE IF EXISTS EkkateTableNKindCountryOrVillage;


CREATE TABLE EkkateTableNSettlement
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
ekatte int NOT NULL,
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNManicipality
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
ekatte int NOT NULL,
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNArea
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
ekatte int NOT NULL,
curtailment varchar(255),
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNTSB
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
curtailment varchar(255),
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNDocument
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
doc_date DATE,
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNKindCountryOrVillage
(
U_ID int NOT NULL AUTO_INCREMENT,
kind_id BIT,
name varchar(30),
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;
INSERT INTO EkkateTableNKindCountryOrVillage (kind_id,name) VALUES(false,"град");
INSERT INTO EkkateTableNKindCountryOrVillage (kind_id,name) VALUES(true,"село");

/*connecting tables*/
CREATE TABLE EkkateTableNManicipalityAreaNumber
(
U_ID int NOT NULL AUTO_INCREMENT,
manicipality_id int NOT NULL,
area_id int NOT NULL,
area_number int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (manicipality_id) REFERENCES EkkateTableNManicipality(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNManicipalitySettlement
(
U_ID int NOT NULL AUTO_INCREMENT,
manicipality_id int NOT NULL,
settlement_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (manicipality_id) REFERENCES EkkateTableNManicipality(U_ID),
FOREIGN KEY (settlement_id) REFERENCES EkkateTableNSettlement(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNSettlementTSB
(
U_ID int NOT NULL AUTO_INCREMENT,
tsb_id int NOT NULL,
settlement_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (tsb_id) REFERENCES EkkateTableNTSB(U_ID),
FOREIGN KEY (settlement_id) REFERENCES EkkateTableNSettlement(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNSettlementKind
(
U_ID int NOT NULL AUTO_INCREMENT,
kind_id int NOT NULL,
settlement_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (kind_id) REFERENCES EkkateTableNKindCountryOrVillage(U_ID),
FOREIGN KEY (settlement_id) REFERENCES EkkateTableNSettlement(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNDocumentArea
(
U_ID int NOT NULL AUTO_INCREMENT,
document_id int NOT NULL,
area_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (document_id) REFERENCES EkkateTableNDocument(U_ID),
FOREIGN KEY (area_id) REFERENCES EkkateTableNArea(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNDocumentManicipality
(
U_ID int NOT NULL AUTO_INCREMENT,
document_id int NOT NULL,
manicipality_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (document_id) REFERENCES EkkateTableNDocument(U_ID),
FOREIGN KEY (manicipality_id) REFERENCES EkkateTableNManicipality(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkkateTableNDocumentSettlement
(
U_ID int NOT NULL AUTO_INCREMENT,
document_id int NOT NULL,
settlement_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (document_id) REFERENCES EkkateTableNDocument(U_ID),
FOREIGN KEY (settlement_id) REFERENCES EkkateTableNSettlement(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;



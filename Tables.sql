
DROP TABLE IF EXISTS EkatteTableNDocumentArea;
DROP TABLE IF EXISTS EkatteTableNDocumentManicipality;
DROP TABLE IF EXISTS EkatteTableNDocumentSettlement;

DROP TABLE IF EXISTS EkatteTableNManicipalityAreaNumber;
DROP TABLE IF EXISTS EkatteTableNManicipalitySettlement;
DROP TABLE IF EXISTS EkatteTableNSettlementTSB;
DROP TABLE IF EXISTS EkatteTableNSettlementKind;


DROP TABLE IF EXISTS EkatteTableNSettlement;
DROP TABLE IF EXISTS EkatteTableNManicipality;
DROP TABLE IF EXISTS EkatteTableNArea;
DROP TABLE IF EXISTS EkatteTableNTSB;
DROP TABLE IF EXISTS EkatteTableNDocument;
DROP TABLE IF EXISTS EkatteTableNKindCountryOrVillage;


CREATE TABLE EkatteTableNSettlement
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
ekatte int NOT NULL,
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNManicipality
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
ekatte int NOT NULL,
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNArea
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
ekatte int NOT NULL,
curtailment varchar(255),
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNTSB
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
curtailment varchar(255),
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNDocument
(
U_ID int NOT NULL,
name varchar(255),
doc_date DATE,
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNKindCountryOrVillage
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(30),
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;
INSERT INTO EkatteTableNKindCountryOrVillage (name) VALUES("град");
INSERT INTO EkatteTableNKindCountryOrVillage (name) VALUES("село");

/*connecting tables*/
CREATE TABLE EkatteTableNManicipalityAreaNumber
(
U_ID int NOT NULL AUTO_INCREMENT,
manicipality_id int NOT NULL,
area_id int NOT NULL,
manicipality_number int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (manicipality_id) REFERENCES EkatteTableNManicipality(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNManicipalitySettlement
(
U_ID int NOT NULL AUTO_INCREMENT,
manicipality_id int NOT NULL,
settlement_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (manicipality_id) REFERENCES EkatteTableNManicipality(U_ID),
FOREIGN KEY (settlement_id) REFERENCES EkatteTableNSettlement(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNSettlementTSB
(
U_ID int NOT NULL AUTO_INCREMENT,
tsb_id int NOT NULL,
settlement_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (tsb_id) REFERENCES EkatteTableNTSB(U_ID),
FOREIGN KEY (settlement_id) REFERENCES EkatteTableNSettlement(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNSettlementKind
(
U_ID int NOT NULL AUTO_INCREMENT,
kind_id int NOT NULL,
settlement_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (kind_id) REFERENCES EkatteTableNKindCountryOrVillage(U_ID),
FOREIGN KEY (settlement_id) REFERENCES EkatteTableNSettlement(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNDocumentArea
(
U_ID int NOT NULL AUTO_INCREMENT,
document_id int NOT NULL,
area_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (document_id) REFERENCES EkatteTableNDocument(U_ID),
FOREIGN KEY (area_id) REFERENCES EkatteTableNArea(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNDocumentManicipality
(
U_ID int NOT NULL AUTO_INCREMENT,
document_id int NOT NULL,
manicipality_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (document_id) REFERENCES EkatteTableNDocument(U_ID),
FOREIGN KEY (manicipality_id) REFERENCES EkatteTableNManicipality(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNDocumentSettlement
(
U_ID int NOT NULL AUTO_INCREMENT,
document_id int NOT NULL,
settlement_id int NOT NULL,
PRIMARY KEY (U_ID),
FOREIGN KEY (document_id) REFERENCES EkatteTableNDocument(U_ID),
FOREIGN KEY (settlement_id) REFERENCES EkatteTableNSettlement(U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;




DROP TABLE IF EXISTS EkatteTableNManicipalityAreaNumber;
DROP TABLE IF EXISTS EkatteTableNManicipalitySettlement;


DROP TABLE IF EXISTS EkatteTableNSettlement;
DROP TABLE IF EXISTS EkatteTableNManicipality;
DROP TABLE IF EXISTS EkatteTableNArea;


CREATE TABLE EkatteTableNSettlement
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
ekatte int NOT NULL UNIQUE,
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNManicipality
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
ekatte int NOT NULL UNIQUE,
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE EkatteTableNArea
(
U_ID int NOT NULL AUTO_INCREMENT,
name varchar(255),
ekatte int NOT NULL UNIQUE,
curtailment varchar(255),
PRIMARY KEY (U_ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;


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


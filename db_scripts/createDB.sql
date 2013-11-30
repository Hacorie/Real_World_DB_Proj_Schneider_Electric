DROP TABLE IF EXISTS Tag;
CREATE TABLE Tag
(
	Num INT AUTO_INCREMENT,
	Revision INT AUTO_INCREMENT,
	LTime INT,
	CDate DATE,
	Description VARCHAR(250),
	TNotes VARCHAR(100),
	PNotes VARCHAR(100),
	PExpire DATE,
	MCost	DECIMAL(7,3),
	LCost 	DECIMAL(7,3),
	ECost	DECIMAL(7,3),
	ICost	DECIMAL(7,3),
	APath	VARCHAR(100),	
	SName 	VARCHAR(30),
	CName	VARCHAR(30),
	UName	VARCHAR(30),

	FOREIGN KEY(Sname) REFERENCES Subcategory(SName),
	FOREIGN KEY(CName) REFERENCES Complexity(CName),
	FOREIGN KEY(UName)  REFERENCES User(UName),

	Primary Key(Num, Revision)
);

DROP TABLE IF EXISTS Subcategory;
CREATE TABLE Subcategory
(
	SName VARCHAR(30),
	PRIMARY KEY(SName)
);

DROP TABLE IF EXISTS Complexity;
CREATE TABLE Complexity
(
	CName VARCHAR(30),
	PRIMARY KEY(CName)
);

DROP TABLE IF EXISTS User;
CREATE TABLE User
(
	UName VARCHAR(30),
	Password VARCHAR(40),
	PRIMARY KEY (UName)
);

DROP TABLE IF EXISTS Groups;
CREATE TABLE Groups
(
	GName VARCHAR(30),
	PRIMARY KEY(GName)
);

DROP TABLE IF EXISTS Permissions;
CREATE TABLE Permissions
(
    GName VARCHAR(30),
    iTags BOOLEAN,
    rTags BOOLEAN,
    sTags BOOLEAN,
    vTags BOOLEAN,
    vPrices BOOLEAN,

    FOREIGN KEY(GName) REFERENCES Groups(Gname),

    PRIMARY KEY (GName)
);

DROP TABLE IF EXISTS Applied_FO_Table;
CREATE TABLE Applied_FO_Table
(
	FONumber INT AUTO_INCREMENT,
	Notes VARCHAR(100),
	Num INT,
	RevNo INT,
	Typeof VARCHAR(30),

	FOREIGN KEY(Num, RevNo) REFERENCES Tag(Num, Revision),

	PRIMARY KEY(FONumber, Num, RevNo)
);

DROP TABLE IF EXISTS Product_Type;
CREATE TABLE Product_Type
(
	PName VARCHAR(30),
	Multiplier Decimal(7,3),

	PRIMARY KEY(PName)
);

DROP TABLE IF EXISTS Country;
CREATE TABLE Country
(
	CName VARCHAR(30),
	Multiplier Decimal(7,3),

	PRIMARY KEY(CName)
);

DROP TABLE IF EXISTS Member_Of;
CREATE TABLE Member_Of
(
	Username VARCHAR(30),
	GName VARCHAR(30),

	FOREIGN KEY(Username) REFERENCES User(UName),
	FOREIGN KEY(GName) REFERENCES Groups(GName),

	PRIMARY KEY(Username, GName)
);

DROP TABLE IF EXISTS Type;
CREATE TABLE Type
(
	PName VARCHAR(30),
	CName VARCHAR(30),
	Num INT,
	Revision INT,

	FOREIGN KEY(Num, Revision) REFERENCES Tag(Num, Revision),
	FOREIGN KEY(PName) REFERENCES Product_Type(PName),
	FOREIGN KEY(CName) REFERENCES Country(CName),

	PRIMARY KEY (PName, CName, Num, Revision)
);

DROP TABLE IF EXISTS Log;
CREATE TABLE Log
(
	UID	INT AUTO_INCREMENT,
	UName VARCHAR(30),
	LTime	TIMESTAMP,
	IP	VARCHAR(45),
	MName VARCHAR(50),

	FOREIGN KEY(UName) REFERENCES User(UName),

	PRIMARY KEY(UID)
);

DROP TABLE IF EXISTS Per_Hour;
CREATE TABLE Per_Hour
(
	UID INT,
	Labor Decimal(7,3),
	Engineering Decimal(7,3),

	PRIMARY KEY(UID)
);

DROP TABLE IF EXISTS Log_In;
CREATE TABLE Log_In
(
	UName VARCHAR(30),
	Initial_Date DATE,
	Expire_Date DATE,
	IP VARCHAR(45),
	MName VARCHAR(100),
	Token VARCHAR(500),

	FOREIGN KEY(UName) REFERENCES User(UName),
	PRIMARY KEY(UName, Token)
);

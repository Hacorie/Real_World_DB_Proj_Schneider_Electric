CREATE TABLE Tag
(
	Num INT,
	Revision INT,
	LTime VARCHAR(5),
	CDate DATE,
	Description VARCHAR(250),
	TNotes VARCHAR(100),
	PNotes VARCHAR(100),
	PExpire DATE,
	MCost	VARCHAR(10),
	LCost 	VARCHAR(10),
	ECost	VARCHAR(10),
	ICost	VARCHAR(10),
	APath	VARCHAR(100),	
	SName 	VARCHAR(30),
	CName	VARCHAR(30),
	UName	VARCHAR(30),

	FOREIGN KEY(Sname) REFERENCES Subcategory(SName),
	FOREIGN KEY(CName) REFERENCES Complexity(CName),
	FOREIGN KEY(UName)  REFERENCES User(UName),

	Primary Key(Num, Revision, Sname, CName, UName)
);

CREATE TABLE Subcategory
(
	SName VARCHAR(30),
	PRIMARY KEY(SName)
);

CREATE TABLE Complexity
(
	CName VARCHAR(30),
	PRIMARY KEY(CName)
);

CREATE TABLE User
(
	UName VARCHAR(30),
	Password VARCHAR(30),
	PRIMARY KEY (UName)
);

CREATE TABLE Groups
(
	GName VARCHAR(30),
	PRIMARY KEY(GName)
);

CREATE TABLE Applied_FO_Table
(
	FONumber INT,
	Notes VARCHAR(100),
	Num INT,
	RevNo INT,
	Type VARCHAR(30),

	FOREIGN KEY(Num) REFERENCES Tag(Num),
	FOREIGN KEY(RevNo) REFERENCES Tag(Revision),

	PRIMARY KEY(FONumber, Num, RevNo)
);

CREATE TABLE Product_Type
(
	PName VARCHAR(30),
	Multiplier Decimal(7,3),

	PRIMARY KEY(PName)
);

CREATE TABLE Country
(
	CName VARCHAR(30),
	Multiplier Decimal(7,3),

	PRIMARY KEY(CName)
);

CREATE TABLE Member_Of
(
	Username VARCHAR(30),
	GName VARCHAR(30),

	FOREIGN KEY(Username) REFERENCES User(UName),
	FOREIGN KEY(GName) REFERENCES Groups(GName),

	PRIMARY KEY(Username, GName)
);

CREATE TABLE Type
(
	PName VARCHAR(30),
	CName VARCHAR(30),

	FOREIGN KEY(PName) REFERENCES Product_Type(PName),
	FOREIGN KEY(CName) REFERENCES Country(CName),

	PRIMARY KEY (PName, CName)
);

CREATE TABLE Log
(
	UID	INT,
	UName VARCHAR(30),
	LDate	DATE,
	LTime	TIMESTAMP,
	IP	VARCHAR(9),
	
	PRIMARY KEY(UID)
);
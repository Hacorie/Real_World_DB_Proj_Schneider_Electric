INSERT INTO Type
VALUES
("Name of Product 3", "USA", 1, 1),
("Name of Product 2", "Canada", 2, 1),
("Name of Product 1", "Mexico",3, 10);

INSERT INTO Country
VALUES
("USA", 1.5),
("Canada", 2.0),
("Mexico", 0.3);

INSERT INTO Product_Type
VALUES
("Name of Product 1", 1.2),
("Name of Product 2", 2.3),
("Name of Product 3", 5.0);

INSERT INTO Applied_FO_Table(Notes, Num, RevNo, Typeof)
VALUES
("Notes about this FO", 1, 1, "Type1"),
("Notes about second FO", 2, 1, "Type2");

INSERT INTO Member_Of
VALUES
("Nobody", "Group1"),
("Nobody2", "Group2"),
("Pew", "Group3");

INSERT INTO Groups
VALUES
("Group1"), ("Group2"), ("Group3");

INSERT INTO Log
VALUES
();

INSERT INTO User(UName)
VALUES
("Nobody"), ("Nobody2"), ("Pew");

INSERT INTO Complexity
VALUES
("Easy"), ("Medium"), ("Hard");

INSERT INTO Subcategory
VALUES
("Movies"), ("Books"), ("DVDS");

INSERT INTO Tag(Revision, LTime, CDate, Description, TNotes, PNotes, PExpire, MCost, LCost, 
				ECost, ICost, APath, SName, CName, UName)
VALUES
(1, "12:20", CURDATE(), "This is a description", "This is Tag Notes", "This is Price Notes", 
	CURDATE(), 12.50, 13.50, 14.50, NULL, "c:/users/blah/test.txt", "Movies", "Hard", "Nobody" ),
(1, "1:30", CURDATE(), "This is another description", "This is another Tag Notes", "This is another Price Notes", 
	CURDATE(), 112.50, 213.50, 134.50, NULL, "c:/users/blah/nomnom.txt", "Books", "Easy", "Pew" ),
(10, "18:11", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
	CURDATE(), 50, 22.50, 455.11, NULL, "c:/users/awesomefile.blah", "DVDS", "Medium", "Nobody2" );
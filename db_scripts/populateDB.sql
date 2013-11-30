INSERT INTO Type
VALUES
("Name of Product 3", "USA", 1, 1),
("Name of Product 2", "Canada", 2, 1),
("Name of Product 1", "Mexico",3, 10);

INSERT INTO Country
VALUES
("USA", 1.0),
("Canada", 0.75),
("Mexico", 1.0);

INSERT INTO Product_Type
VALUES
("Name of Product 1", 1.2),
("Name of Product 2", 2.3),
("Name of Product 3", 5.0),
("HVL", 12),
("HVL/CC", 12),
("Metal Clad", 12),
("MVMCC", 12),
("", 0);

INSERT INTO Applied_FO_Table(Notes, Num, RevNo, Typeof)
VALUES
("Notes about this FO", 1, 1, "Type1"),
("Notes about second FO", 2, 1, "Type2");

INSERT INTO Member_Of
VALUES
("Nobody", "Group1"),
("Nobody2", "Group2"),
("Pew", "Group3"),
("Nathan", "Administrator"),
("Tony", "Tag Members"),
("Oliver", "OE"),
("Uma", "User"),
("Adam", "Administrator");

INSERT INTO Groups
VALUES
("Group1"), ("Group2"), ("Group3"), ("Tag Members"),
("OE"), ("User"), ("Administrator");

INSERT INTO Log
VALUES
();

INSERT INTO User(UName)
VALUES
("Nobody"), ("Nobody2"), ("Pew"), ("Nathan"), 
("Tony"), ("Oliver"), ("Uma"), ("Adam");

INSERT INTO Complexity
VALUES
("A"), ("B"), ("C"), ("D"), ("E"), ("F"), ("G"), ("*");

INSERT INTO Subcategory
VALUES
("Movies"), ("Books"), ("DVDS");

INSERT INTO Tag(Revision, LTime, CDate, Description, TNotes, PNotes, PExpire, MCost, LCost, 
				ECost, ICost, APath, SName, CName, UName)
VALUES
(1, "12:20", CURDATE(), "This is a description", "This is Tag Notes", "This is Price Notes", 
	CURDATE(), 12.50, 13.50, 14.50, NULL, "c:/users/blah/test.txt", "Movies", "A", "Nobody" ),
(1, "1:30", CURDATE(), "This is another description", "This is another Tag Notes", "This is another Price Notes", 
	CURDATE(), 112.50, 213.50, 134.50, NULL, "c:/users/blah/nomnom.txt", "Books", "B", "Pew" ),
(10, "18:11", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
	CURDATE(), 50, 22.50, 455.11, NULL, "c:/users/awesomefile.blah", "DVDS", "G", "Nobody2" );

INSERT INTO Tag
VALUES
(6001, 1, "12:20", CURDATE(), "This is a description", "This is Tag Notes", "This is Price Notes", 
	CURDATE(), 12.50, 13.50, 14.50, NULL, "c:/users/blah/test.txt", "Movies", "A", "Nobody" ),
(6002, 2, "1:30", CURDATE(), "This is another description", "This is another Tag Notes", "This is another Price Notes", 
	CURDATE(), 112.50, 213.50, 134.50, NULL, "c:/users/blah/nomnom.txt", "Books", "B", "Pew" ),
(6003, 3, "18:11", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
	CURDATE(), 50, 22.50, 455.11, NULL, "c:/users/awesomefile.blah", "DVDS", "G", "Nobody2" ),
(6004, 4, "18:11", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
	CURDATE(), 50, 22.50, 455.11, NULL, "c:/users/awesomefile.blah", "DVDS", "G", "Nobody2" );
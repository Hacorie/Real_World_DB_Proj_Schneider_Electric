INSERT INTO User(UName, Password) VALUES
	("Nathan", "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8"), 
	("Tony", "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8"),
	("Oliver", "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8"),
	("Uma", "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8"),
	("Adam", "5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8");

INSERT INTO Groups VALUES
	("Tag Members"),
	("OE"),
	("User"),
	("Administrator");

INSERT INTO Member_Of VALUES
	("Nathan", "Administrator"),
	("Tony", "Tag Members"),
	("Oliver", "OE"),
	("Uma", "User"),
	("Adam", "Administrator");

INSERT INTO Country VALUES
	("USA", 1.0),
	("Canada", 0.75),
	("Mexico", 1.0);

INSERT INTO Product_Type VALUES
	("HVL", 12),
	("HVL/CC", 12),
	("Metal Clad", 12),
	("MVMCC", 12);

INSERT INTO Applied_FO_Table(Notes, Num, RevNo, Typeof) VALUES
	("Notes about this FO", 1, 1, "Type1"),
	("Notes about second FO", 2, 1, "Type2");

INSERT INTO Complexity VALUES
	("A"), ("B"), ("C"), ("D"), ("E"), ("F"), ("G");

INSERT INTO Subcategory VALUES
	("AC Panel"), ("Arc Resistant"), ("Auto Xfer"), ("Barrier"), ("Battery System"), ("Bus"),
	("Bus Duct"), ("Cable Lugs"), ("Cables"), ("Ckt Bkr"), ("Connect to other equipment"), ("Contacts");

INSERT INTO Tag VALUES
	(6001, 1, "1", CURDATE(), "This is a description", "This is Tag Notes", "This is Price Notes", 
		CURDATE(), 12.50, 13.50, 14.50, NULL, "AC Panel", "A", "Tony", 1, 0, 1, 0),
	(6002, 1, "2", CURDATE(), "This is another description", "This is another Tag Notes", "This is another Price Notes", 
		CURDATE(), 112.50, 213.50, 134.50, NULL, "Arc Resistant", "B", "Tony", 0, 0, 0, 0),
	(6002, 2, "2", CURDATE(), "This is another description", "This is another Tag Notes", "This is another Price Notes", 
		CURDATE(), 112.50, 213.50, 134.50, NULL, "Arc Resistant", "B", "Tony", 0, 0, 0, 0),
	(6003, 1, "3", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
		CURDATE(), 50, 22.50, 455.11, NULL, "Cables", "G", "Adam", 0, 0, 0, 0),
	(6003, 2, "3", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
		CURDATE(), 50, 22.50, 455.11, NULL, "Cables", "G", "Adam", 0, 0, 0, 0),
	(6003, 3, "3", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
		CURDATE(), 50, 22.50, 455.11, NULL, "Cables", "G", "Adam", 0, 0, 0, 0),
	(6004, 1, "4", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
		CURDATE(), 50, 22.50, 455.11, NULL, "Battery System", "E", "Tony", 0, 0, 0, 0),
	(6004, 2, "4", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
		CURDATE(), 50, 22.50, 455.11, NULL, "Battery System", "E", "Tony", 0, 0, 0, 0),
	(6004, 3, "4", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
		CURDATE(), 50, 22.50, 455.11, NULL, "Battery System", "E", "Tony", 0, 0, 0, 0),
	(6004, 4, "4", CURDATE(), "This is an awesome description", "This is an awesome Tag Notes", "This is an awesome Price Notes", 
		CURDATE(), 50, 22.50, 455.11, NULL, "Battery System", "E", "Tony", 0, 0, 0, 0);

INSERT INTO Attachment VALUES
	(6001, 'test.txt', NULL),
	(6003, 'test.pdf', NULL);
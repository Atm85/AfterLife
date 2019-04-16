CREATE TABLE IF NOT EXISTS afterlife (
	id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name TEXT NOT NULL,
    kills int(5) NOT NULL,
    deaths int(5) NOT NULL,
    ratio FLOAT NOT NULL,
    totalXP int(5) NOT NULL,
    xp int(5) NOT NULL,
    level int(5) NOT NULL,
    streak int(5) NOT NULL
);
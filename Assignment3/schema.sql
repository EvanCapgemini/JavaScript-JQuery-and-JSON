-- Create database and tables for the Resume Registry (Mod3)
CREATE DATABASE IF NOT EXISTS education_study CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE education_study;

DROP TABLE IF EXISTS Education;
DROP TABLE IF EXISTS Institution;
DROP TABLE IF EXISTS Position;
DROP TABLE IF EXISTS Profile;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  user_id INTEGER NOT NULL AUTO_INCREMENT,
  name VARCHAR(128),
  email VARCHAR(128),
  password VARCHAR(128),
  PRIMARY KEY(user_id),
  INDEX(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert a seed user: evan@umich.edu / php123
INSERT INTO users(name,email,password) VALUES
('Evan Elijah Mendonsa','evan@umich.edu','1a52e17fa899cf40fb04cfc42e6352f1');

CREATE TABLE Profile (
  profile_id INTEGER NOT NULL AUTO_INCREMENT,
  user_id INTEGER,
  first_name VARCHAR(128),
  last_name VARCHAR(128),
  email VARCHAR(128),
  headline VARCHAR(255),
  summary TEXT,
  PRIMARY KEY(profile_id),
  INDEX(user_id),
  CONSTRAINT profile_ibfk_1
    FOREIGN KEY (user_id) REFERENCES users (user_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Position (
  position_id INTEGER NOT NULL AUTO_INCREMENT,
  profile_id INTEGER,
  rank INTEGER,
  year INTEGER,
  description TEXT,
  PRIMARY KEY(position_id),
  INDEX(profile_id),
  CONSTRAINT position_ibfk_1
    FOREIGN KEY (profile_id) REFERENCES Profile (profile_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Institution (
  institution_id INTEGER NOT NULL AUTO_INCREMENT,
  name VARCHAR(255),
  PRIMARY KEY(institution_id),
  UNIQUE(name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Education (
  profile_id INTEGER,
  institution_id INTEGER,
  rank INTEGER,
  year INTEGER,
  PRIMARY KEY(profile_id, institution_id, rank),
  INDEX(profile_id),
  INDEX(institution_id),
  CONSTRAINT education_ibfk_1
    FOREIGN KEY (profile_id)
    REFERENCES Profile (profile_id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT education_ibfk_2
    FOREIGN KEY (institution_id)
    REFERENCES Institution (institution_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Preload Institutions from assignment spec
INSERT INTO Institution (name) VALUES ('University of Michigan');
INSERT INTO Institution (name) VALUES ('University of Virginia');
INSERT INTO Institution (name) VALUES ('University of Oxford');
INSERT INTO Institution (name) VALUES ('University of Cambridge');
INSERT INTO Institution (name) VALUES ('Stanford University');
INSERT INTO Institution (name) VALUES ('Duke University');
INSERT INTO Institution (name) VALUES ('Michigan State University');
INSERT INTO Institution (name) VALUES ('Mississippi State University');
INSERT INTO Institution (name) VALUES ('Montana State University');

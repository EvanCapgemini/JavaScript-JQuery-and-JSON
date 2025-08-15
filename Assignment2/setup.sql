-- XAMPP/phpMyAdmin setup for the assignment (MySQL)
-- Database: misc
CREATE DATABASE IF NOT EXISTS resume_upg DEFAULT CHARACTER SET utf8;
USE resume_upg;

-- Users table (pre-populated)
CREATE TABLE IF NOT EXISTS users (
   user_id INTEGER NOT NULL AUTO_INCREMENT,
   name VARCHAR(128),
   email VARCHAR(128),
   password VARCHAR(128),
   PRIMARY KEY(user_id),
   INDEX(email),
   INDEX(password)
) ENGINE=InnoDB CHARSET=utf8;

INSERT INTO users (name,email,password)
    VALUES ('Evan Elijah Mendonsa','evan@example.com','1a52e17fa899cf40fb04cfc42e6352f1')
    ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO users (name,email,password)
    VALUES ('UMSI','umsi@umich.edu','1a52e17fa899cf40fb04cfc42e6352f1')
    ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Profile table
CREATE TABLE IF NOT EXISTS Profile (
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
        FOREIGN KEY (user_id)
        REFERENCES users (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Position table
CREATE TABLE IF NOT EXISTS Position (
  position_id INTEGER NOT NULL AUTO_INCREMENT,
  profile_id INTEGER,
  rank INTEGER,
  year INTEGER,
  description TEXT,
  PRIMARY KEY(position_id),
  CONSTRAINT position_ibfk_1
        FOREIGN KEY (profile_id)
        REFERENCES Profile (profile_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

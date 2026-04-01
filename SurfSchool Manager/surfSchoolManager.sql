CREATE DATABASE surfSchoolManager ;

USE surfSchoolManager;

CREATE TABLE users (
	id          INT           AUTO_INCREMENT PRIMARY KEY ,
	email       VARCHAR(150)  UNIQUE ,
    password    VARCHAR(255)  NOT NULL,
    role        ENUM('student','admin') DEFAULT 'student'
);

CREATE TABLE students (
    id          INT     	  NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id     INT    		  NOT NULL UNIQUE,   
    name        VARCHAR(100)  NOT NULL,
    country     VARCHAR(100)  NOT NULL,
    level       ENUM('Beginner', 'Intermediate', 'Advanced') DEFAULT 'Beginner',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE lessons (
    id          INT           NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    title       VARCHAR(255)  NOT NULL ,
    coach       VARCHAR(50)   NOT NULL ,
    datetime    DATETIME      NOT NULL ,
    price       DECIMAL(5,2)  NOT NULL 
);

CREATE TABLE lesson_student (
    id          INT           NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    student_id  INT           NOT NULL UNIQUE ,
    lesson_id   INT           NOT NULL UNIQUE ,
    pay_status  ENUM('payed','notPayed') DEFAULT 'notPayed',
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (lesson_id) REFERENCES lessons(id)
);
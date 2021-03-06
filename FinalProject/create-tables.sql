CREATE DATABASE evaluationDB;

USE evaluationDB;

CREATE TABLE student
(
	studentID VARCHAR(100) NOT NULL,
	sname VARCHAR(100) NOT NULL,
	CONSTRAINT studPK PRIMARY KEY (studentID)
);

CREATE TABLE ranID
(
	studentID VARCHAR(100) NOT NULL,
	randomID VARCHAR(100) NOT NULL,
	CONSTRAINT riPK2 PRIMARY KEY (randomID),
	CONSTRAINT riFK2 FOREIGN KEY (studentID) REFERENCES student(studentID)
);

CREATE TABLE instructor
(
	instructorID VARCHAR(100) NOT NULL,
	ifname	VARCHAR(15) NOT NULL,
	ilname VARCHAR(15) NOT NULL,
	CONSTRAINT instPK PRIMARY KEY (instructorID)
);

CREATE TABLE department
(
	departmentID VARCHAR(100) NOT NULL,
	department VARCHAR(100) NOT NULL,
	chairID VARCHAR(100) NOT NULL,
	CONSTRAINT deptPK PRIMARY KEY (departmentID)
);

CREATE TABLE course
(
	courseID VARCHAR(100) NOT NULL,
	departmentID VARCHAR(15) NOT NULL,
	CONSTRAINT coursePK PRIMARY KEY (courseID),
	CONSTRAINT courseFK1 FOREIGN KEY (departmentID) REFERENCES department(departmentID)
);

CREATE TABLE class
(
	classID INT NOT NULL,
	sectionNumber INT NOT NULL,
	semester VARCHAR(15) NOT NULL,
	year INT NOT NULL,
	courseID VARCHAR(100),
	instructorID VARCHAR(100) NOT NULL,
	CONSTRAINT classPK PRIMARY KEY (classID),
	CONSTRAINT classcourseFK FOREIGN KEY (courseID) REFERENCES course(courseID),
	CONSTRAINT classteachFK	FOREIGN KEY (instructorID) REFERENCES instructor(instructorID)
);

CREATE TABLE questionType
(
	qTypeID INT NOT NULL,
	qType VARCHAR(15) NOT NULL,
	CONSTRAINT qtPK PRIMARY KEY (qTypeID)
);

CREATE TABLE question
(
	qID INT NOT NULL,
	qText VARCHAR(100) NOT NULL,
	qTypeID INT NOT NULL,
	CONSTRAINT qPK PRIMARY KEY (qID),
	CONSTRAINT qFK FOREIGN KEY (qTypeID) REFERENCES questionType(qTypeID)
);

CREATE TABLE student_takes_class 
(
	classNumber INT NOT NULL,
	studentID VARCHAR(100),
	CONSTRAINT stcFK1 FOREIGN KEY (classNumber) REFERENCES class(classID),
	CONSTRAINT stcFK2 FOREIGN KEY (studentID) REFERENCES student(studentID)
);


CREATE TABLE evaluation
(
	qID INT NOT NULL,
	classID INT NOT NULL,
	randomID VARCHAR(100) NOT NULL,
	atext VARCHAR(10000),
	CONSTRAINT eFK1 FOREIGN KEY (qID) REFERENCES question(qID),
	CONSTRAINT eFK2 FOREIGN KEY (randomID) REFERENCES ranID(randomID),
	CONSTRAINT eFK3 FOREIGN KEY (classID) REFERENCES class(classID)
);

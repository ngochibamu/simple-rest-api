CREATE DATABASE IF NOT EXISTS staff_records;


CREATE TABLE departments (
	department_id INT NOT NULL AUTO_INCREMENT,
	department_name VARCHAR(50) DEFAULT '',
	contact_name VARCHAR(50) NOT NULL,
	contact_emailaddress VARCHAR(50) NOT NULL,
	PRIMARY KEY(department_id)
) ENGINE=INNODB;

CREATE TABLE contacts (
	contact_id INT NOT NULL AUTO_INCREMENT,
	first_name varchar(50) NOT NULL DEFAULT '',
	last_name varchar(50) NOT NULL DEFAULT '',
	email_address varchar(50) NOT NULL,
	gender char(1) NOT NULL,
	department_id INT NOT NULL,
	PRIMARY KEY(contact_id),
	FOREIGN KEY (department_id)
) ENGINE=INNODB;




-- ----------------
-- TABLAS
-- ----------------

-- show engines;
DROP DATABASE ronintop_asesoriaspar;

CREATE DATABASE ronintop_asesoriaspar CHARACTER SET utf8 COLLATE utf8_general_ci;
use ronintop_asesoriaspar;



CREATE TABLE role(
	name 	    VARCHAR(20) NOT NULL PRIMARY KEY,
	description VARCHAR(100) NULL
);


CREATE TABLE user(
	user_id	     	BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	email 		 	VARCHAR(100) NOT NULL UNIQUE,
	password 	 	VARCHAR(255) NOT NULL,
	register_date 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	status			TINYINT NOT NULL DEFAULT 1, -- '0 = Inactivo, 1 = sin confirmar, 2 = Activo,
	
	-- Foraneas
	fk_role VARCHAR(20) NOT NULL,
	FOREIGN KEY (fk_role) REFERENCES role(name) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE career (
	career_id 			BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name 			VARCHAR(100) NOT NULL UNIQUE,
	short_name		VARCHAR(10) UNIQUE,
	status			TINYINT NOT NULL DEFAULT 2, -- 0 OFF, 1 ON
	date_register   TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE period(
	period_id	 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date_start 		DATE NOT NULL,
	date_end	 		DATE NOT NULL,
	date_register   TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	status    TINYINT NOT NULL DEFAULT 2  -- 0 OFF, 1 ON
);


CREATE TABLE plan(
	plan_id			BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	year 				VARCHAR(4) NOT NULL,
	status			TINYINT NOT NULL DEFAULT 2, -- 0 OFF, 1 ON
	register_date 	TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE subject(
	subject_id 		BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	semester 		INT NOT NULL,
	name 			VARCHAR(100) NOT NULL,
	short_name      VARCHAR(10) NOT NULL,
	description     VARCHAR(250) NULL,
	date_register TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	status			TINYINT NOT NULL DEFAULT 2, -- '0 = Inactivo, 1 = Activo,

	-- Foranea	
	fk_career BIGINT NOT NULL,
	FOREIGN KEY (fk_career) REFERENCES career(career_id) ON UPDATE CASCADE ON DELETE CASCADE,
	fk_plan BIGINT NOT NULL,
	FOREIGN KEY (fk_plan) REFERENCES plan(plan_id) ON UPDATE CASCADE ON DELETE CASCADE
);



CREATE TABLE subject_similary(
	pk_similary BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	fk_subject_1 BIGINT NOT NULL,
	FOREIGN KEY (fk_subject_1) REFERENCES subject(subject_id) ON UPDATE CASCADE ON DELETE CASCADE,
	fk_subject_2 BIGINT NOT NULL,
	FOREIGN KEY (fk_subject_2) REFERENCES subject(subject_id) ON UPDATE CASCADE ON DELETE CASCADE
	
);



CREATE TABLE student(
	student_id 				BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	itson_id 			VARCHAR(10) NOT NULL,
	first_name 			VARCHAR(100) NOT NULL,
	last_name 			VARCHAR(100) NOT NULL,
	phone 				VARCHAR(15) NULL,
	avatar 				VARCHAR(255) NULL,
	facebook 			VARCHAR(100) NULL,
	date_register   	TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	status				TINYINT NOT NULL DEFAULT 2, -- '0 = Inactivo, 1 = Activo,
	
	-- llaves foraneas
	fk_user BIGINT NOT NULL UNIQUE,
	FOREIGN KEY (fk_user) REFERENCES user(user_id) ON UPDATE CASCADE ON DELETE CASCADE,
	fk_career BIGINT NOT NULL,
	FOREIGN KEY (fk_career) REFERENCES career(career_id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE day_and_hour (
	day_hour_id 		INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	hour 		TIME NOT NULL,
	day 		VARCHAR(20) NOT NULL,
	day_number	INT NOT NULL
);


CREATE TABLE schedule(
	schedule_id 			BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date_register   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	status			TINYINT NOT NULL DEFAULT 2, -- '0 = Inactivo, 1 = Activo,
	
	-- Foranea
	fk_student 	BIGINT NOT NULL,
	FOREIGN KEY (fk_student) REFERENCES student(student_id) ON UPDATE CASCADE ON DELETE CASCADE,
	fk_period 	INT NOT NULL,
	FOREIGN KEY (fk_period) REFERENCES period(period_id) ON UPDATE CASCADE ON DELETE CASCADE
);



CREATE TABLE schedule_days_hours(
	schedule_dh_id 	BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	status	TINYINT NOT NULL DEFAULT 2,

	-- Foreaneas
	fk_day_hour INT NOT NULL,
	FOREIGN KEY (fk_day_hour) REFERENCES day_and_hour(day_hour_id) ON UPDATE CASCADE ON DELETE CASCADE,
	fk_schedule BIGINT NOT NULL,
	FOREIGN KEY (fk_schedule) REFERENCES schedule(schedule_id) ON UPDATE CASCADE ON DELETE CASCADE
);




CREATE TABLE schedule_subjects(
	schedule_subject_id			BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date_register   TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	status			TINYINT NOT NULL DEFAULT 1,
	
	-- Foranea	
	fk_schedule BIGINT NOT NULL,
	FOREIGN KEY (fk_schedule) REFERENCES schedule(schedule_id) ON UPDATE CASCADE ON DELETE CASCADE,
	fk_subject  BIGINT NOT NULL,
	FOREIGN KEY (fk_subject) REFERENCES subject(subject_id) ON UPDATE CASCADE ON DELETE CASCADE
);



CREATE TABLE advisory_request(
	advisory_id  	BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date_register TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	date_start DATETIME NULL, -- Fecha de asignacion
	date_end DATETIME NULL, -- Fecha de finalizacion
	rating tinyint, -- Cuando el alumno califica
	description TEXT,
	status   TINYINT NOT NULL DEFAULT 3,
	
	-- llaves foraneas
	fk_adviser BIGINT,
	FOREIGN KEY (fk_adviser) REFERENCES student(student_id) ON UPDATE CASCADE ON DELETE CASCADE,

	fk_student BIGINT NOT NULL,
	FOREIGN KEY (fk_student) REFERENCES student(student_id) ON UPDATE CASCADE ON DELETE CASCADE,
	
	fk_period 	INT NOT NULL,
	FOREIGN KEY (fk_period) REFERENCES period(period_id) ON UPDATE CASCADE ON DELETE CASCADE,

	-- Directamente con materia
	fk_subject BIGINT NOT NULL,
	FOREIGN KEY (fk_subject) REFERENCES subject(subject_id) ON UPDATE CASCADE ON DELETE CASCADE
	
);


CREATE TABLE advisory_schedule(
	advisory_schedule_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date_register TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	status   TINYINT NOT NULL DEFAULT 2,

	-- ---------llaves foraneas
	-- Directamente con horas
	fk_advisory BIGINT,
	FOREIGN KEY (fk_advisory) REFERENCES advisory_request(advisory_id) ON UPDATE CASCADE ON DELETE CASCADE,
	-- Directamente con horas
	fk_hours BIGINT,
	FOREIGN KEY (fk_hours) REFERENCES schedule_days_hours(schedule_dh_id) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO user(email, password, fk_role, status) VALUES
('unacosa@gmail.com', md5('freedom'), 'basic', 2),
('loquesea@gmail.com', md5('freedom'), 'basic', 2),
('algunos@gmail.com', md5('freedom'), 'basic', 2),
('otracosa@gmail.com', md5('freedom'), 'basic', 2);



INSERT INTO career(name, short_name) VALUES 
('Ingenieria en software', 'ISW'),
('Ingeniería Electromecánico', 'IEM'),
('Ingeniería en Electrónica', 'IE'),
('Ingeniería Industrial y de Sistemas', 'IIS'),
('Ingeniería en Mecatrónica', 'IMT');


INSERT INTO student(itson_id, first_name, last_name, phone, fk_user, fk_career) VALUES
('0001', 'first_name_1', 'last_name_1', '4444', 2, 1),
('0002', 'first_name_2', 'last_name_2', '4444', 3, 1),
('0003', 'first_name_3', 'last_name_3', '4444', 4, 1),
('0004', 'first_name_4', 'last_name_4', '4444', 5, 1);


-- aaaa-mm-dd
INSERT INTO period(date_start, date_end) VALUES
('2017-08-08','2017-12-03'),
('2018-01-01','2017-05-03'),
('2018-08-08','2018-12-03');



INSERT INTO plan(year) VALUES
('2009'),
('2016');



INSERT INTO subject(name, short_name, semester, description, fk_plan, fk_career) VALUES
('MAT-1', 'M1', 1, 'PRUEBA', 1,1),
('MAT-2', 'M2', 1, 'PRUEBA', 1,1),
('MAT-3', 'M3', 2, 'PRUEBA', 1,1),
('MAT-4', 'M4', 2, 'PRUEBA', 2,1),
('MAT-5', 'M5', 4, 'PRUEBA', 2,1),
('MAT-6', 'M6', 4, 'PRUEBA', 2,1),
('MAT-7', 'M7', 4, 'PRUEBA', 2,1);





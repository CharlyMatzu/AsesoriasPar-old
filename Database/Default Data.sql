-- ----------------------------
-- DATOS DEFAULT
-- ----------------------------

INSERT INTO role(name, description) VALUES
('admin', "Control de asesorias, usuarios, registros, etc."),
('moderator', "Control de asesorias"),
('basic', "Solo el control de su perfil de usuario y solicitu de asesorias");



-- ----------------------------
-- USUARIOS
-- ----------------------------

INSERT INTO user(email, password, fk_role, status) VALUES
('c_01_12@gmail.com', md5('freedom'), 'admin', 2);



START TRANSACTION;
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '8:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '9:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '10:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '11:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '12:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '13:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '14:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '15:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '16:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '17:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '18:00:00', 'Lunes', 1);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '8:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '9:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '10:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '11:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '12:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '13:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '14:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '15:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '16:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '17:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '18:00:00', 'Martes', 2);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '8:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '9:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '10:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '11:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '12:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '13:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '14:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '15:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '16:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '17:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '18:00:00', 'Miercoles', 3);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '8:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '9:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '10:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '11:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '12:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '13:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '14:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '15:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '16:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '17:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '18:00:00', 'Jueves', 4);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '8:00:00', 'Viernes', 5);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '9:00:00', 'Viernes', 5);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '10:00:00', 'Viernes', 5);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '11:00:00', 'Viernes', 5);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '12:00:00', 'Viernes', 5);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '13:00:00', 'Viernes', 5);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '14:00:00', 'Viernes', 5);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '15:00:00', 'Viernes', 5);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '16:00:00', 'Viernes', 5);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '17:00:00', 'Viernes', 5);
INSERT INTO day_and_hour (day_hour_id, hour, day, day_number) VALUES (DEFAULT, '18:00:00', 'Viernes', 5);

COMMIT;





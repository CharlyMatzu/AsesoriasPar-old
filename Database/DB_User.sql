-- ----------------
-- USUARIO BASE DE DATOS
-- ----------------

-- http://dev.mysql.com/doc/refman/5.0/en/user-account-management.html

DROP USER asesoriaspar_user;

GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP 
ON asesoriaspar.* TO 'ronintop_asesoriaspar_user'@'localhost' IDENTIFIED BY 'asesoriaspar_pass';
FLUSH PRIVILEGES;

SHOW GRANTS FOR 'ronintop_asesoriaspar_user'@'localhost';


-- -----------Actualizar privilegios
REVOKE ALL PRIVILEGES ON asesoriaspar.* FROM 'asesoriaspar_user'@'localhost';
GRANT SELECT,INSERT,UPDATE,DELETE ON asesoriaspar.* TO 'asesoriaspar_user'@'localhost';
FLUSH PRIVILEGES;



-- GRANT SELECT,INSERT,UPDATE,DELETE
-- ON asesoriaspar.* TO 'asesoriaspar_user'@'%' IDENTIFIED BY 'asesoriaspar_pass';
-- FLUSH PRIVILEGES;
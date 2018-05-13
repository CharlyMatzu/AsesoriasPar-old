<?php namespace Persistence;

use Control\Functions;
use objects\User;
use Utils;

/**
 * Class Users
 * @package Model\Persistence
 */
class Users extends Persistence{

    public function __construct(){}

    private $SELECT = "SELECT 
                        u.user_id,
                        u.email,
                        u.password,
                        u.register_date,
                        u.status,
                        r.name
                    FROM user u
                    INNER JOIN role r ON r.name = u.fk_role";

    /**
     * Método que regresa todos los usuarios
     * @return \Objects\DataResult
     */
    public function getUsers(){
        $query = $this->SELECT;
        return  self::executeQuery($query);
    }


    //TODO: change for auth with JWT token based

    /**
     * Método que regresa un usuario en la coincidencia con un nombre de
     * usuario y la contraseña
     * @param String $email Correo del usuario
     * @param String $pass Contraseña
     * @return \Objects\DataResult
     */
    public function getUser_ByAuth($email, $pass){
        $ePass = $this->crypt($pass);
        $query = $this->SELECT."
                WHERE (u.email = '".$email."') 
                AND u.password = '".$ePass."' ";
        return  self::executeQuery($query);
    }

    /**
     * Método que regresa un usuario en la coincidencia con el ID
     * @param int $id ID del usuario
     * @return \Objects\DataResult
     */
    public function getUser_ById($id){
        $query = $this->SELECT."
                WHERE u.user_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     * @return \Objects\DataResult
     */
    public function getRoleUser($id){
        $query = $this->SELECT."
                WHERE u.user_id = ".$id." AND r.name = '".Utils::$ROLE_BASIC."' AND u.status = ".Utils::$STATUS_ENABLE;
        return  self::executeQuery($query);
    }


    /**
     * @return \Objects\DataResult
     */
    public function getUser_Last(){
        $query = $this->SELECT." 
                  ORDER BY pk_id DESC LIMIT 1";
        return  self::executeQuery($query);
    }

    /**
     * @param $email String
     * @return \Objects\DataResult
     */
    public function getUser_ByEmail($email){
        $query = $this->SELECT."
                 WHERE u.email LIKE '$email'";
        return  self::executeQuery($query);
    }

    /**
     * @param $user User objeto tipo User con la informacion de registro
     * @return \Objects\DataResult
     */
    public function insertUser( $user ){
        $passC = self::crypt( $user->getPassword() );
        $query = "INSERT INTO user (email, password, fk_role)
                  VALUES('".$user->getEmail()."','".$passC."', '".$user->getRole()."')";
        return  self::executeQuery($query);
    }

    /**
     * @param $user User objeto tipo User con la informacion de registro
     * @return \Objects\DataResult
     */
    public function updateUser( $user ){
        $passC = self::crypt( $user->getPassword() );

        $query = "UPDATE user u
                         SET u.email = '".$user->getEmail()."', u.password = '".$passC."', u.fk_role = '".$user->getRole()."'   
                         WHERE u.user_id = ".$user->getId();
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     * @return \Objects\DataResult
     */
    public function deleteUser( $id ){
        $query = "UPDATE user u
                         SET u.status = 0    
                         WHERE user_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $roleName String
     * @return \Objects\DataResult
     */
    public function getRole_ByName($roleName)
    {
        $query = "SELECT * FROM Role WHERE name = '$roleName'";
        return  self::executeQuery($query);
    }


}
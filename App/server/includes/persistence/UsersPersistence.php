<?php namespace Persistence;

use Model\User;
use Utils;

/**
 * Class Users
 * @package Model\Persistence
 */
class UsersPersistence extends Persistence{

    public function __construct(){}

    private $SELECT = "SELECT 
                        u.user_id,
                        u.email,
                        u.password,
                        u.register_date,
                        u.status,
                        r.name as 'role_name'
                    FROM user u
                    INNER JOIN role r ON r.name = u.fk_role ";

    /**
     * Método que regresa todos los usuarios
     * @return \Model\DataResult
     */
    public function getUsers(){
        $query = $this->SELECT;
        return  self::executeQuery($query);
    }

    public function getUsers_Active()
    {
        $query = $this->SELECT.
                "WHERE u.status = ".Utils::$STATUS_ACTIVE;
        return  self::executeQuery($query);
    }

    public function getUsers_Deleted()
    {
        $query = $this->SELECT.
            "WHERE u.status = ".Utils::$STATUS_DELETED;
        return  self::executeQuery($query);
    }


    /**
     * Método que regresa un usuario en la coincidencia con un nombre de
     * usuario y la contraseña
     * @param String $email Correo del usuario
     * @param String $pass Contraseña
     * @return \Model\DataResult
     */
    public function getUser_BySignIn($email, $pass){
        $ePass = $this->crypt($pass);
        $query = $this->SELECT."
                WHERE (u.email = '".$email."') 
                AND u.password = '".$ePass."' ";
        return  self::executeQuery($query);
    }

    /**
     * @param $id int
     * @return \Model\DataResult
     */
    public function getUserByTokenAuth($id)
    {
        $query = $this->SELECT."
                WHERE u.user_id = $id";
        return  self::executeQuery($query);
    }

    /**
     * Método que regresa un usuario en la coincidencia con el ID
     * @param int $id ID del usuario
     * @return \Model\DataResult
     */
    public function getUser_ById($id){
        $query = $this->SELECT."
                WHERE u.user_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     * @return \Model\DataResult
     */
    public function getRoleUser($id){
        $query = $this->SELECT."
                WHERE u.user_id = ".$id." AND r.name = '".Utils::$ROLE_BASIC."' AND u.status = ".Utils::$STATUS_ACTIVE;
        return  self::executeQuery($query);
    }


    /**
     * @return \Model\DataResult
     */
    public function getUser_Last(){
        $query = $this->SELECT." 
                  ORDER BY user_id DESC LIMIT 1";
        return  self::executeQuery($query);
    }

    /**
     * @param $email String
     * @return \Model\DataResult
     */
    public function getUser_ByEmail($email){
        $query = $this->SELECT."
                 WHERE u.email = '$email'";
        return  self::executeQuery($query);
    }

    /**
     * @param $user User objeto tipo User con la informacion de registro
     * @return \Model\DataResult
     */
    public function insertUser( $user ){
        $passC = self::crypt( $user->getPassword() );
        $query = "INSERT INTO user (email, password, fk_role)
                  VALUES('".$user->getEmail()."','".$passC."', '".$user->getRole()."')";
        return  self::executeQuery($query);
    }

    /**
     * @param $user User objeto tipo User con la informacion de registro
     * @return \Model\DataResult
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
     * @return \Model\DataResult
     */
    public function changeStatusToDeleted($id ){
        $query = "UPDATE user u
                         SET u.status = ".Utils::$STATUS_DELETED."    
                         WHERE user_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $roleName String
     * @return \Model\DataResult
     */
    public function getRole_ByName($roleName)
    {
        $query = "SELECT * FROM Role WHERE name = '$roleName'";
        return  self::executeQuery($query);
    }




}
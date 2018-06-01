<?php namespace App\Persistence;

use App\Model\User;
use App\Utils;

/**
 * Class Users
 * @package Model\Persistence
 */
class UsersPersistence extends Persistence{

    public function __construct(){}

    private $SELECT = "SELECT 
                        u.user_id as 'id',
                        u.email,
                        u.register_date,
                        u.status,
                        r.name as 'role'
                    FROM user u
                    INNER JOIN role r ON r.name = u.fk_role ";

    /**
     * Método que regresa todos los usuarios
     * @return \App\Model\DataResult
     */
    public function getUsers(){
        $query = $this->SELECT;
        return  self::executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     */
    public function getStaffUsers()
    {
        $query = $this->SELECT.
            "WHERE r.name = '".Utils::$ROLE_MOD."' OR r.name = '".Utils::$ROLE_ADMIN."'";
        return  self::executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     */
    public function getEnableUsers()
    {
        $query = $this->SELECT.
                "WHERE u.status = ".Utils::$STATUS_ENABLE;
        return  self::executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     */
    public function getDisabledUsers()
    {
        $query = $this->SELECT.
            "WHERE u.status = ".Utils::$STATUS_DISABLE;
        return  self::executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     */
    public function getNoconfirmUsers()
    {
        $query = $this->SELECT.
            "WHERE u.status = ".Utils::$STATUS_NO_CONFIRM;
        return  self::executeQuery($query);
    }


    /**
     * Método que regresa un usuario en la coincidencia con un nombre de
     * usuario y la contraseña
     * @param String $email Correo del usuario
     * @param String $pass Contraseña
     * @return \App\Model\DataResult
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
     * @return \App\Model\DataResult
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
     * @return \App\Model\DataResult
     */
    public function getUser_ById($id){
        $query = $this->SELECT."
                WHERE u.user_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     * @return \App\Model\DataResult
     */
    public function getRoleUser($id){
        $query = $this->SELECT."
                WHERE u.user_id = ".$id." AND r.name = '".Utils::$ROLE_BASIC."' AND u.status = ".Utils::$STATUS_ENABLE;
        return  self::executeQuery($query);
    }

    /**
     * @param $email
     *
     * @return \App\Model\DataResult
     */
    public function searchUsers_ByEmail($email)
    {
        $query = $this->SELECT."
                WHERE u.email LIKE '%$email%'";
        return  self::executeQuery($query);
    }

    /**
     * @param $email
     *
     * @return \App\Model\DataResult
     */
    public function searchStaffUsers_ByEmail($email)
    {
        $query = $this->SELECT."
                WHERE u.email LIKE '%$email%' AND 
                (r.name = '".Utils::$ROLE_MOD."' OR r.name = '".Utils::$ROLE_ADMIN."')";
        return  self::executeQuery($query);
    }


    /**
     * @return \App\Model\DataResult
     */
    public function getUser_Last(){
        $query = $this->SELECT." 
                  ORDER BY user_id DESC LIMIT 1";
        return  self::executeQuery($query);
    }

    /**
     * @param $email String
     * @return \App\Model\DataResult
     */
    public function getUser_ByEmail($email){
        $query = $this->SELECT."
                 WHERE u.email = '$email'";
        return  self::executeQuery($query);
    }

    /**
     * @param $user User objeto tipo User con la informacion de registro
     * @return \App\Model\DataResult
     */
    public function insertUser( $user ){
        $passC = self::crypt( $user->getPassword() );
        $query = "INSERT INTO user (email, password, fk_role)
                  VALUES('".$user->getEmail()."','".$passC."', '".$user->getRole()."')";
        return  self::executeQuery($query);
    }

    /**
     * @param $user User objeto tipo User con la informacion de registro
     * @return \App\Model\DataResult
     */
    public function updateUser( $user ){
        $query = "UPDATE user u
                         SET    u.email = '".$user->getEmail()."',
                                u.fk_role = '".$user->getRole()."'   
                         WHERE u.user_id = ".$user->getId();

        //Si hay password, se agrega
        if( !empty($user->getPassword()) ){
            $passC = self::crypt( $user->getPassword() );
            $query = "UPDATE user u
                         SET    u.email = '".$user->getEmail()."',
                                u.password = '".$passC."', 
                                u.fk_role = '".$user->getRole()."'   
                         WHERE u.user_id = ".$user->getId();
        }
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     * @return \App\Model\DataResult
     */
    public function changeStatusToDisable($id ){
        $query = "UPDATE user u
                         SET u.status = ".Utils::$STATUS_DISABLE."    
                         WHERE user_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     * @return \App\Model\DataResult
     */
    public function changeStatusToEnable($id ){
        $query = "UPDATE user u
                         SET u.status = ".Utils::$STATUS_ENABLE."    
                         WHERE user_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $roleName String
     * @return \App\Model\DataResult
     */
    public function getRole_ByName($roleName)
    {
        $query = "SELECT * FROM role WHERE name = '$roleName'";
        return  self::executeQuery($query);
    }



    /**
     * @param $id int
     * @return \App\Model\DataResult
     */
    public function deleteUser_ById($id)
    {
        $query = "DELETE FROM user 
                  WHERE user_id = $id";
        return  self::executeQuery($query);
    }




}
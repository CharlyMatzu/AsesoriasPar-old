<?php namespace App\Persistence;

use App\Model\UserModel;
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
                        u.date_register,
                        u.status,
                        -- Role
                        r.name as 'role'
                    FROM user u
                    INNER JOIN role r ON r.name = u.fk_role ";

    /**
     * Método que regresa todos los usuarios
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getUsers(){
        $query = $this->SELECT;
        return  self::executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getStaffUsers()
    {
        $query = $this->SELECT.
            "WHERE r.name = '".Utils::$ROLE_MOD."' OR r.name = '".Utils::$ROLE_ADMIN."'";
        return  self::executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getEnableUsers()
    {
        $query = $this->SELECT.
                "WHERE u.status = '".Utils::$STATUS_ACTIVE."'";
        return  self::executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getDisabledUsers()
    {
        $query = $this->SELECT.
            "WHERE u.status = '".Utils::$STATUS_DISABLE."'";
        return  self::executeQuery($query);
    }

    /**
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getNoConfirmUsers()
    {
        $query = $this->SELECT.
            "WHERE u.status = '".Utils::$STATUS_PENDING."'";
        return  self::executeQuery($query);
    }


    /**
     * Método que regresa un usuario en la coincidencia con un nombre de
     * usuario y la contraseña
     *
     * @param String $email Correo del usuario
     * @param String $pass Contraseña
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getUser_BySignIn($email, $pass){
        $ePass = $this->crypt($pass);
        $query = $this->SELECT."
                WHERE u.email = '$email' AND u.password = '$ePass' ";
        return  self::executeQuery($query);
    }

    /**
     * Método que regresa un usuario en la coincidencia con el ID
     *
     * @param int $id ID del usuario
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getUser_ById($id){
        $query = $this->SELECT."
                WHERE u.user_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getEnabledBasicUser_ById($id){
        $query = $this->SELECT."
                WHERE u.user_id = $id AND (u.status = '".Utils::$STATUS_ACTIVE."' AND u.fk_role = '".Utils::$ROLE_BASIC."')";
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getRoleUser($id){
        $query = $this->SELECT."
                WHERE u.user_id = $id AND r.name = '".Utils::$ROLE_BASIC."' AND u.status = '".Utils::$STATUS_ACTIVE."'";
        return  self::executeQuery($query);
    }

    /**
     * @param $id int
     * @param $pass String
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getUser_ById_ByPassword($id, $pass){
        $query = $this->SELECT."
                WHERE u.user_id = $id AND u.password = '$pass' LIMIT 1";
        return  self::executeQuery($query);
    }

    /**
     * @param $student_id int
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getUser_ByStudentId( $student_id ){
        $query = $this->SELECT.
                "INNER JOIN student s ON u.user_id = s.fk_user
                WHERE s.student_id = $student_id";
        return  self::executeQuery($query);
    }

    /**
     * @param $email
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
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
     * @throws \App\Exceptions\Request\InternalErrorException
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
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getUser_Last(){
        $query = $this->SELECT." 
                  ORDER BY user_id DESC LIMIT 1";
        return  self::executeQuery($query);
    }

    /**
     * @param $email String
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getUser_ByEmail($email){
        $query = $this->SELECT."
                 WHERE u.email = '$email'";
        return  self::executeQuery($query);
    }

    /**
     * @param $user UserModel objeto tipo User con la información de registro
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function insertUser( $user ){
        $passC = self::crypt( $user->getPassword() );
        $query = "INSERT INTO user (email, password, fk_role, status)
                  VALUES('".$user->getEmail()."','".$passC."', '".$user->getRole()."', '".Utils::$STATUS_PENDING."')";
        return  self::executeQuery($query);
    }

    /**
     * @param $user_id
     * @param $email String
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function updateUserEmail($user_id, $email ){
        $query = "UPDATE user u
                         SET    u.email = '$email'   
                         WHERE  u.user_id = $user_id";
        return  self::executeQuery($query);
    }

    /**
     * @param $user_id int
     * @param $newPass String
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function updateUserPassword( $user_id, $newPass ){
        $ePass = self::crypt( $newPass );
        $query = "UPDATE user u
                         SET    u.password = '$ePass'   
                         WHERE  u.user_id = $user_id";
        return  self::executeQuery($query);
    }

    /**
     * @param $user UserModel objeto tipo User con la información de registro
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function updateUserRole( $user ){
        $query = "UPDATE user u
                         SET    u.fk_role = '".$user->getRole()."'   
                         WHERE  u.user_id = ".$user->getId();
        return  self::executeQuery($query);
    }

    /**
     * @param $id
     *
     * @param $status
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function changeStatus($id, $status ){
        $query = "UPDATE user u
                         SET u.status = '$status'    
                         WHERE user_id = ".$id;
        return  self::executeQuery($query);
    }

    /**
     * @param $roleName String
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function getRole_ByName($roleName)
    {
        $query = "SELECT * FROM role WHERE name = '$roleName'";
        return  self::executeQuery($query);
    }


    /**
     * @param $id int
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function deleteUser_ById($id)
    {
        $query = "DELETE FROM user 
                  WHERE user_id = $id";
        return  self::executeQuery($query);
    }

    /**
     * @param $career_id int
     *
     * @return \App\Model\DataResult
     * @throws \App\Exceptions\Request\InternalErrorException
     */
    public function deleteUsers_ByCareer($career_id)
    {
        $query = "DELETE u.* FROM user u
                  INNER JOIN student s ON s.fk_user = u.user_id
                  WHERE s.fk_career = $career_id";
        return  self::executeQuery($query);
    }


}
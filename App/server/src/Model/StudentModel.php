<?php namespace App\Model;

    class StudentModel {


        private $id;
        private $itson_id;
        private $first_name;
        private $last_name;
        private $phone;
        private $facebook;
        private $date_register;
        private $status;
        private $career;
        private $user;


        public function __construct(){}

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param mixed $id
         */
        public function setId($id)
        {
            $this->id = $id;
        }

        /**
         * @return String
         */
        public function getItsonId()
        {
            return $this->itson_id;
        }

        /**
         * @param String $itson_id
         */
        public function setItsonId($itson_id)
        {
            $this->itson_id = $itson_id;
        }

        /**
         * @return String
         */
        public function getFirstName()
        {
            return $this->first_name;
        }

        /**
         * @param String $first_name
         */
        public function setFirstName($first_name)
        {
            $this->first_name = $first_name;
        }

        /**
         * @return String
         */
        public function getLastName()
        {
            return $this->last_name;
        }

        /**
         * @param String $last_name
         */
        public function setLastName($last_name)
        {
            $this->last_name = $last_name;
        }

        /**
         * @return String
         */
        public function getPhone()
        {
            return $this->phone;
        }

        /**
         * @param String $phone
         */
        public function setPhone($phone)
        {
            $this->phone = $phone;
        }

        /**
         * @return String
         */
        public function getFacebook()
        {
            return $this->facebook;
        }

        /**
         * @param String $facebook
         */
        public function setFacebook($facebook)
        {
            $this->facebook = $facebook;
        }

        /**
         * @return String
         */
        public function getRegisterDate()
        {
            return $this->date_register;
        }

        /**
         * @param String $date_register
         */
        public function setRegisterDate($date_register)
        {
            $this->date_register = $date_register;
        }

        /**
         * @return int
         */
        public function getStatus()
        {
            return $this->status;
        }

        /**
         * @param int $status
         */
        public function setStatus($status)
        {
            $this->status = $status;
        }

        /**
         * @return int|CareerModel
         */
        public function getCareer()
        {
            return $this->career;
        }

        /**
         * @param int|CareerModel $career
         */
        public function setCareer($career)
        {
            $this->career = $career;
        }

        /**
         * @return int|UserModel
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * @param int|UserModel $user
         */
        public function setUser($user)
        {
            $this->user = $user;
        }
    }

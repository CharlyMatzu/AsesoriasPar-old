<?php namespace Objects;

    class Student {


        private $id;
        private $itson_id;
        private $first_name;
        private $last_name;
        private $phone;
        private $facebook;
        private $avatar;
        private $register_date;
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
         * @return mixed
         */
        public function getItsonId()
        {
            return $this->itson_id;
        }

        /**
         * @param mixed $itson_id
         */
        public function setItsonId($itson_id)
        {
            $this->itson_id = $itson_id;
        }

        /**
         * @return mixed
         */
        public function getFirstName()
        {
            return $this->first_name;
        }

        /**
         * @param mixed $first_name
         */
        public function setFirstName($first_name)
        {
            $this->first_name = $first_name;
        }

        /**
         * @return mixed
         */
        public function getLastName()
        {
            return $this->last_name;
        }

        /**
         * @param mixed $last_name
         */
        public function setLastName($last_name)
        {
            $this->last_name = $last_name;
        }

        /**
         * @return mixed
         */
        public function getPhone()
        {
            return $this->phone;
        }

        /**
         * @param mixed $phone
         */
        public function setPhone($phone)
        {
            $this->phone = $phone;
        }

        /**
         * @return mixed
         */
        public function getFacebook()
        {
            return $this->facebook;
        }

        /**
         * @param mixed $facebook
         */
        public function setFacebook($facebook)
        {
            $this->facebook = $facebook;
        }

        /**
         * @return mixed
         */
        public function getAvatar()
        {
            return $this->avatar;
        }

        /**
         * @param mixed $avatar
         */
        public function setAvatar($avatar)
        {
            $this->avatar = $avatar;
        }

        /**
         * @return mixed
         */
        public function getRegisterDate()
        {
            return $this->register_date;
        }

        /**
         * @param mixed $register_date
         */
        public function setRegisterDate($register_date)
        {
            $this->register_date = $register_date;
        }

        /**
         * @return mixed
         */
        public function getStatus()
        {
            return $this->status;
        }

        /**
         * @param mixed $status
         */
        public function setStatus($status)
        {
            $this->status = $status;
        }

        /**
         * @return mixed
         */
        public function getCareer()
        {
            return $this->career;
        }

        /**
         * @param mixed $career
         */
        public function setCareer($career)
        {
            $this->career = $career;
        }

        /**
         * @return mixed
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * @param mixed $user
         */
        public function setUser($user)
        {
            $this->user = $user;
        }
    }


?>
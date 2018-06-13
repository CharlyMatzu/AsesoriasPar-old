<?php namespace App\Model;

    class Career{

        private $id;
        private $name;
        private $short_name;
        private $date_register;
        private $status;

        /**
         * Career constructor.
         */
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
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param mixed $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

        /**
         * @return mixed
         */
        public function getShortName()
        {
            return $this->short_name;
        }

        /**
         * @param mixed $short_name
         */
        public function setShortName($short_name)
        {
            $this->short_name = $short_name;
        }

        /**
         * @return mixed
         */
        public function getRegisterDate()
        {
            return $this->date_register;
        }

        /**
         * @param mixed $date_register
         */
        public function setRegisterDate($date_register)
        {
            $this->date_register = $date_register;
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
    }


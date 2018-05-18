<?php namespace Model;

    class Schedule{
        
        private $id;
        private $register_date;
        private $status;
        private $student;
        private $period;
        private $hours;
        private $subjects;


        /**
         * Schedule constructor.
         */
        public function __construct(){}

        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param int $id
         */
        public function setId($id)
        {
            $this->id = $id;
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
         * @return mixed
         */
        public function getStudent()
        {
            return $this->student;
        }

        /**
         * @param Student $student
         */
        public function setStudent($student)
        {
            $this->student = $student;
        }

        /**
         * @return Period
         */
        public function getPeriod()
        {
            return $this->period;
        }

        /**
         * @param Period $period
         */
        public function setPeriod($period)
        {
            $this->period = $period;
        }

        /**
         * @return mixed
         */
        public function getHours()
        {
            return $this->hours;
        }

        /**
         * @param mixed $hours
         */
        public function setHours($hours)
        {
            $this->hours = $hours;
        }

        /**
         * @return mixed
         */
        public function getSubjects()
        {
            return $this->subjects;
        }

        /**
         * @param mixed $subjects
         */
        public function setSubjects($subjects)
        {
            $this->subjects = $subjects;
        }


    }
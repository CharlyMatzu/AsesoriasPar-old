<?php namespace App\Model;

    class ScheduleModel{
        
        private $id;
        private $date_register;
        private $status;
        private $student;
        private $period;
        private $hours;
        private $subjects;


        /**
         * ScheduleModel constructor.
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
         * @param StudentModel $student
         */
        public function setStudent($student)
        {
            $this->student = $student;
        }

        /**
         * @return PeriodModel
         */
        public function getPeriod()
        {
            return $this->period;
        }

        /**
         * @param PeriodModel $period
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
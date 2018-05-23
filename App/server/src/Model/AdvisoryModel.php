<?php namespace App\Model;

    class AdvisoryModel{

        private $id;
        private $register_date;
        private $status;
        private $student;
        private $adviser;
        private $subject;
        
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
         * @return Student
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
         * @return Student
         */
        public function getAdviser()
        {
            return $this->adviser;
        }

        /**
         * @param Student $adviser
         */
        public function setAdviser($adviser)
        {
            $this->adviser = $adviser;
        }

        /**
         * @return Subject
         */
        public function getSubject()
        {
            return $this->subject;
        }

        /**
         * @param Subject $subject
         */
        public function setSubject($subject)
        {
            $this->subject = $subject;
        }

    }
<?php namespace App\Model;

    class AdvisoryModel{

        private $id;
        private $date_register;
        private $date_start;
        private $date_end;
        private $description;
        private $status;
        private $student;
        private $adviser;
        private $subject;
        private $schedule;
        
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
         * @return StudentModel|int
         */
        public function getStudent()
        {
            return $this->student;
        }

        /**
         * @param StudentModel|int $student
         */
        public function setStudent($student)
        {
            $this->student = $student;
        }

        /**
         * @return StudentModel|int
         */
        public function getAdviser()
        {
            return $this->adviser;
        }

        /**
         * @param StudentModel $adviser
         */
        public function setAdviser($adviser)
        {
            $this->adviser = $adviser;
        }

        /**
         * @return SubjectModel|int
         */
        public function getSubject()
        {
            return $this->subject;
        }

        /**
         * @param SubjectModel|int $subject
         */
        public function setSubject($subject)
        {
            $this->subject = $subject;
        }

        /**
         * @return mixed
         */
        public function getDateStart()
        {
            return $this->date_start;
        }

        /**
         * @param mixed $date_start
         */
        public function setDateStart($date_start)
        {
            $this->date_start = $date_start;
        }

        /**
         * @return mixed
         */
        public function getDateEnd()
        {
            return $this->date_end;
        }

        /**
         * @param mixed $date_end
         */
        public function setDateEnd($date_end)
        {
            $this->date_end = $date_end;
        }

        /**
         * @return mixed
         */
        public function getDescription()
        {
            return $this->description;
        }

        /**
         * @param mixed $description
         */
        public function setDescription($description)
        {
            $this->description = $description;
        }

        /**
         * @return array
         */
        public function getSchedule()
        {
            return $this->schedule;
        }

        /**
         * @param array $schedule
         */
        public function setSchedule($schedule)
        {
            $this->schedule = $schedule;
        }



    }
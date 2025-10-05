<?php
/**
 * Encapsulated version of a dbs entry.
 */
class Event {
    private $id;
    private $name;
  
    private $date;
    private $startTime;
    private $endTime;
    private $description;
   
    private $capacity;
    private $completed;
    private $restricted_signup;

    private $type;
   

    function __construct($id, $name, $date, $startTime, $endTime, $description, $capacity, $completed, $restricted_signup, $type) {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->description = $description;
        $this->capacity = $capacity;
        $this->completed = $completed;
        $this->restricted_signup = $restricted_signup;

        $this->type = $type;
    }

    function getID() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

   
    // new Event
    function getDate() {
        return $this->date;
    }

    function getStartTime() {
        return $this->startTime;
    }

    function getEndTime() {
        return $this->endTime;
    }

    function getDescription() {
        return $this->description;
    }

  

    function getCapacity() {
        return $this->capacity;
    }

    function getCompleted() {
        return $this->completed;
    }

    function getRestrictedSignup() {
        return $this->restricted_signup;
    }

    function getEventType(){
        return $this->type;
    }

   
}
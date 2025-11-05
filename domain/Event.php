<?php
/**
 * Encapsulated version of a dbs entry.
 */
class Event {
    private $id;
    private $name;
    private $goalAmount;
    private $endDate;
    private $startDate;
    private $startTime;
    private $endTime;
    private $description;
    private $location;
    private $completed;

   

    function __construct($id, $name,$goalAmount, $startDate,$endDate, $startTime, $endTime, $description, $location, $completed) {
        $this->id = $id;
        $this->name = $name;
        $this->goalAmount = $goalAmount;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->description = $description;
        $this->location = $location;
        $this->completed = $completed;
    }

    function getID() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }
    function getGoalAmount() {
        return $this->goalAmount;
    }
   
    // new Event
    function getStartDate() {
        return $this->startDate;
    }
     function getEndDate() {
        return $this->endDate;
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
    function getLocation() {
        return $this->location;
    }
    function getCompleted() {
        return $this->completed;
    }



   
}
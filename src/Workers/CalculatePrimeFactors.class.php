<?php
require_once "Worker.class.php";

class CalculatePrimeFactors extends Worker {
  function performTask($task) {
    //TODO: Complete this method with correct calculation based on task parameters
    return json_encode(array("answer" => 42));
  }
}
?>

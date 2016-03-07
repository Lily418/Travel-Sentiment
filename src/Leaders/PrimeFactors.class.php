<?php
require_once "Leader.class.php";

class PrimeFactors extends Leader {
  
  private $toFactorize;

  public function __construct() {
    $this->toFactorize = range(0,100);
  }
 

  function generateTask() {
    if(count($this->toFactorize) > 0) {
      $nextNumber = array_shift($this->toFactorize);
      return array("toFactorize" => $nextNumber);
    } else {
      return NULL;
    }
  }

  function complete() {
    return count($toFactorize) === 0;
  }
}
?>

<?php
abstract class Worker {
  abstract protected function performTask($task);  
  
  private $protocolPort;

  public function __construct() {
    $this->protocolPort = json_decode(file_get_contents("worker_config.json"))->port;
  }

  function work($leaderIp) {
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', "http://" . $leaderIp . ":" . $this->protocolPort . "/getTask/");
    echo $response->getBody() . "\n";
    $taskResult = $this->performTask(json_decode($response->getBody()));
    //TODO: Make POST request to leaders /completedTask endpoint with $taskResult
  }

}
?>

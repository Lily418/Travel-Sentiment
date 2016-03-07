<?php
namespace TravelSentiment;

require_once 'vendor/autoload.php';

use \GuzzleHttp\Client;



class NetworkDiscovery {
  
  private $discoveryProtocolPort;
  private $machineNetworkId;
  private $workerIps;
  private $config;
  
  public function __construct(){
    $this->config = json_decode(file_get_contents("worker_config.json"));
    $this->workerIps = $this->config->workerIps;
    $this->discoveryProtocolPort = $this->config->port;
    
    $ifconfig = new \Datasift\IfconfigParser\Parser\Ubuntu;
    $interfaces = $ifconfig->parse(shell_exec("ifconfig"));
    $machinesIps = [];
    
    foreach($interfaces as $interface) {
      if (false !== $key = array_search($interface["ip_address"], $this->workerIps)) {
        $this->machineNetworkId = $key;
      }
    }
    
    if(!isset($this->machineNetworkId)) {
      //echo "No machine ip matches network configuration";
    } else {
      //echo "This is machine " . $this->machineNetworkId . "\n";
    }
  }

  public function getLeader() {
    $leaderId = json_decode($this->sendNetworkId($this->getOwnNetworkId()));
    return $this->workerIps[$leaderId];
  }
  
  function getOwnIp() {
    return $this->workerIps[$this->machineNetworkId];
  }

  function getOwnNetworkId() {
    return $this->machineNetworkId;
  }

  function getConfig() {
    return $this->config;
  }


  
  function sendNetworkId($maxid, $id = NULL) {
    if(!isset($id)) {
      $id = $this->getOwnNetworkId();
    }
    
    
    $client = new \GuzzleHttp\Client();
    
    $nextMachineIdIndex = $this->machineNetworkId + 1;

    if($nextMachineIdIndex >= count($this->workerIps)) {
      $nextMachineIdIndex=0;
    }

    while($nextMachineIdIndex != $this->machineNetworkId) {
      $nextMachineIp = $this->workerIps[$nextMachineIdIndex];
      try {
        $response = $client->request('GET', "http://" . $nextMachineIp . ":" . $this->discoveryProtocolPort . "/messageRing/" . $id . "/" . $maxid);
        return $response->getBody();
      } catch (\GuzzleHttp\Exception\ConnectException $e) {
        $nextMachineIdIndex++;
        if($nextMachineIdIndex >= count($this->workerIps)) {
          $nextMachineIdIndex=0;
        }
      }
    }
    
    return $this->machineNetworkId;
  }
}





$leader = NULL;


?>

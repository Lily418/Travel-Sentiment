<?php
namespace TravelSentiment;

require_once 'vendor/autoload.php';

use \GuzzleHttp\Client;



class NetworkDiscovery {
  
  private $discoveryProtocolPort;
  private $machineNetworkId;
  private $workerIps;
  
  public function __construct(){
    $config = json_decode(file_get_contents("worker_config.json"));
    $this->workerIps = $config->workerIps;
    $this->discoveryProtocolPort = $config->port;
    
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

  function getOwnNetworkId() {
    return $this->machineNetworkId;
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

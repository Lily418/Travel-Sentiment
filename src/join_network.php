<?php
namespace TravelSentiment;
require_once 'vendor/autoload.php';
require_once 'NetworkDiscovery.class.php';

$networkDiscovery = new NetworkDiscovery();

$workerClass = $networkDiscovery->getConfig()->worker;

require_once 'Workers/'. $workerClass .'.class.php';

$leaderIp = $networkDiscovery->getLeader();
$worker = new $workerClass;

while(true) {
  $worker->work($leaderIp);
}

?>

<?php
namespace TravelSentiment;
require_once 'distributed_network_discovery.php';

$networkDiscovery = new NetworkDiscovery();

echo $networkDiscovery->sendNetworkId($networkDiscovery->getOwnNetworkId());

?>

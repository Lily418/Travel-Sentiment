<?php
namespace TravelSentiment;
require_once 'vendor/autoload.php';
require_once 'distributed_network_discovery.php';

\Flight::set('networkDiscovery', new NetworkDiscovery());

\Flight::route('/messageRing/@id/@maxid', function($id, $maxid){
  $networkDiscovery = \Flight::get('networkDiscovery');
  if($id == $networkDiscovery->getOwnNetworkId()) {
    echo $maxid;
  } else {
    if($networkDiscovery->getOwnNetworkId() > $maxid) {
      $maxid = $networkDiscovery->getOwnNetworkId();
    }
    echo $networkDiscovery->sendNetworkId($maxid, $id);
    
  }
});

\Flight::start();


?>

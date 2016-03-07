<?php
namespace TravelSentiment;
require_once 'vendor/autoload.php';
require_once 'NetworkDiscovery.class.php';

$networkDiscovery = new NetworkDiscovery();

\Flight::set('networkDiscovery', $networkDiscovery);

$leaderClass = $networkDiscovery->getConfig()->leader;
require_once 'Leaders/'. $leaderClass .'.class.php';
$leader = new $leaderClass;
\Flight::set('leader', $leader);


\Flight::route('/messageRing/@id/@maxid', function($id, $maxid){
  $networkDiscovery = \Flight::get('networkDiscovery');
  if($id == $networkDiscovery->getOwnNetworkId()) {
    \Flight::json($maxid);
  } else {
    if($networkDiscovery->getOwnNetworkId() > $maxid) {
      $maxid = $networkDiscovery->getOwnNetworkId();
    }
    echo $networkDiscovery->sendNetworkId($maxid, $id);
    
  }
});

\Flight::route('/getTask', function(){
  $leader = \Flight::get('leader');
  \Flight::json($leader->generateTask());
});

\Flight::start();


?>

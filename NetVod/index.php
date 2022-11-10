<?php

use iutnc\netvod\dispatcher\Dispatcher;
use iutnc\netvod\db\ConnectionFactory;
require_once 'vendor/autoload.php';

session_start();
ConnectionFactory::setConfig('db.config.ini');
/*$t = iutnc\netvod\model\list\Serie::getSeriesByKeywords(['ville']);
echo count($t);
foreach ($t as $serie){
    echo $serie->getName();
}*/

$test = new Dispatcher();
$test->run();

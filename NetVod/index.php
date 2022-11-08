<?php

use iutnc\netvod\dispatcher\Dispatcher;
use iutnc\netvod\db\ConnectionFactory;
require_once 'vendor/autoload.php';

session_start();
ConnectionFactory::setConfig('db.config.ini');

$test = new Dispatcher();
$test->run();

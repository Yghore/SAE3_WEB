<?php

use iutnc\netvod\dispatcher\Dispatcher;
require_once 'vendor/autoload.php';

session_start();

$test = new Dispatcher();
$test->run();

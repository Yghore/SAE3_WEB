<?php

require_once 'vendor/autoload.php';

session_start();

$test = new \iutnc\netvod\dispatcher\Dispacher();
$test->run();

<?php

namespace iutnc\netvod\action;

abstract class Action
{
    protected ?string $http_method = null;
    protected ?string $hostname = null;
    protected ?string $script_name = null;

    public function __construct()
    {

        $this->http_method = $_SERVER['REQUEST_METHOD'];
        $this->hostname = $_SERVER['HTTP_HOST'];
        $this->script_name = $_SERVER['SCRIPT_NAME'];
    }

    public function execute() : string
    {
        if ($this->http_method == 'GET') {
            return $this->executeGET();
        } else if ($this->http_method == 'POST') {
            return $this->executePOST();
        } else {
            return 'Une erreur est survenue';
        }
    }

    protected abstract function executeGET() : string;

    protected abstract function executePOST() : string;
}
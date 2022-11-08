<?php

namespace iutnc\netvod\render;

use iutnc\netvod\model\User;

class UserRenderer implements Renderer
{

    protected User $user;

    public function __construct($u)
    {
        $this->user = $u;
    }

    public function render(int $selector = 1): string
    {
        $html = "";
        return $html;
    }
}
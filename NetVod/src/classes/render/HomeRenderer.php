<?php

namespace iutnc\netvod\render;

class HomeRenderer implements Renderer
{

    public function render(int $selector = 1) : string
    {
        return "Bienvenue sur le site !";
    }
}
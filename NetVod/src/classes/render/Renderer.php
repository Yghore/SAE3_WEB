<?php

namespace iutnc\netvod\render;

use ViewException;

interface Renderer
{
    public function render(int $selector = 1) : string;
}
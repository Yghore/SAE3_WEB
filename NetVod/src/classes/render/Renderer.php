<?php

namespace iutnc\netvod\render;


interface Renderer
{
    public function render(int $selector = 1) : string;
}

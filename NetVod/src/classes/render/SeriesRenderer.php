<?php

namespace iutnc\netvod\render;

class SeriesRenderer implements Renderer
{

    protected array $series;

    public function __construct(array $s)
    {
        $this->series = $s;
    }


    public function render(int $selector = 1): string
    {
        $html = "<div>";
        foreach ($this->series as $value)
        {

            $render = new SerieRenderer($value);
            $html .= $render->render($selector);
        }
        $html .= "</div>";
        return $html;

    }
}
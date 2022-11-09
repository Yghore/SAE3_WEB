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
        $html = "<div class='list-card'>";
        foreach ($this->series as $value)
        {

            $render = new SerieRenderer($value, "?action=catalogue-print");
            $html .= $render->render($selector);
        }
        $html .= "</div>";
        return $html;

    }
}
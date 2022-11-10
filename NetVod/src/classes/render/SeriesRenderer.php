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
        $html = '';
        if ($_GET['action'] == 'print-catalogue'){
        $html .= <<<end
            <form>
                <label for="pet-select">Sort by : </label>
                <input type="hidden" name="action" value="print-catalogue">
                <select name="orderBy" id="trie-liste">
                    <option value="">--Please choose an option--</option>
                    <option value="titre">Titre</option>
                    <option value="date_ajout">date d'ajout</option>
                    <option value="nbEpisodes">nombres d'episode</option>
                </select>
                <button>entrer</button>
            </form>
            end;
        }
        $html .= "<div class='list-card'>";
        foreach ($this->series as $value)
        {

            $render = new SerieRenderer($value, "?action=catalogue-print");
            $html .= $render->render($selector);
        }
        $html .= "</div>";
        return $html;

    }
}
<?php

namespace iutnc\netvod\render;

use iutnc\netvod\model\list\Serie;
use iutnc\netvod\model\video\Episode;

class EpisodeRenderer implements Renderer
{

    protected Episode $episode;

    public function __construct($episode){
        $this->episode = $episode;
    }

    public function render(int $selector = 1): string
    {
        $html = '';
        if ($selector == 1) {
            $html .= <<<EOF
                <div class="card">
                    <a href="?action=print-catalogue&id={$this->episode->id}">
                    <div class="img" style='background: no-repeat url("ressources/img/{$this->episode->getThumbnails()}") center; background-size: cover'>

                    </div>
                    </a>
                     <div class="other">
                        <h4>{$this->episode->titre}</h4>
                        <p>{$this->episode->resume}</p>
                     </div>
                </div>
                
               
                
            EOF;
        }

        if ($selector == 2){
            $html .= "</br>Titre de l'episode : {$this->episode->titre}";
            $html .= "</br>Resume : {$this->episode->resume}";
            $html .= "</br>Duree : {$this->episode->duree} min";
        }
        return $html;
    }
}
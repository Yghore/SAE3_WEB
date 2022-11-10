<?php

namespace iutnc\netvod\render;

use iutnc\netvod\model\list\Serie;
use iutnc\netvod\model\video\Episode;

class EpisodeRenderer implements Renderer
{

    protected Episode $episode;
    protected ?Serie $serie;

    public function __construct(Episode $episode, ?Serie $serie = null){
        $this->episode = $episode;
        $this->serie = $serie;
    }

    public function render(int $selector = 1): string
    {
        $html = '';
        if ($selector == 1) {
            $html .= <<<EOF
                <div class="card">
                    <a href="?action=print-catalogue&id={$this->serie->id}&idepisode={$this->episode->id}">
                    <div class="img" style='background: no-repeat url("{$this->episode->getThumbnails()}") center; background-size: cover'>

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

            $html .= <<<EOF
                <p>Titre de l'episode : {$this->episode->titre}</p>
                <p>Resume : {$this->episode->resume}</p>
                <p>Duree : {$this->episode->duree} min</p>
                <video controls width="100%">
                
                    <source src="ressources/video/{$this->episode->filename}" type="video/mp4">

                </video>
            EOF;


        }
        return $html;
    }
}
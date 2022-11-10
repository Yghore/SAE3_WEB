<?php

namespace iutnc\netvod\render;

use iutnc\netvod\model\User;
use iutnc\netvod\model\User2genre;

class GenresRenderer implements Renderer
{
    protected array $genres;
    protected array $genres2user;

    public function __construct(array $genres, array $genres2user)
    {
        $this->genres = $genres;
        $this->genres2user = $genres2user;
    }

    public function render(int $selector = 1): string
    {
        $if = function($condition, $then, $else) {
            return $condition ? $then : $else;
        };
        $res = <<<EOF

        <fieldset>
                  <legend>Quels sont vos genres préférés:</legend>

        EOF;

        foreach ($this->genres as $genre) {
            $res .= <<<EOF
            <div>
                <input type="checkbox" name="{$genre->id}" value="{$genre->libelle}" id="{$genre->id}" {$if(User2genre::isUserAsGenre($genre->id), 'checked', '')}>
                <label for="{$genre->id}">{$genre->libelle}</label>
            </div>
            EOF;
        }

        $res.= <<<EOF
            </fieldset>
        EOF;
        return $res;
    }
}
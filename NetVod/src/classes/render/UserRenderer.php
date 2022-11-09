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
        $html = <<<EOF
            <div>
                <p>Email : {$this->user->email}</p>
                <p>Email : {$this->user->role}</p>
                <p>Email : {$this->user->id}</p>
                
            </div>
        EOF;
        return $html;
    }

    public function renderCheckBox(): string
    {
        $res = <<<EOF
        <fieldset>
                  <legend>Quels sont vos genres préférés:</le
                  <div>
                      <input type="checkbox" id="horreur" name="horreur" checked>
                      <label for="scales">Horreur</label>
                  <
                  <div>
                    <input type="checkbox" id="comedie" name="comedie">
                    <label for="horns">Comédie</label>
                  </div>
                  
                  <div>
                    <input type="checkbox" id="action" name="action">
                    <label for="horns">Action</label>
                  </div>
                  
                  <div>
                    <input type="checkbox" id="enfant" name="enfant">
                    <label for="horns">Enfant</label>
                  </div>
                  
                  <div>
                    <input type="checkbox" id="drame" name="drame">
                    <label for="horns">Drame</label>
                  </div>
                  
        </fieldset>
        EOF;
        return $res;

    }
}
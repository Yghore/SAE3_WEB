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
                  <legend>Quels sont vos genres préférés:</legend>
                  <div>
                    <label for="horreur">Horreur</label>
                    <input type="checkbox" id="horreur" name="horreur" >
                  </div>
                  
                  <div>
                    <label for="comedie">Comédie</label>
                    <input type="checkbox" id="comedie" name="comedie">
                  </div>
                  
                  <div>
                    <label for="action">Action</label>
                    <input type="checkbox" id="action" name="action">
                  </div>
                  
                  <div>
                    <label for="enfant">Enfant</label>
                    <input type="checkbox" id="enfant" name="enfant">
                  </div>
                  
                  <div>
                    <label for="drame">Drame</label>
                    <input type="checkbox" id="drame" name="drame">
                  </div>
                  
        </fieldset>
        EOF;
        return $res;

    }
}
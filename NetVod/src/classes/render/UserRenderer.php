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
                      <input type="checkbox" id="scales" name="scales" checked>
                      <label for="scales">Scales</label>
                  <
                  <div>
                    <input type="checkbox" id="horns" name="horns">
                    <label for="horns">Horns</label>
                  </div>
                  
                  <div>
                    <input type="checkbox" id="horns" name="horns">
                    <label for="horns">Horns</label>
                  </div>
                  
                  <div>
                    <input type="checkbox" id="horns" name="horns">
                    <label for="horns">Horns</label>
                  </div>
                  
                  <div>
                    <input type="checkbox" id="horns" name="horns">
                    <label for="horns">Horns</label>
                  </div>
                  
                  <div>
                    <input type="checkbox" id="horns" name="horns">
                    <label for="horns">Horns</label>
                  </div>
        </fieldset>
        EOF;
        return $res;

    }
}
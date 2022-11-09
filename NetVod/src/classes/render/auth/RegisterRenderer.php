<?php

namespace iutnc\netvod\render\auth;

use iutnc\netvod\render\Renderer;

class RegisterRenderer implements Renderer
{

    /**
     * @var string
     */
    private string $error;

    public function __construct(string $error = "")
    {
        $this->error = $error;
    }

    public function render(int $selector = 1): string
    {
        $content = <<<EOF
            <div class="bg-form">
                <div class="form">
                    <h1>Ajout d’un utilisateur</h1>
                    <form action="?action=add-user" method="post">
                         <label for="email">Email</label>
                         <input type="email" name="email" id="email" required>
                         <label for="password">Mot de passe</label>
                         <input type="password" name="password" id="password" required>
                         <label for="password">Confirmer</label>
                         <input type="password" name="confirmer" id="confirmer" required>
                         <input type="submit" value="Ajouter">
                         <p class="error-text">{$this->error}</p>
                    </form>
                </div>
            </div>
         EOF;
        return $content;
    }
}
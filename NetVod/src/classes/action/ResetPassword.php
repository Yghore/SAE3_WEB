<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\model\User;

class ResetPassword extends Action
{

    protected function executeGET(): string
    {
        $token = $_SESSION['token'];
        $url = "?action=resetpassword&token=$token";
        $html = <<<EOF
                <div class="bg-form">
                    <div class="form">
                        <a href="$url">Réinitialiser le mot de passe</a>
                        
                    </div>
                </div>

                <div class="bg-form">
                    <div class="form">
                        <h1>Réinitialisation de votre mot de passe</h1>
                        <form action=$url method="post">
                            <label for="password">Nouveau mot de passe</label>
                            <input type="password" name="password" id="password" required>
                            <label for="password">Confirmer le mot de passe</label>
                            <input type="password" name="confirmpassword" id="confirmpassword" required>
                            <input type="submit" value="Envoyer">
                        </form>
                    </div>
                </div>
            EOF;
        return $html;
    }

    protected function executePOST(): string
    {
        $db = ConnectionFactory::makeConnection();
        if (isset($_POST['password']) && $_POST['password'] == $_POST['confirmpassword']) {
            $password = $_POST['password'];
            $email = $_SESSION['email'];
            $idUser = User::getIdUserFromEmail($email);
            $user = User::getFromEmail($email);
            $user->pass = password_hash($password, PASSWORD_DEFAULT);
            $password = $user->pass;
            $query = "update user set pass = ? where email = ? and id = ?";
            $stmt = $db->prepare($query);
            $stmt->bindParam(1, $password);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $idUser);
            $stmt->execute();
            $html = <<<EOF
                <div class="bg-form">
                    <div class="form">
                        <h1>Mot de passe oublié</h1>
                        <p>Votre mot de passe a bien été réinitialisé</p>
                        <button><a href="?action=signin">Se connecter</a></button>
                    </div>
                </div>
            EOF;
        } else {
            if (!isset($_POST['password'])) {
                $html = <<<EOF
                    <div class="bg-form">
                        <div class="form">
                            <h2>veuillez renseigner les champs</h2>
                        </div>
                    </div>
                EOF;
            } else {
                $html = <<<EOF
                    <div class="bg-form">
                        <div class="form">
                            <h2>Les mots de passe ne correspondent pas</h2>
                        </div>
                    </div>
                EOF;
            }
        }
        return $html;
    }
}
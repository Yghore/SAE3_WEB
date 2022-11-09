<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\model\User;

class ForgotPassword extends Action
{

    protected function executeGET(): string
    {
        $html = <<<EOF
            <div class="bg-form">
                <div class="form">
                    <h1>Mot de passe oublié</h1>
                    <form action="index.php?action=forgotpassword" method="post">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
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
        $email = $_POST['email'];
        $user = User::emailExistInDatabase($email);
        if ($user != null) {
            if (!isset($_POST['envoyer'])) {
                $token = bin2hex(random_bytes(32));
                $id = User::getIdUserFromEmail($email);
                $query = "Insert into token_reset (token, iduser) values (:token, :id)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':token', $token);
                $stmt->execute();
                $html = <<<EOF
                <div class="bg-form">
                    <div class="form">
                        <h1>Mot de passe oublié</h1>
                        <h2>Réinitialisation de votre mot de passe</h2>
                        <p>Bonjour, vous avez demandé à réinitialiser votre mot de passe. 
                        Pour ce faire, veuillez cliquer sur le lien suivant : ?action=resetpassword&token=$token</p>
                    </div>
                </div>
            EOF;
                $url = "?action=resetpassword&token=$token";
                $html .= <<<EOF
                <div class="bg-form">
                    <div class="form">
                        <a href="$url">Réinitialiser le mot de passe</a>
                        
                    </div>
                </div>

                <div class="bg-form">
                    <div class="form">
                        <h1>Réinitialisation de votre mot de passe</h1>
                        <form action="index.php?action=resetpassword&token=$token" method="post">
                            <label for="password">Nouveau mot de passe</label>
                            <input type="password" name="password" id="password" required>
                            <label for="password">Confirmer le mot de passe</label>
                            <input type="password" name="confirmpassword" id="confirmpassword" required>
                            <input type="submit" value="Envoyer">
                        </form>
                    </div>
                </div>
            EOF;
                if (isset($_POST['password']) && $_POST['password'] == $_POST['confirmpassword']) {
                    $password = $_POST['password'];
                    $query = "update user set pass = :password where email = :email";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    $html .= <<<EOF
                    <div class="bg-form">
                        <div class="form">
                            <h2>Votre mot de passe a bien été réinitialisé</h2>
                        </div>
                    </div>
                EOF;
                    return $html;
                } else {
                    return $html;
                }
            } else {
                $html = <<<EOF
                <div class="bg-form">
                    <div class="form">
                        <h1>Mot de passe oublié</h1>
                        <p>Cet email n'existe pas</p>
                    </div>
                </div>
            EOF;
                return $html;
            }
        }else{
            return "";
        }
    }
}
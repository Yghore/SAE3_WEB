<?php

namespace iutnc\netvod\action;

use iutnc\netvod\model\User;

class AddCommentNote extends Action
{

    protected function executeGET(): string
    {
        $content = <<<EOF
        <h1>Ajout d’un commentaire</h1>
        <form action="index.php?action=add-comment-note" method="post">
        <label for="commentaire">Commentaire</label>
        <input type="text" name="commentaire" id="commentaire" required>
        <label for="note">Note</label>
        <input type="number" name="note" id="note" required>
        <input type="hidden" name="id" value="{$_GET['id']}">
        <input type="submit" value="Ajouter">
        </form>

        EOF;



        return $content;
    }

    protected function executePOST(): string
    {
        if ($_POST['commentaire'] != null && $_POST['note'] != null) {
            $commentaire = $_POST['commentaire'];
            $note = $_POST['note'];
            var_dump($_POST);
            $idserie = $_POST['id'];
            try {
                $iduser = User::getFromSession()->getId();
                $user = User::getFromSession();
                $user->putCommentNoteSerie($idserie,$iduser,$note,$commentaire);
            } catch (\Exception $e){
                echo $e->getMessage();
            }

            die();
        } else {
            return '<h1>Les champs ne sont pas remplis</h1>';
        }
    }
}
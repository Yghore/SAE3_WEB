<?php

namespace iutnc\netvod\action;

use iutnc\netvod\model\User;

class AddCommentNote extends Action
{

    protected function executeGET(): string
    {
        $content = <<<EOF
        <div class="form">
            <h1>Ajout d’un commentaire</h1>
            <form action="index.php?action=add-comment-note" method="post">
            <label for="commentaire">Commentaire</label>
            <input type="text" name="commentaire" id="commentaire" required>
            <label for="note">Note</label>
            <input type="number" name="note" min=1 max=5 step=1 id="note" required>
            <input type="hidden" name="id" value="{$_GET['id']}">
            <input type="submit" value="Ajouter">
            </form>
        </div>

        EOF;



        return $content;
    }

    protected function executePOST(): string
    {
        if ($_POST['commentaire'] != null && $_POST['note'] != null) {
            $commentaire = filter_var($_POST['commentaire'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $note = filter_var($_POST['note'], FILTER_SANITIZE_NUMBER_INT);
            $idserie = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            if ($note>5) $note = 5;
            if ($note<1) $note = 1;
            try {
                $user = User::getFromSession();
                $user->putCommentNoteSerie($idserie,$note,$commentaire);
                header('location: ?action=print-catalogue&id='.$idserie);
            } catch (\Exception $e){
                echo $e->getMessage();
            }

            die();
        } else {
            return '<h1>Les champs ne sont pas remplis</h1>';
        }
    }
}
<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;

class CatalogueAction extends Action
{

    protected function executeGET(): string
    {
        $html = '';
        $pdo = ConnectionFactory::makeConnection();
        if (! isset($_GET['titre'])){
            $query = <<<end
            SELECT
                titre,
                img
            FROM
                serie
        end;
            $resultatSet = $pdo->prepare($query);
            $resultatSet->execute();
            $html .= '<form action="index.php?action=add-user" method="post">';
            $html .= '<nav><ul>';
            while ($row = $resultatSet->fetch()){
                $titre = $row['titre'];
                $image = $row['img'];
                $html .= "<li><a methods='post' href=\"index.php?action=print-catalogue&titre=$titre\">$titre $image</a></li>";
            }
            $html .= '</nav></ul>';
        } else {
            $titre = $_GET['titre'];
            $query = <<<end
            SELECT
                *
            FROM
                serie
            WHERE
                titre = ?
            end;
            $resultatSet= $pdo->prepare($query);
            $resultatSet->execute([$titre]);
            while ($row = $resultatSet->fetch()){
                $html .= 'titre : ' .  $row['titre'] . "<br/>";
                $html .= 'descriptif : ' . $row['descriptif'] . "<br/>";
                $html .= 'img : ' . $row['img'] . "<br/>";
                $html .= 'annee : ' . $row['annee'] . "<br/>";
                $html .= 'date d ajout' . $row['date_ajout'] . "<br/>";
            }

            $query2 = <<<end

            end;

        }

        return $html;
    }

    protected function executePOST(): string
    {
        $html = 'yo';
        return $html;
    }
}

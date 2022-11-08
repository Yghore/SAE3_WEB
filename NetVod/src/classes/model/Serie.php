<?php

namespace iutnc\netvod\model;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\user\AttributException;

class Serie
{

    private int $id;
    private String $titre;
    private String $descriptif;
    private String $img;
    private int $annee;
    private String $date_ajout;


    public function __get(string $name)
    {
        if(property_exists($this, $name))
        {
            return $this->$name;
        }
        throw new AttributException("l'attribut " . $name . " n'existe pas !");
    }

    public static function getSerie(int $id)
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT * FROM serie WHERE id = ?");
        $state->execute([$id]);
        return $state->fetchObject(Serie::class);
    }








}
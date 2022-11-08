<?php

namespace iutnc\netvod\model;

use iutnc\netvod\db\ConnectionFactory;

class Serie
{

    private int $id;
    private String $titre;
    private String $description;
    private String $img;
    private int $annee;
    private String $date;


    public static function getSerie(int $id)
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("SELECT * FROM serie WHERE id = ?");
        $state->execute([$id]);
        return $state->fetchObject(Serie::class);
    }



}
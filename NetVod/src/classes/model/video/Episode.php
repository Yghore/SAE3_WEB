<?php

namespace iutnc\netvod\model\video;

use iutnc\netvod\exception\video\InvalidPropertyValueException as InvalidPropertyValueException;
use iutnc\netvod\exception\video\InvalidPropertyNameException;

class Episode
{

    protected int $numero;

    protected string $titre;

    protected string $resume;

    protected int $duree;

    protected string $filename;

    protected int $serie_id;

    public function __construct(string $titre, $filename){
        $this->id = $titre;
        $this->filename = $filename;
    }

    public function __toString(): string{
        return json_encode($this);
    }

    public function __get(string $attribut) : mixed{
        if (property_exists($this, $attribut)){
            return $this->$attribut;
        } else {
            throw new InvalidPropertyNameException();
        }
    }

    public function __set(string $attribut, mixed $valeur){
        if (property_exists($this, $attribut)){
            if ($attribut !== 'filename'){
                $this->$attribut = $valeur;
            } else {
                throw new InvalidPropertyValueException();
            }
        } else {
            throw new InvalidPropertyNameException();
        }
    }
}
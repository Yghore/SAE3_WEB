<?php

namespace iutnc\netvod\model;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\video\InvalidPropertyNameException;

class Token
{

    protected string $token;
    protected string $email;
    protected int $date_expiration;
    protected string $type_token;

    public static function deleteTokenFormDB(mixed $token)
    {
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("DELETE FROM token_reset WHERE token = ?");
        $state->execute([$token]);
    }

    public function __get(string $attribut) : mixed{
        if (property_exists($this, $attribut)){
            return $this->$attribut;
        } else {
            throw new InvalidPropertyNameException("Attribut : " . $attribut);
        }
    }

    public function __set(string $attribut, mixed $valeur){
        if (property_exists($this, $attribut)){
            $this->$attribut = $valeur;
        } else {
            throw new InvalidPropertyNameException("Attribut existe pas : $attribut");
        }
    }

    public function generateToken(String $typeToken, string $email)
    {
        $this->token = bin2hex(random_bytes(32));
        $this->type_token = $typeToken;
        $this->email = $email;
        $this->date_expiration = time() + 60 * 5;
        $db = ConnectionFactory::makeConnection();
        $state = $db->prepare("INSERT INTO token_reset values(?, ?, FROM_UNIXTIME(?), ?)");
        $state->execute([$this->token, $this->email, $this->date_expiration, $this->type_token]);

    }

    public function getValidateURL()
    {
        return "?action=validate&token=". $this->token;
    }

}
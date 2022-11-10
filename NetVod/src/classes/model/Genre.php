<?php

namespace iutnc\netvod\model;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\video\InvalidPropertyNameException;
use PDO;

class Genre
{

        protected int $id;

        protected string $nom;

        public function __get(string $attribut) : mixed{
            if (property_exists($this, $attribut)){
                return $this->$attribut;
            } else {
                throw new InvalidPropertyNameException(" $attribut n'existe pas");
            }
        }

        public function __set(string $attribut, mixed $valeur){
            if (property_exists($this, $attribut)){
                $this->$attribut = $valeur;
            } else {
                throw new InvalidPropertyNameException("Attribut existe pas : $attribut");
            }
        }

        public static function getGenres(): array {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                SELECT
                    id,
                    libelle
                FROM
                    genre
                ORDER BY
                    libelle
                end;
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $genres = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $genre = new Genre();
                $genre->id = $row['id'];
                $genre->nom = $row['nom'];
                $genres[] = $genre;
            }
            return $genres;
        }

        public static function getGenreById(int $id): Genre {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                SELECT
                    id,
                    libelle
                FROM
                    genre
                WHERE
                    id = :id
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $genre = new Genre();
            $genre->id = $row['id'];
            $genre->nom = $row['nom'];
            return $genre;
        }

        public static function getGenreByLibelle(string $nom): Genre
        {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                SELECT
                    id,
                    libelle
                FROM
                    genre
                WHERE
                    libelle = :nom
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $genre = new Genre();
            $genre->id = $row['id'];
            $genre->nom = $row['nom'];
            return $genre;
        }
}
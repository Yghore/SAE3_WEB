<?php

namespace iutnc\netvod\model;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\video\InvalidPropertyNameException;
use PDO;

class Genre
{

        protected int $id;

        protected string $libelle;


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
            $stmt->setFetchMode(PDO::FETCH_CLASS, Genre::class);
            $stmt->execute();
            return $stmt->fetchAll();

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
            $stmt->setFetchMode(PDO::FETCH_CLASS, Genre::class);
            $stmt->execute();
            return $stmt->fetch();
        }

       public static function getIdByGenre(string $libelle): int{
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                SELECT
                    id,
                    libelle
                FROM
                    genre
                WHERE
                    libelle = :libelle
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':libelle', $libelle);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        }

        public static function addGenre(int $id,string $libelle): void {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                INSERT INTO
                    genre
                VALUES
                    (:id, :libelle)
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':libelle', $libelle, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }

        public static function deleteGenre(int $id): void {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                DELETE FROM
                    genre
                WHERE
                    id = :id
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }

        public static function updateGenre(int $id, string $libelle): void {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                UPDATE
                    genre
                SET
                    libelle = :libelle
                WHERE
                    id = :id
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':libelle', $libelle, PDO::PARAM_STR);
            $stmt->execute();

       }
}
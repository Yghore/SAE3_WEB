<?php

namespace iutnc\netvod\model;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\exception\video\InvalidPropertyNameException;
use PDO;

class User2genre
{

        protected int $iduser;

        protected int $idgenre;

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

        public static function addGenre2user(int $iduser, int $idgenre): void {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                INSERT INTO
                    user2genre
                VALUES
                    (:id_user, :id_genre)
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id_user', $iduser, PDO::PARAM_INT);
            $stmt->bindValue(':id_genre', $idgenre, PDO::PARAM_INT);
            $stmt->execute();
        }

        public static function deleteGenre2user(int $iduser, int $idgenre): void {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                DELETE FROM
                    user2genre
                WHERE
                    iduser = :id_user
                AND
                    idgenre = :id_genre
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id_user', $iduser, PDO::PARAM_INT);
            $stmt->bindValue(':id_genre', $idgenre, PDO::PARAM_INT);
            $stmt->execute();
        }

        public static function getGenresByUser(int $iduser): array {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                SELECT
                    idgenre
                FROM
                    user2genre
                WHERE
                    iduser = :id_user
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id_user', $iduser, PDO::PARAM_INT);
            $stmt->execute();
            $genres = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $genre = new Genre();
                $genre->id = $row['idgenre'];
                $genres[] = $genre;
            }
            return $genres;
        }

        public static function isUserAsGenre(int $idgenre) : bool {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                SELECT
                    idgenre
                FROM
                    user2genre
                WHERE
                    idgenre = :id_genre
                AND
                    iduser = :id_user
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id_genre', $idgenre, PDO::PARAM_INT);
            $stmt->bindValue(':id_user', User::getFromSession()->id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row){
                return true;
            } else {
                return false;
            }
        }

        public static function getUsersByGenre(int $idgenre): array {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                SELECT
                    iduser
                FROM
                    user2genre
                WHERE
                    idgenre = :id_genre
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id_genre', $idgenre, PDO::PARAM_INT);
            $stmt->execute();
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $user = new User();
                $user->id = $row['iduser'];
                $users[] = $user;
            }
            return $users;
        }

        public static function exists(int $iduser, int $idgenre): bool {
            $pdo = ConnectionFactory::makeConnection();
            $query = <<<end
                SELECT
                    *
                FROM
                    user2genre
                WHERE
                    iduser = :id_user
                AND
                    idgenre = :id_genre
                end;
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id_user', $iduser, PDO::PARAM_INT);
            $stmt->bindValue(':id_genre', $idgenre, PDO::PARAM_INT);
            $stmt->execute();
            var_dump($stmt->rowCount());
            return $stmt->rowCount() > 0;
        }




}
<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository
{
    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT u.id, u.email, u.password, ud.name, ud.lastname, u.role
            FROM public.users u
            LEFT JOIN public.userdetails ud ON u.id = ud.user_id
            WHERE u.email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User(
            $user['id'],
            $user['email'],
            $user['password'],
            $user['name'],
            $user['lastname'],
            $user['role']

        );
    }

    public function addUser(User $user)
    {
        $database = $this->database->connect();

        try {
            $stmt = $database->prepare('
                INSERT INTO public.users (email, password)
                VALUES (?, ?)
                RETURNING id
            ');

            $email = $user->getEmail();
            $password = $user->getPassword();

            $stmt->execute([$email, $password]);

            $userId = $stmt->fetchColumn();

            $stmt = $database->prepare('
                INSERT INTO public.userdetails (user_id, name, lastname)
                VALUES (?, ?, ?)
            ');

            $name = $user->getName();
            $lastname = $user->getLastname();

            $stmt->execute([$userId, $name, $lastname]);
        } catch (PDOException $e) {
            die("Błąd podczas dodawania użytkownika do bazy danych: " . $e->getMessage());
        }
    }

    public function updatePassword(string $email, string $newPassword)
    {
        $database = $this->database->connect();

        try {
            $stmt = $database->prepare('
            UPDATE public.users 
            SET password = :password 
            WHERE email = :email
        ');

            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $e) {
            die("Błąd podczas aktualizacji hasła: " . $e->getMessage());
        }
    }

    public function getAllUsers(): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT u.id, u.email, u.password, ud.name, ud.lastname, u.role
        FROM public.users u
        LEFT JOIN public.userdetails ud ON u.id = ud.user_id
        ORDER BY u.role, u.id
    ');

        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($users as $user) {
            $result[] = new User(
                $user['id'],
                $user['email'],
                $user['password'],
                $user['name'],
                $user['lastname'],
                $user['role']
            );
        }

        return $result;
    }

    function updateUser(User $user)
    {
        $pdo = $this->database->connect();

        try {



            $id = $user->getId();
            error_log("ID użytkownika to: " . $id);  // Wypisanie wartości ID do logó

            $email = $user->getEmail();
            $role = $user->getRole();
            $id = $user->getId();

            if(empty($id)) {
                error_log("Błąd: ID użytkownika jest puste!");
            } else {
                error_log("ID użytkownika to: " . $id);
            }

            $stmt = $pdo->prepare('SELECT id FROM users');
            $stmt->execute();
            $allIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
            error_log("Wszystkie ID z tabeli users: " . implode(", ", $allIds));

            // Aktualizacja tabeli users
            $stmt = $pdo->prepare('
        UPDATE users 
        SET email = :email, role = :role
        WHERE id = :id
    ');

            $email = $user->getEmail();
            $role = $user->getRole();
            $id = $user->getId();

            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            // Aktualizacja tabeli userdetails
            $stmt = $pdo->prepare('
        UPDATE userdetails
        SET name = :name, lastname = :lastname
        WHERE user_id = :user_id
    ');

            $name = $user->getName();
            $lastname = $user->getLastname();

            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);

            $stmt->execute();



        } catch (PDOException $e) {
            throw new Exception("Błąd podczas aktualizacji danych użytkownika: " . $e->getMessage());
        }
    }
}
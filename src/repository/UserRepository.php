<?php
require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository
{
    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT u.email, u.password, ud.name, ud.lastname, u.role
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

}
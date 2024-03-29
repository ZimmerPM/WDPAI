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
            LEFT JOIN public.user_details ud ON u.id = ud.user_id
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
                INSERT INTO public.user_details (user_id, name, lastname)
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
        LEFT JOIN public.user_details ud ON u.id = ud.user_id
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
            $pdo->beginTransaction();

            $id = $user->getId();
            $email = $user->getEmail();
            $role = $user->getRole();


            // Aktualizacja tabeli users
            $stmt = $pdo->prepare('
            UPDATE users 
            SET email = :email, role = :role
            WHERE id = :id
        ');

            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            // Aktualizacja tabeli user_details
            $stmt = $pdo->prepare('
            UPDATE user_details
            SET name = :name, lastname = :lastname
            WHERE user_id = :user_id
        ');

            $name = $user->getName();
            $lastname = $user->getLastname();

            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);

            $stmt->execute();

            $pdo->commit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            throw new Exception("Błąd podczas aktualizacji danych użytkownika: " . $e->getMessage());
        }
    }

    public function deleteUser(int $userId): bool
    {
        $pdo = $this->database->connect();

        try {
            // Rozpoczęcie transakcji
            $pdo->beginTransaction();

            // Usuwanie powiązanych rekordów z tabeli user_details
            $stmt = $pdo->prepare('DELETE FROM user_details WHERE user_id = :id');
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Usuwanie użytkownika z tabeli users
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Zatwierdzenie zmian w transakcji
            $pdo->commit();

            // Zwracanie prawdy, jeśli przynajmniej jeden użytkownik został usunięty
            return $stmt->rowCount() > 0;

        } catch (\Exception $e) {
            // Wycofanie zmian w transakcji w przypadku błędu
            $pdo->rollBack();
            throw $e;  // Rzucenie wyjątku dalej, aby móc go obsłużyć w kodzie wywołującym
        }
    }
}
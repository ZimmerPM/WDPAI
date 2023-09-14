<?php
require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository
{
    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM public.users WHERE email = :email
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
            $user['lastname']
        );
    }

    public function addUser(User $user)
    {
        $database = $this->database->connect();

        try {
            $stmt = $database->prepare('
            INSERT INTO public.users (email, password, name, lastname, role)
            VALUES (?, ?, ?, ?, ?)
        ');

            // Pobierz dane z obiektu User
            $email = $user->getEmail();
            $password = $user->getPassword();
            $name = $user->getName();
            $lastname = $user->getLastname();
            $role = $user->getRole(); // Upewnij się, że metoda getRole istnieje w klasie User i zwraca odpowiednią rolę (np. 'user')

            $stmt->execute([
                $email,
                $password,
                $name,
                $lastname,
                $role
            ]);
        } catch (PDOException $e) {
            // Jeśli coś pójdzie nie tak, możesz tutaj obsłużyć wyjątek
            die("Błąd podczas dodawania użytkownika do bazy danych: " . $e->getMessage());
        }
    }

}




<?php

class AppController
{
    private $request;
    protected $loggedInUser = null;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];

        // Sprawdzanie czy użytkownik jest zalogowany
     //   if (isset($_SESSION['user'])) {
       //     $email = $_SESSION['user']['email'];
         //   $password = ''; // Haseł nie przechowujemy w sesji ze względów bezpieczeństwa
         //   $name = $_SESSION['user']['name'];
         //   $lastname = $_SESSION['user']['lastname'];
         //   $role = $_SESSION['user']['role'];

            // Utworzenie obiektu użytkownika na podstawie danych z sesji
         //   $this->loggedInUser = new User($email, $password, $name, $lastname, $role);
      //  }
    }

    protected function isGet(): bool
    {
        return $this->request === 'GET';
    }

    protected function isPost(): bool
    {
        return $this->request === 'POST';
    }

    protected function render(string $template = null, array $variables = [])
    {
       // $variables['loggedInUser'] = $this->loggedInUser; // Dodaj zalogowanego użytkownika do zmiennych przekazywanych do widoku

        $templatePath = 'public/views/'.$template.'.php';
        $output = 'File cannot be found';

        if(file_exists($templatePath))
        {
            extract($variables);
            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }

        print $output;
    }

    protected function isLoggedIn(): bool
    {
        if (isset($_SESSION['user']))
        {
            return true;
        }
        else
        {
            $this->render('login', ['messages' => ['Aby zobaczyć zawartość strony, musisz się zalogować!']]);
            return false;
        }
    }

    protected function isAdmin(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    protected function isUser(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user';
    }
}
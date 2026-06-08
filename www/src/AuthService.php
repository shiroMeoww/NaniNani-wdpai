<?php

declare(strict_types=1);

class AuthService
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function authenticate(string $email, string $password): ?string
    {
        $user = $this->users->findByEmail($email);
        if ($user === null) {
            return null;
        }

        if (password_verify($password, $user['password'])) {
            return $user['uid'];
        }

        if ($user['password'] === $password) {
            return $user['uid'];
        }

        return null;
    }

    public function register(string $name, string $surname, string $email, string $password): bool
    {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        try {
            return $this->users->createStudentUser($name, $surname, $email, $hashed);
        } catch (PDOException) {
            return false;
        }
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}

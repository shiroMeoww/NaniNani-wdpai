<?php

declare(strict_types=1);

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findByEmail(string $email): ?array
    {
        $query = $this->pdo->prepare('select uid, password from "user" where email = ?');
        $query->execute([$email]);
        $result = $query->fetch();
        return $result === false ? null : $result;
    }

    public function findByUid(string $uid): ?array
    {
        $query = $this->pdo->prepare('select * from "user" where uid = ?');
        $query->execute([$uid]);
        $result = $query->fetch();
        return $result === false ? null : $result;
    }

    public function createStudentUser(string $name, string $surname, string $email, string $password): bool
    {
        $query = $this->pdo->prepare('insert into "user" (name, surname, email, password) values (?, ?, ?, ?)');
        $query->execute([$name, $surname, $email, $password]);

        $query = $this->pdo->prepare('select uid from "user" where email = ?');
        $query->execute([$email]);
        $result = $query->fetch();
        if ($result === false) {
            return false;
        }

        $query = $this->pdo->prepare('insert into "student" ("userUid") values (?)');
        $query->execute([$result['uid']]);
        return true;
    }

    public function updateDescription(string $uid, ?string $description): void
    {
        $query = $this->pdo->prepare('update "user" set description = ? where uid = ?');
        $query->execute([$description, $uid]);
    }
}

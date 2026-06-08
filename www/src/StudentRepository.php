<?php

declare(strict_types=1);

class StudentRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getStudentId(string $userUid): ?int
    {
        if ($userUid === '') {
            return null;
        }

        $query = $this->pdo->prepare('select id from "student" where "userUid" = ?');
        $query->execute([$userUid]);
        $result = $query->fetch();
        return $result === false ? null : (int) $result['id'];
    }

    public function getTeacherId(string $userUid): ?int
    {
        if ($userUid === '') {
            return null;
        }

        $query = $this->pdo->prepare('select id from "teacher" where "userUid" = ?');
        $query->execute([$userUid]);
        $result = $query->fetch();
        return $result === false ? null : (int) $result['id'];
    }
}

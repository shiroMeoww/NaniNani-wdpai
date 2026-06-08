<?php

declare(strict_types=1);

class TeacherRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getAllTeachers(): array
    {
        $query = $this->pdo->prepare(
            "select t.id, u.name || ' ' || u.surname as name from \"teacher\" t join \"user\" u on t.\"userUid\" = u.uid order by u.name, u.surname"
        );
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exists(int $teacherId): bool
    {
        $query = $this->pdo->prepare('select 1 from "teacher" where id = ?');
        $query->execute([$teacherId]);
        return $query->fetch() !== false;
    }

    public function getTeacherName(int $teacherId): ?string
    {
        $query = $this->pdo->prepare(
            "select u.name || ' ' || u.surname as name from \"teacher\" t join \"user\" u on t.\"userUid\" = u.uid where t.id = ?"
        );
        $query->execute([$teacherId]);
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['name'] : null;
    }

    public function isTeacher(string $userUid): bool
    {
        if ($userUid === '') {
            return false;
        }
        $query = $this->pdo->prepare('select 1 from "teacher" where "userUid" = ?');
        $query->execute([$userUid]);
        return $query->fetch() !== false;
    }
}
<?php

declare(strict_types=1);

class NoteRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function addNote(int $studentId, string $content): void
    {
        $query = $this->pdo->prepare('insert into "note" ("studentId", "content") values (?, ?)');
        $query->execute([$studentId, $content]);
    }

    public function getNotes(int $studentId): array
    {
        $query = $this->pdo->prepare('select content from "note" where "studentId" = ? order by id desc');
        $query->execute([$studentId]);
        return $query->fetchAll();
    }
}

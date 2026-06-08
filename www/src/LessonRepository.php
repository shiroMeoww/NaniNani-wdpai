<?php

declare(strict_types=1);

class LessonRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getLessonsForStudent(int $studentId): array
    {
        $query = $this->pdo->prepare(
            "select l.uid, l.datetime, l.duration, lt.name, u.name || ' ' || u.surname as teacherName from \"lesson\" l "
            . "join \"lessonType\" lt on l.type = lt.id "
            . "join \"teacher\" t on l.\"teacherId\" = t.id "
            . "join \"user\" u on t.\"userUid\" = u.uid "
            . "where l.\"studentId\" = ?"
        );
        $query->execute([$studentId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLessonsForTeacher(int $teacherId): array
    {
        $query = $this->pdo->prepare(
            "select l.uid, l.datetime, l.duration, lt.name, u.name || ' ' || u.surname as studentName from \"lesson\" l "
            . "join \"lessonType\" lt on l.type = lt.id "
            . "join \"student\" s on l.\"studentId\" = s.id "
            . "join \"user\" u on s.\"userUid\" = u.uid "
            . "where l.\"teacherId\" = ?"
        );
        $query->execute([$teacherId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function teacherHasLessonAt(int $teacherId, string $datetime): bool
    {
        $query = $this->pdo->prepare('select 1 from "lesson" where "teacherId" = ? and datetime = ? limit 1');
        $query->execute([$teacherId, $datetime]);
        return $query->fetch() !== false;
    }

    public function studentHasLessonAt(int $studentId, string $datetime): bool
    {
        $query = $this->pdo->prepare('select 1 from "lesson" where "studentId" = ? and datetime = ? limit 1');
        $query->execute([$studentId, $datetime]);
        return $query->fetch() !== false;
    }

    public function createLesson(int $studentId, int $teacherId, string $datetime, int $duration = 60, int $lessonTypeId = 2): string
    {
        $uid = $this->generateUuid();
        $query = $this->pdo->prepare('insert into "lesson" (uid, datetime, duration, type, "studentId", "teacherId") values (?, ?, ?, ?, ?, ?)');
        $query->execute([$uid, $datetime, $duration, $lessonTypeId, $studentId, $teacherId]);
        return $uid;
    }

    private function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function deleteLesson(string $uid): void
    {
        $query = $this->pdo->prepare('delete from "lesson" where uid = ?');
        $query->execute([$uid]);
    }
}

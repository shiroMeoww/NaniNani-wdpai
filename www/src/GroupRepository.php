<?php

declare(strict_types=1);

class GroupRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getAllGroups(): array
    {
        $query = $this->pdo->query('select * from "group"');
        return $query->fetchAll();
    }

    public function getMemberCount(string $groupUid): int
    {
        $query = $this->pdo->prepare('select count(1) as count from "studentGroup" where "groupUid" = ?');
        $query->execute([$groupUid]);
        $result = $query->fetch();
        return $result === false ? 0 : (int) $result['count'];
    }

    public function getMemberNames(string $groupUid): array
    {
        $query = $this->pdo->prepare(
            'select u.name, u.surname from "studentGroup" sg '
            . 'join "student" s on s.id = sg."studentId" '
            . 'join "user" u on u.uid = s."userUid" '
            . 'where sg."groupUid" = ? '
            . 'order by u.name, u.surname'
        );
        $query->execute([$groupUid]);

        $names = [];
        while ($row = $query->fetch()) {
            $names[] = trim($row['name'] . ' ' . $row['surname']);
        }

        return $names;
    }

    public function isMember(int $studentId, string $groupUid): bool
    {
        $query = $this->pdo->prepare('select count(1) as count from "studentGroup" where "groupUid" = ? and "studentId" = ?');
        $query->execute([$groupUid, $studentId]);
        $result = $query->fetch();
        return $result !== false && (int) $result['count'] > 0;
    }

    public function joinGroup(int $studentId, string $groupUid): void
    {
        $query = $this->pdo->prepare('insert into "studentGroup" ("studentId", "groupUid") values (?, ?)');
        try {
            $query->execute([$studentId, $groupUid]);
        } catch (PDOException) {
            // ignore duplicate membership
        }
    }

    public function leaveGroup(int $studentId, string $groupUid): void
    {
        $query = $this->pdo->prepare('delete from "studentGroup" where "studentId" = ? and "groupUid" = ?');
        $query->execute([$studentId, $groupUid]);
    }
}

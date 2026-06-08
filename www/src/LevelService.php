<?php

declare(strict_types=1);

class LevelService
{
    private array $allowedPages = [
        '1', '2', '3', '4', '5',
        'desu-masu',
    ];

    public function getPagePath(string $page): ?string
    {
        if (in_array($page, ['1', '2', '3', '4', '5'], true)) {
            return __DIR__ . '/../levels/n' . $page . '.php';
        }

        if (in_array($page, ['desu-masu'], true)) {
            return __DIR__ . '/../levels/' . $page . '.php';
        }

        return null;
    }

    public function isValidPage(string $page): bool
    {
        return in_array($page, $this->allowedPages, true);
    }
}

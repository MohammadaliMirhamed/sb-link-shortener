<?php

declare(strict_types=1);

namespace App\Domain\Link;

interface LinkRepository
{
    /**
     * @param string $link
     * @return string
     */
    public function create(string $link): string;

    /**
     * @return array
     */
    public function list(): array;

    /**
     * @param int $id
     * @throws LinkException
     */
    public function view(int $id): array;

    /**
     * @param int $id
     * return void
     */
    public function delete(int $id): void;

    /**
     * @param int $id
     * @param string $link
     * @return void
     */
    public function update(int $id, string $string): string;

}

<?php

namespace App\DAL\Interfaces;

interface BaseRepositoryInterface
{
    public function startTransaction(): void;

    public function endTransaction(bool $success = true): void;

    public function reset(): void;
}

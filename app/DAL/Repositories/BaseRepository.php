<?php

namespace App\DAL\Repositories;

use App\DAL\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements BaseRepositoryInterface
{
    private static ?BaseRepositoryInterface $instance = null;
    protected Model $model;

    public abstract function model(): string;

    public function __construct()
    {
        $this->setup();
    }

    public static function getInstance(): BaseRepositoryInterface
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    protected function setup(): void
    {
        $this->setModel(app()->make($this->model()));
    }

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function reset(): void
    {
        $this->setup();
    }

    public function startTransaction(): void
    {
        DB::beginTransaction();
    }

    public function endTransaction(bool $success = true): void
    {
        if ($success) {
            DB::commit();
            return;
        }

        DB::rollBack();
    }
}

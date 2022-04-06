<?php

namespace App\Repository;

use Illuminate\Support\Str;

/**
 * Class BaseRepository
 * @package App\repository
 */
abstract class BaseRepository
{
    protected $modelNamespace = 'App\\Models\\';

    /**
     * @var string
     */
    public $sortBy = 'created_at';
    /**
     * @var string
     */
    public $sortOrder = 'asc';

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    public function getModel()
    {
        $repositoryClassName = (new \ReflectionClass($this))->getShortName();

        $modelRepositoryClassName = $this->modelNamespace . Str::replaceLast('Repository', '', $repositoryClassName);

        if (!class_exists($modelRepositoryClassName)) {
            throw new \RuntimeException("Class {$modelRepositoryClassName} does not exists.");
        }

        return new $modelRepositoryClassName;
    }

    /**
     * @param array $conditions
     * @return mixed
     * @throws \ReflectionException
     */
    public function all(array $conditions = [])
    {
        return $this->getModel()->query()
            ->where($conditions)
            ->orderBy(
                $params['sortBy'] ?? $this->sortBy,
                $params['sortOrder'] ?? $this->sortOrder
            )
            ->paginate(
                $params['length'] ?? config('paginate.size'),
                ['*'],
                'page',
                $params['page'] ?? 1
            );
    }

    /**
     * @param array $input
     * @return mixed
     * @throws \ReflectionException
     */
    public function create(array $input = [])
    {
        $model = $this->getModel();
        $model->fill($input);
        $model->save();

        return $model;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \ReflectionException
     */
    public function find(int $id)
    {
        return $this->getModel()->where('id', $id)->first();
    }

    /**
     * @param int $id
     * @param array $input
     * @return mixed
     * @throws \ReflectionException
     */
    public function update(int $id, array $input)
    {
        $model = $this->find($id);
        $model->fill($input);
        $model->save();

        return $model;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \ReflectionException
     */
    public function destroy(int $id)
    {
        return $this->find($id)->delete();
    }
}

<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(array $columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * @param int|string $id
     * @param array $columns
     * @param array $relations
     * @return Model|null
     */
    public function find($id, array $columns = ['*'], array $relations = [])
    {
        return $this->model->with($relations)->find($id, $columns);
    }

    public function findByIdWithTrashed($id, array $relations = [])
    {
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->model))) {
            return $this->model->withTrashed()->with($relations)->find($id);
        }
        return $this->find($id, ['*'], $relations);
    }

    /**
     * @param array $payload
     * @return Model|null
     */
    public function create(array $payload)
    {
        $model = $this->model->create($payload);

        return $model->fresh();
    }

    /**
     * @param int|string $id
     * @param array $payload
     * @return bool
     */
    public function update($id, array $payload)
    {
        $model = $this->find($id);

        if (!$model) {
            return false;
        }

        $model->update($payload);
        return $model->fresh();
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->find($id);

        if (!$model) {
            return false;
        }

        return $model->delete();
    }
}

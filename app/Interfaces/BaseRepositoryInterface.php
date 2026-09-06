<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    /**
     * Get all models.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(array $columns = ['*']);

    /**
     * Get a model by its primary key.
     *
     * @param int|string $id
     * @param array $columns
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find($id, array $columns = ['*'], array $relations = []);

    /**
     * Get a model with trashed by its primary key.
     *
     * @param int|string $id
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByIdWithTrashed($id, array $relations = []);

    /**
     * Create a new model.
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function create(array $payload);

    /**
     * Update an existing model.
     *
     * @param int|string $id
     * @param array $payload
     * @return bool
     */
    public function update($id, array $payload);

    /**
     * Delete a model.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id);
}

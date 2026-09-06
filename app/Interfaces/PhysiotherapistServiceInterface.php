<?php

namespace App\Interfaces;

interface PhysiotherapistServiceInterface
{
    public function getPaginatedPhysiotherapists(array $filters = [], int $perPage = 15);
    public function getPhysiotherapistById(string $id);
    public function createPhysiotherapist(array $data);
    public function updatePhysiotherapist(string $id, array $data);
    public function deletePhysiotherapist(string $id);
    public function restorePhysiotherapist(string $id);
}

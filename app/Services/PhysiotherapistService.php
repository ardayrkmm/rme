<?php

namespace App\Services;

use App\Interfaces\PhysiotherapistServiceInterface;
use App\Interfaces\PhysiotherapistRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Exception;

class PhysiotherapistService implements PhysiotherapistServiceInterface
{
    protected $physiotherapistRepository;

    public function __construct(PhysiotherapistRepositoryInterface $physiotherapistRepository)
    {
        $this->physiotherapistRepository = $physiotherapistRepository;
    }

    public function getPaginatedPhysiotherapists(array $filters = [], int $perPage = 15)
    {
        return $this->physiotherapistRepository->getPaginated($filters, $perPage);
    }

    public function exportCsv(array $filters = [])
    {
        $physiotherapists = $this->physiotherapistRepository->getAllForExport($filters);

        $csvData = [];
        $headers = ['Nama', 'SIP', 'Spesialisasi', 'No. Telepon', 'Status', 'Terdaftar Pada'];

        foreach ($physiotherapists as $physio) {
            $csvData[] = [
                $physio->name,
                $physio->sip,
                $physio->specialization ?? '-',
                $physio->phone,
                $physio->status,
                $physio->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return [
            'headers' => $headers,
            'data' => $csvData
        ];
    }

    public function getPhysiotherapistById(string $id)
    {
        $physio = $this->physiotherapistRepository->findByIdWithTrashed($id);

        if (!$physio) {
            throw new Exception('Fisioterapis tidak ditemukan', 404);
        }

        return $physio;
    }

    public function createPhysiotherapist(array $data)
    {
        if (isset($data['photo'])) {
            $data['photo'] = $data['photo']->store('physiotherapists/photos', 'public');
        }

        return $this->physiotherapistRepository->create($data);
    }

    public function updatePhysiotherapist(string $id, array $data)
    {
        $physiotherapist = $this->physiotherapistRepository->find($id);

        if (!$physiotherapist) {
            throw new Exception('Fisioterapis tidak ditemukan', 404);
        }

        if (isset($data['photo'])) {
            if ($physiotherapist->photo) {
                Storage::disk('public')->delete($physiotherapist->photo);
            }
            $data['photo'] = $data['photo']->store('physiotherapists/photos', 'public');
        }

        return $this->physiotherapistRepository->update($id, $data);
    }

    public function deletePhysiotherapist(string $id)
    {
        return $this->physiotherapistRepository->delete($id);
    }

    public function restorePhysiotherapist(string $id)
    {
        return $this->physiotherapistRepository->restore($id);
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisModel extends Model
{
    protected $table            = 'frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['minimum', 'maximum', 'valeur', 'typeTransactionId'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getFraisPourMontant(int $typeTransactionId, float $montant): ?array
    {
        return $this->where('typeTransactionId', $typeTransactionId)
            ->where('minimum <=', $montant)
            ->where('maximum >=', $montant)
            ->first();
    }

    public function getAllWithType(): array
    {
        return $this->select('frais.*, typeTransaction.nom as typeNom')
            ->join('typeTransaction', 'typeTransaction.id = frais.typeTransactionId')
            ->orderBy('typeTransaction.nom', 'ASC')
            ->orderBy('frais.minimum', 'ASC')
            ->findAll();
    }

    public function hasOverlap(int $typeTransactionId, float $minimum, float $maximum, ?int $excludeId = null): bool
    {
        $builder = $this->where('typeTransactionId', $typeTransactionId)
            ->where('minimum <=', $maximum)
            ->where('maximum >=', $minimum);

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }
}

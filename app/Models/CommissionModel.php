<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionModel extends Model
{
    protected $table            = 'commission';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['source', 'destinataire', 'pourcentage'];

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

    /**
     * Retourne le pourcentage de commission pour un transfert
     * entre l'operateur source et l'operateur destinataire.
     */
    public function getCommission(int $sourceOperateurId, int $destinataireOperateurId): ?float
    {
        $row = $this->where('source', $sourceOperateurId)
            ->where('destinataire', $destinataireOperateurId)
            ->first();
        return $row ? (float) $row['pourcentage'] : null;
    }

    /**
     * Retourne toutes les commissions avec les labels des operateurs.
     */
    public function getAllWithOperateurs(): array
    {
        return $this->select('commission.*, src.labelle as sourceLabelle, dst.labelle as destinataireLabelle')
            ->join('operateur src', 'src.id = commission.source', 'left')
            ->join('operateur dst', 'dst.id = commission.destinataire', 'left')
            ->orderBy('src.labelle, dst.labelle', 'ASC')
            ->findAll();
    }
}
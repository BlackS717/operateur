<?php

namespace App\Models;

use CodeIgniter\Model;

class PorteFeuilleModel extends Model
{
    protected $table            = 'porteFeuille';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['utilisateurId', 'solde'];

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

    public function getByUtilisateurId(int $utilisateurId): ?array
    {
        return $this->where('utilisateurId', $utilisateurId)->first();
    }

    public function crediter(int $utilisateurId, float $montant): bool
    {
        $portefeuille = $this->getByUtilisateurId($utilisateurId);
        if ($portefeuille === null) {
            return false;
        }
        return $this->update($portefeuille['id'], [
            'solde' => $portefeuille['solde'] + $montant,
        ]);
    }

    public function debiter(int $utilisateurId, float $montant): bool
    {
        $portefeuille = $this->getByUtilisateurId($utilisateurId);
        if ($portefeuille === null || $portefeuille['solde'] < $montant) {
            return false;
        }
        return $this->update($portefeuille['id'], [
            'solde' => $portefeuille['solde'] - $montant,
        ]);
    }
}

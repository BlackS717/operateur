<?php

namespace App\Models;

use CodeIgniter\Model;


class UtilisateurModel extends Model
{
    protected $table            = 'utilisateur';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['numero'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];


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

    public function getByNumero(string $numero): ?array
    {
        return $this->where('numero', $numero)->first();
    }

    public function register(string $numero): ?array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $id = $this->insert([
            'numero' => $numero
        ], true);

        $prefixModel = new PrefixModel();
        $prefix = $prefixModel->getByNumero($numero);

        $porteFeuilleModel = new PorteFeuilleModel();
        $porteFeuilleModel->insert([
            'utilisateurId' => $id,
            'solde' => 0,
            'operateurId' => $prefix ? $prefix['operateurId'] : null,
        ]);

        $defaultMontant = 0;
        $defaultPercentage = 0;

        $epargneModel = new EpargneModel();
        $compteEpargneModel = new CompteEpargneModel();

        $epargneModel->insert([
            'utilisateurId' => $id,
            'pourcentage' => $defaultPercentage,
        ]);

        $compteEpargneModel->insert([
            'utilisateurId' => $id,
            'montant' => $defaultMontant,
        ]);


        $db->transComplete();

        return $this->find($id);
    }
}

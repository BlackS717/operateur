<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['utilisateurId', 'destinataireId', 'typeTransactionId', 'montant', 'frais'];



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

    public function getHistorique(int $utilisateurId): array
    {
        return $this->select('transactions.*, typeTransaction.nom as typeNom')
            ->join('typeTransaction', 'typeTransaction.id = transactions.typeTransactionId')
            ->groupStart()
                ->where('transactions.utilisateurId', $utilisateurId)
                ->orWhere('transactions.destinataireId', $utilisateurId)
            ->groupEnd()
            ->orderBy('transactions.dateTransaction', 'DESC')
            ->findAll();
    }

    public function getTotalFraisParType(): array
    {
        return $this->select('typeTransaction.nom as typeNom, SUM(transactions.frais) as total, COUNT(transactions.id) as nombre')
            ->join('typeTransaction', 'typeTransaction.id = transactions.typeTransactionId')
            ->groupBy('transactions.typeTransactionId')
            ->findAll();
    }
}

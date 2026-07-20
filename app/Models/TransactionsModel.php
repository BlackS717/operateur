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

    /**
     * Frais par type de transaction (global).
     */
    public function getTotalFraisParType(): array
    {
        return $this->select('typeTransaction.nom as typeNom, SUM(transactions.frais) as total, COUNT(transactions.id) as nombre')
            ->join('typeTransaction', 'typeTransaction.id = transactions.typeTransactionId')
            ->groupBy('transactions.typeTransactionId')
            ->findAll();
    }

    /**
     * Frais par type de transaction, séparés entre transactions
     * intra-operateur (même operateur) et inter-operateurs (operateurs differents).
     */
    public function getFraisParTypeAvecSeparation(): array
    {
        $sql = "
            SELECT
                t.typeTransactionId,
                tp.nom as typeNom,
                CASE WHEN pfSource.operateurId = pfDest.operateurId THEN 'intra' ELSE 'inter' END as categorie,
                SUM(t.frais) as total,
                COUNT(t.id) as nombre
            FROM transactions t
            JOIN typeTransaction tp ON tp.id = t.typeTransactionId
            LEFT JOIN porteFeuille pfSource ON pfSource.utilisateurId = t.utilisateurId
            LEFT JOIN porteFeuille pfDest ON pfDest.utilisateurId = t.destinataireId
            GROUP BY t.typeTransactionId, categorie
            ORDER BY tp.nom, categorie
        ";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    /**
     * Calcule les commissions inter-operateurs dues à chaque operateur
     * pour les transferts. Retourne un tableau avec les colonnes :
     * sourceOperateurId, sourceLabelle, destinataireOperateurId, destinataireLabelle, montantCommission
     */
    public function getCommissionsDues(): array
    {
        $sql = "
            SELECT
                pfSource.operateurId as sourceOperateurId,
                src.labelle as sourceLabelle,
                pfDest.operateurId as destinataireOperateurId,
                dst.labelle as destinataireLabelle,
                SUM(t.montant * c.pourcentage / 100.0) as montantCommission,
                COUNT(t.id) as nombreTransferts
            FROM transactions t
            JOIN typeTransaction tp ON tp.id = t.typeTransactionId AND tp.nom = 'Transfert'
            LEFT JOIN porteFeuille pfSource ON pfSource.utilisateurId = t.utilisateurId
            LEFT JOIN porteFeuille pfDest ON pfDest.utilisateurId = t.destinataireId
            LEFT JOIN operateur src ON src.id = pfSource.operateurId
            LEFT JOIN operateur dst ON dst.id = pfDest.operateurId
            LEFT JOIN commission c ON c.source = pfSource.operateurId AND c.destinataire = pfDest.operateurId
            WHERE pfSource.operateurId IS NOT NULL
              AND pfDest.operateurId IS NOT NULL
              AND pfSource.operateurId != pfDest.operateurId
            GROUP BY pfSource.operateurId, pfDest.operateurId
            ORDER BY src.labelle, dst.labelle
        ";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }
}
<?php

namespace App\Services;

use App\Models\PrefixModel;
use App\Models\TypeTransactionModel;
use App\Models\FraisModel;
use App\Models\TransactionsModel;
use App\Models\UtilisateurModel;
use App\Models\PorteFeuilleModel;
use App\Models\OperateurModel;
use App\Models\CommissionModel;
use App\Models\PromotionModel;

class OperateurService
{
    private $prefixModel;
    private $typeTransactionModel;
    private $fraisModel;
    private $transactionsModel;
    private $utilisateurModel;
    private $porteFeuilleModel;
    private $operateurModel;
    private $commissionModel;

    private $promotionModel;
    public function __construct()
    {
        $this->prefixModel = new PrefixModel();
        $this->typeTransactionModel = new TypeTransactionModel();
        $this->fraisModel = new FraisModel();
        $this->transactionsModel = new TransactionsModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->porteFeuilleModel = new PorteFeuilleModel();
        $this->operateurModel = new OperateurModel();
        $this->commissionModel = new CommissionModel();
        $this->promotionModel = new PromotionModel();
    }

    // Authentification operateur (admin)

    public function authenticate(string $labelle, string $motDePasse): ?array
    {
        $operateur = $this->operateurModel->getByLabelle($labelle);
        if (!$operateur || !password_verify($motDePasse, $operateur['motDePasse'])) {
            return null;
        }
        unset($operateur['motDePasse']);
        return $operateur;
    }

    // Operateurs

    public function getAllOperateurs(): array
    {
        return $this->operateurModel->orderBy('labelle', 'ASC')->findAll();
    }

    // Prefixes

    public function getAllPrefixes(): array
    {
        return $this->prefixModel
            ->select('prefix.*, operateur.labelle as operateurLabelle')
            ->join('operateur', 'operateur.id = prefix.operateurId', 'left')
            ->orderBy('nom', 'ASC')
            ->findAll();
    }

    public function getPrefixById(int $id): ?array
    {
        return $this->prefixModel->find($id);
    }

    /** @return array{success: bool, message: string} */
    public function addPrefix(string $nom, int $operateurId): array
    {
        if ($this->prefixModel->where('nom', $nom)->first()) {
            return ['success' => false, 'message' => 'Ce prefixe existe deja.'];
        }
        if (!$this->operateurModel->find($operateurId)) {
            return ['success' => false, 'message' => "L'operateur selectionne est introuvable."];
        }
        $this->prefixModel->insert(['nom' => $nom, 'operateurId' => $operateurId]);
        return ['success' => true, 'message' => 'Prefixe ajoute.'];
    }

    public function deletePrefix(int $id): bool
    {
        return $this->prefixModel->delete($id);
    }

    // Types de transaction

    public function getAllTypes(): array
    {
        return $this->typeTransactionModel->orderBy('nom', 'ASC')->findAll();
    }

    /** @return array{success: bool, message: string} */
    public function addType(string $nom): array
    {
        if ($this->typeTransactionModel->where('nom', $nom)->first()) {
            return ['success' => false, 'message' => 'Ce type existe deja.'];
        }
        $this->typeTransactionModel->insert(['nom' => $nom]);
        return ['success' => true, 'message' => "Type d'operation ajoute."];
    }

    // Baremes de frais

    public function getAllFrais(): array
    {
        return $this->fraisModel->getAllWithType();
    }

    public function getFraisById(int $id): ?array
    {
        return $this->fraisModel->find($id);
    }

    /** @return array{success: bool, message: string} */
    public function addFrais(int $typeTransactionId, float $minimum, float $maximum, float $valeur): array
    {
        if (!$this->typeTransactionModel->find($typeTransactionId)) {
            return ['success' => false, 'message' => "Type d'operation introuvable."];
        }
        if ($minimum < 0 || $valeur < 0) {
            return ['success' => false, 'message' => 'Les montants ne peuvent pas etre negatifs.'];
        }
        if ($maximum <= $minimum) {
            return ['success' => false, 'message' => 'Le montant maximum doit etre superieur au montant minimum.'];
        }
        if ($this->fraisModel->hasOverlap($typeTransactionId, $minimum, $maximum)) {
            return ['success' => false, 'message' => 'Cette tranche chevauche un bareme deja existant pour ce type.'];
        }

        $this->fraisModel->insert([
            'typeTransactionId' => $typeTransactionId,
            'minimum' => $minimum,
            'maximum' => $maximum,
            'valeur' => $valeur,
        ]);
        return ['success' => true, 'message' => 'Bareme ajoute.'];
    }

    /** @return array{success: bool, message: string} */
    public function updateFrais(int $id, int $typeTransactionId, float $minimum, float $maximum, float $valeur): array
    {
        if (!$this->fraisModel->find($id)) {
            return ['success' => false, 'message' => 'Bareme introuvable.'];
        }
        if (!$this->typeTransactionModel->find($typeTransactionId)) {
            return ['success' => false, 'message' => "Type d'operation introuvable."];
        }
        if ($minimum < 0 || $valeur < 0) {
            return ['success' => false, 'message' => 'Les montants ne peuvent pas etre negatifs.'];
        }
        if ($maximum <= $minimum) {
            return ['success' => false, 'message' => 'Le montant maximum doit etre superieur au montant minimum.'];
        }
        if ($this->fraisModel->hasOverlap($typeTransactionId, $minimum, $maximum, $id)) {
            return ['success' => false, 'message' => 'Cette tranche chevauche un bareme deja existant pour ce type.'];
        }

        $this->fraisModel->update($id, [
            'typeTransactionId' => $typeTransactionId,
            'minimum' => $minimum,
            'maximum' => $maximum,
            'valeur' => $valeur,
        ]);
        return ['success' => true, 'message' => 'Bareme modifie.'];
    }

    public function deleteFrais(int $id): bool
    {
        return $this->fraisModel->delete($id);
    }

    // Situation des gains (frais percus sur les retraits et transferts)

    public function getSituationGains(): array
    {
        $fraisSepares = $this->transactionsModel->getFraisParTypeAvecSeparation();
        $commissionsDues = $this->transactionsModel->getCommissionsDues();

        // Structurer les frais par type avec séparation intra/inter
        $fraisIntra = [];
        $fraisInter = [];
        $totalIntra = 0.0;
        $totalInter = 0.0;

        foreach ($fraisSepares as $ligne) {
            $item = [
                'typeNom' => $ligne['typeNom'],
                'nombre' => (int) $ligne['nombre'],
                'total' => (float) $ligne['total'],
            ];
            if ($ligne['categorie'] === 'intra') {
                $fraisIntra[] = $item;
                $totalIntra += $item['total'];
            } else {
                $fraisInter[] = $item;
                $totalInter += $item['total'];
            }
        }

        // Total des commissions dues aux operateurs destinataires
        $totalCommissions = 0.0;
        foreach ($commissionsDues as $c) {
            $totalCommissions += (float) $c['montantCommission'];
        }

        return [
            'fraisIntra' => $fraisIntra,
            'fraisInter' => $fraisInter,
            'totalIntra' => $totalIntra,
            'totalInter' => $totalInter,
            'totalGeneral' => $totalIntra + $totalInter,
            'commissionsDues' => $commissionsDues,
            'totalCommissions' => $totalCommissions,
        ];
    }

    // Statistiques pour les graphiques

    public function getStatsData(): array
    {
        // Transactions par type (pour bar chart)
        $parType = $this->transactionsModel->getTotalFraisParType();

        // Frais intra/inter separes (pour pie chart des frais)
        $fraisSepares = $this->transactionsModel->getFraisParTypeAvecSeparation();

        // Commissions dues (pour bar chart des commissions)
        $commissionsDues = $this->transactionsModel->getCommissionsDues();

        // Stats clients par operateur
        $clientsParOperateur = $this->utilisateurModel
            ->select('operateur.labelle, COUNT(utilisateur.id) as total, SUM(porteFeuille.solde) as soldeTotal')
            ->join('porteFeuille', 'porteFeuille.utilisateurId = utilisateur.id', 'left')
            ->join('operateur', 'operateur.id = porteFeuille.operateurId', 'left')
            ->groupBy('operateur.id')
            ->orderBy('total', 'DESC')
            ->findAll();

        // Total frais intra
        $totalIntra = 0.0;
        $totalInter = 0.0;
        foreach ($fraisSepares as $l) {
            if ($l['categorie'] === 'intra') {
                $totalIntra += (float) $l['total'];
            } else {
                $totalInter += (float) $l['total'];
            }
        }

        // Total commissions
        $totalCommissions = 0.0;
        foreach ($commissionsDues as $c) {
            $totalCommissions += (float) $c['montantCommission'];
        }

        return [
            'parType' => $parType,
            'totalIntra' => $totalIntra,
            'totalInter' => $totalInter,
            'totalGeneral' => $totalIntra + $totalInter,
            'commissionsDues' => $commissionsDues,
            'totalCommissions' => $totalCommissions,
            'clientsParOperateur' => $clientsParOperateur,
        ];
    }

    // Situation des comptes clients

    public function getSituationComptesClients(): array
    {
        return $this->utilisateurModel
            ->select('utilisateur.id, utilisateur.numero, utilisateur.dateCreation, porteFeuille.solde, operateur.labelle as operateurLabelle')
            ->join('porteFeuille', 'porteFeuille.utilisateurId = utilisateur.id', 'left')
            ->join('operateur', 'operateur.id = porteFeuille.operateurId', 'left')
            ->orderBy('utilisateur.dateCreation', 'DESC')
            ->findAll();
    }

    // Commissions inter-operateurs

    public function getAllCommissions(): array
    {
        return $this->commissionModel->getAllWithOperateurs();
    }

    public function getCommissionById(int $id): ?array
    {
        return $this->commissionModel->find($id);
    }

    /** @return array{success: bool, message: string} */
    public function addCommission(int $source, int $destinataire, float $pourcentage): array
    {
        if ($source === $destinataire) {
            return ['success' => false, 'message' => 'La source et le destinataire doivent etre differents.'];
        }
        if (!$this->operateurModel->find($source)) {
            return ['success' => false, 'message' => 'Operateur source introuvable.'];
        }
        if (!$this->operateurModel->find($destinataire)) {
            return ['success' => false, 'message' => 'Operateur destinataire introuvable.'];
        }
        if ($pourcentage < 0 || $pourcentage > 100) {
            return ['success' => false, 'message' => 'Le pourcentage doit etre compris entre 0 et 100.'];
        }
        if ($this->commissionModel->where('source', $source)->where('destinataire', $destinataire)->first()) {
            return ['success' => false, 'message' => 'Cette commission existe deja.'];
        }

        $this->commissionModel->insert([
            'source' => $source,
            'destinataire' => $destinataire,
            'pourcentage' => $pourcentage,
        ]);
        return ['success' => true, 'message' => 'Commission ajoutee.'];
    }

    /** @return array{success: bool, message: string} */
    public function updateCommission(int $id, int $source, int $destinataire, float $pourcentage): array
    {
        if (!$this->commissionModel->find($id)) {
            return ['success' => false, 'message' => 'Commission introuvable.'];
        }
        if ($source === $destinataire) {
            return ['success' => false, 'message' => 'La source et le destinataire doivent etre differents.'];
        }
        if (!$this->operateurModel->find($source)) {
            return ['success' => false, 'message' => 'Operateur source introuvable.'];
        }
        if (!$this->operateurModel->find($destinataire)) {
            return ['success' => false, 'message' => 'Operateur destinataire introuvable.'];
        }
        if ($pourcentage < 0 || $pourcentage > 100) {
            return ['success' => false, 'message' => 'Le pourcentage doit etre compris entre 0 et 100.'];
        }

        $existing = $this->commissionModel->where('source', $source)->where('destinataire', $destinataire)->first();
        if ($existing && (int) $existing['id'] !== $id) {
            return ['success' => false, 'message' => 'Cette commission existe deja.'];
        }

        $this->commissionModel->update($id, [
            'source' => $source,
            'destinataire' => $destinataire,
            'pourcentage' => $pourcentage,
        ]);
        return ['success' => true, 'message' => 'Commission modifiee.'];
    }

    public function deleteCommission(int $id): bool
    {
        return $this->commissionModel->delete($id);
    }
}

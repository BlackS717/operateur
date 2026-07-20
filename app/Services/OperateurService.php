<?php

namespace App\Services;

use App\Models\PrefixModel;
use App\Models\TypeTransactionModel;
use App\Models\FraisModel;
use App\Models\TransactionsModel;
use App\Models\UtilisateurModel;
use App\Models\PorteFeuilleModel;
use App\Models\OperateurModel;

class OperateurService
{
    private $prefixModel;
    private $typeTransactionModel;
    private $fraisModel;
    private $transactionsModel;
    private $utilisateurModel;
    private $porteFeuilleModel;
    private $operateurModel;

    public function __construct()
    {
        $this->prefixModel = new PrefixModel();
        $this->typeTransactionModel = new TypeTransactionModel();
        $this->fraisModel = new FraisModel();
        $this->transactionsModel = new TransactionsModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->porteFeuilleModel = new PorteFeuilleModel();
        $this->operateurModel = new OperateurModel();
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
        $totaux = $this->transactionsModel->getTotalFraisParType();
        $totalGeneral = 0.0;
        foreach ($totaux as $ligne) {
            $totalGeneral += (float) $ligne['total'];
        }
        return [
            'parType' => $totaux,
            'total' => $totalGeneral,
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
}
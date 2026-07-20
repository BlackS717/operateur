<?php

namespace App\Services;

use App\Models\UtilisateurModel;
use App\Models\PorteFeuilleModel;
use App\Models\TransactionsModel;
use App\Models\FraisModel;
use App\Models\TypeTransactionModel;

class ClientService
{
    private $clientModel;
    private $porteFeuilleModel;
    private $transactionsModel;
    private $fraisModel;
    private $typeTransactionModel;

    public function __construct()
    {
        $this->clientModel = new UtilisateurModel();
        $this->porteFeuilleModel = new PorteFeuilleModel();
        $this->transactionsModel = new TransactionsModel();
        $this->fraisModel = new FraisModel();
        $this->typeTransactionModel = new TypeTransactionModel();
    }

    public function getAllClients()
    {
        return $this->clientModel->findAll();
    }

    public function getClientById($id)
    {
        return $this->clientModel->find($id);
    }

    public function getSolde(int $utilisateurId): float
    {
        $portefeuille = $this->porteFeuilleModel->getByUtilisateurId($utilisateurId);
        return $portefeuille ? (float) $portefeuille['solde'] : 0.0;
    }

    private function getTypeIdByNom(string $nom): ?int
    {
        $type = $this->typeTransactionModel->where('nom', $nom)->first();
        return $type ? (int) $type['id'] : null;
    }

    private function calculerFrais(int $typeTransactionId, float $montant): float
    {
        $frais = $this->fraisModel->getFraisPourMontant($typeTransactionId, $montant);
        return $frais ? (float) $frais['valeur'] : 0.0;
    }

    /**
     * @return array{success: bool, message: string}
     */
    public function depot(int $utilisateurId, float $montant): array
    {
        if ($montant <= 0) {
            return ['success' => false, 'message' => 'Le montant doit etre positif.'];
        }

        $typeId = $this->getTypeIdByNom('Depot');
        $frais = $this->calculerFrais($typeId, $montant);

        $this->porteFeuilleModel->crediter($utilisateurId, $montant);

        $this->transactionsModel->insert([
            'utilisateurId' => $utilisateurId,
            'destinataireId' => $utilisateurId,
            'typeTransactionId' => $typeId,
            'montant' => $montant,
            'frais' => $frais,
        ]);

        return ['success' => true, 'message' => 'Depot effectue avec succes.'];
    }

    /**
     * @return array{success: bool, message: string}
     */
    public function retrait(int $utilisateurId, float $montant): array
    {
        if ($montant <= 0) {
            return ['success' => false, 'message' => 'Le montant doit etre positif.'];
        }

        $typeId = $this->getTypeIdByNom('Retrait');
        $frais = $this->calculerFrais($typeId, $montant);
        $total = $montant + $frais;

        if ($this->getSolde($utilisateurId) < $total) {
            return ['success' => false, 'message' => 'Solde insuffisant (montant + frais de ' . $frais . ' Ar).'];
        }

        $this->porteFeuilleModel->debiter($utilisateurId, $total);

        $this->transactionsModel->insert([
            'utilisateurId' => $utilisateurId,
            'destinataireId' => $utilisateurId,
            'typeTransactionId' => $typeId,
            'montant' => $montant,
            'frais' => $frais,
        ]);

        return ['success' => true, 'message' => 'Retrait effectue avec succes.'];
    }

    /**
     * @return array{success: bool, message: string}
     */
    public function transfert(int $utilisateurId, string $numeroDestinataire, float $montant): array
    {
        if ($montant <= 0) {
            return ['success' => false, 'message' => 'Le montant doit etre positif.'];
        }

        $destinataire = $this->clientModel->getByNumero($numeroDestinataire);
        if (!$destinataire) {
            return ['success' => false, 'message' => 'Destinataire introuvable.'];
        }
        if ((int) $destinataire['id'] === $utilisateurId) {
            return ['success' => false, 'message' => 'Impossible de se transferer de l\'argent a soi-meme.'];
        }

        $typeId = $this->getTypeIdByNom('Transfert');
        $frais = $this->calculerFrais($typeId, $montant);
        $total = $montant + $frais;

        if ($this->getSolde($utilisateurId) < $total) {
            return ['success' => false, 'message' => 'Solde insuffisant (montant + frais de ' . $frais . ' Ar).'];
        }

        $this->porteFeuilleModel->debiter($utilisateurId, $total);
        $this->porteFeuilleModel->crediter((int) $destinataire['id'], $montant);

        $this->transactionsModel->insert([
            'utilisateurId' => $utilisateurId,
            'destinataireId' => $destinataire['id'],
            'typeTransactionId' => $typeId,
            'montant' => $montant,
            'frais' => $frais,
        ]);

        return ['success' => true, 'message' => 'Transfert effectue avec succes.'];
    }

    public function getHistorique(int $utilisateurId): array
    {
        return $this->transactionsModel->getHistorique($utilisateurId);
    }
}

<?php

namespace App\Services;

use App\Models\UtilisateurModel;
use App\Models\PorteFeuilleModel;
use App\Models\TransactionsModel;
use App\Models\FraisModel;
use App\Models\TypeTransactionModel;
use App\Models\PrefixModel;
use App\Models\CommissionModel;

class ClientService
{
    private $clientModel;
    private $porteFeuilleModel;
    private $transactionsModel;
    private $fraisModel;
    private $typeTransactionModel;
    private $prefixModel;
    private $commissionModel;

    public function __construct()
    {
        $this->clientModel = new UtilisateurModel();
        $this->porteFeuilleModel = new PorteFeuilleModel();
        $this->transactionsModel = new TransactionsModel();
        $this->fraisModel = new FraisModel();
        $this->typeTransactionModel = new TypeTransactionModel();
        $this->prefixModel = new PrefixModel();
        $this->commissionModel = new CommissionModel();
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
     * Retourne l'operateurId d'un utilisateur via son portefeuille.
     */
    private function getOperateurIdByUtilisateur(int $utilisateurId): ?int
    {
        $portefeuille = $this->porteFeuilleModel->getByUtilisateurId($utilisateurId);
        return $portefeuille ? (int) $portefeuille['operateurId'] : null;
    }

    /**
     * Calcule la commission inter-operateur si les deux utilisateurs
     * appartiennent a des operateurs differents.
     * Retourne 0 si meme operateur ou pas de commission configurée.
     */
    private function calculerCommissionInterOperateur(int $sourceUtilisateurId, int $destinataireUtilisateurId, float $montant): float
    {
        $sourceOperateur = $this->getOperateurIdByUtilisateur($sourceUtilisateurId);
        $destinataireOperateur = $this->getOperateurIdByUtilisateur($destinataireUtilisateurId);

        if ($sourceOperateur === null || $destinataireOperateur === null) {
            return 0.0;
        }

        // Meme operateur => pas de commission
        if ($sourceOperateur === $destinataireOperateur) {
            return 0.0;
        }

        $pourcentage = $this->commissionModel->getCommission($sourceOperateur, $destinataireOperateur);
        if ($pourcentage === null) {
            return 0.0;
        }

        return $montant * ($pourcentage / 100.0);
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
        $commissionInter = $this->calculerCommissionInterOperateur($utilisateurId, (int) $destinataire['id'], $montant);
        $total = $montant + $frais + $commissionInter;

        if ($this->getSolde($utilisateurId) < $total) {
            $msg = 'Solde insuffisant (montant + frais de ' . number_format($frais, 0, ',', ' ') . ' Ar';
            if ($commissionInter > 0) {
                $msg .= ' + commission inter-operateur de ' . number_format($commissionInter, 0, ',', ' ') . ' Ar';
            }
            $msg .= ').';
            return ['success' => false, 'message' => $msg];
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

        $message = 'Transfert effectue avec succes.';
        if ($commissionInter > 0) {
            $message .= ' (Commission inter-operateur de ' . number_format($commissionInter, 0, ',', ' ') . ' Ar appliquee.)';
        }
        return ['success' => true, 'message' => $message];
    }

    public function getHistorique(int $utilisateurId): array
    {
        return $this->transactionsModel->getHistorique($utilisateurId);
    }
}
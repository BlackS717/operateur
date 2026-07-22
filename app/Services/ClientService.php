<?php

namespace App\Services;

use App\Models\UtilisateurModel;
use App\Models\PorteFeuilleModel;
use App\Models\TransactionsModel;
use App\Models\FraisModel;
use App\Models\TypeTransactionModel;
use App\Models\PrefixModel;
use App\Models\CommissionModel;
use App\Models\PromotionModel;
use App\Models\EpargneModel;
use App\Models\CompteEpargneModel;

class ClientService
{
    private $clientModel;
    private $porteFeuilleModel;
    private $transactionsModel;
    private $fraisModel;
    private $typeTransactionModel;
    private $prefixModel;
    private $commissionModel;
    private $promotionModel;

    private $epargneModel;


    private $compteEpargneModel;


    public function __construct()
    {
        $this->clientModel = new UtilisateurModel();
        $this->porteFeuilleModel = new PorteFeuilleModel();
        $this->transactionsModel = new TransactionsModel();
        $this->fraisModel = new FraisModel();
        $this->typeTransactionModel = new TypeTransactionModel();
        $this->prefixModel = new PrefixModel();
        $this->commissionModel = new CommissionModel();
        $this->promotionModel = new PromotionModel();
        $this->epargneModel = new EpargneModel();
        $this->compteEpargneModel = new CompteEpargneModel();
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
     * @deprecated Use transfertMultiple() instead.
     */
    public function transfert(int $utilisateurId, string $numeroDestinataire, float $montant): array
    {
        return $this->transfertMultiple($utilisateurId, [$numeroDestinataire], $montant, true);
    }

    /**
     * Transfert vers un ou plusieurs destinataires.
     *
     * @param int     $utilisateurId  Expéditeur
     * @param array   $numeros        Liste des numéros destinataires
     * @param float   $montantTotal   Montant total à partager entre les destinataires
     * @param bool    $inclureFrais   Si true, les frais sont ajoutés au débit (expéditeur paie les frais)
     *                                Si false, les frais sont déduits du montant envoyé à chaque destinataire
     * @return array{success: bool, message: string}
     */
    public function transfertMultiple(int $utilisateurId, array $numeros, float $montantTotal, bool $inclureFrais): array
    {
        if ($montantTotal <= 0) {
            return ['success' => false, 'message' => 'Le montant doit etre positif.'];
        }

        $nbDestinataires = count($numeros);
        if ($nbDestinataires === 0) {
            return ['success' => false, 'message' => 'Aucun destinataire fourni.'];
        }

        // Vérifier tous les destinataires et les récupérer
        $destinataires = [];
        foreach ($numeros as $numero) {
            $numero = trim($numero);
            if (!preg_match('/^[0-9]/', $numero)) {
                return ['success' => false, 'message' => "Le numero '$numero' n'est pas un numero valide (10 chiffres)."];
            }
            $dest = $this->clientModel->getByNumero($numero);
            if (!$dest) {
                return ['success' => false, 'message' => "Destinataire '$numero' introuvable."];
            }
            if ((int) $dest['id'] === $utilisateurId) {
                return ['success' => false, 'message' => 'Impossible de se transferer de l\'argent a soi-meme.'];
            }
            $destinataires[] = $dest;
        }

        // Montant par destinataire
        $montantParDest = $montantTotal / $nbDestinataires;
        if ($montantParDest < 100) {
            return ['success' => false, 'message' => "Le montant par destinataire ($montantParDest Ar) est inferieur au minimum de 100 Ar."];
        }

        $typeId = $this->getTypeIdByNom('Transfert');

        // Calculer les frais et commissions pour chaque destinataire
        $totalFrais = 0.0;
        $totalCommissions = 0.0;
        $details = [];
        $fraisRetraitId = $this->getTypeIdByNom('Retrait');

        foreach ($destinataires as $dest) {
            $frais = $this->calculerFrais($typeId, $montantParDest);
            $commissionInter = $this->calculerCommissionInterOperateur($utilisateurId, (int) $dest['id'], $montantParDest);
            $user1 = $this->getOperateurIdByUtilisateur($utilisateurId);
            $user2 = $this->getOperateurIdByUtilisateur((int) $dest['id']);
            if ($user1 == $user2) {
                $frais = $frais * ((100-$this->promotionModel->getByOperateurId($user1)['pourcentage']) / 100);
            }
            $fraisRetrait = $this->calculerFrais($fraisRetraitId, $montantParDest);
            $totalFrais += $frais;
            $totalCommissions += $commissionInter;
            if ($inclureFrais) {
                // L'expéditeur paie les frais en plus
                $montantEnvoye = $montantParDest + $fraisRetrait;
                $totalFrais += $fraisRetrait; // Ajouter les frais de retrait au total des frais
            } else {
                // Les frais sont déduits du montant envoyé
                $montantEnvoye = $montantParDest;
                if ($montantEnvoye <= 0) {
                    return ['success' => false, 'message' => "Les frais de $frais Ar depassent le montant de $montantParDest Ar pour le destinataire {$dest['numero']}."];
                }
            }

            $details[] = [
                'destinataire' => $dest,
                'montantEnvoye' => $montantEnvoye,
                'frais' => $frais,
                'commissionInter' => $commissionInter,
            ];
        }

        // Montant total à débiter
        $totalADebiter = $montantTotal + $totalFrais + $totalCommissions;

        if ($this->getSolde($utilisateurId) < $totalADebiter) {
            $msg = 'Solde insuffisant (montant total ' . number_format($montantTotal, 0, ',', ' ') . ' Ar';
            if ($totalFrais > 0) {
                $msg .= ' + frais de ' . number_format($totalFrais, 0, ',', ' ') . ' Ar';
            }
            if ($totalCommissions > 0) {
                $msg .= ' + commission inter-operateur de ' . number_format($totalCommissions, 0, ',', ' ') . ' Ar';
            }
            $msg .= ').';
            return ['success' => false, 'message' => $msg];
        }

        // Débiter l'expéditeur une seule fois pour le total
        $this->porteFeuilleModel->debiter($utilisateurId, $totalADebiter);

        // Exécuter les transferts vers chaque destinataire
        foreach ($details as $d) {
            $destId = (int) $dest['id'];
            $montantEpargne = $montantParDest * $this->epargneModel->getEpargneByUserId($destId);
            $montantCrediter =  $$d['montantEnvoye'] - $montantEpargne;

            $this->compteEpargneModel->crediter((int) $d['destinataire']['id'], $montantEpargne);
            $this->porteFeuilleModel->crediter((int) $d['destinataire']['id'], $montantCrediter);

            $this->transactionsModel->insert([
                'utilisateurId' => $utilisateurId,
                'destinataireId' => $d['destinataire']['id'],
                'typeTransactionId' => $typeId,
                'montant' => $d['montantEnvoye'],
                'frais' => $d['frais'],
            ]);
        }

        $message = 'Transfert effectue avec succes.';
        if ($nbDestinataires > 1) {
            $message .= " $nbDestinataires destinataires servis.";
        }
        if ($totalCommissions > 0) {
            $message .= ' (Commission inter-operateur de ' . number_format($totalCommissions, 0, ',', ' ') . ' Ar appliquee.)';
        }

        return ['success' => true, 'message' => $message];
    }

    public function updateEpargne(int $userId, $pourcentage){
        $epargneId = $this->getEpargne($userId)['id'];

       return  $this->epargneModel->update( $epargneId,
        [
            'pourcentage' => $pourcentage,
        ]);
    }

    public function getEpargne(int $userId){
        return $this->epargneModel->getEpargneByUserId($userId);
    }

    public function getHistorique(int $utilisateurId): array
    {
        return $this->transactionsModel->getHistorique($utilisateurId);
    }
}

<?php

namespace App\Services;

use \App\Models\UtilisateurModel;
use \App\Models\PorteFeuilleModel;

class ClientService
{

    private $clientModel;
    private $porteFeuilleModel;
    public function __construct()
    {
        $this->clientModel = new UtilisateurModel();
        $this->porteFeuilleModel = new PorteFeuilleModel();
    }

    public function getAllClients()
    {
        return $this->clientModel->findAll();
    }

    public function getSoldeByClientId($clientId)
    {
        $client = $this->porteFeuilleModel->findByUtilisateurId($clientId);
        return $client['solde'] ?? null;
    }

    public function effectuerTransaction($destinataireId, $typetransactionId, $montant, $frais)
    {
        // Vérifier si le destinataire existe
        $destinataire = $this->clientModel->getByNumero($destinataireId);
        if (!$destinataire) {
            return ['success' => false, 'message' => 'Destinataire non trouvé.'];
        }

        // Vérifier si le destinataire a un portefeuille
        $porteFeuille = $this->porteFeuilleModel->findByUtilisateurId($destinataireId);
        if (!$porteFeuille) {
            return ['success' => false, 'message' => 'Le destinataire n\'a pas de portefeuille.'];
        }

        if ($typetransactionId == 2) {
            // Vérifier si le solde est suffisant pour la transaction
            if ($porteFeuille['solde'] < ($montant + $frais)) {
                return ['success' => false, 'message' => 'Solde insuffisant pour effectuer la transaction.'];
            }
        } else {
            return ['success' => false, 'message' => 'Type de transaction non pris en charge.'];
        }

        // Débiter le montant et les frais du solde du destinataire
        $nouveauSolde = $porteFeuille['solde'] - ($montant + $frais);
        $this->porteFeuilleModel->update($porteFeuille['id'], ['solde' => $nouveauSolde]);

        return ['success' => true, 'message' => 'Transaction effectuée avec succès.', 'nouveauSolde' => $nouveauSolde];
    }
}

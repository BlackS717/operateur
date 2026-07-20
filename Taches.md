# TODO – Application Mobile Money

# v1

## Initialisation du projet ```ETU 3893```
- [x] Concevoir le schéma de la base de données. 
- [x] Créer les migrations de la base de données.
- [x] Mettre en place les modèles.
- [x] Préparer les données de test (seeders).

## Côté opérateur ```ETU 4316```
- [ ] Configurer les préfixes valides de l'opérateur (ex. : 033, 037).
- [ ] Créer les types d'opérations : dépôt, retrait et transfert.
- [ ] Configurer les barèmes de frais par tranche de montant (modifiables).
- [ ] Gérer les paramètres des frais.
- [ ] Consulter la situation des gains générés par les frais (retraits et transferts).
- [ ] Consulter la situation des comptes clients.

## Côté client ```ETU 3893```
- [x] Mettre en place la connexion automatique avec le numéro de téléphone.
- [x] Supprimer l'inscription préalable.
- [x] Créer automatiquement un compte client lors de la première connexion.
- [x] Consulter le solde.
- [x] Effectuer un dépôt (simulation automatique).
- [x] Effectuer un retrait (simulation automatique).
- [x] Effectuer un transfert.
- [x] Consulter l'historique des opérations.

## Backend ```ETU 4316```
- [x] Développer les API pour l'authentification.
- [x] Développer les API des opérations (dépôt, retrait, transfert).
- [x] Implémenter le calcul automatique des frais.
- [x] Valider les données et gérer les erreurs.
- [x] Journaliser les opérations.

## Tests 
- [x] Tester les opérations de dépôt.
- [x] Tester les opérations de retrait.
- [x] Tester les opérations de transfert.
- [x] Vérifier le calcul des frais.
- [x] Vérifier les historiques et les soldes.
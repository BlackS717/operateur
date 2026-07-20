# TODO – Application Mobile Money

# v1

## Initialisation du projet ```ETU 3893```
- [x] Concevoir le schéma de la base de données. 
- [x] Créer les migrations de la base de données.
- [x] Mettre en place les modèles.
- [x] Préparer les données de test (seeders).

## Côté opérateur ```ETU 4316```
- [X] Configurer les préfixes valides de l'opérateur (ex. : 033, 037).
- [X] Créer les types d'opérations : dépôt, retrait et transfert.
- [X] Configurer les barèmes de frais par tranche de montant (modifiables).
- [X] Gérer les paramètres des frais.
- [X] Consulter la situation des gains générés par les frais (retraits et transferts).
- [X] Consulter la situation des comptes clients.

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

# v2

## Modification base de donnee

- [ ] Modification de la base pour separer l'operateur du client
- [ ] Modification de l'authentification pour separer les utilisateurs des operateurs

## Côté opérateur

### Gestion des opérateurs
- [ ] Ajouter la gestion de plusieurs opérateurs.
- [ ] Configurer les préfixes valides pour chaque opérateur (ex. : 032, 031, 033, 037...).
- [ ] Associer chaque préfixe à son opérateur.

### Gestion des frais
- [ ] Configurer un pourcentage de commission supplémentaire pour les transferts vers les autres opérateurs.
- [ ] Appliquer automatiquement cette commission lors des transferts inter-opérateurs.

### Tableaux de bord
- [ ] Séparer les gains provenant des opérations du même opérateur et des autres opérateurs dans la page **"Situation gain via les différents frais"**.
- [ ] Ajouter une situation des montants envoyés vers chaque opérateur.

## Côté client

### Transfert
- [ ] Ajouter une option permettant d'inclure les frais de retrait lors de l'envoi.
- [ ] Ne pas appliquer de frais de retrait pour les transferts vers les autres opérateurs.
- [ ] Permettre l'envoi multiple vers plusieurs numéros.
- [ ] Répartir automatiquement le montant total entre les différents numéros.
- [ ] Limiter l'envoi multiple aux destinataires appartenant au même opérateur.

## Backend

### API
- [ ] Adapter les API pour la gestion de plusieurs opérateurs.
- [ ] Implémenter le calcul des commissions inter-opérateurs.
- [ ] Implémenter le calcul des frais avec l'option "inclure les frais de retrait".
- [ ] Développer les API d'envoi multiple.
- [ ] Ajouter les validations pour empêcher un envoi multiple entre différents opérateurs.

## Tests

- [ ] Tester les transferts entre opérateurs.
- [ ] Vérifier le calcul des commissions supplémentaires.
- [ ] Tester l'option d'inclusion des frais de retrait.
- [ ] Vérifier l'absence de frais de retrait pour les autres opérateurs.
- [ ] Tester les envois multiples.
- [ ] Vérifier la répartition correcte des montants.
- [ ] Vérifier que l'envoi multiple est limité à un seul opérateur.
- [ ] Vérifier les statistiques et rapports par opérateur.
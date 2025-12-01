# 📖 GUIDE D'UTILISATION - ETS DUBAI

## 📋 Table des Matières

1. [Premiers Pas](#premiers-pas)
2. [Guide Administrateur](#guide-administrateur)
3. [Guide Responsable](#guide-responsable)
4. [Guide Vendeur](#guide-vendeur)
5. [FAQ & Dépannage](#faq--dépannage)

---

# 🚀 Premiers Pas

## Accès à l'Application

**URL :** `https://etsdubai.onrender.com` (ou votre domaine)

### Credentials par Défaut

#### **👨‍💼 Administrateur**

-   **Email :** admin@depot.cm
-   **Mot de passe :** password

#### **🎯 Responsable**

-   **Email :** responsable@depot.cm
-   **Mot de passe :** password

#### **👤 Vendeurs**

-   **Email :** jean@depot.cm, marie@depot.cm, paul@depot.cm, etc.
-   **Mot de passe :** password

> ⚠️ **IMPORTANT :** Changez tous les mots de passe lors de la première connexion !

---

## Connexion

1. Allez sur la page de connexion
2. Entrez votre **email** et **mot de passe**
3. Cliquez sur **"Se connecter"**
4. Vous êtes redirigé vers le **Dashboard**

### Mot de Passe Oublié ?

1. Cliquez sur **"Mot de passe oublié ?"**
2. Entrez votre email
3. Vous recevrez un lien de réinitialisation
4. Créez un nouveau mot de passe

---

## Navigation Générale

### Menu Latéral (Sidebar)

**Sur Desktop :**

-   Menu fixe à gauche
-   Toujours visible

**Sur Mobile/Tablette :**

-   Icône hamburger (☰) en haut à gauche
-   Menu coulissant

### En-tête (Header)

**Éléments :**

-   🔔 **Notifications** (à venir)
-   👤 **Profil utilisateur**
    -   Mon profil
    -   Déconnexion

---

# 👨‍💼 GUIDE ADMINISTRATEUR

## Vue d'Ensemble

L'administrateur a accès à **toutes les fonctionnalités** de l'application et peut :

-   Gérer les utilisateurs
-   Voir toutes les données
-   Analyser les performances
-   Configurer le système

---

## 1. Gestion des Utilisateurs

### Accéder à la Gestion

**Navigation :** Menu > Administration > Utilisateurs

### Créer un Utilisateur

1. Cliquez sur **"Nouvel Utilisateur"**
2. Remplissez le formulaire :
    - **Nom complet**
    - **Email** (unique)
    - **Mot de passe** (min. 8 caractères)
    - **Confirmation mot de passe**
    - **Rôle** :
        - `Admin` → Accès total
        - `Responsable` → Gestion opérationnelle
        - `Vendeur` → Point de vente uniquement
    - **Point de Vente** (si Responsable ou Vendeur)
    - **Statut** : Actif / Inactif
3. Cliquez sur **"Créer l'utilisateur"**

### Modifier un Utilisateur

1. Dans la liste, cliquez sur le nom de l'utilisateur
2. Cliquez sur **"Modifier"**
3. Modifiez les informations
4. **Enregistrer les modifications**

### Désactiver un Utilisateur

1. Dans la fiche utilisateur
2. Cliquez sur **"Désactiver"**
3. Confirmez

> 💡 Un utilisateur désactivé ne peut plus se connecter mais ses données sont conservées.

### Réinitialiser le Mot de Passe

1. Fiche utilisateur > **"Réinitialiser le mot de passe"**
2. Entrez le nouveau mot de passe (2 fois)
3. **Enregistrer**
4. Informez l'utilisateur de son nouveau mot de passe

### Supprimer un Utilisateur

1. Fiche utilisateur > **"Supprimer"**
2. **Attention :** Impossible si l'utilisateur a des ventes
3. Solution : Désactiver plutôt que supprimer

---

## 2. Gestion des Points de Vente

### Accéder

**Navigation :** Paramètres > Points de Vente (à créer si pas existant)

### Types de Points

-   **Central** : Dépôt principal (Dubai Magasin)
-   **Point de Vente** : Boutiques (Cave, Lounge, Meyomadjom)

### Créer un Point de Vente

1. **Nom** : Dubai [Nom]
2. **Code** : DXB-XXX (3 lettres)
3. **Type** : Central / Point de Vente
4. **Adresse complète**
5. **Téléphone**
6. **Email** (optionnel)
7. **Statut** : Actif

---

## 3. Analyses des Vendeurs

### Accéder

**Navigation :** Administration > Analyse Vendeurs

### Tableau de Bord

**Indicateurs par vendeur :**

-   💰 CA Total
-   🛒 Nombre de ventes
-   📊 Panier moyen
-   📅 Ventes du jour/semaine/mois

### Comparer les Vendeurs

1. Sélectionnez la période
2. Filtrez par point de vente
3. Tableau comparatif avec :
    - Classement
    - Évolution
    - Graphiques

### Voir les Détails d'un Vendeur

1. Cliquez sur le nom du vendeur
2. Vue détaillée :
    - Historique des ventes
    - Produits les plus vendus
    - Modes de paiement préférés
    - Clients fidélisés
    - Performance journalière

---

## 4. Paramètres Système

### Accéder

**Navigation :** Administration > Paramètres

### Configuration Générale

**Informations Entreprise :**

-   Nom commercial
-   Adresse siège
-   Téléphone
-   Email
-   Logo (optionnel)

**Paramètres Fiscaux :**

-   TVA par défaut : 19.25%
-   Devise : FCFA
-   Format numéros (factures, commandes, etc.)

**Règles Métier :**

-   Délai annulation vente : 24h
-   Stock minimum alerte : 10 unités
-   Délai validation transfert : 48h

---

## 5. Rapports Globaux

### Accéder

**Navigation :** Rapports

### Types de Rapports

#### **📊 Rapport des Ventes**

**Période :** Aujourd'hui / Cette semaine / Ce mois / Personnalisée

**Données :**

-   CA total
-   Évolution vs période précédente
-   Top 10 produits
-   Répartition par point de vente
-   Répartition par mode de paiement
-   Graphique d'évolution

**Actions :**

-   Exporter en PDF
-   Exporter en Excel
-   Imprimer

#### **📦 Rapport des Stocks**

**Vue :**

-   Stock actuel tous points
-   Valorisation totale
-   Produits en rupture
-   Produits à rotation lente
-   Mouvements du jour

#### **💰 Rapport Financier**

**Données :**

-   Trésorerie
-   Créances clients
-   Dettes fournisseurs
-   Marges par produit/catégorie
-   Rentabilité par point

#### **🚚 Rapport Achats**

**Analyse :**

-   Commandes par fournisseur
-   Montants dépensés
-   Délais de livraison moyens
-   Ristournes obtenues
-   Top fournisseurs

---

## 6. Cas d'Usage : Début de Mois

### Tâches Administratives

**1. Clôture du Mois Précédent**

-   Vérifier les rapports financiers
-   Valider les ristournes fournisseurs
-   Analyser les performances vendeurs

**2. Planification**

-   Consulter les stocks
-   Identifier les besoins
-   Préparer les commandes

**3. Gestion RH**

-   Analyser les performances
-   Féliciter les meilleurs vendeurs
-   Former ceux en difficulté

**4. Réunion de Direction**

-   Préparer les rapports
-   Présenter les KPIs
-   Définir les objectifs du mois

---

# 🎯 GUIDE RESPONSABLE

## Vue d'Ensemble

Le Responsable gère les **opérations** et la **logistique** :

-   Commandes fournisseurs
-   Gestion stocks
-   Transferts entre points
-   Ristournes
-   Vue globale des ventes

> 🚫 **Pas d'accès :** Utilisateurs, Analyses vendeurs, Paramètres

---

## 1. Gestion des Commandes Fournisseurs

### Accéder

**Navigation :** Commandes

### Créer une Commande

**Étape 1 : Informations Générales**

1. Cliquez sur **"Nouvelle Commande"**
2. Sélectionnez le **Fournisseur**
3. **Date de commande** : Aujourd'hui (par défaut)
4. **Date de livraison prévue** : Selon délai fournisseur

**Étape 2 : Ajouter des Produits**

1. Recherchez un produit
2. Cliquez sur **"Ajouter"**
3. Entrez la **quantité**
4. Le **prix unitaire** est pré-rempli (modifiable)
5. Répétez pour tous les produits

**Étape 3 : Révision**

-   Vérifiez les totaux :
    -   Montant HT
    -   TVA (19.25%)
    -   Montant TTC
-   Ajoutez des **notes** (optionnel)

**Étape 4 : Enregistrer**

**2 Options :**

**A) Enregistrer en Brouillon**

-   Statut : `Brouillon`
-   Modifiable
-   Non envoyée au fournisseur

**B) Envoyer au Fournisseur**

-   Statut : `En attente`
-   Plus modifiable
-   Envoi notification fournisseur (à venir)

### Suivre une Commande

**Workflow Complet :**

```
Brouillon → En attente → Confirmée → En préparation
    ↓
Expédiée → Livrée (ou Partiellement livrée)
    ↓
Annulée (si problème)
```

**Changer le Statut :**

1. Ouvrir la commande
2. Cliquez sur **"Changer le statut"**
3. Sélectionnez le nouveau statut
4. Ajoutez une note (optionnel)
5. **Confirmer**

### Réceptionner une Livraison

**Livraison Complète :**

1. Commande en statut `Expédiée`
2. Cliquez sur **"Réceptionner"**
3. Pour chaque produit :
    - Quantité commandée affichée
    - Entrez **quantité reçue**
4. Si toutes les quantités correspondent :
    - Cochez **"Livraison complète"**
5. Ajoutez des notes si besoin
6. **Valider la réception**
7. Le stock est **automatiquement mis à jour**

**Livraison Partielle :**

1. Certaines quantités manquantes
2. Entrez les quantités réelles reçues
3. Le statut passe à `Partiellement livrée`
4. Les produits manquants restent en attente
5. Nouvelle réception possible ultérieurement

---

## 2. Gestion des Fournisseurs

### Accéder

**Navigation :** Fournisseurs

### Créer un Fournisseur

1. **"Nouveau Fournisseur"**
2. **Informations :**

    - Nom commercial
    - Raison sociale (optionnel)
    - Email
    - Téléphone
    - Adresse complète
    - Code fournisseur (auto-généré)

3. **Conditions Commerciales :**

    - Délai de livraison (jours)
    - Mode de paiement par défaut
    - Conditions de paiement (ex: 30 jours)

4. **Contact Principal :**

    - Nom
    - Fonction
    - Téléphone direct
    - Email

5. **Statut :** Actif

6. **Enregistrer**

### Fiche Fournisseur

**Informations affichées :**

-   Coordonnées complètes
-   Historique des commandes
-   Montant total acheté
-   Ristournes obtenues
-   Dernier achat
-   Délai moyen de livraison

---

## 3. Gestion des Ristournes

### Comprendre les Ristournes

**Définition :** Remise accordée par le fournisseur selon le volume d'achats.

**Exemple :**

```
Fournisseur : Brasseries du Cameroun
Période : Septembre 2025
Barème :
- 0 à 5M FCFA → 0%
- 5M à 10M → 2%
- 10M à 20M → 5%
- > 20M → 8%

Total acheté : 15 000 000 FCFA
→ Ristourne = 15M × 5% = 750 000 FCFA
```

### Créer une Ristourne

**Étape 1 : Configuration du Barème**

1. **Navigation :** Ristournes > **"Nouvelle Ristourne"**
2. Sélectionnez le **Fournisseur**
3. **Période :**
    - Type : Mensuelle / Trimestrielle / Annuelle
    - Date début
    - Date fin
4. **Type de barème :**

    - Par montant (FCFA)
    - Par volume (unités)

5. **Définir les seuils :**

    Exemple :

```
   Seuil 1 : 0 à 5 000 000 → 0%
   Seuil 2 : 5 000 000 à 10 000 000 → 2%
   Seuil 3 : 10 000 000 à 20 000 000 → 5%
   Seuil 4 : > 20 000 000 → 8%
```

6. **Enregistrer**

**Étape 2 : Calcul Automatique**

-   À la fin de la période
-   Le système calcule automatiquement :
    -   Total des achats
    -   Seuil applicable
    -   Montant de la ristourne
-   Statut : `En attente de validation`

**Étape 3 : Validation**

1. Vérifier les montants
2. Vérifier les achats inclus
3. Cliquer sur **"Valider"**
4. Statut : `Validée`

**Étape 4 : Paiement**

1. Fournisseur verse la ristourne
2. Cliquer sur **"Marquer comme payée"**
3. Entrer la date de paiement
4. Référence de paiement (optionnel)
5. Statut : `Payée`

### Simulateur de Ristourne

**Utilité :** Projeter les ristournes futures

1. **Navigation :** Ristournes > **"Simulateur"**
2. Sélectionnez fournisseur
3. Entrez un montant projeté
4. Le système affiche :
    - Seuil applicable
    - Pourcentage
    - Montant de ristourne
    - Prochain seuil
    - Montant manquant pour atteindre le prochain seuil

**Exemple d'utilisation :**

```
Achats actuels : 14 500 000 FCFA
Ristourne actuelle : 5% = 725 000 FCFA

Simulation : 16 000 000 FCFA
Ristourne : 5% = 800 000 FCFA

Prochain seuil : 20 000 000 FCFA (8%)
Manque : 4 000 000 FCFA
Ristourne potentielle : 1 600 000 FCFA
```

---

## 4. Gestion des Transferts de Stock

### Workflow de Transfert

```
1. DEMANDE (Point demandeur)
   ↓
2. VALIDATION (Responsable/Admin)
   ↓
3. EXPÉDITION (Point source)
   ↓
4. RÉCEPTION (Point demandeur)
   ↓
5. Stocks mis à jour automatiquement
```

### Recevoir une Demande de Transfert

**Notification :** Nouvelle demande de transfert

1. **Navigation :** Transferts Stock
2. Onglet **"En attente de validation"**
3. Cliquez sur la demande

**Informations affichées :**

-   Point demandeur
-   Point source (le vôtre)
-   Liste des produits demandés
-   Quantités
-   Stock disponible à l'instant

**Actions :**

**A) Valider le Transfert**

1. Vérifiez les stocks disponibles
2. Cliquez sur **"Valider"**
3. Le transfert passe en statut `Validé`
4. Le point source peut maintenant expédier

**B) Refuser le Transfert**

1. Si stocks insuffisants ou autre raison
2. Cliquez sur **"Refuser"**
3. Indiquez le motif
4. Le demandeur est notifié

**C) Modifier les Quantités**

1. Vous pouvez ajuster les quantités
2. Si stocks partiels disponibles
3. Sauvegarder et valider

### Expédier un Transfert

**Contexte :** Vous êtes le point source (Dubai Magasin généralement)

1. Transfert en statut `Validé`
2. Préparez physiquement la marchandise
3. Cliquez sur **"Expédier"**
4. Confirmez les quantités expédiées
5. Ajoutez des notes (n° colis, transporteur, etc.)
6. **Confirmer l'expédition**
7. Les stocks du point source sont **déduits automatiquement**
8. Le statut passe à `Expédié`
9. Le point destinataire est notifié

### Annuler un Transfert

**Conditions :**

-   Statut `En attente` ou `Validé`
-   Pas encore expédié

**Procédure :**

1. Ouvrir le transfert
2. **"Annuler"**
3. Indiquer le motif
4. Confirmer

---

## 5. Vue Globale des Ventes

### Accéder

**Navigation :** Ventes > Historique

### Filtres Disponibles

-   **Période** : Aujourd'hui, Cette semaine, Ce mois, Personnalisée
-   **Point de vente** : Tous / Spécifique
-   **Vendeur** : Tous / Spécifique
-   **Mode de paiement** : Tous / Espèces / Mobile Money / Crédit
-   **Statut** : Toutes / Complétée / Annulée

### Statistiques Affichées

**Cartes de Statistiques :**

-   💰 **CA Total**
-   🛒 **Nombre de ventes**
-   📊 **Panier moyen**
-   💵 **Ventes espèces**
-   📱 **Ventes mobile money**
-   💳 **Ventes crédit**

### Détails d'une Vente

1. Cliquez sur une vente
2. **Informations affichées :**

    - N° vente
    - Date et heure
    - Vendeur
    - Client (si renseigné)
    - Liste des produits
    - Quantités
    - Prix unitaires
    - Total HT / TVA / TTC
    - Mode de paiement
    - Montant payé / Rendu
    - Statut

3. **Actions possibles :**
    - **Imprimer le ticket**
    - **Annuler** (si vente du jour)

---

## 6. Gestion des Produits

### Créer un Produit

1. **Navigation :** Produits > **"Nouveau Produit"**

2. **Informations Générales :**

    - Nom du produit
    - Référence (unique)
    - Code-barre (optionnel)
    - Catégorie
    - Fournisseur principal

3. **Conditionnement :**

    - **Type** : Casier, Pack, Carton, etc.
    - **Unités par conditionnement** : Ex: 24 bouteilles/casier

4. **Prix (HT) :**

    - Prix achat conditionnement
    - Prix achat unité
    - Prix vente conditionnement
    - Prix vente unité
    - **Marges calculées automatiquement**

5. **Gestion Stocks :**

    - Stock minimum
    - Stock maximum
    - Point de réapprovisionnement

6. **Fiscal :**

    - TVA applicable : Oui/Non
    - Taux TVA : 19.25% (par défaut)

7. **Statut :** Actif

8. **Enregistrer**

### Modifier un Produit

1. Liste des produits
2. Cliquez sur le produit
3. **"Modifier"**
4. Modifiez les informations
5. **Enregistrer**

> ⚠️ **Attention :** La modification des prix n'affecte pas les ventes passées.

---

## 7. Cas d'Usage : Journée Type

### Matin (8h - 10h)

**1. Vérification des Stocks**

-   Consulter le rapport de stock
-   Identifier les produits en rupture
-   Identifier les alertes stock bas

**2. Traitement des Demandes**

-   Consulter les demandes de transfert
-   Valider les transferts
-   Préparer les expéditions

**3. Commandes Fournisseurs**

-   Vérifier les livraisons prévues
-   Contacter les fournisseurs si retard

### Journée (10h - 17h)

**4. Réceptions de Marchandise**

-   Réceptionner les livraisons
-   Vérifier les quantités
-   Mettre à jour les stocks

**5. Expéditions**

-   Préparer les transferts validés
-   Expédier vers les points de vente
-   Notifier les destinataires

**6. Suivi**

-   Consulter les ventes en temps réel
-   Vérifier les niveaux de stock
-   Ajuster si nécessaire

### Soir (17h - 19h)

**7. Bilan de la Journée**

-   CA du jour
-   Mouvements de stock
-   Commandes à préparer pour demain

**8. Planification**

-   Commandes à passer
-   Transferts à valider demain
-   Points d'attention

---

# 👤 GUIDE VENDEUR

## Vue d'Ensemble

Le Vendeur gère son **point de vente** au quotidien :

-   Ouvrir/fermer sa caisse
-   Enregistrer les ventes (POS)
-   Gérer les clients
-   Consulter le stock
-   Demander des transferts

> 🚫 **Pas d'accès :** Commandes, Fournisseurs, Ristournes, Gestion globale

---

## 1. Gérer sa Caisse

### Ouvrir la Caisse

**Quand ?** En début de journée, avant la première vente

**Procédure :**

1. **Navigation :** Ventes & Caisse > Gestion Caisses
2. Cliquez sur **"Ouvrir une Caisse"**
3. Remplissez le formulaire :
    - **Nom de la caisse** : Ex: "Caisse Dubai Cave - 15/01/2025"
    - **Point de vente** : Automatiquement le vôtre
    - **Fond de caisse** : Montant en espèces au départ
        - Ex: 50 000 FCFA
    - **Notes** : Optionnel (ex: Billets de 10 000 x 5)
4. **Ouvrir la Caisse**

**Résultat :**

-   Caisse en statut `Ouverte`
-   Vous pouvez commencer les ventes
-   Badge vert "Caisse ouverte" visible

> ⚠️ **Important :** Sans caisse ouverte, impossible de faire des ventes !

### Clôturer la Caisse

**Quand ?** En fin de journée

**Procédure :**

1. **Navigation :** Ventes & Caisse > Gestion Caisses
2. Cliquez sur votre caisse ouverte
3. **"Fermer la Caisse"**
4. Remplissez le formulaire :

    **Comptage Physique :**

    - **Espèces réelles** : Comptez l'argent dans la caisse
    - **Mobile Money** : Montant reçu (si applicable)

    **Réconciliation Automatique :**

    - Fond de départ : 50 000 FCFA
    - Ventes espèces théoriques : 287 500 FCFA
    - Total théorique : 337 500 FCFA
    - Espèces réelles comptées : 335 000 FCFA
    - **Écart : -2 500 FCFA**

5. **Justifier les Écarts** (si applicable)

    - Si écart, indiquez le motif :
        - Monnaie rendue en trop
        - Erreur de frappe
        - Oubli d'enregistrement
        - Etc.

6. **Confirmer la Clôture**

**Résultat :**

-   Caisse en statut `Fermée`
-   Rapport de clôture généré
-   Impossible de modifier
-   Historique conservé

### Consulter l'Historique des Caisses

1. **Navigation :** Ventes & Caisse > Gestion Caisses
2. Onglet **"Historique"**
3. Filtres :
    - Par date
    - Par statut (Ouverte/Fermée)
4. Cliquez sur une caisse pour voir :
    - Détails complets
    - Tous les mouvements
    - Ventes associées
    - Rapport de clôture

---

## 2. Enregistrer une Vente (Point de Vente)

### Accéder au POS

**Navigation :** Ventes & Caisse > Point de Vente

### Interface POS

**Zones de l'écran :**

```
┌─────────────────────────┬──────────────────┐
│  Recherche Produit      │  Résumé Vente    │
│  Liste Produits         │  Panier          │
│                         │  Paiement        │
│                         │  Totaux          │
└─────────────────────────┴──────────────────┘
```

### Étape 1 : Sélectionner le Client (Optionnel)

1. **Client passager** : Ne rien sélectionner
2. **Client fidèle** :
    - Liste déroulante "Client"
    - Sélectionnez le client
    - Son crédit disponible s'affiche

### Étape 2 : Ajouter des Produits

**Méthode 1 : Recherche**

1. Tapez dans la barre de recherche :
    - Nom du produit
    - Référence
    - Code-barre (à venir)
2. Cliquez sur le produit dans les résultats
3. Il s'ajoute au panier avec quantité = 1

**Méthode 2 : Navigation**

1. Parcourez la liste des produits disponibles
2. Produits triés par :
    - En stock en premier (alphabétique)
    - Puis épuisés (grisés)
3. Cliquez pour ajouter au panier

**Méthode 3 : Scan (à venir)**

1. Scanner le code-barre avec un lecteur
2. Produit ajouté automatiquement

### Étape 3 : Gérer le Panier

**Pour chaque article :**

**Modifier la Quantité**

-   Boutons **+** / **-**
-   Ou saisir directement
-   Maximum = stock disponible

**Modifier le Prix**

-   Cliquez dans le champ "Prix unitaire"
-   Modifiez (si vous avez les droits)
-   Recalcul automatique

**Retirer un Article**

-   Icône poubelle (🗑️)
-   Confirmation

### Étape 4 : Appliquer une Remise (Optionnel)

**Remise Globale :**

1. Champ "Remise globale (%)"
2. Entrez le pourcentage : Ex: 5
3. Totaux recalculés automatiquement

**Exemple :**

```
Sous-total HT : 50 000 FCFA
TVA (19.25%) : 9 625 FCFA
Total TTC : 59 625 FCFA

Remise 5% : -2 981 FCFA
TOTAL À PAYER : 56 644 FCFA
```

### Étape 5 : Choisir le Mode de Paiement

**4 Options :**

#### **A) Espèces**

1. Sélectionnez "Espèces"
2. Entrez le **montant payé**
    - Ex: Client donne 60 000 FCFA
3. Le **rendu** est calculé automatiquement
    - Ex: Rendu = 3 356 FCFA
4. Remettez la monnaie au client

#### **B) Mobile Money**

1. Sélectionnez "Mobile Money"
2. Entrez le montant payé
3. Vérifiez la transaction sur votre téléphone
4. Attendez la confirmation SMS

#### **C) Crédit**

1. **Client obligatoire** (sélectionné à l'étape 1)
2. Sélectionnez "Crédit"
3. Le système vérifie :
    - Le client a une limite de crédit
    - Le montant ne dépasse pas la limite
4. Si OK, validation possible
5. Le crédit du client augmente

#### **D) Mixte**

1. Sélectionnez "Mixte"
2. Entrez :
    - Montant espèces
    - Montant mobile money
    - Montant crédit (si client)
3. Total doit correspondre au montant à payer

### Étape 6 : Valider la Vente

1. Vérifiez tous les détails
2. Cliquez sur **"Valider la vente"** (bouton vert)
3. Confirmation affichée
4. **Options :**
    - **Imprimer le ticket** immédiatement
    - **Nouvelle vente** pour continuer
    - **Voir la vente** pour consulter

### Imprimer le Ticket

**Automatique ou Manuel :**

1. Ticket au format thermique (80mm)
2. Contient :
    - Logo ETS DUBAI
    - N° de vente
    - Date et heure
    - Vendeur
    - Liste des articles
    - Totaux (HT, TVA, TTC)
    - Mode de paiement
    - Montant payé / Rendu
3. Bouton **"Imprimer"**
4. Utilise l'imprimante par défaut

---

## 3. Gérer les Clients

### Créer un Client

**Quand ?** Client régulier qui souhaite acheter à crédit

**Procédure :**

1. **Navigation :** Ventes & Caisse > Clients
2. **"Nouveau Client"**
3. **Informations :**

    - Nom complet
    - Téléphone
    - Email (optionnel)
    - Adresse
    - Quartier / Ville

4. **Crédit :**

    - **Autoriser le crédit** : Oui/Non
    - Si Oui :
        - **Limite de crédit** : Ex: 500 000 FCFA
        - Le client pourra acheter à crédit dans cette limite

5. **Notes** : Infos supplémentaires

6. **Statut** : Actif

7. **Enregistrer**

### Consulter un Client

1. Liste des clients
2. Cliquez sur le nom
3. **Fiche Client affichée :**
    - Coordonnées
    - Limite de crédit
    - **Crédit actuel** (en rouge si élevé)
    - Crédit disponible
    - Historique des achats
    - Total acheté
    - Dernier achat

### Enregistrer un Paiement Crédit

**Contexte :** Le client rembourse une partie de son crédit

**Procédure :**

1. Fiche client
2. Section **"Ajuster le crédit"**
3. **Type** : Réduire (paiement)
4. **Montant** : Montant remboursé
5. **Motif** : "Paiement espèces" ou "Paiement mobile money"
6. **Valider**
7. Le crédit du client diminue

---

## 4. Consulter le Stock

### Accéder

**Navigation :** Gestion Stocks

### Vue Stock

**Informations affichées :**

-   Liste des produits
-   **Stock actuel à votre point de vente**
-   Valeur du stock
-   Produits en alerte (rouge)
-   Produits OK (vert)

**Filtres :**

-   Par catégorie
-   Par fournisseur
-   Par niveau de stock
-   Recherche

### Détails d'un Produit

1. Cliquez sur un produit
2. **Informations :**
    - Stock actuel
    - Valeur totale
    - Prix de vente
    - Historique des mouvements :
        - Date
        - Type (Vente, Transfert reçu, Ajustement)
        - Quantité
        - Solde après mouvement

---

## 5. Demander un Transfert de Stock

### Quand ?

-   Produit en rupture
-   Stock bas
-   Besoin urgent

### Procédure

1. **Navigation :** Transferts Stock
2. **"Nouvelle Demande"**
3. **Point source** : Dubai Magasin (généralement)
4. **Ajouter des produits :**
    - Recherchez le produit
    - Ajoutez-le
    - Entrez la quantité souhaitée
    - Le stock disponible à la source s'affiche
5. **Priorité** : Normale / Urgente
6. **Notes** : Justification (ex: "Rupture, forte demande")
7. **Envoyer la demande**

**Résultat :**

-   Demande en statut `En attente de validation`
-   Le responsable reçoit une notification
-   Vous recevez une notification de validation/refus

### Suivre une Demande

1. **Navigation :** Transferts Stock
2. Onglets :
    - **Mes demandes** : Toutes vos demandes
    - **À réceptionner** : Celles expédiées vers vous
3. Statuts possibles :
    - `En attente` : Pas encore validée
    - `Validée` : Approuvée, en attente d'expédition
    - `Expédiée` : En route vers vous
    - `Reçue` : Livrée et réceptionnée
    - `Refusée` : Rejetée (voir motif)
    - `Annulée` : Annulée avant expédition

### Réceptionner un Transfert

**Contexte :** Le transfert est arrivé physiquement

1. Transfert en statut `Expédié`
2. **"Réceptionner"**
3. Vérifiez les produits :
    - Cochez chaque produit reçu
    - Vérifiez les quantités
    - Signalez les anomalies (casse, manquant)
4. Ajoutez des notes si problème
5. **Confirmer la réception**
6. Les stocks sont **automatiquement ajoutés** à votre point

---

## 6. Consulter ses Rapports

### Accéder

**Navigation :** Rapports

### Rapports Disponibles (Vos Données Uniquement)

#### **Mes Ventes**

**Période sélectionnable**

**Données :**

-   Votre CA
-   Votre nombre de ventes
-   Votre panier moyen
-   Vos top produits
-   Graphique d'évolution

**Comparaison :**

-   Vs hier
-   Vs semaine dernière
-   Vs mois dernier

#### **Mon Stock**

-   Stock actuel à votre point
-   Valorisation
-   Alertes

#### **Mes Clients**

-   Clients créés par vous
-   Clients ayant acheté chez vous
-   Crédit en cours
-   Top clients

---

## 7. Cas d'Usage : Journée Type

### Matin (7h30 - 8h)

**1. Arrivée**

-   Se connecter
-   Consulter le dashboard
-   Vérifier les notifications

**2. Ouvrir la Caisse**

-   Compter le fond de caisse
-   Ouvrir la caisse dans l'application
-   Préparer la monnaie

**3. Vérifier le Stock**

-   Consulter les produits disponibles
-   Identifier les ruptures
-   Demander des transferts si nécessaire

### Journée (8h - 18h)

**4. Ventes**

-   Accueillir les clients
-   Enregistrer les ventes dans le POS
-   Imprimer les tickets
-   Gérer les paiements

**5. Pause (selon planning)**

**6. Réception de Transferts**

-   Si livraison, réceptionner dans le système
-   Vérifier les quantités
-   Ranger la marchandise

**7. Gestion Clients**

-   Enregistrer les nouveaux clients
-   Enregistrer les paiements de crédit

### Soir (18h - 19h)

**8. Dernières Ventes**

-   Finaliser les ventes en cours

**9. Clôture de Caisse**

-   Compter l'argent
-   Clôturer la caisse dans l'application
-   Justifier les écarts éventuels

**10. Rapport**

-   Consulter le CA du jour
-   Vérifier les objectifs

**11. Départ**

-   Sécuriser l'argent
-   Fermer le point de vente
-   Se déconnecter

---

# ❓ FAQ & Dépannage

## Questions Fréquentes

### **Q1 : J'ai oublié mon mot de passe, que faire ?**

**R :**

1. Page de connexion > "Mot de passe oublié ?"
2. Entrez votre email
3. Vous recevrez un lien de réinitialisation
4. Si pas reçu, contactez votre administrateur

---

### **Q2 : Je ne peux pas faire de vente, pourquoi ?**

**R :** Vérifiez que :

1. ✅ Vous avez une caisse ouverte
2. ✅ Le produit a du stock disponible
3. ✅ Votre compte est actif
4. ✅ Vous êtes connecté à Internet

**Solution :** Ouvrez une caisse d'abord !

---

### **Q3 : Comment annuler une vente ?**

**R :**

-   ✅ Possible uniquement le jour même
-   1. Ventes > Historique
-   2. Cliquez sur la vente
-   3. Bouton "Annuler"
-   4. Indiquez le motif
-   5. Confirmez
-   ⚠️ Les stocks sont restaurés automatiquement

**Impossible après 24h** pour des raisons comptables.

---

### **Q4 : Le client veut acheter à crédit mais le système refuse**

**R :** Vérifiez :

1. Le client est bien enregistré dans le système
2. Le crédit est activé pour ce client
3. Sa limite de crédit n'est pas atteinte
4. Son compte est actif

**Si limite atteinte :**

-   Demander un paiement partiel
-   Ou contacter le responsable pour augmenter la limite

---

### **Q5 : Un produit est grisé dans le POS, pourquoi ?**

**R :** Le produit est **épuisé** à votre point de vente.

**Solutions :**

1. Demander un transfert du dépôt central
2. Proposer un produit similaire au client
3. Prendre une commande pour livraison ultérieure

---

### **Q6 : Écart de caisse, que faire ?**

**R :**

-   **Écart normal :** < 1% du CA
    -   Justifiez simplement (erreur de rendu, etc.)
-   **Écart important :** > 1%
    -   Recomptez l'argent
    -   Vérifiez toutes les ventes du jour
    -   Cherchez les erreurs possibles
    -   Signalez à votre responsable

---

### **Q7 : Comment voir mes performances ?**

**R :**

-   **Dashboard** : Vue d'ensemble du jour
-   **Rapports > Mes Ventes** : Statistiques détaillées
-   Comparez avec vos objectifs
-   Consultez votre évolution

---

### **Q8 : Je veux changer mon mot de passe**

**R :**

1. Cliquez sur votre profil (en haut à droite)
2. "Mon profil"
3. Section "Changer le mot de passe"
4. Entrez :
    - Mot de passe actuel
    - Nouveau mot de passe (min. 8 caractères)
    - Confirmation
5. Enregistrer

---

### **Q9 : Un produit est manquant dans le POS**

**R :** Le produit n'apparaît pas si :

-   Il est inactif (désactivé)
-   Il n'a plus de stock à votre point
-   Il n'a pas été transféré à votre point

**Solution :** Contactez le responsable.

---

### **Q10 : L'imprimante ne fonctionne pas**

**R :**

1. Vérifiez qu'elle est allumée et connectée
2. Vérifiez le papier
3. Essayez d'imprimer une page test depuis Windows
4. Relancez le navigateur
5. Si ça persiste, appelez le support technique

---

## Problèmes Techniques

### **Problème : Page blanche ou erreur 500**

**Causes possibles :**

-   Serveur en maintenance
-   Problème de connexion
-   Bug temporaire

**Solutions :**

1. Rafraîchissez la page (F5)
2. Videz le cache du navigateur (Ctrl+Shift+Del)
3. Déconnectez-vous et reconnectez-vous
4. Si ça persiste, contactez l'administrateur

---

### **Problème : Impossible de se connecter**

**Vérifiez :**

1. L'email est correct (pas d'espace, bon format)
2. Le mot de passe est correct (majuscules/minuscules)
3. Votre compte est actif (demandez à l'admin)
4. Vous êtes sur la bonne URL

**Messages d'erreur :**

-   "Compte désactivé" → Contactez l'admin
-   "Email/mot de passe incorrects" → Réinitialisez votre mot de passe
-   "Trop de tentatives" → Attendez 5 minutes

---

### **Problème : Les données ne se chargent pas**

**Causes :**

-   Connexion Internet lente
-   Serveur surchargé

**Solutions :**

1. Attendez quelques secondes
2. Rafraîchissez
3. Vérifiez votre connexion Internet
4. Essayez sur un autre navigateur

---

### **Problème : Le POS est lent**

**Solutions :**

1. Fermez les autres onglets du navigateur
2. Videz le cache
3. Redémarrez le navigateur
4. Vérifiez votre connexion Internet
5. Utilisez Chrome ou Edge (recommandé)

---

## Bonnes Pratiques

### ✅ Sécurité

1. **Ne partagez jamais votre mot de passe**
2. **Déconnectez-vous** quand vous quittez
3. **Changez votre mot de passe** régulièrement
4. **Verrouillez votre écran** pendant les pauses

### ✅ Utilisation Quotidienne

1. **Ouvrez la caisse** dès le matin
2. **Vérifiez le stock** régulièrement
3. **Clôturez la caisse** chaque soir
4. **Enregistrez chaque vente** (même petite)
5. **Imprimez les tickets** pour le client

### ✅ Performance

1. **Soyez rapide** au POS (clients satisfaits)
2. **Conseillez les clients** (augmente le panier)
3. **Proposez le crédit** aux bons clients
4. **Demandez les transferts** à l'avance
5. **Suivez vos objectifs** quotidiens

---

## Contact & Support

### 📞 Support Technique

**Problèmes techniques, bugs, questions :**

-   **Email :** support@etsdubai.cm
-   **Téléphone :** +237 XXX XXX XXX
-   **Disponibilité :** Lun-Sam, 8h-18h

### 👨‍💼 Questions Métier

**Procédures, autorisations, clients :**

-   Contactez votre **Responsable**
-   Ou l'**Administrateur**

### 📚 Documentation

-   **Guide utilisateur :** Ce document
-   **Présentation :** `PRESENTATION.md`
-   **GitHub :** [Lien vers repository]

---

## Glossaire

**CA** : Chiffre d'Affaires
**FCFA** : Franc CFA (devise camerounaise)
**HT** : Hors Taxes
**TTC** : Toutes Taxes Comprises
**TVA** : Taxe sur la Valeur Ajoutée (19.25% au Cameroun)
**POS** : Point Of Sale (Point de Vente)
**Dashboard** : Tableau de bord
**Stock** : Quantité de produits disponibles
**Transfert** : Mouvement de stock entre points de vente
**Ristourne** : Remise accordée par le fournisseur selon le volume
**Crédit** : Vente avec paiement différé
**Panier** : Ensemble des produits d'une vente
**Soft delete** : Suppression logique (données gardées en base)

---

**Version :** 1.0.0  
**Dernière mise à jour :** Janvier 2025  
**Auteur :** ETS DUBAI - Équipe Développement

---

**🎉 Félicitations ! Vous êtes prêt à utiliser ETS DUBAI efficacement ! 🎉**

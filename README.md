# 🏪 ETS DUBAI - Système de Gestion Commercial

## 📌 Vue d'Ensemble

**ETS DUBAI** est une application web complète de gestion commerciale conçue pour les entreprises de distribution de boissons au Cameroun. Elle permet de gérer l'ensemble des opérations : achats fournisseurs, stocks, ventes, caisses, clients et rapports.

### 🎯 Objectifs

-   Centraliser la gestion de plusieurs points de vente
-   Automatiser les opérations quotidiennes (ventes, caisses, stocks)
-   Suivre les performances en temps réel
-   Optimiser la gestion des stocks et des transferts
-   Gérer les relations fournisseurs et clients

---

## 🚀 Fonctionnalités Principales

### 1. 🔐 Gestion des Utilisateurs et Permissions

#### **3 Rôles avec Permissions Granulaires**

**👨‍💼 ADMINISTRATEUR**

-   Accès complet à toutes les fonctionnalités
-   Gestion des utilisateurs
-   Analyses détaillées des vendeurs
-   Paramètres système
-   Vue globale de l'entreprise

**🎯 RESPONSABLE (Sous-administrateur)**

-   Gestion du dépôt central
-   Commandes fournisseurs
-   Gestion des ristournes
-   Validation des transferts
-   Rapports globaux
-   Gestion produits/fournisseurs/clients

**👤 VENDEUR**

-   Point de vente (POS)
-   Gestion de sa caisse
-   Ses ventes uniquement
-   Demandes de transfert
-   Ses rapports personnels
-   Consultation produits

---

### 2. 📦 Gestion des Produits

-   **Catalogue complet** avec catégories
-   **Multi-tarification** : Prix achat/vente par conditionnement et unité
-   **Gestion des stocks** multi-points
-   **Historique** des mouvements
-   **Alertes** de stock bas
-   **Code-barre** pour scan rapide (à venir)

---

### 3. 🏪 Gestion Multi-Points de Vente

-   **Point Central** (Dubai Magasin) : Dépôt source
-   **Points de Vente** : Dubai Cave, Lounge Plus, Meyomadjom
-   **Stocks séparés** par point
-   **Transferts** automatisés entre points
-   **Rapports** par point

---

### 4. 🚚 Gestion Fournisseurs

-   **Fiche fournisseur** complète
-   **Conditions commerciales** : délais, modes de paiement
-   **Historique** des commandes
-   **Barèmes de ristournes** configurables
-   **Calcul automatique** des remises volume

---

### 5. 📋 Système de Commandes

**Workflow complet :**

1. Création commande (brouillon/envoyée)
2. Confirmation fournisseur
3. Suivi préparation
4. Expédition
5. Réception partielle/complète
6. Mise à jour stocks automatique

**Statuts :**

-   Brouillon, En attente, Confirmée, En préparation
-   Expédiée, Livrée, Partiellement livrée, Annulée

---

### 6. 🔄 Transferts de Stock

**Entre Points de Vente :**

-   **Demande** par le point demandeur
-   **Validation** par le responsable
-   **Expédition** depuis le point source
-   **Réception** avec confirmation
-   **Mise à jour** automatique des stocks

**Traçabilité complète** de chaque mouvement

---

### 7. 💰 Point de Vente (POS)

**Interface optimisée pour la vente rapide :**

-   Recherche produit instantanée
-   Ajout au panier en 1 clic
-   Calculs automatiques (HT, TVA 19.25%, TTC)
-   Remise globale
-   Multi-modes de paiement :
    -   Espèces avec calcul de rendu
    -   Mobile Money
    -   Crédit client
    -   Mixte
-   Impression ticket thermique
-   Vente client passager ou fidélisé

---

### 8. 🛒 Gestion des Ventes

-   **Historique** complet avec filtres
-   **Recherche** avancée
-   **Statistiques** en temps réel :
    -   CA total
    -   Panier moyen
    -   Nombre de ventes
    -   Répartition par mode de paiement
-   **Annulation** avec restauration stocks (même jour uniquement)
-   **Impression** tickets

---

### 9. 👥 Gestion Clients

**Fiche Client :**

-   Informations complètes
-   Limite de crédit configurable
-   Suivi crédit actuel
-   Historique achats
-   Alertes dépassement

**Crédit Client :**

-   Activation par client
-   Limite personnalisée
-   Blocage automatique si dépassement
-   Suivi paiements

---

### 10. 💵 Gestion des Caisses

**Cycle de Caisse :**

1. **Ouverture** : Fond de caisse obligatoire
2. **Opérations** : Ventes, entrées, sorties
3. **Clôture** : Réconciliation automatique
4. **Rapport** : Écarts, mouvements détaillés

**Suivi :**

-   Fond ouverture/clôture
-   Espèces théoriques vs réelles
-   Écarts avec justifications
-   Historique des opérations

---

### 11. 📊 Ristournes Fournisseurs

**Système de Remises Volume :**

-   **Barèmes configurables** par fournisseur
-   **Périodes** : mensuelle, trimestrielle, annuelle
-   **Seuils progressifs** en montant ou volume
-   **Calcul automatique** à la fin de période
-   **Validation** par responsable
-   **Paiement** ou avoir fournisseur
-   **Simulateur** pour projections

**Exemple :**

```
Fournisseur : Brasseries du Cameroun
Période : Mensuelle
Barème :
- 0 à 5M FCFA → 0%
- 5M à 10M → 2%
- 10M à 20M → 5%
- > 20M → 8%

Achats septembre : 15M FCFA
→ Ristourne = 750 000 FCFA (5%)
```

---

### 12. 📈 Rapports et Analyses

**Rapports Disponibles :**

**📊 Ventes**

-   CA par période
-   Top produits
-   Performance par vendeur
-   Évolution temporelle

**📦 Stocks**

-   État actuel tous points
-   Mouvements
-   Valorisation
-   Alertes rupture
-   Rotation des stocks

**🛒 Achats**

-   Commandes par fournisseur
-   Dépenses
-   Délais livraison
-   Ristournes générées

**💰 Financier**

-   Trésorerie
-   Créances clients
-   Dettes fournisseurs
-   Marges

**Filtres :** Période, point de vente, catégorie, fournisseur

---

### 13. 🔍 Analyses Vendeurs (Admin)

**Tableau de bord complet :**

-   Performance par vendeur
-   CA individuel
-   Nombre de ventes
-   Panier moyen
-   Comparaisons
-   Logs d'activité

**Objectif :** Identifier les meilleurs vendeurs et optimiser les performances

---

### 14. ⚙️ Paramètres Système

**Configuration Globale :**

-   Informations entreprise
-   TVA par défaut (19.25%)
-   Devise (FCFA)
-   Points de vente
-   Catégories de produits
-   Modes de paiement
-   Règles métier

---

## 🛠️ Technologies Utilisées

### **Backend**

-   **Laravel 11** - Framework PHP moderne
-   **PHP 8.3** - Langage serveur
-   **SQLite** (dev) / **PostgreSQL** (prod) - Bases de données

### **Frontend**

-   **Tailwind CSS** - Framework CSS utility-first
-   **Alpine.js** - JavaScript réactif léger
-   **Blade Templates** - Moteur de templates Laravel

### **Librairies**

-   **Chart.js** - Graphiques interactifs
-   **Laravel Breeze** - Authentification

### **Architecture**

-   **MVC** - Modèle-Vue-Contrôleur
-   **REST** - API conventions
-   **SPA Partiel** - JavaScript où nécessaire

---

## 📊 Architecture de la Base de Données

### **Tables Principales**

**👥 Utilisateurs**

-   `users` - Comptes utilisateurs (3 rôles)
-   `password_reset_tokens` - Réinitialisation mot de passe

**🏢 Configuration**

-   `points_vente` - Points de vente
-   `categories` - Catégories produits

**📦 Produits & Stock**

-   `produits` - Catalogue produits
-   `stocks_produits` - Stock par point de vente
-   `mouvements_stocks` - Historique mouvements

**🚚 Achats**

-   `fournisseurs` - Fournisseurs
-   `commandes` - Commandes fournisseurs
-   `commandes_items` - Lignes de commandes
-   `ristournes` - Ristournes fournisseurs
-   `ristournes_baremes` - Barèmes de remise

**🔄 Transferts**

-   `transferts_stocks` - Transferts entre points
-   `lignes_transferts` - Détail transferts

**💰 Ventes**

-   `clients` - Clients
-   `ventes` - Ventes
-   `lignes_vente` - Lignes de vente
-   `caisses` - Caisses
-   `mouvements_caisses` - Mouvements de caisse

### **Relations Clés**

-   1 User → N Ventes
-   1 Point de Vente → N Users (vendeurs)
-   1 Produit → N Stocks (par point)
-   1 Fournisseur → N Commandes
-   1 Fournisseur → 1 Ristourne (par période)

---

## 🔒 Sécurité

### **Authentification**

-   Hachage sécurisé des mots de passe (bcrypt)
-   Protection CSRF sur tous les formulaires
-   Session sécurisée
-   Vérification email

### **Autorisations**

-   Middleware de permissions par rôle
-   Contrôle d'accès granulaire
-   Isolation des données par point de vente
-   Désactivation de compte instantanée

### **Données**

-   Soft deletes (suppression logique)
-   Logs d'activité
-   Traçabilité complète
-   Validation stricte des entrées

---

## 📱 Responsive Design

**Interface adaptée à tous les appareils :**

-   💻 **Desktop** - Interface complète
-   📱 **Tablette** - Optimisé pour POS
-   📱 **Mobile** - Navigation simplifiée

**Menu latéral rétractable** sur mobile

---

## 🎨 Interface Utilisateur

### **Design System**

-   **Couleurs** : Bleu (primary), Vert (success), Rouge (danger), Orange (warning)
-   **Typographie** : Inter (Google Fonts)
-   **Icônes** : Heroicons (SVG)
-   **Composants** : Cards, Badges, Modals, Toasts

### **Expérience Utilisateur**

-   Navigation intuitive
-   Feedback visuel immédiat
-   Messages de confirmation
-   Loader pendant les opérations
-   Animations fluides

---

## 📊 Statistiques de l'Application

**Code :**

-   ~50 fichiers controllers
-   ~80 vues Blade
-   ~25 models Eloquent
-   ~40 migrations
-   ~10 seeders

**Fonctionnalités :**

-   14 modules complets
-   3 rôles utilisateurs
-   8 types de rapports
-   100+ routes

---

## 🚀 Performance

**Optimisations :**

-   Eager loading (N+1 queries évité)
-   Cache des requêtes fréquentes
-   Pagination automatique
-   Index sur colonnes critiques
-   Assets minifiés (Vite)

**Temps de réponse moyen :** < 200ms

---

## 🌍 Localisation

**Langue :** Français
**Devise :** FCFA (Franc CFA)
**TVA :** 19.25% (Cameroun)
**Format date :** DD/MM/YYYY
**Fuseau horaire :** Africa/Sangmelima (UTC+1)

---

## 📈 Évolutions Futures

### **Court Terme**

-   Scanner code-barre dans POS
-   Export Excel des rapports
-   Notifications push
-   Mode sombre

### **Moyen Terme**

-   Application mobile (PWA)
-   API REST complète
-   Intégration paiement mobile (MTN, Orange Money)
-   Facturation électronique

### **Long Terme**

-   BI avancée (Business Intelligence)
-   Machine Learning pour prédictions
-   Module RH
-   Module comptabilité

---

## 💼 Cas d'Usage Typiques

### **Scenario 1 : Matinée du Vendeur**

1. Connexion → Dashboard
2. Ouvrir la caisse (fond 50 000 FCFA)
3. Créer ventes clients
4. Consulter stock
5. Demander transfert si rupture
6. Clôturer caisse en fin de journée

### **Scenario 2 : Responsable Dépôt**

1. Vérifier stock global
2. Créer commande fournisseur
3. Valider transferts demandés
4. Réceptionner livraison fournisseur
5. Consulter ristournes du mois
6. Générer rapports pour direction

### **Scenario 3 : Administrateur**

1. Vue d'ensemble entreprise
2. Analyser performances vendeurs
3. Créer nouveau point de vente
4. Ajouter utilisateur
5. Configurer paramètres système
6. Exporter rapports complets

---

## 👨‍💻 Équipe de Développement

**Développeur Principal :** [Ton Nom]
**Client :** ETS DUBAI
**Durée Développement :** [X semaines]
**Stack :** Laravel, Tailwind, Alpine.js

---

## 📞 Support

**Documentation :** Voir `GUIDE_UTILISATION.md`
**GitHub :** [Lien vers repo]
**Email Support :** support@etsdubai.cm

---

## 📜 Licence

**Propriétaire :** ETS DUBAI
**Usage :** Application privée à usage interne
**Copyright :** © 2025 ETS DUBAI - Tous droits réservés

---

**Version :** 1.0.0  
**Dernière mise à jour :** Janvier 2025  
**Statut :** ✅ Production Ready

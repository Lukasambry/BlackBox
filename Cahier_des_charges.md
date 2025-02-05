# Cahier des Charges - Silent

## 1. Présentation du Projet

### 1.1 Contexte
Silent est une application web permettant d'organiser des sessions de partage d'anecdotes en temps réel entre utilisateurs. Le concept s'inspire des jeux de société collaboratifs, transposé dans un environnement numérique.

### 1.2 Objectifs
- Créer une plateforme sociale interactive et ludique
- Favoriser le partage d'expériences entre utilisateurs
- Offrir une expérience de jeu fluide et engageante
- Garantir l'anonymat des participants pendant les phases de vote

## 2. Fonctionnalités Principales

### 2.1 Gestion des Utilisateurs
- Inscription avec email et pseudo
- Confirmation par email
- Authentification sécurisée
- Profil utilisateur personnalisable
- Système de rôles (utilisateur, administrateur)
- Export des logs d'activité

### 2.2 Gestion des Salles
- Création de salles publiques ou privées
- Limitation du nombre de participants (2-10 joueurs)
- Système de code d'invitation pour les salles privées
- Interface d'administration des salles
- Statuts des salles (en attente, en cours, terminée)

### 2.3 Système de Jeu
Le jeu se déroule en plusieurs phases :

#### Phase de Préparation
- Sélection aléatoire d'un thème
- Période d'attente pour les joueurs (10 secondes)
- Affichage du décompte

#### Phase de Jeu
- Temps limité (30 secondes)
- Saisie des anecdotes par les joueurs
- Soumission automatique à la fin du temps

#### Phase de Vote
- Présentation successive des anecdotes
- Système de vote binaire (positif/négatif)
- Impossibilité de voter pour sa propre anecdote
- Temps limité par anecdote (20 secondes)

#### Phase de Résultats
- Classement des anecdotes
- Affichage des scores
- Identification des auteurs
- Option de démarrer une nouvelle partie

## 3. Aspects Techniques

### 3.1 Architecture
- Framework Symfony
- Base de données PostgreSQL
- Interface utilisateur avec Twig et Tailwind CSS
- Communication en temps réel

### 3.2 Sécurité
- Authentification sécurisée
- Protection CSRF
- Validation des données utilisateur
- Gestion des permissions (voters)
- Journalisation des actions

### 3.3 Performance
- Mise en cache optimisée
- Requêtes asynchrones
- Gestion efficace des sessions multijoueurs

## 4. Interface Utilisateur

### 4.1 Design
- Interface moderne et responsive
- Thème sombre avec dégradés
- Animations fluides
- Retours visuels des actions
- Indicateurs de progression

### 4.2 Navigation
- Menu principal intuitif
- Accès rapide aux fonctionnalités
- Boutons d'action contextuels
- Messages de confirmation/erreur

## 5. Administration

### 5.1 Panneau d'Administration
- Gestion des utilisateurs
- Modération des salles
- Gestion des thèmes
- Visualisation des logs
- Statistiques d'utilisation

### 5.2 Gestion du Contenu
- Création/modification des thèmes
- Suivi des sessions de jeu
- Maintenance des données

## 6. Tests et Qualité

### 6.1 Tests Automatisés
- Tests unitaires
- Tests fonctionnels
- Tests d'intégration

### 6.2 Qualité du Code
- Respect des standards PSR
- Documentation du code
- Revue de code
- Intégration continue

## 7. Évolutions Futures

### 7.1 Fonctionnalités Envisagées
- Système de classement global
- Modes de jeu supplémentaires
- Personnalisation avancée des salles
- Intégration de médias
- Application mobile

### 7.2 Améliorations Techniques
- Optimisation des performances
- Scalabilité améliorée
- Nouvelles intégrations
- API publique

## 8. Support et Maintenance

### 8.1 Documentation
- Guide d'installation
- Documentation technique
- Guide utilisateur
- Procédures de maintenance

### 8.2 Maintenance
- Mises à jour de sécurité
- Correctifs de bugs
- Sauvegardes régulières
- Monitoring
# Project Context

## 1. Vision
- Produit: boutique e-commerce mono-vendeur
- Probleme resolu: proposer un parcours d'achat simple et clair
- Valeur pour l'utilisateur: trouver rapidement un produit, comparer via filtres et acceder aux informations detaillees

## 2. Objectifs
- Objectif principal: livrer un MVP e-commerce fonctionnel en Laravel + Inertia React
- KPIs de succes: taux de conversion, taux d'abandon navigation, taux d'inscription
- Deadline cible: a definir

## 3. Perimetre MVP
### Inclus
- Page d'accueil
- Header avec categories
- Footer avec pages legales et cookies
- Listing produits par categorie
- Sidebar filtres (prix, type, format)
- Page detail produit
- Auth Laravel (register/login/logout)
- Base panier et preparation checkout Stripe

### Exclu
- Marketplace multi-vendeur
- Logique livraison avancee (zones/transporteurs dynamiques)
- Fonctionnalites de fidelite avancees

## 4. Stack cible
- Frontend: Inertia.js + React
- Backend: Laravel
- Base de donnees: MySQL (propose, a confirmer)
- Infra / deploiement: a confirmer
- Outils qualite (lint/test/CI): ESLint, formatter JS, tests Laravel, CI lint+test+build

## 5. Contraintes
- Securite: validation stricte, auth securisee, protections CSRF
- Performance: navigation catalogue fluide, pages principales rapides
- Budget: a definir
- Reglementaire: pages legales et cookies obligatoires pour le MVP
- Compatibilite: navigateur desktop et mobile recents

## 6. Standards d'equipe
- Conventions de code: bonnes pratiques Laravel et React, code lisible et testable
- Strategie de branching: feature branches + pull requests
- Politique de review: au moins une review avant merge
- Definition of Done: fonctionnalite testee, lint OK, spec/doc mise a jour

## 7. Architecture (haut niveau)
- Modules principaux: catalogue, auth, produits, panier, legal
- Interfaces entre modules: Laravel fournit les donnees aux pages Inertia React
- Points sensibles / risques: filtrage performant, preparation Stripe, choix livraison encore ouvert

## 8. Plan d'execution
- Phase 1: setup technique, auth et layout global
- Phase 2: catalogue, categories, filtres et detail produit
- Phase 3: panier, preparation checkout Stripe et stabilisation

## 9. Decisions ouvertes
- [ ] Regles de livraison MVP
- [ ] Hebergement cible
- [ ] Validation finale DB et strategie de cache

## 10. Historique des changements
- 2026-04-08: contexte complete pour projet e-commerce MVP mono-vendeur

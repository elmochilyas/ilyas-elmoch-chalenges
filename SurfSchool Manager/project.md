Contexte du projet

Vous travaillez pour PixelCraft Agency, une agence qui transforme les entreprises locales grâce au digital. Votre client, Taghazout Surf Expo, est victime de son succès : les inscriptions papier et les fichiers Excel ne permettent plus de suivre efficacement les centaines d'élèves qui viennent surfer chaque semaine.

Le Problème : Les moniteurs ne connaissent pas le niveau des élèves avant la session, et les gérants ont du mal à suivre qui a payé ses cours.

En tant que développeur Backend, votre rôle est de :

Implémenter l'architecture MVC (Model-View-Controller) : Séparer physiquement la logique de données, la logique métier et l'affichage pour un code maintenable.

Adopter la POO (Programmation Orientée Objet) : Créer des classes (ex: Student, Lesson, Database) avec une utilisation stricte de l'encapsulation (propriétés private et méthodes public).

Gérer le Relationnel Avancé : Concevoir une base de données normalisée (3 tables : users, students, lessons) avec une table de liaison ou des clés étrangères pour les inscriptions.

Garantir la Sécurité & l'Intégrité : Utiliser PDO (Prepared Statements), le hachage des mots de passe et la validation stricte des formulaires.

User Stories (Besoins Business)

A. Le Gérant (Accès Administrateur)

US1 (Pilotage Global) : En tant que Gérant, je veux me connecter pour accéder au tableau de bord affichant tous les élèves et tous les cours prévus.

US2 (Gestion du Planning) : En tant que Gérant, je veux créer une session de surf (Titre, Coach, Date/Heure) et y inscrire des élèves selon leur niveau.

US3 (Suivi Pédagogique) : En tant que Gérant, je veux pouvoir modifier le niveau d'un élève (Débutant, Intermédiaire, Avancé) pour assurer son suivi après chaque cours.

B. Le Surfeur (Accès Client)

US4 (Auto-Enregistrement) : En tant que nouveau surfeur, je veux créer mon profil (Nom, Pays, Niveau 
auto-évalué) pour être répertorié dans le système de l'école.

US5 (Mon Agenda) : En tant que surfeur connecté, je veux consulter uniquement la liste de mes cours à venir et vérifier si mon statut de paiement est à jour ("Payé" ou "En attente").

++ Bonus

MVC Router : Mettre en place un point d'entrée unique (index.php) qui dirige les requêtes vers les bons contrôleurs.

Logiciel de Stats : Afficher sur le dashboard le taux de remplissage moyen des cours (ex: "Moyenne de 5 élèves par session").

------------------------------------------------------------------------------------------------------

⚠️ Règle d'Or de PixelCraft Agency : > L'usage de l'IA (ChatGPT, Claude, Copilot) est strictement 

interdit pour la génération de la Logique Métier et de la Structure du projet.
Interdit : Demander à l'IA de générer une classe entière, un contrôleur ou la structure des dossiers.
Interdit : Copier-coller du code logique (boucles, conditions, calculs) sans être capable de l'expliquer ligne par ligne.

Autorisé : Utiliser l'IA comme une documentation (ex: "Quelle est la syntaxe d'un try/catch en PHP 8.4 ?").
Autorisé : Générer du contenu de test (Seeding) ou du CSS/HTML de base pour le design.
Sanction : Tout code "magique" non maîtrisé lors de la revue de code entraînera l'invalidation de la User Story correspondante.

Modalités pédagogiques

Mode : Individuel (5 jours).

Date de lancement : Lundi 30/03/2026 – 09:45 AM.
Deadline : Vendredi 03/04/2026 – 17:00 PM.

Modalités d'évaluation
Entretien individuel de 25 à 30 minutes composé de :
Démonstration (10 min) : Parcours utilisateur complet (Inscription -> Création de cours -> Consultation agenda client).
Code Review (10 min) : Analyse de la structure des dossiers et vérification de l'encapsulation dans les classes.
Mise en situation (10 min) : Modification "Live" (ex: "Ajoutez un champ 'Téléphone' à l'élève et affichez-le dans le dashboard gérant").
Livrables
Dépôt GitHub : Minimum 10 commits explicites reflétant l'évolution (ex: "Add MVC Structure", "Create Student Class", etc.).

Fichier SQL : Script complet de création et de "seeding" (données de test).

README.md : Documentation incluant le schéma DB et l'arborescence du projet.
Critères de performance
Zéro SQL dans la Vue : Aucune requête PDO ne doit apparaître dans les fichiers HTML.

Encapsulation : Utilisation correcte des visibilités (private/public) dans les classes.

Propreté : Indentation parfaite et séparation logique des responsabilités (MVC).
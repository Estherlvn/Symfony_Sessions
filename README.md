# Symfony Sessions

Symfony Sessions est une application web permettant de gérer des sessions de formations pour les administrateurs d’un centre de formation.

## 🚀 Objectif
L'application permet aux administrateurs de créer, gérer et suivre les sessions de formation, les modules associés ainsi que les stagiaires inscrits.

---

## 🛠️ Technologies Utilisées

- **Langage Backend** : PHP 8.2
- **Framework** : Symfony 7
- **Base de données** : HeidiSQL (MySQL)
- **ORM** : Doctrine
- **Template Engine** : Twig
- **Frontend** : CSS, Bootstrap, JavaScript

---

## 📂 Architecture MVC

- **Controllers** : Gèrent la logique de traitement.
- **Models** : Gèrent l’interaction avec la base de données (Entity, Repository).
- **Views** : Affichent les pages via Twig.

### Structure du projet
```
📁 Symfony_Sessions
│-- 📁 public
│   ├── index.php  # Point d'entrée du projet
│-- 📁 templates
│   ├── base.html.twig  # Fichier squelette (layout commun)
│-- .env  # Configuration des variables d'environnement
│-- composer.json  # Dépendances PHP
│-- symfony.lock  # Fichier de verrouillage des dépendances
```

## 📥 Installation

### 1️⃣ Cloner le projet
```bash
cd /chemin_souhaité
git clone https://github.com/Estherlvn/Symfony_Sessions.git
```

### 2️⃣ Installer les dépendances
```bash
cd Symfony_Sessions
composer install
```

### 3️⃣ Configurer la base de données

- Ouvrir le fichier `.env`
- Modifier la ligne suivante avec vos informations de base de données :
```env
DATABASE_URL="mysql://root@127.0.0.1:3306/nom_de_la_base"
```

### 4️⃣ Exécuter les migrations
```bash
symfony console doctrine:migrations:migrate
```

### 5️⃣ Lancer le serveur Symfony
```bash
symfony serve -d
```
L'application sera accessible via `http://127.0.0.1:8000`

---

## ⚙️ Fonctionnalités
- Gestion des sessions de formation
- Gestion des stagiaires
- Affichage des modules par catégorie
- Affichage du nombre de places restantes par session
- Ajout et suppression des sessions et des stagiaires
- Programmation des modules dans les sessions

---

## 🚀 Déploiement
L'application peut être hébergée sur un serveur web compatible avec Symfony 7 (Apache/Nginx, PHP 8.2, MySQL).

---

💡 **Auteur** : [Estherlvn](https://github.com/Estherlvn)


# JRA API RESTful

## Introduction

Ce projet est une API RESTful permettant de gérer des contacts et des véhicules.
Il aurait été plus simple de réaliser une `api` basée sur `apache` concernant la gestion des routes, cela aurait été plus simple et plus rapide à mettre en place.
J'aurais probablement fais cela si l'idée avait été de mettre en place un micro-service suivant la logique d'une solution `SAAS`.
Mais ici l'idée est de montrer mes compétences en `PHP` et en `API RESTful` a travers sur une solution `from scratch` avec une deadline de `7 jours`.
J'ai donc du tout repenser et tout réécrire pour coller au mieux à la consigne, je me suis bien evidemment inspiré de mes experiences sur certains frameworks existants mais sans en consulter le code, un peu comme Linus Torvalds lorsqu'il créa `Linux`.
Finalement c'est un debut de framework qui a vu le jour, et je suis assez satisfais du resultat obtenu meme si je sais que cela peut etre largement amelioré.
Je n'ai pas eu le temps de mettre en place les tests unitaires ainsi qu'un gestionnaire centralisé pour des erreurs et des exceptions.

Temps : 54h

Bonne lecture !

## Features

- [x] API RESTful
  - [x] Authentification utilisateur JWT
    - [x] Mettre en place un système d'authentification avec gestion des rôles.
    - [x] CRUD pour les ADMIN (`BONUS`)
  - [x] Gestion des contacts
    - [x] Lire la liste paginée des contacts. USER et ADMIN
    - [x] CRUD pour les ADMIN
    - [x] Historique des modifications
      - [x] Conserver un historique des modifications effectuées sur les contacts.
    - [ ] Permettre la recherche et le filtrage des contacts. USER et ADMIN
    - [x] Recherche et filtres sur les contacts
      - [x] Contacts ayant un véhicule de +30000km
      - [x] Contacts ayant un véhicule de +3 ans
      - [x] Contacts n’ayant pas de véhicule
      - [x] Filtrage par model, marque...
  - [x] Gestion des Models de véhicules
      - [x] CRUD pour les ADMIN
  - [x] Gestion des Vehicules
      - [x] Associer des véhicules aux contacts. ADMIN
      - [x] CRUD pour les ADMIN
  - [x] Gestion des fichiers
      - [x] Permettre l'upload et le téléchargement de fichiers associés à un contact (pdf, png, jpeg, jpg).
  - [x] Lire les stats (nb de contacts, nb de véhicules). USER et ADMIN
  - [x] Ajout quotidien des contacts
    - [x]  Mettre en place une tâche récurrente quotidienne qui alimente la BDD avec plusieurs contacts depuis un fichier excel déposé sur un ftp.
  - [x] Export des contacts
    - [x] Mettre en place une requête exportant au format csv/xlsx les contacts
      - [x] Contacts ayant un véhicule de +30000km
      - [x] Contacts ayant un véhicule de +3 ans
      - [x] Contacts n’ayant pas de véhicule
- [x] Gestion des logs
- [x] Gestion des erreurs
- [x] Gestion des exceptions
- [x] Gestion des middlewares
- [x] Gestion des services
- [x] Gestion des controlleurs
- [x] Gestion des routes
- [x] Gestion des managers
- [x] Gestion des entités
- [x] Gestion des caches
- [x] Gestion des requetes
- [x] Gestion des reponses
- [x] Gestion des headers
- [x] Gestion des routes par attributs
- [x] Documentation
  - [x] Wiki sur github
  - [x] Doc Endpoint API via aglio (API Blueprint)
  - [x] Doc exporté via phpdoc -> `php -S localhost:8080 -t /var/www/jra/docs`


## Qualité
### Respect des normes PSR
- [x] PSR-1 [Basic Coding Standard](https://www.php-fig.org/psr/psr-1)
- [x] PSR-3 [Logger Interface](https://www.php-fig.org/psr/psr-3)
- [x] PSR-4 [Autoloading Standard ](https://www.php-fig.org/psr/psr-4) ⚠️ l'autoloading via composer est fortement recommandée.
- [ ] PSR-6 [Caching Interface](https://www.php-fig.org/psr/psr-6) -> Remplacé par la PSR-16.
- [ ] PSR-7 [HTTP Message Interface](https://www.php-fig.org/psr/psr-7/) -> Partiellement...
- [ ] PSR-11 [Container Interface](https://www.php-fig.org/psr/psr-11/) -> non mis en place, permettrait l'injection de dépendances.
- [x] PSR-12 [Extended Coding Style Guide](https://www.php-fig.org/psr/psr-12)
- [ ] PSR-13 [Hypermedia Links](https://www.php-fig.org/psr/psr-13) -> non necessaire
- [ ] PSR-14 [Event Dispatcher](https://www.php-fig.org/psr/psr-14) -> Partiellement...
- [ ] PSR-15 [HTTP Middleware](https://www.php-fig.org/psr/psr-15) -> Partiellement...
- [ ] PSR-16 [Simple Cache](https://www.php-fig.org/psr/psr-16) -> Partiellement...
- [ ] PSR-17 [HTTP Factories](https://www.php-fig.org/psr/psr-17) -> non necessaire.
- [ ] PSR-18 [HTTP Client](https://www.php-fig.org/psr/psr-18) -> non necessaire.
- [x] PSR-20 [Clock](https://www.php-fig.org/psr/psr-20)

### Testes unitaires avec PHPUnit
- 0% de couverture de code -> Je n'ai pas eu le temps de mettre en place les tests unitaires.

## Sécurité
- [x] Utilisation d'un repertoire `public` pour les ressources accessibles par le client.
- [x] Protection contre les injections SQL
- [x] Interactions avec la base de données via PDO
- [x] Protection contre les attaques XSS (Sanitisation)
- [x] Protection contre les attaques DDoS
- [x] Validation des données
- [x] Mise en place d'une gestion granulaire des permissions par rôle. (Role-Based Access Control)
- [x] Mise en place d'un delais de 5sec en cas d'erreur de connexion pour eviter les attaques par bruteforce.
- [x] Utilisation d'un mot de passe fort en BDD.
- [x] Utilisation d'un utilisateur randomisé en BDD avec restriction srictes des privileges.
- [x] Désactivation des methodes HTTP inutilisées.
- [x] Limitation des informations exposées par les headers.
- [x] Usage de `Namespace` pour éviter les collisions de noms.
- [x] Restreindre les domaines autorisés pour les requêtes cross-origin. (CORS)

## Performances
- [x] Utilisation de requetes SQL préparées 
- [x] Utilisation de sous-requêtes SQL
- [x] Utilisation de transactions SQL
- [x] Utilisation de clés étrangères (foreign keys)
- [x] Utilisation de clés primaires (primary keys)
- [x] Utilisation de clés uniques (unique keys)
- [x] Utilisation de clés indexées (index keys)
- [x] Utilisation de fonctions de jointure SQL (INNER JOIN, LEFT JOIN, RIGHT JOIN)
- [x] Utilisation de fonctions de filtrage SQL (WHERE, HAVING)
- [x] Utilisation de fonctions de tri SQL (ORDER BY)
- [x] Utilisation de fonctions de pagination SQL (LIMIT, OFFSET)
- [x] Utilisation de fonctions de recherche SQL (LIKE, MATCH)

## Améliorations possibles

### General
- Rendre le HTTPS obligatoire.
- Dans la meme logique mettre en place le HSTS sur le serveur web.
- Limiter le nombre de requêtes autorisées par utilisateur/IP sur une période donnée. (Rate Limiting)
  - Réduis le risque de DDoS et de Bruteforce.
- Utiliser le serveur web `Frankenphp` ecrit en `Go` pour des performances accrues. 
- Les fichiers PDF, PNG, JPEG, JPG sont stockés en base de données, il serait plus judicieux de les stocker sur le disque et de ne conserver que le chemin en base de données.
- Gestion de l'injection de dépendances via les interfaces.
- Chiffrement des données sensibles de la base de donnée en AES-256.
- J'ai décidé de ne pas mettre en place car en cas de migration de base de données cela peut poser des problèmes : 
  - Utilisation de procédures stockées
  - Utilisation de triggers
- Utilisation de vues SQL (VIEW)
- Mise en place du `SSE` pour les actualisations plutot que du simple `fetch`, voir un `websocket`. (A lire)
- Un systeme de `session` permettrait de sécuriser davantage les attaques `CSRF`. (Cross-Site Request Forgery)
- Protection contre les attaques par dépassement
- Mise en place de schéma sql strict

## Installation

### PHP Built-in

```bash
sudo apt install php php-mysql mariadb-server -y
git clone https://github.com/damienmillet/jra -C /var/www
sudo mysql < /var/www/jra/sql/db.sql
# sécurisation des en-tetes http
sed -i '/require_once __DIR__ . '\''\/..\/src\/app.php'\'';/i \
header("X-Content-Type-Options: nosniff"); \
header("X-Frame-Options: DENY"); \
header("X-XSS-Protection: 1; mode=block"); \
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload"); \
header("Referrer-Policy: no-referrer-when-downgrade"); \
header("Content-Security-Policy: default-src '\''self'\''; script-src '\''self'\''; style-src '\''self'\''; img-src '\''self'\'' data:; font-src '\''self'\'';");' /var/www/jra/public/index.php
# lancement du serveur built-in
php -S localhost:8000 -t /var/www/jra/public
```

### Apache 

```bash
sudo apt install php php-mysql mariadb-server apache2 -y
git clone https://github.com/damienmillet/jra -C /var/www
cd /var/www/jra
sudo mysql < /var/www/jra/sql/db.sql
sudo cp apache.conf /etc/apache2/sites-available/jra.conf
sudo a2enmod rewrite
sudo a2ensite jra
sudo systemctl restart apache2
```

### Nginx 

```bash
sudo apt install php php-mysql mariadb-server nginx -y
git clone https://github.com/damienmillet/jra -C /var/www
sudo mysql < /var/www/jra/sql/db.sql
sudo cp /var/www/jra/nginx.conf /etc/nginx/sites-available/jra.conf
# sudo cp nginx-ssl.conf /etc/nginx/sites-available/jra.conf # (optionnel) si https
sudo ln -s /etc/nginx/sites-available/jra.conf /etc/nginx/sites-enabled/jra.conf
sudo systemctl reload nginx
```

### HTTPS (auto-signé) :

```bash
sudo a2enmod ssl (optionnel) # si https
sudo a2enmod rewrite (optionnel) # si https
sudo openssl genpkey -algorithm RSA -out /etc/ssl/private/jra.key
sudo openssl x509 -req -days 365 -in /etc/ssl/certs/jra.csr -signkey /etc/ssl/private/jra.key -out /etc/ssl/certs/jra.crt
sudo cp /etc/ssl/certs/jra.crt /etc/ssl/certs/jra.pem
```

### Clean

``` bash
  sudo mysql -e "drop database jra;"
  sudo mysql -e "drop user 'jra'@'localhost';"
  sudo mysql -e "revoke all privileges, grant option from 'jra'@'localhost';"
  sudo rm -rf /var/www/jra
  # (optionnel) si apache ou nginx
  # sudo rm /etc/apache2/sites-available/jra.conf
  # sudo rm /etc/nginx/sites-available/jra.conf
  # (optionnel) si https
  # sudo rm /etc/ssl/certs/jra.crt 
  # sudo rm /etc/ssl/certs/jra.pem
  # sudo rm /etc/ssl/private/jra.key
```

## Documentation

Le wiki a été mis en place pour expliquer le fonctionnement de l'API.
L'url `/` permet d'accéder à la documentation `endpoint`.

## A propos

### Concernant la consigne : Ajout quotidien des contacts

A savoir : Mettre en place une tâche récurrente quotidienne qui alimente la BDD avec
plusieurs contacts depuis un fichier excel déposé sur un ftp.

Je ne suis pas sur d'avoir saisi la demande. Ici si je devais en PHP natif je devrais laisser une requete php en tache de fond et je pense que cela serait contre productif. j'en deduis donc qu'il s'agit d'un script PHP qui sera executé par un job cron.

### Concernant la consigne : Export de contacts

Ici bien que suite a la consigne precedente j'ai présumé qu'il s'agissait toujours de l'api.
Dans le doute j'ai egalement fais un script qui permet l'extraction des contacts en CSV via le cli.

### PHP Version

J'ai décidé de rester sur la version PHP 8.2 puisqu'il semble que ce soit la version vers laquelle vous semblez vous diriger.
La version 8.4 aurait cependant eu un impact positif sur la qualité du code. Notamment concernant les attribues de methodes.

## Evolution

La logique de l'application est assez simple et peut etre facilement étendue.
La création d'un nouveau `Controlleur` ainsi que la définition d'une nouvelle `route`, permet la création de nouvelles fonctionnalités.
Mettre en place une gestion des routes plus avancée permettrait de mieux gérer les parametres optionnels.
La gestion par `Interface` permet un code homogene et facilement maintenable.

Les Services permettent de centraliser la logique métier et de la rendre plus facilement maintenable.
Ils sont instanciés dans les controlleurs necessitant une modification et sont statiques pour les autres.
L'objectif est d'eviter d'instancier x fois les managers.

## Dev

Update de la doc

```bash
aglio -i jra.apib -o ../src/Views/documentation.html
```

![separe](https://github.com/studoo-app/.github/blob/main/profile/studoo-banner-logo.png)
# Cyber Bank Offensive Security
[![Version](https://img.shields.io/badge/Version-1.0.0-blue)]()

## Description
Projet proposant un scénario de cybersécurité offensive simulant l'attaque du site de la banque **HSBank**.

## Contexte
Vos premières phases de collectes d'informations ont fait apparaitre les points suivants:

 - La banque HSBank propose à ses clients une application permettant de visualiser
    le solde de son compte en se connectant sur leur espace privé.
 - Cette application est aussi utilisé par le staff de la banque pour effectuer des transferts inter-comptes.
 - Une remontée d'informations est effectuée quotidiennement à la maison mère basée en Suisse

## Missions
Votre objectif est de réussir à vous transférez 10 000 € sur un compte souscrit à la banque sous une fausse identité
afin de procéder ultérieurement à un virement vers un compte intraçable ou diluer ce compte en crypto-monnaie.

Vous prendrez soin de ne pas éveiller les potentiels systèmes d'alertes mis en place.

Afin de prouver vos actions,vous devrez fournir:
- Fournir le FLAG justifiant d'un virement réussi
- Fournir le FLAG justifiant d'une balance de compte supérieure à 5 000 €
  - une capture d'écran de votre compte fictif indiquant une balance supérieure à 5 000 €

> ##### Identifiant du compte fictif
> **login** : john.doe@mail.dev
> **password** : shadow-account

## Installation
### Démarrage des conteneurs
`docker compose up -d`
### Configuration de la machine attacker
- Accès à la machine attacker `http://localhost:3000`
- Ouvrir un terminal dans le dossier `/tmp/share/scripts`
- Autoriser l'éxécution du script `install-tools.sh` -> `chmod +x install-tools.sh`
- Executer le script `install-tools.sh` -> `install-tools.sh`

> ##### Attention
> Si vous ne parvenez pas a éxéuter le script d'installation, vous devez passez à l'installation manuelle des outils.
> Pour ce faire, éxécuter les commandes situées dans le fichier `/scripts/install-tools.sh`.

### Configuration de la machine target-site
- Installer les dépendances composer -> `docker exec -it target-site composer install`
- Création de la base -> `docker exec -it target-site symfony console d:d:c`
- Migraiton de la base -> `docker exec -it target-site symfony console d:m:m`
- Déploiement du jeu de test -> `docker exec -it target-site symfony console d:f:l`
- Suppression du fichier de tracking de progression -> `docker exec -it target-site cp /dev/null var/log/ctf.log`


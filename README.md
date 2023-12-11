![separe](https://github.com/studoo-app/.github/blob/main/profile/studoo-banner-logo.png)
# Cyber Bank Offensive Security
[![Version](https://img.shields.io/badge/Version-1.0.0-blue)]()

## Description
Projet proposant une initiation aux méthodes de Digital Forensic (techniques numériques d'enquêtes).
Ces techniques ont pour but d'enquêter sur des crimes et établir des faits.
## Installation
### Démarrage des conteneurs
`docker compose up -d`

### Configuration de la machine attacker

#### Connexion à la console du conteneur attacker
`docker exec -it attacker /bin/bash`
#### Lancement du script d'installation des outils
- Se positionner dans le dossier `/home/scripts`
- Autoriser l'éxécution du script `install-tools.sh`
    ```bash
        chmod +x install-tools.sh
    ```
- Executer le script `install-tools.sh`
    ```bash
        ./install-tools.sh
    ```
> ##### Attention
> Si vous ne parvenez pas a éxéuter le script d'installation, vous devez passez à l'installation manuelle des outils.
> Pour ce faire, éxécuter les commandes situées dans le fichier `/scripts/install-tools.sh`.

### Configuration de la machine target-site
- Installer les dépendances composer
    ```bash
        docker exec -it target-site composer install
    ```
- Création de la base
    ```bash
        docker exec -it target-site symfony d:d:c
    ```
- Migraiton de la base
  ```bash
      docker exec -it target-site symfony d:m:m
  ```
- Déploiement du jeu de test
    ```bash
        docker exec -it target-site symfony d:f:l
    ```


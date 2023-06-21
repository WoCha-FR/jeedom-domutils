# Plugin mqttDomutils

## Description

Publie les valeurs d'api ouverte française vers MQTT et d'autres informations utiles.

- Informations sur l'année (nombre de jours dans l'année, numéro de jour dans l'année, numéro de semaine, ...)
- Saint du jour et de demain
- Couleur EDF Tempo d'aujourd'hui et de demain

Et pour chaque ville fournie en paramètre :

- Informations sur la ville (code INSEE, Zone scolaire, Latitude et Longitude, ...)
- Informations sur le soleil (coucher, lever du soleil, ...)
- Informations sur la lune (coucher, lever de la lune, ....)
- Jours fériés (avec ceux de l'Alsace-Moselle si concernée)
- Vacances scolaires (selon la zone scolaire de la ville)
- Vigilance Météo (si API key fournis dans la configuration du plugin)

### API & Librairies Utilisées

Ce plugin se base sur des API, gratuites et ne nécessitant pas la création de clef et des librairies installées en dépendances.

- [geo.api.gouv.fr](https://geo.api.gouv.fr/)
- [DonneesPubliquesVigilance](https://portail-api.meteofrance.fr/devportal/apis/5e99a87c-d50d-465b-a33f-1f12cf675161/overview)
- [Le calendrier scolaire](https://data.education.gouv.fr/explore/dataset/fr-en-calendrier-scolaire/information/)
- [Jours fériés en France](https://calendrier.api.gouv.fr/jours-feries/)
- [SunCalc](https://github.com/mourner/suncalc)

## Pré-Requis

Ce plugin requiert [MQTT Manager](https://market.jeedom.com/index.php?v=d&p=market_display&id=4213), plugin officiel et gratuit.

## Installation 

- Télécharger le plugin depuis le market
- Activer le plugin
- Installer les Dépendances
- Démarrer le démon

## Configuration

### Paramètres de configuration :
- **Topic racine** : Sujet racine que Jeedom doit écouter
- **APIKey Meteo France** : API key fournis par le site portail-api.meteofrance.fr

#### Comment créer mon API Key

- Commencer par créer un compte comme indiqué [ici](https://portail-api.meteofrance.fr/authenticationendpoint/aide_fr.do#create-count)
- Obtenez votre API Key en suivant ces [étapes](https://portail-api.meteofrance.fr/authenticationendpoint/aide_fr.do#logic-schema)

## Configuration des équipements

La configuration des équipements mqttDomutils est accessible à partir du menu Plugins → Organisation.

### Equipement par défaut

Un équipement nommé "GLOBAL" est automatiquement créé au lancement du démon. Il regroupe les informations
non liées à une ville :

- Saints du jour et de demain.
- Numéro du jour / de semaine / Informations sur l'année.
- Informations TEMPO EDF.

### Création d'un équipement

Chaque équipement représentera une Ville. Il vous suffit de cliquer sur "Ajouter".

### Configuration d'un équipement

Vous retrouvez ici toute la configuration de votre équipement :

- **Nom de l’équipement** : nom de votre équipement.
- **Objet parent** : indique l’objet parent auquel appartient l’équipement.
- **Activer** : permet de rendre votre équipement actif.
- **Visible** : rend votre équipement visible sur le dashboard.

En dessous vous retrouvez le paramètre spécifique de votre équipement:

- **Ville**: Saisir ici la ville dont vous voulez les informations.

Sur la droite, le champ commentaire sera mis à jour s'il est vide avec les informations de la ville :

- Nom & code INSEE
- Position GPS
- Zone de vacance scolaire

### Les commandes

Pour chaque ville configurée, le plugins va créer des commandes "info" pour chaque informations.

- **Jour Férié** : Est-ce férié ? Nom du jour férié, Prochain jour férie (nom & date), Nombre de jour vant prochain férié.
- **Vacances** : Est-ce les vacances ? Nom des vacances, Nombre de jour avant la fin des vacances, Prochaines vavances (nom & date), Nombre de jour avant les prochaines vacances.
- **Soleil** : Heure de lever, de coucher, du zenith, durée du jour (et différence par rapport à la veille), Azimuth et Elévation.
- **Lune** : Heure de lever, de coucher, Phase de lune, Elévation, Lune toujours levée ou absente toute la journée.
- **Vigilance** : Couleur par type de danger.

## Actualisation des données

Les données sont actualisés automatiquement à fréquence fixe.

**Toutes les 5 minutes** :
- Soleil
- Lune
- Vigilance

**Toutes les heures** :
- EDF Tempo

**Tous les jours** :
- Saint du jour et de demain
- Numéro du jour / de semaine
- Jour férié
- Vacances scolaires

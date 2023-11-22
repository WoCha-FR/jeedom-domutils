# Plugin mqttDomutils

## Description

Publishes French open api values to MQTT and other useful information.

- Information about the year (number of days in the year, day number in the year, week number, ...)
- Saint of the day and tomorrow
- EDF Tempo color of today and tomorrow

And for each city provided in parameter :

- Information about the city (INSEE code, school zone, Latitude and Longitude, ...)
- Information about the sun (sunset, sunrise, ...)
- Information about the moon (sunset, moonrise, ....)
- Public holidays (with those of Alsace-Moselle if concerned)
- School vacations (according to the school zone of the city)
- Weather watch (if API key provided in plugin configuration)

### API & Libraries Used

This plugin is based on APIs, which are free and do not require the creation of keys and libraries installed as dependencies.

- [geo.api.gouv.fr](https://geo.api.gouv.fr/)
- [DonneesPubliquesVigilance](https://portail-api.meteofrance.fr/web)
- [Le calendrier scolaire](https://data.education.gouv.fr/explore/dataset/fr-en-calendrier-scolaire/information/)
- [Jours fériés en France](https://calendrier.api.gouv.fr/jours-feries/)
- [SunCalc](https://github.com/mourner/suncalc)

## Prerequisites

This plugin requires [MQTT Manager](https://market.jeedom.com/index.php?v=d&p=market_display&id=4213), an official and free plugin.

## Installation

- Download the plugin from the market
- Activate the plugin
- Install the dependencies
- Start the daemon

## Configuration

### Configuration parameters :
- **Topic racine** : Root subject that Jeedom must listen to
- **APIKey Meteo France** : API key provided by the portal-api.meteofrance.fr website

#### How to create my API Key

- Follow the Quick Start instructions [here](https://portail-api.meteofrance.fr/web/en/faq) 

## Equipment configuration

mqttDomutils equipment configuration is accessible from the Plugins → Organization menu.

### Default equipment

A device named "GLOBAL" is automatically created when the daemon is launched. It gathers all the information
not linked to a city:

- Saints of the day and tomorrow.
- Day / week number / year information.
- Information TEMPO EDF.

### Creation of an equipment

Each equipment will represent a City. Just click on "Add".

### Configuring an equipment

Here you will find all the configuration of your equipment:

- **Equipment name**: name of your equipment.
- **Parent object**: indicates the parent object to which the equipment belongs.
- **Activate**: allows you to make your equipment active.
- **Visible**: makes your equipment visible on the dashboard.

Below you will find the specific parameter of your equipment:

- **City**: Enter here the city for which you want the information.

On the right, the comment field will be updated if it is empty with the city information:

- Name & INSEE code
- GPS position
- School vacancy zone

### Orders

For each configured city, the plugin will create "info" commands for each information.

- **Holiday** : Is it a holiday ? Name of the holiday, Next holiday (name & date), Number of days before next holiday.
- **School vacations**: Is it a vacation? Name of vacation, Number of days before vacation, Next holiday (name & date), Number of days before next vacation.
- **Sun**: Sunrise, sunset, zenith, day length (and difference from the day before), Azimuth and Elevation.
- **Moon**: Time of rising, setting, Moon phase, Elevation, Moon always up or absent all day.
- **Vigilance** : Colour by type of hazard with start and end times. 3 periods per type of vigilance.

## Data update

The data is automatically updated at a fixed frequency.

**Every 5 minutes** :
- Sun
- Moon
- Vigilance

**Every hour** :
- EDF Tempo

**Every day** :
- Saint of the day and tomorrow
- Day / week number
- Public holiday
- School vacations

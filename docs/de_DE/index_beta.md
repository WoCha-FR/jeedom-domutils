# Plugin mqttDomutils

## Beschreibung

Veröffentlicht die Werte der französischen Open Api zu MQTT und andere nützliche Informationen.

- Informationen über das Jahr (Anzahl der Tage im Jahr, Nummer des Tages im Jahr, Nummer der Woche, ...).
- Sankt von heute und morgen
- EDF Tempo-Farbe von heute und morgen

Und für jede in Parameter gelieferte Stadt :

- Informationen über die Stadt (INSEE-Code, Schulbezirk, Längen- und Breitengrad, ...)
- Informationen über die Sonne (Sonnenuntergang, Sonnenaufgang, ...)
- Informationen über den Mond (Monduntergang, Mondaufgang, ....).
- Feiertage (mit denen von Elsass-Moselle, falls betroffen).
- Schulferien (entsprechend der Schulzone der Stadt).
- Wetterwarnung

### Verwendete APIs & Bibliotheken

Dieses Plugin basiert auf APIs, die kostenlos sind und keine Erstellung von Schlüsseln und Bibliotheken erfordern, die als Abhängigkeiten installiert werden.

- [geo.api.gouv.fr](https://geo.api.gouv.fr/)
- [Risques Météorologiques](https://public.opendatasoft.com/explore/dataset/risques-meteorologiques-copy/api/)
- [Le calendrier scolaire](https://data.education.gouv.fr/explore/dataset/fr-en-calendrier-scolaire/information/)
- [Jours fériés en France](https://calendrier.api.gouv.fr/jours-feries/)
- [SunCalc](https://github.com/mourner/suncalc)

## Voraussetzungen

Dieses Plugin erfordert [MQTT Manager](https://market.jeedom.com/index.php?v=d&p=market_display&id=4213), ein offizielles und kostenloses Plugin.

## Installation

- Laden Sie das Plugin vom Market herunter.
- Das Plugin aktivieren
- Die Abhängigkeiten installieren
- Dämon starten

## Einrichten der Geräte

Die Konfiguration der Pushover2-Geräte ist über das Menü Plugins → Organisation zugänglich.

### Standardausstattung

Eine Ausrüstung mit dem Namen "GLOBAL" wird beim Start des Daemons automatisch erstellt. Sie fasst die Informationen zusammen.
die nicht an eine Stadt gebunden sind :

- Heilige des Tages und des morgigen Tages.
- Nummer des Tages / der Woche / Informationen über das Jahr.
- TEMPO EDF-Informationen.

### Erstellen einer Ausrüstung

Jede Ausrüstung wird eine Stadt repräsentieren. Klicken Sie einfach auf "Hinzufügen".

### Einrichten einer Ausrüstung

Hier finden Sie alle Konfigurationsmöglichkeiten für Ihre Ausrüstung:

- **Ausrüstungsname**: Name Ihrer Ausrüstung.
- **Elternobjekt**: Gibt das Elternobjekt an, zu dem die Ausrüstung gehört.
- **Aktivieren**: Hiermit können Sie Ihre Ausrüstung aktiv machen.
- **Sichtbar**: Macht Ihre Ausrüstung auf dem Dashboard sichtbar.

Darunter finden Sie die spezifische Einstellung für Ihre Ausrüstung:

- Stadt: Geben Sie hier die Stadt ein, über die Sie informiert werden möchten.

Auf der rechten Seite wird das Kommentarfeld mit den Informationen der Stadt aktualisiert, wenn es leer ist:

- Name & INSEE-Code
- GPS-Position
- Schulferiengebiet

### Die Bestellungen

Für jede konfigurierte Stadt wird das Plugin "info"-Befehle für jede Information erstellen.

- **Feiertag**: Ist das ein Feiertag? Name des Feiertags, Nächster Feiertag (Name & Datum), Anzahl der Tage bis zum nächsten Feiertag.
- **Schulferien**: Ist dies der Urlaub? Name des Urlaubs, Anzahl der Tage bis zum Ende des Urlaubs, Nächster Urlaub (Name & Datum), Anzahl der Tage bis zum nächsten Urlaub.
- **Sonne**: Aufgangs-, Untergangs- und Zenit-Zeit, Tageslänge (und Unterschied zum Vortag), Azimuth und Elevation.
- **Mond**: Aufgangs- und Untergangszeit, Mondphase, Elevation, Mond immer aufgegangen oder den ganzen Tag abwesend.
- **Wachsamkeit**: Farbe nach Gefahrenart (Wind, Gewitter, Schnee, ...), Wachsamkeitsbezogener Ratschlag, Kommentar zur Situation.

## Aktualisierung der Daten

Die Daten werden in festgelegten Abständen automatisch aktualisiert.

**Alle 5 Minuten** :
- Sonne
- Mond
- Wachsamkeit

**Alle Stunden**:
- EDF Tempo

**Täglich**:
- Heilige des Tages und der Zukunft
- Nummer des Tages / der Woche
- Feiertag
- Schulferien

# Plugin mqttDomutils

## Descripción

Publica los valores de la api abierta francesa en MQTT y otra información útil.

- Información sobre el año (número de días del año, número de día del año, número de semana, ...)
- Santo del día y de mañana
- El color del EDF Tempo de hoy y de mañana

Y para cada ciudad prevista en el parámetro :

- Información sobre la ciudad (código INSEE, zona escolar, latitud y longitud, ...)
- Información sobre el sol (puesta de sol, salida del sol, ...)
- Información sobre la luna (puesta de sol, salida de la luna, ....)
- Días festivos (con los de Alsacia-Mosela si se trata)
- Vacaciones escolares (según la zona escolar de la ciudad)
- Vigilancia meteorológica (si se proporciona la clave API en la configuración del complemento)

### API y bibliotecas utilizadas

Este plugin se basa en APIs, que son gratuitas y no requieren la creación de claves y librerías instaladas como dependencias.

- [geo.api.gouv.fr](https://geo.api.gouv.fr/)
- [DonneesPubliquesVigilance](https://portail-api.meteofrance.fr/devportal/apis/5e99a87c-d50d-465b-a33f-1f12cf675161/overview)
- [Le calendrier scolaire](https://data.education.gouv.fr/explore/dataset/fr-en-calendrier-scolaire/information/)
- [Jours fériés en France](https://calendrier.api.gouv.fr/jours-feries/)
- [SunCalc](https://github.com/mourner/suncalc)

## Requisitos previos

Este plugin requiere [MQTT Manager](https://market.jeedom.com/index.php?v=d&p=market_display&id=4213), plugin oficial y gratuito.

## Instalación

- Descargue el plugin del mercado
- Activar el plugin
- Instalar las dependencias
- Iniciar el demonio

## Configuración

### Parámetros de configuración :
- **Tema racine** : Tema raíz que Jeedom debe escuchar
- **APIKey Meteo France** : Clave API proporcionada por el sitio web portal-api.meteofrance.fr

#### Cómo crear mi clave API

- Empiece por crear una cuenta como se muestra [aquí].(https://portail-api.meteofrance.fr/authenticationendpoint/aide.do#create-count)
- Obtenga su clave API siguiendo estos [pasos].(https://portail-api.meteofrance.fr/authenticationendpoint/aide.do#logic-schema)

## Configuración del equipo

Se puede acceder a la configuración del equipo mqttDomutils desde el menú Plugins → Organización.

### Equipo por defecto

Un dispositivo llamado "GLOBAL" se crea automáticamente cuando se lanza el demonio. Recoge información
no vinculado a una ciudad:

- Santos del día y de mañana.
- Información sobre el día, el número de la semana y el año.
- Información de EDF TEMPO.

### Creación de una instalación

Cada instalación representará a una ciudad. Sólo tienes que hacer clic en "Añadir".

### Configurar una instalación

Aquí encontrarás toda la configuración de tu equipo:

- **Nombre del equipo**: nombre de su equipo.
- **Objeto padre**: indica el objeto padre al que pertenece el equipo.
- **Activar**: Permite activar el equipo.
- **Visible**: hace que su equipo sea visible en el salpicadero.

A continuación encontrará el parámetro específico de su equipo:

- **Ciudad**: Introduzca aquí la ciudad cuya información desea.

A la derecha, el campo de comentarios se actualizará si está vacío con la información de la ciudad:

- Nombre y código INSEE
- Posición GPS
- Zona de vacantes escolares

### Los pedidos

Para cada ciudad configurada, el plugin creará comandos "info" para cada información.

- **Día festivo** : ¿Es un día festivo? Nombre de las vacaciones, Próximas vacaciones (nombre y fecha), Número de días antes de las próximas vacaciones.
- **Vacaciones**: ¿Es un día de fiesta? Nombre de las vacaciones, Número de días antes de que terminen las vacaciones, Próximas vacaciones (nombre y fecha), Número de días antes de las próximas vacaciones.
- **Sol**: Hora de salida y puesta del sol, cenit, duración del día (y diferencia con el día anterior), acimut y elevación.
- **Luna**: Hora de salida, puesta, fase lunar, Elevación, Luna siempre arriba o ausente todo el día.
- **Vigilancia**: Color por tipo de peligro.

## Actualización de datos

Los datos se actualizan automáticamente con una frecuencia fija.

**Cada 5 minutos** :
- Sol
- Luna
- Vigilancia

**Cada hora** :
- EDF Tempo

**Todos los días** :
- Santo del día y de mañana
- Número de día/semana
- Día festivo
- Vacaciones escolares

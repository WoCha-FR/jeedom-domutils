#!/bin/bash

set -x  # make sure each command is printed in the terminal
echo "Post installation de l'installation/mise à jour des dépendances mqttDomutils"

PROGRESS_FILE=/tmp/jeedom_install_in_progress_mqttDomutils
echo 50 > ${PROGRESS_FILE}

BASEDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd ${BASEDIR}
source ../core/config/mqttDomutils.config.ini &> /dev/null
echo "Version requise : ${mqttDomutilsRequire}"

npm i mqtt4frenchtools@${mqttDomutilsRequire} --no-save

echo 90 > ${PROGRESS_FILE}
chown www-data:www-data -R ${BASEDIR}/node_modules

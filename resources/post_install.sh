#!/bin/bash

set -x  # make sure each command is printed in the terminal
echo "Post installation de l'installation/mise à jour des dépendances mqttDomutils"

PROGRESS_FILE=/tmp/jeedom_install_in_progress_mqttDomutils
echo 60 > ${PROGRESS_FILE}

BASEDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd ${BASEDIR}/mqtt4frenchtools
npm ci

echo 90 > ${PROGRESS_FILE}
chown www-data:www-data -R ${BASEDIR}/mqtt4frenchtools

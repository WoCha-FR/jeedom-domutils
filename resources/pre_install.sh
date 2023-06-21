#!/bin/bash

set -x  # make sure each command is printed in the terminal
echo "Pre installation de l'installation/mise à jour des dépendances mqttDomutils"

PROGRESS_FILE=/tmp/jeedom_install_in_progress_mqttDomutils
echo 5 > ${PROGRESS_FILE}

BASEDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

if [ -d "${BASEDIR}/mqtt4frenchtools" ]; then
  rm -R ${BASEDIR}/mqtt4frenchtools
fi
if [ -d "${BASEDIR}/node_modules" ]; then
  rm -R ${BASEDIR}/node_modules
fi
echo 15 > ${PROGRESS_FILE}
echo "Pre install finished"

#!/bin/bash

set -x  # make sure each command is printed in the terminal
echo "Pre installation de l'installation/mise à jour des dépendances mqttDomutils"

PROGRESS_FILE=/tmp/jeedom_install_in_progress_mqttDomutils
echo 5 > ${PROGRESS_FILE}

BASEDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

if [ -d "${BASEDIR}/mqtt4frenchtools" ]; then
  rm -R ${BASEDIR}/mqtt4frenchtools
fi

echo 15 > ${PROGRESS_FILE}

cd ${BASEDIR}
source ../core/config/mqttDomutils.config.ini &> /dev/null
echo "Version requise : ${mqttDomutilsRequire}"

curl -L -s https://github.com/WoCha-FR/mqtt4frenchtools/archive/refs/tags/${mqttDomutilsRequire}.tar.gz | tar zxf -
mv mqtt4frenchtools-${mqttDomutilsRequire} mqtt4frenchtools

echo 20 > ${PROGRESS_FILE}
echo "Pre install finished"

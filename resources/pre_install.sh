#!/bin/bash

set -x  # make sure each command is printed in the terminal
echo "Pre installation de l'installation/mise à jour des dépendances mqttDomutils"

BASEDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd ${BASEDIR}
if [ -d "${BASEDIR}/mqtt4frenchtools" ]; then
  rm -R ${BASEDIR}/mqtt4frenchtools
fi

git clone --depth 1 https://github.com/WoCha-FR/mqtt4frenchtools.git ${BASEDIR}/mqtt4frenchtools
echo "Pre install finished"

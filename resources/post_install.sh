#!/bin/bash

set -x  # make sure each command is printed in the terminal
echo "Post installation de l'installation/mise à jour des dépendances mqttDomutils"

BASEDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd ${BASEDIR}/mqtt4frenchtools
npm ci

chown www-data:www-data -R ${BASEDIR}/mqtt4frenchtools

<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function mqttDomutils_install() {
}

function mqttDomutils_update() {
  // Suppression des vigilances inutiles
  // VigiConseil / vigiComment / vigiCrue
  foreach (eqLogic::byType('mqttDomutils') as $eqLogic) {
    # Update 06/2023
    $cmd = $eqLogic->getCmd('info', 'vigiConseil');
    if (is_object($cmd)) {
      $cmd->remove();
    }
    $cmd = $eqLogic->getCmd('info', 'vigiComment');
    if (is_object($cmd)) {
      $cmd->remove();
    }
    # Update 11/2023
    // vigiInondation passe à Vigicrue (qui etait à effacer en juin...)
    // si vigiCrue ET VigiInnondation => pas fait update en Juin donc effacement commande VigiCrue
    // ensuite, si VigiInnondation existe, on renome en VigiCrue => Update novembre.
    $cmd1 = $eqLogic->getCmd('info', 'vigiCrue');
    $cmd2 = $eqLogic->getCmd('info', 'vigiInondation');
    if (is_object($cmd1) && is_object($cmd2)) {
      $cmd1->remove();
    }
    if (is_object($cmd2)) {
      $cmd2->setLogicalId('vigiCrue');
      $cmd2->save(true);
    }
  }
}

function mqttDomutils_remove() {
}

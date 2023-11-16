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

class mqttDomutils extends eqLogic {

  /* Handle MQTT */
  public static function handleMqttMessage($_message) {
    if (isset($_message[config::byKey('mqtt::topic', __CLASS__, 'domutils')])) {
      $message = $_message[config::byKey('mqtt::topic', __CLASS__, 'domutils')];
    } else {
      log::add(__CLASS__, 'debug', '[' . __FUNCTION__ . '] ' . __('Le message reçu n\'est pas un message mqttDomutils', __FILE__));
      return;
    }
    foreach ($message as $key => $value) {
      if ($key == 'global') {
        $eqLogic = self::byLogicalId($key, __CLASS__);
        if (!is_object($eqLogic)) {
          $eqLogic = new mqttDomutils();
          $eqLogic->setEqType_name(__CLASS__);
          $eqLogic->setLogicalId($key);
          $eqLogic->setIsEnable(1);
          $eqLogic->setIsVisible(0);
          $eqLogic->setName('Global');
          $eqLogic->setConfiguration('ville', 'FRANCE');
          $eqLogic->save();
        }
        // Handle Message
        self::handleGlobals($value);
      } elseif ($key == 'connected') {
        # code...
      } else {
        $eqLogic = self::byLogicalId($key, __CLASS__);
        if (is_object($eqLogic)) {
          self::handleCity($key, $value);
        } else {
          log::add(__CLASS__, 'debug', '[' . __FUNCTION__ . '] ' . __('Le message reçu est de type inconnu', __FILE__));
        }
      }
    }
  }

  /* Fonctions Propres au module */
  public static function handleGlobals($_globals) {
    $eqLogic = self::byLogicalId('global', __CLASS__);
    if (is_object($eqLogic)) {
      foreach ($_globals as $key => $value) {
        // key : saints / annee / edftempo
        switch ($key) {
          case 'saints' :
            self::handleSaints('global', $value);
            break;
          case 'annee':
            self::handleAnnee('global', $value);
            break;
          case 'edftempo':
            self::handleEdftempo('global', $value);
            break;
          default:
            log::add(__CLASS__, 'debug', '[' . __FUNCTION__ . '] ' . __('Le message reçu est de type inconnu', __FILE__));
        }
      }
    }
  }

  public static function handleSaints($_eqlogic, $_values) {
    $eqLogic = self::byLogicalId($_eqlogic, __CLASS__);
    foreach ($_values as $key => $value) {
      $command = $eqLogic->getCmd('info', $key);
      if (!is_object($command)) {
        $command = new mqttDomutilsCmd();
        $command->setLogicalId($key);
        $command->setName($key);
        $command->setEqLogic_id($eqLogic->getId());
        $command->setType('info');
        $command->setSubType('string');
        $command->setIsVisible(1);
        $command->save();
      }
      $eqLogic->checkAndUpdateCmd($key, $value);
    }
  }

  public static function handleAnnee($_eqlogic, $_values) {
    $eqLogic = self::byLogicalId($_eqlogic, __CLASS__);
    foreach ($_values as $key => $value) {
      $command = $eqLogic->getCmd('info', $key);
      if (!is_object($command)) {
        $command = new mqttDomutilsCmd();
        $command->setLogicalId($key);
        $command->setName($key);
        $command->setEqLogic_id($eqLogic->getId());
        $command->setType('info');
        if ($key == 'dstDate') {
          $command->setSubType('string');
        } else {
          $command->setSubType('numeric');
          $command->setTemplate('dashboard', 'core::line');
          $command->setTemplate('mobile', 'core::line');
          $command->setIsHistorized(0);
        }
        $command->setIsVisible(1);
        $command->save();
      }
      $eqLogic->checkAndUpdateCmd($key, $value);
    }
  }

  public static function handleEdftempo($_eqlogic, $_values) {
    $eqLogic = self::byLogicalId($_eqlogic, __CLASS__);
    foreach ($_values as $key => $value) {
      $command = $eqLogic->getCmd('info', $key);
      if (!is_object($command)) {
        $command = new mqttDomutilsCmd();
        $command->setLogicalId($key);
        $command->setName($key);
        $command->setEqLogic_id($eqLogic->getId());
        $command->setType('info');
        if ($key == 'tempoBleu' || $key == 'tempoBlanc' || $key == 'tempoRouge') {
          $command->setSubType('numeric');
          $command->setTemplate('dashboard', 'core::line');
          $command->setTemplate('mobile', 'core::line');
          $command->setIsHistorized(0);
          $command->setUnite('j');
        } else {
          $command->setSubType('string');
        }
        $command->setIsVisible(1);
        $command->save();
      }
      $eqLogic->checkAndUpdateCmd($key, $value);
    }
  }

  public static function handleCity($_eqlogic, $_city) {
    foreach ($_city as $key => $value) {
      log::add(__CLASS__, 'debug', '[' . __FUNCTION__ . '] '. $key . ' => ' . json_encode($value));
      // key : infos / ferie / vacances / soleil / lune / vigilance
      switch ($key) {
        case 'infos' :
          $eqLogic = self::byLogicalId($_eqlogic, __CLASS__);
          if(empty($eqLogic->getComment())) {
            $comment = $value['nom'] . ' (Code INSEE: ' . $value['insee'] . ')'.PHP_EOL;
            $comment .= 'Département: ' . $value['deptnom'] . ' (' . $value['deptnum'] . ')'.PHP_EOL;
            $comment .= 'Zone Scolaire: ' . $value['zonevacances'].PHP_EOL;
            $comment .= 'Latitude: ' . $value['latitude'].PHP_EOL;
            $comment .= 'Longitude: ' . $value['longitude'] ;
            $eqLogic->setComment($comment);
            $eqLogic->save();
            log::add(__CLASS__, 'debug', '[' . __FUNCTION__ . '] '. __('Commentaire equipement mis à jour', __FILE__));
          }
          break;
        case 'ferie' :
          self::handleFeries($_eqlogic, $value);
          break;
        case 'vacances' :
          self::handleVacances($_eqlogic, $value);
          break;
        case 'soleil':
          self::handleSoleil($_eqlogic, $value);
          break;
        case 'lune':
          self::handleLune($_eqlogic, $value);
          break;
        case 'vigilance':
          self::handleVigilance($_eqlogic, $value);
          break;
        default:
          log::add(__CLASS__, 'debug', '[' . __FUNCTION__ . '] ' . __('Le message reçu est de type inconnu', __FILE__));
      }
    }
  }

  public static function handleFeries($_eqlogic, $_values) {
    $eqLogic = self::byLogicalId($_eqlogic, __CLASS__);
    foreach ($_values as $key => $value) {
      $command = $eqLogic->getCmd('info', $key);
      if (!is_object($command)) {
        $command = new mqttDomutilsCmd();
        $command->setLogicalId($key);
        $command->setName($key);
        $command->setEqLogic_id($eqLogic->getId());
        $command->setType('info');
        if ($key == 'ferCejour') {
          $command->setSubType('binary');
          $command->setTemplate('dashboard', 'core::line');
          $command->setTemplate('mobile', 'core::line');
          $command->setIsHistorized(0);
        } elseif ($key == 'ferProchainjour') {
          $command->setSubType('numeric');
          $command->setTemplate('dashboard', 'core::line');
          $command->setTemplate('mobile', 'core::line');
          $command->setIsHistorized(0);
          $command->setUnite('j');
        } else {
          $command->setSubType('string');
        }
        $command->setIsVisible(1);
        $command->save();
      }
      $eqLogic->checkAndUpdateCmd($key, $value);
    }
  }

  public static function handleVacances($_eqlogic, $_values) {
    $eqLogic = self::byLogicalId($_eqlogic, __CLASS__);
    foreach ($_values as $key => $value) {
      $command = $eqLogic->getCmd('info', $key);
      if (!is_object($command)) {
        $command = new mqttDomutilsCmd();
        $command->setLogicalId($key);
        $command->setName($key);
        $command->setEqLogic_id($eqLogic->getId());
        $command->setType('info');
        if ($key == 'vacCejour') {
          $command->setSubType('binary');
          $command->setTemplate('dashboard', 'core::line');
          $command->setTemplate('mobile', 'core::line');
          $command->setIsHistorized(0);
        } elseif ($key == 'vacFin' || $key == 'vacProchainjour') {
          $command->setSubType('numeric');
          $command->setTemplate('dashboard', 'core::line');
          $command->setTemplate('mobile', 'core::line');
          $command->setIsHistorized(0);
          $command->setUnite('j');
        } else {
          $command->setSubType('string');
        }
        $command->setIsVisible(1);
        $command->save();
      }
      $eqLogic->checkAndUpdateCmd($key, $value);
    }
  }

  public static function handleSoleil($_eqlogic, $_values) {
    $eqLogic = self::byLogicalId($_eqlogic, __CLASS__);
    foreach ($_values as $key => $value) {
      if ($key !== "soleilPosv" && $key !== "soleilPosh" ) {
        $command = $eqLogic->getCmd('info', $key);
        if (!is_object($command)) {
          $command = new mqttDomutilsCmd();
          $command->setLogicalId($key);
          $command->setName($key);
          $command->setEqLogic_id($eqLogic->getId());
          $command->setType('info');
          $command->setSubType('string');
          $command->setIsVisible(1);
          $command->save();
        }
        $eqLogic->checkAndUpdateCmd($key, $value);
      }
    }
  }

  public static function handleLune($_eqlogic, $_values) {
    $eqLogic = self::byLogicalId($_eqlogic, __CLASS__);
    foreach ($_values as $key => $value) {
      $command = $eqLogic->getCmd('info', $key);
      if (!is_object($command)) {
        $command = new mqttDomutilsCmd();
        $command->setLogicalId($key);
        $command->setName($key);
        $command->setEqLogic_id($eqLogic->getId());
        $command->setType('info');
        if ($key == 'luneToujours' || $key == 'luneAbsente') {
          $command->setSubType('binary');
          $command->setTemplate('dashboard', 'core::line');
          $command->setTemplate('mobile', 'core::line');
          $command->setIsHistorized(0);
        } else {
          $command->setSubType('string');
        }
        $command->setIsVisible(1);
        $command->save();
      }
      $eqLogic->checkAndUpdateCmd($key, $value);
    }
  }
  
  public static function handleVigilance($_eqlogic, $_values) {
    $eqLogic = self::byLogicalId($_eqlogic, __CLASS__);
    foreach ($_values as $key => $value) {
      $command = $eqLogic->getCmd('info', $key);
      if (!is_object($command)) {
        $command = new mqttDomutilsCmd();
        $command->setLogicalId($key);
        $command->setName($key);
        $command->setEqLogic_id($eqLogic->getId());
        $command->setType('info');
        $command->setSubType('string');
        $command->setIsVisible(1);
        $command->save();
      }
      // Champs Timestamp ?
      if ($value != '' && (substr($key, -1) === 'D' || substr($key, -1) === 'F')) {
        // Timestamp est demain 
        if ($value > strtotime('tomorrow')) {
          $_add = ' '.__('demain', __FILE__);
        } else {
          $_add = ' '.__("aujourd'hui", __FILE__);;
        }
        // Timestamp to string.
        $value = date(__('H:i', __FILE__), $value).$_add;
      }
      $eqLogic->checkAndUpdateCmd($key, $value);
    }
  }

  /* Dependencies */
  public static function dependancy_info() {
    $return = array();
    $return['progress_file'] = jeedom::getTmpFolder(__CLASS__) . '/dependance';
    $return['state'] = 'ok';
    if (config::byKey('lastDependancyInstallTime', __CLASS__) == '') {
      $return['state'] = 'nok';
    } else if (!file_exists(__DIR__ . '/../../resources/node_modules/')) {
      $return['state'] = 'nok';
    } else if (!file_exists(__DIR__ . '/../../resources/node_modules/mqtt4frenchtools/index.js')) {
      $return['state'] = 'nok';
    } else if (config::byKey('mqttDomutilsRequire', __CLASS__) != config::byKey('mqttDomutilsVersion', __CLASS__)) {
      $return['state'] = 'nok';
    }
    return $return;
  }

  /* Deamon */
  public static function deamon_start() {
    self::deamon_stop();
    $deamon_info = self::deamon_info();
    if ($deamon_info['launchable'] != 'ok') {
      throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
    }

    mqtt2::addPluginTopic(__CLASS__, config::byKey('mqtt::topic', __CLASS__, 'domutils'));
    $mqttInfos = mqtt2::getFormatedInfos();
    log::add(__CLASS__, 'debug', '[' . __FUNCTION__ . '] ' . __('Informations reçues de MQTT Manager', __FILE__) . ' : ' . json_encode($mqttInfos));
    $mqtt_url = ($mqttInfos['port'] === 1883) ? 'mqtts://' : 'mqtt://';
    $mqtt_url .= ($mqttInfos['password'] === null) ? '' : $mqttInfos['user'].':'.$mqttInfos['password'].'@';
    $mqtt_url .= $mqttInfos['ip'].':'.$mqttInfos['port'];

    $appjs_path = realpath(dirname(__FILE__) . '/../../resources/node_modules/mqtt4frenchtools');
    chdir($appjs_path);
    $cmd = ' /usr/bin/node ' . $appjs_path . '/index.js -z';

    $eqLogics = self::byType(__CLASS__, true);
    foreach ($eqLogics as $eqLogic) {
      $param = $eqLogic->getConfiguration('daemoncmd');
      if ($param !== '') {
        $cmd .= ' -a "'.$param.'"';
      }
    }
    // API Meteo France
    if ( config::byKey('mqttDomutils::apikey', __CLASS__) != '') {
      $cmd.= ' -m '.config::byKey('mqttDomutils::apikey', __CLASS__);
    }
    $cmd .= ' -u '.$mqtt_url;
    $cmd .= ' -t '.config::byKey('mqtt::topic', __CLASS__, 'domutils');
    $cmd .= ' -v '.log::convertLogLevel(log::getLogLevel(__CLASS__));
    log::add(__CLASS__, 'info', __('Démarrage du démon mqttDomutils', __FILE__) . ' : ' . $cmd);
    exec(system::getCmdSudo() . $cmd . ' >> ' . log::getPathToLog('mqttDomutilsd') . ' 2>&1 &');
    $i = 0;
    while ($i < 30) {
      $deamon_info = self::deamon_info();
      if ($deamon_info['state'] == 'ok') {
        break;
      }
      sleep(1);
      $i++;
    }
    if ($i >= 30) {
      log::add(__CLASS__, 'error', __('Impossible de démarrer le démon mqttDomutils, consultez les logs', __FILE__), 'unableStartDeamon');
      return false;
    }
    message::removeAll(__CLASS__, 'unableStartDeamon');
    return true;
  }

  public static function deamon_stop() {
    log::add(__CLASS__, 'info', __('Arrêt du démon mqttDomutils', __FILE__));
    $find = 'mqtt4frenchtools/index.js';
    $cmd = "(ps ax || ps w) | grep -ie '" . $find . "' | grep -v grep | awk '{print $1}' | xargs " . system::getCmdSudo() . "kill -15 > /dev/null 2>&1";
    exec($cmd);
    $i = 0;
    while ($i < 5) {
      $deamon_info = self::deamon_info();
      if ($deamon_info['state'] == 'nok') {
        break;
      }
      sleep(1);
      $i++;
    }
    if ($i >= 5) {
      system::kill($find, true);
      $i = 0;
      while ($i < 5) {
        $deamon_info = self::deamon_info();
        if ($deamon_info['state'] == 'nok') {
          break;
        }
        sleep(1);
        $i++;
      }
    }
  }

  public static function deamon_info() {
    $return = array();
    $return['log'] = __CLASS__;
    $return['launchable'] = 'ok';
    $return['state'] = 'nok';
    if (self::isRunning()) {
      $return['state'] = 'ok';
    }
    if (!class_exists('mqtt2')) {
      $return['launchable'] = 'nok';
      $return['launchable_message'] = __('Le plugin MQTT Manager n\'est pas installé', __FILE__);
    } else {
      if (mqtt2::deamon_info()['state'] != 'ok') {
        $return['launchable'] = 'nok';
        $return['launchable_message'] = __('Le démon MQTT Manager n\'est pas démarré', __FILE__);
      }
    }
    // Dépendances
    if (self::dependancy_info()['state'] == 'nok') {
      $return['launchable'] = 'nok';
      $return['launchable_message'] = __('Dépendances non installées.', __FILE__);
    }    
    return $return;
  }

  public static function dependancy_end() {
    config::save('mqttDomutilsVersion', config::byKey('mqttDomutilsRequire', __CLASS__), __CLASS__);
  }

  public static function isRunning() {
    if (!empty(system::ps('mqtt4frenchtools/index.js'))) {
      return true;
    }
    return false;
  }

  /* Pre Post */
  public function postSave() {
    if(empty($this->getComment()) && $this->getLogicalId() !== 'global') {
      self::deamon_start();
    }
  }

  public function postRemove() {
    self::deamon_start();
  }
}

class mqttDomutilsCmd extends cmd {

}

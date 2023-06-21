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
include_file('core', 'authentification', 'php');
if (!isConnect()) {
  include_file('desktop', '404', 'php');
  die();
}
?>
<form class="form-horizontal">
  <fieldset>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Version Librairie Domutils}}</label>
      <div class="col-md-3">
      <?php
        $file = dirname(__FILE__) . '/../resources/node_modules/mqtt4frenchtools/package.json';
        $package = array();
        if (file_exists($file)) {
          $package = json_decode(file_get_contents($file), true);
        }
        if (isset($package['version'])){
          config::save('mqttDomutilsVersion', $package['version'], 'mqttDomutils');
        }
        $localVersion = config::byKey('mqttDomutilsVersion', 'mqttDomutils', 'N/A');
        $wantedVersion = config::byKey('mqttDomutilsRequire', 'mqttDomutils', '');
        if ($localVersion != $wantedVersion) {
          echo '<span class="label label-warning">' . $localVersion . '</span><br>';
          echo "<div class='alert alert-danger text-center'>{{Veuillez relancer les dépendances pour mettre à jour la librairie. Relancez ensuite le démon pour voir la nouvelle version.}}</div>";
        } else {
          echo '<span class="label label-success">' . $localVersion . '</span><br>';
        }
      ?>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Topic racine}}</label>
      <div class="col-md-3">
        <input class="configKey form-control" data-l1key="mqtt::topic" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{APIKey Meteo France}}</label>
      <div class="col-md-3">
        <textarea class="configKey form-control autogrow" data-l1key="mqttDomutils::apikey"></textarea>
      </div>
    </div>
  </fieldset>
</form>

<script>
  $('body').off('mqttDomutils::dependancy_end').on('mqttDomutils::dependancy_end', function(_event, _options) {
    window.location.reload()
  })  
</script>

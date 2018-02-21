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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class jeebike extends eqLogic
{
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    public static function cron15()
    {
        if (trim(config::byKey('apikey', 'jeebike')) != '') {
            log::add('jeebike', 'debug', 'jeebike cron15');
            foreach (eqLogic::byType('jeebike', true) as $jeebike) {
                if ($jeebike->getIsEnable() == 1) {
                    if (!empty($jeebike->getConfiguration('contract_name')) && !empty($jeebike->getConfiguration('number'))) {
                        log::add('jeebike', 'debug', 'récuperation données ' . $jeebike->getConfiguration('contract_name') . ' ' . $jeebike->getConfiguration('number'));
                        $jeebike->getBikes($jeebike->getConfiguration('number'), $jeebike->getConfiguration('contract_name'), trim(config::byKey('apikey', 'jeebike')));
                    } else {
                        log::add('jeebike', 'debug', 'config incomplète');
                    }
                }
            }
        } else {
            log::add('jeebike', 'debug', 'pas de clé api');
        }
    }

    public function getAllContracts()
    {
        $apikey       = trim(config::byKey('apikey', 'jeebike'));
        $url          = 'https://api.jcdecaux.com/vls/v1/contracts?apiKey=' . $apikey;
        //log::add('jeebike', 'debug', print_r($url, true));
        $json         = file_get_contents($url);
        $allcontracts = json_decode($json);
        //log::add('jeebike', 'debug', print_r($allcontracts, true));
        return $allcontracts;
    }

    public function getStationsForContract($contract)
    {
        $apikey   = trim(config::byKey('apikey', 'jeebike'));
        $url      = 'https://api.jcdecaux.com/vls/v1/stations?contract=' . $contract . '&apiKey=' . $apikey;
        //log::add('jeebike', 'debug', print_r($url, true));
        $json     = file_get_contents($url);
        $stations = json_decode($json);
        //log::add('jeebike', 'debug', print_r($stations, true));
        return $stations;
    }

    public function getAllStations()
    {
        $apikey      = trim(config::byKey('apikey', 'jeebike'));
        $url         = 'https://api.jcdecaux.com/vls/v1/stations?apiKey=' . $apikey;
        //log::add('jeebike', 'debug', print_r($url, true));
        $json        = file_get_contents($url);
        $allstations = json_decode($json);
        //log::add('jeebike', 'debug', print_r($allstations, true));
        return $allstations;
    }

    /*     * *********************Méthodes d'instance************************* */

    public function preInsert()
    {
        
    }

    public function postInsert()
    {
        
    }

    public function preSave()
    {
        
    }

    public function postSave()
    {
        
    }

    public function preUpdate()
    {
        if (empty($this->getConfiguration('contract_name'))) {
            throw new Exception(__('La ville doit être spécifiée', __FILE__));
        }
        if (empty($this->getConfiguration('number'))) {
            throw new Exception(__('La numéro de station doit être spécifié', __FILE__));
        }
    }

    public function postUpdate()
    {
        log::add('jeebike', 'debug', '** postUpdate **');
        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'contract_name');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Ville', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('contract_name');
            $jeebikeCmd->setConfiguration('data', 'contract_name');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('string');
            $jeebikeCmd->setIsHistorized(0);
            $jeebikeCmd->save();
        }

        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'number');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Numéro', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('number');
            $jeebikeCmd->setConfiguration('data', 'number');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('string');
            $jeebikeCmd->setIsHistorized(0);
            $jeebikeCmd->save();
        }

        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'name');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Nom', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('name');
            $jeebikeCmd->setConfiguration('data', 'name');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('string');
            $jeebikeCmd->setIsHistorized(0);
            $jeebikeCmd->save();
        }

        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'address');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Adresse', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('address');
            $jeebikeCmd->setConfiguration('data', 'address');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('string');
            $jeebikeCmd->setIsHistorized(0);
            $jeebikeCmd->save();
        }

        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'banking');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Borne de paiement', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('banking');
            $jeebikeCmd->setConfiguration('data', 'banking');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('binary');
            $jeebikeCmd->setIsHistorized(0);
            $jeebikeCmd->save();
        }

        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'bonus');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Bonus', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('bonus');
            $jeebikeCmd->setConfiguration('data', 'bonus');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('string');
            $jeebikeCmd->setIsHistorized(0);
            $jeebikeCmd->save();
        }

        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'status');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Statut', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('status');
            $jeebikeCmd->setConfiguration('data', 'status');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('string');
            $jeebikeCmd->setIsHistorized(0);
            $jeebikeCmd->save();
        }

        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'bike_stands');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Points d attache', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('bike_stands');
            $jeebikeCmd->setConfiguration('data', 'bike_stands');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('string');
            $jeebikeCmd->setIsHistorized(0);
            $jeebikeCmd->save();
        }

        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'available_bike_stands');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Points d attache libres', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('available_bike_stands');
            $jeebikeCmd->setConfiguration('data', 'available_bike_stands');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('numeric');
            $jeebikeCmd->setIsHistorized(0);
            $jeebikeCmd->save();
        }

        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'available_bikes');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Vélos disponibles', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('available_bikes');
            $jeebikeCmd->setConfiguration('data', 'available_bikes');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('numeric');
            $jeebikeCmd->setIsHistorized(1);
            $jeebikeCmd->save();
        }

        // recuperation données statiques
        if ($this->getIsEnable() == 1) {
            $this->getStationInfo($this->getConfiguration('number'), $this->getConfiguration('contract_name'), trim(config::byKey('apikey', 'jeebike')));
        }
        // recuperation données dynamiques
        if ($this->getIsEnable() == 1) {
            $this->getBikes($this->getConfiguration('number'), $this->getConfiguration('contract_name'), trim(config::byKey('apikey', 'jeebike')));
        }
    }

    public function preRemove()
    {
        
    }

    public function postRemove()
    {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*     * **********************Getteur Setteur*************************** */

    public function getBikes($number, $contract, $apikey)
    {
        $url      = 'https://api.jcdecaux.com/vls/v1/stations/' . $number . '?contract=' . $contract . '&apiKey=' . $apikey;
        $json     = file_get_contents($url);
        $json_ret = json_decode($json);
        log::add('jeebike', 'debug', print_r($json_ret, true));
        // epoch timestamp
        $date     = date("Y-m-d H:i:s", substr($json_ret->last_update, 0, 10));

        $cmd = $this->getCmd(null, 'status');
        if (is_object($cmd)) {
            $cmd->setCollectDate($date);
            $cmd->event($json_ret->status);
        }

        $cmd = $this->getCmd(null, 'bike_stands');
        if (is_object($cmd)) {
            $cmd->setCollectDate($date);
            $cmd->event($json_ret->bike_stands);
        }

        $cmd = $this->getCmd(null, 'available_bike_stands');
        if (is_object($cmd)) {
            $cmd->setCollectDate($date);
            $cmd->event($json_ret->available_bike_stands);
        }

        $cmd = $this->getCmd(null, 'available_bikes');
        if (is_object($cmd)) {
            $cmd->setCollectDate($date);
            $cmd->event($json_ret->available_bikes);
        }
    }

    public function getStationInfo($number, $contract, $apikey)
    {
        $url      = 'https://api.jcdecaux.com/vls/v1/stations/' . $number . '?contract=' . $contract . '&apiKey=' . $apikey;
        $json     = file_get_contents($url);
        $json_ret = json_decode($json);
        log::add('jeebike', 'debug', print_r($json_ret, true));
        // epoch timestamp
        $date     = date("Y-m-d H:i:s", substr($json_ret->last_update, 0, 10));

        $cmd = $this->getCmd(null, 'number');
        if (is_object($cmd)) {
            $cmd->setCollectDate($date);
            $cmd->event($json_ret->number);
        }

        $cmd = $this->getCmd(null, 'contract_name');
        if (is_object($cmd)) {
            $cmd->setCollectDate($date);
            $cmd->event($json_ret->contract_name);
        }

        $cmd = $this->getCmd(null, 'name');
        if (is_object($cmd)) {
            $cmd->setCollectDate($date);
            $cmd->event($json_ret->name);
        }

        $cmd = $this->getCmd(null, 'address');
        if (is_object($cmd)) {
            $cmd->setCollectDate($date);
            $cmd->event($json_ret->address);
        }

        $cmd = $this->getCmd(null, 'banking');
        if (is_object($cmd)) {
            $cmd->setCollectDate($date);
            $cmd->event($json_ret->banking);
        }

        $cmd = $this->getCmd(null, 'bonus');
        if (is_object($cmd)) {
            $cmd->setCollectDate($date);
            $cmd->event($json_ret->bonus);
        }
    }

}

class jeebikeCmd extends cmd
{
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array())
    {
        
    }

    /*     * **********************Getteur Setteur*************************** */
}

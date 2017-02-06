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

class jeebike extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    public static function cron15() {
        foreach (eqLogic::byType('jeebike', true) as $jeebike) {
            if ($jeebike->getIsEnable() == 1) {
                if (!empty($jeebike->getConfiguration('apikey'))) {
                    log::add('jeebike', 'debug', 'récuperation données');
                } else {
                     log::add('jeebike', 'debug', 'pas de clé api');
                }
            }
        }
    }




    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        
    }

    public function preUpdate() {
        if (empty($this->getConfiguration('apikey'))) {
            throw new Exception(__('La clé api ne peut pas être vide',__FILE__));
        }
    }

    public function postUpdate() {
        log::add('jeebike', 'debug', '** postUpdate **');
        $cmdlogic = jeebikeCmd::byEqLogicIdAndLogicalId($this->getId(), 'number');
        if (!is_object($cmdlogic)) {
            $jeebikeCmd = new jeebikeCmd();
            $jeebikeCmd->setName(__('Numéro', __FILE__));
            $jeebikeCmd->setEqLogic_id($this->id);
            $jeebikeCmd->setLogicalId('number');
            $jeebikeCmd->setConfiguration('data', 'number');
            $jeebikeCmd->setType('info');
            $jeebikeCmd->setSubType('numeric');
            $jeebikeCmd->setIsHistorized(0);
            $jeebikeCmd->save();
        }
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*     * **********************Getteur Setteur*************************** */
    public function getBikes() {
    }
}

class jeebikeCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>

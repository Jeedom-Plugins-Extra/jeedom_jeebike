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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    //log::add('jeebike', 'debug', 'appel ajax');
    //log::add('jeebike', 'debug', 'get ' . print_r($_GET, true));
    //log::add('jeebike', 'debug', 'post ' . print_r($_POST, true));
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

    if(isset($_POST['go']) || isset($_GET['go']) || isset($_POST['id_contract']) || isset($_GET['id_contract'])) {
 
        $json = array();
     
        if(isset($_POST['go']) || isset($_GET['go'])) {
            $jeebike = new jeebike();
            $allcontracts = $jeebike->getAllContracts();
            foreach ($allcontracts as $contract) {
                $json[$contract->name][] = $contract->country_code . '-' . $contract->name . '-' . $contract->commercial_name;
            }
            //log::add('jeebike', 'debug', print_r($json, true));
        } else if(isset($_POST['id_contract'])) {
            $id = htmlentities($_POST['id_contract']); // nom du contrat
            if ($id!='null') {
                $jeebike = new jeebike();
                $stations = $jeebike->getStationsForContract($id);
                foreach ($stations as $station) {
                    $json[$station->number][] = $station->name;
                }
                //log::add('jeebike', 'debug', print_r($json, true));
            }
        }
     
        // envoi du résultat au success
        ajax::success($json);

    }


    throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>

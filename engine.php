<?php

/*
 * PHP Site for Icecast MySQL Stats
 * Copyright (C) 2013  Luca Cireddu
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * You can also find an on-line copy of this license at:
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Created and mantained by: Luca Cireddu
 *                           sardylan@gmail.com
 *                           http://www.lucacireddu.it
 *
 */


$page_path = "";
$cwd = getcwd();
$site_root = substr(getcwd(), 0, strlen($cwd) - strlen($page_path));

require_once($site_root . "/includes/head.php");
require_once($site_root . "/includes/functions.php");

$action = my_get("action");

if($action == "refresh") {
    $ret = array();

    $sql_interval = "whenquery > '{$today} 00:00:00' AND whenquery < '{$today} 23:59:59'";
    $sql_interval_yesterday = "whenquery > '{$yesterday} 00:00:00' AND whenquery < '{$yesterday} 23:59:59'";

    $sql_query = "SELECT whenquery AS start FROM " . MYSQL_TABLE . " WHERE {$sql_interval} AND PAC > 0 ORDER BY whenquery ASC LIMIT 0, 1";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC))
                $ret["res_start"] = strftime("%H:%M", strtotime($sql_data["start"]));

    $sql_query = "SELECT whenquery AS stop FROM " . MYSQL_TABLE . " WHERE {$sql_interval} AND PAC > 0 ORDER BY whenquery DESC LIMIT 0, 1";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC))
                $ret["res_stop"] = strftime("%H:%M", strtotime($sql_data["stop"]));

    $sql_query = "SELECT whenquery AS online FROM " . MYSQL_TABLE . " WHERE {$sql_interval} ORDER BY whenquery ASC LIMIT 0, 1";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC))
                $ret["res_online"] = strftime("%H:%M", strtotime($sql_data["online"]));

    $sql_query = "SELECT whenquery AS offline FROM " . MYSQL_TABLE . " WHERE {$sql_interval} ORDER BY whenquery DESC LIMIT 0, 1";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC))
                $ret["res_offline"] = strftime("%H:%M", strtotime($sql_data["offline"]));

    $sql_query = "SELECT whenquery, PAC FROM " . MYSQL_TABLE . " WHERE {$sql_interval} ORDER BY PAC DESC LIMIT 0, 1";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
                $ret["today_max_how"] = $sql_data["PAC"] / 2;
                $ret["today_max_when"] = strftime("%H:%M", strtotime($sql_data["whenquery"]));
            }

    $sql_query = "SELECT PAC, TKK, KDY FROM " . MYSQL_TABLE . " ORDER BY whenquery DESC LIMIT 0, 1";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
                $ret["inv_PAC"] = $sql_data["PAC"] / 2;
                $ret["inv_TKK"] = $sql_data["TKK"];
                $ret["today_prod"] = number_format($sql_data["KDY"] / 10, 1);
            }

    $sql_query = "SELECT whenquery FROM " . MYSQL_TABLE . " WHERE whenquery > (DATE_SUB(NOW(), INTERVAL 1 MINUTE)) ORDER BY whenquery DESC LIMIT 0, 1";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            $ret["isonline"] = 1;
        else
            $ret["isonline"] = 0;

    header("Content-type: application/json");
    header("Cache-Control: no-cache, must-revalidate");

    echo json_encode($ret);
}

if($action == "first") {
    $ret = array();

    $sql_interval_yesterday = "whenquery > '{$yesterday} 00:00:00' AND whenquery < '{$yesterday} 23:59:59'";

    $sql_query = "SELECT whenquery, PAC FROM " . MYSQL_TABLE . " WHERE {$sql_interval_yesterday} ORDER BY PAC DESC LIMIT 0, 1";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
                $ret["yesterday_max_how"] = $sql_data["PAC"] / 2;
                $ret["yesterday_max_when"] = strftime("%H:%M", strtotime($sql_data["whenquery"]));
            }

    $sql_query = "SELECT KLD FROM " . MYSQL_TABLE . " ORDER BY whenquery DESC LIMIT 0, 1";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0)
            if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC))
                $ret["yesterday_prod"] = number_format($sql_data["KLD"] / 10, 1);

    $sql_query = "SELECT whenquery, PAC FROM " . MYSQL_TABLE . " WHERE {$sql_interval_yesterday} AND PAC > 0;";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0) {
            while($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
                if($kwh_last_whenquery != 0) {
                    $kwh_new_whenquery = strtotime($sql_data["whenquery"]);

                    $kwh_duration = $kwh_new_whenquery - $kwh_last_whenquery;
                    $kwh_sum += $kwh_last_PAC * $kwh_duration;
                }

                $kwh_last_whenquery = strtotime($sql_data["whenquery"]);
                $kwh_last_PAC = $sql_data["PAC"] / 2;
            }

            $kwh_sum += $kwh_last_PAC * 10; // 10 seconds medium query time

            $kwh_prod = ($kwh_sum / 3600) / 1000;

            $ret["yesterday_prod"] = number_format($kwh_prod, 1);
        }

    header("Content-type: application/json");
    header("Cache-Control: no-cache, must-revalidate");

    echo json_encode($ret);
}

if($action == "prod") {
    $ret = array();

    $sql_interval = "whenquery > '{$today} 00:00:00' AND whenquery < '{$today} 23:59:59'";

    $kwh_last_whenquery = 0;
    $kwh_last_PAC = 0;
    $kwh_sum = 0;

    $sql_query = "SELECT whenquery, PAC FROM " . MYSQL_TABLE . " WHERE {$sql_interval} AND PAC > 0;";

    if($sql_result = $sql_conn->query($sql_query))
        if($sql_result->num_rows > 0) {
            while($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
                if($kwh_last_whenquery != 0) {
                    $kwh_new_whenquery = strtotime($sql_data["whenquery"]);

                    $kwh_duration = $kwh_new_whenquery - $kwh_last_whenquery;
                    $kwh_sum += $kwh_last_PAC * $kwh_duration;
                }

                $kwh_last_whenquery = strtotime($sql_data["whenquery"]);
                $kwh_last_PAC = $sql_data["PAC"] / 2;
            }

            $kwh_sum += $kwh_last_PAC * 10; // 10 seconds medium query time

            $kwh_prod = ($kwh_sum / 3600) / 1000;

            $ret["today_prod"] = number_format($kwh_prod, 1);
        }

    header("Content-type: application/json");
    header("Cache-Control: no-cache, must-revalidate");

    echo json_encode($ret);
}

?>
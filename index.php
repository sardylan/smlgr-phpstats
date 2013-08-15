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

$sql_interval = "whenquery > '{$today} 00:00:00' AND whenquery < '{$today} 23:59:59'";

$sql_query = "SELECT whenquery AS start FROM " . MYSQL_TABLE . " WHERE {$sql_interval} AND PAC > 0 ORDER BY whenquery ASC LIMIT 0, 1";

error_log($sql_query);

if($sql_result = $sql_conn->query($sql_query)) {
    if($sql_result->num_rows > 0) {
        if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
            $inv_start = $sql_data["start"];
        }
    }
}

$sql_query = "SELECT whenquery AS stop FROM " . MYSQL_TABLE . " WHERE {$sql_interval} AND PAC > 0 ORDER BY whenquery DESC LIMIT 0, 1";

error_log($sql_query);

if($sql_result = $sql_conn->query($sql_query)) {
    if($sql_result->num_rows > 0) {
        if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
            $inv_stop = $sql_data["stop"];
        }
    }
}

$sql_query = "SELECT whenquery AS online FROM " . MYSQL_TABLE . " WHERE {$sql_interval} ORDER BY whenquery ASC LIMIT 0, 1";

error_log($sql_query);

if($sql_result = $sql_conn->query($sql_query)) {
    if($sql_result->num_rows > 0) {
        if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
            $inv_online = $sql_data["online"];
        }
    }
}

$sql_query = "SELECT whenquery AS offline FROM " . MYSQL_TABLE . " WHERE {$sql_interval} ORDER BY whenquery DESC LIMIT 0, 1";

error_log($sql_query);

if($sql_result = $sql_conn->query($sql_query)) {
    if($sql_result->num_rows > 0) {
        if($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
            $inv_offline = $sql_data["offline"];
        }
    }
}

?>
<html>

    <head>
        <title>SolarMax Inverters Stats</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/page.css" />
    </head>

    <body>
        <div id="page">
            <h1>Today activity</h1>
            <h2>Start working at <?php echo strftime("%H:%M", strtotime($inv_start));; ?> and stop working at <?php echo strftime("%H:%M", strtotime($inv_stop)); ?><br />
                Online from <?php echo strftime("%H:%M", strtotime($inv_online)); ?> to <?php echo strftime("%H:%M", strtotime($inv_offline)); ?></h2>
            <p><img src="images.php" alt="Graph" title="Graph" /></p>
        </div>
    </body>

</html>
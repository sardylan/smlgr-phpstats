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

define("MYSQL_SERVER", "127.0.0.1");
define("MYSQL_PORT", 3306);
define("MYSQL_USER", "smlgr");
define("MYSQL_PASSWORD", "smlgr");
define("MYSQL_DB", "smlgr");

define("MYSQL_TABLE", "invdata");

$sql_conn = new mysqli(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

if($sql_conn->connect_error)
    die("Connect Error (" . $mysqli->connect_errno . "): " . $mysqli->connect_error);

$now = time();
$today = date("Y-m-d", $now);
$yesterday = date("Y-m-d", $now - (24*60*60));

?>

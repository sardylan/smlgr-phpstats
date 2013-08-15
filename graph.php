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

require_once($site_root . "/includes/jpgraph-3.5.0b1/jpgraph.php");
require_once($site_root . "/includes/jpgraph-3.5.0b1/jpgraph_line.php");
require_once($site_root . "/includes/jpgraph-3.5.0b1/jpgraph_date.php");

$action = my_get("action");

if($action == "today") {
    $sql_interval = "whenquery > '{$today} 00:00:00' AND whenquery < '{$today} 23:59:59' AND PAC > 0";

    $sql_query = "SELECT PAC, whenquery FROM " . MYSQL_TABLE . " WHERE {$sql_interval}";

    error_log($sql_query);

    $ydata = array();
    $xdata = array();

    if($sql_result = $sql_conn->query($sql_query)) {
        if($sql_result->num_rows > 0) {
            while($sql_data = $sql_result->fetch_array(MYSQLI_ASSOC)) {
                array_push($ydata, $sql_data["PAC"]/2);
                array_push($xdata, strtotime($sql_data["whenquery"]));
            }
        }
    }

    if(count($xdata) > 0) {
        $graph = new Graph(1000,500);
        $graph->SetScale("datlin");
        $graph->SetTickDensity(TICKD_DENSE);
        $graph->SetMargin(60,30,60,70);
        $graph->SetFrame(true);
        $graph->title->Set("Today activity");

        $graph->xaxis->SetTickSide(SIDE_BOTTOM);
        $graph->xaxis->SetLabelAngle(90);
        $graph->xaxis->scale->ticks->SetSize(8, 3);
        $graph->xaxis->scale->ticks->SupressMinorTickMarks(false);
        $graph->xaxis->scale->ticks->SupressTickMarks(false);
        $graph->xaxis->scale->SetTimeAlign(HOURADJ_1);

        if(count($xdata) < 6)
            $graph->xaxis->scale->ticks->Set(10, 1);
        elseif(count($xdata) < 60)
            $graph->xaxis->scale->ticks->Set(60, 10);
        elseif(count($xdata) < 300)
            $graph->xaxis->scale->ticks->Set(10*60, 60);
        elseif(count($xdata) < 600)
            $graph->xaxis->scale->ticks->Set(20*60, 2*60);
        elseif(count($xdata) < 900)
            $graph->xaxis->scale->ticks->Set(30*60, 5*60);
        elseif(count($xdata) < 2000)
            $graph->xaxis->scale->ticks->Set(60*60, 15*60);
        elseif(count($xdata) < 3000)
            $graph->xaxis->scale->ticks->Set(2*60*60, 15*60);
        else
            $graph->xaxis->scale->ticks->Set(3*60*60, 20*60);

        $graph->yaxis->SetTickSide(SIDE_LEFT);

        $lineplot = new LinePlot($ydata, $xdata);
        $lineplot->SetColor("blue");
        $lineplot->SetFillColor("lightblue"); 

        $graph->Add($lineplot);

        header("Content-type: image/png");
        header("Cache-Control: no-cache, must-revalidate");
        $graph->Stroke();
    }
}

?>

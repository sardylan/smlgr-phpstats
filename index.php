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

?>
<html>

    <head>
        <title>SolarMax Inverters Stats</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/page.css" />
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="js/parser.js"></script>
    </head>

    <body>
        <div id="page">
            <div id="header">
                <h1>Today activity</h1>
                <h2>Start working at <span id="res_start"></span> and stop working at <span id="res_stop"></span><br />
                    Online from <span id="res_online"></span> to <span id="res_offline"></span></h2>
            </div>
            <div id="live">
                <div id="live_sx">
                    <div>
                        <div class="inv_label" id="inv_PAC_label">Current Power:</div>
                        <div class="inv_value" id="inv_PAC_value"></div>
                        <div class="inv_sep" id="inv_PAC_sep"></div>
                        <div class="inv_bar" id="inv_PAC_bar"><div class="inv_bar_value" id="inv_PAC_bar_value"></div></div>
                        <div class="inv_instrument floatreset"></div>
                        <div class="inv_label" id="inv_TKK_label">Temperature:</div>
                        <div class="inv_value" id="inv_TKK_value"></div>
                        <div class="inv_sep" id="inv_TKK_sep"></div>
                        <div class="inv_bar" id="inv_TKK_bar"><div class="inv_bar_value" id="inv_TKK_bar_value"></div></div>
                        <div class="floatreset"></div>
                    </div>
                </div>
                <div id="live_dx">
                    Max production: <span id="max_how"></span> at <span id="max_when"></span>
                </div>
            </div>
            <div class="ruler floatreset"></div>
            <div id="content">
                <img id="img_today" src="img/loading.png" alt="Graph" title="Graph" />
            </div>
        </div>
    </body>

</html>
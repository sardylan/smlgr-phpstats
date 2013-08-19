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


function updatePage() {
    $.ajax({
        type: "GET",
        url: "engine.php",
        data: "action=refresh",
        success: function(response) {
            $("#content").show();
            $("#img_today").attr("src", "graph.php?action=today&temp=" + Math.random() * 1000);
            $("#res_start").html(response.res_start);
            $("#res_stop").html(response.res_stop);
            $("#res_online").html(response.res_online);
            $("#res_offline").html(response.res_offline);
            $("#today_max_how").html(response.today_max_how);
            $("#today_max_when").html(response.today_max_when);
            $("#today_prod").html(response.today_prod);

            if(response.isonline == 1) {
                $("#inv_PAC_value").html(response.inv_PAC + " W");
                $("#inv_TKK_value").html(response.inv_TKK + " °C");
                $("#inv_PAC_bar_value").width(($("#inv_PAC_bar").width() / 2750) * response.inv_PAC);
                $("#inv_TKK_bar_value").width(($("#inv_TKK_bar").width() / 80) * response.inv_TKK);
            } else {
                $("#inv_PAC_value").html("OFFLINE");
                $("#inv_TKK_value").html("OFFLINE");
                $("#inv_PAC_bar_value").width(0);
                $("#inv_TKK_bar_value").width(0);
            }
        }
    });
}

function firstLoadPage() {
    $.ajax({
        type: "GET",
        url: "engine.php",
        data: "action=first",
        success: function(response) {
            $("#yesterday_max_how").html(response.yesterday_max_how);
            $("#yesterday_max_when").html(response.yesterday_max_when);
            $("#yesterday_prod").html(response.yesterday_prod);
        }
    });
}

$(document).ready(function() {
    firstLoadPage();
    updatePage();
    setInterval(updatePage, 1000 * (10));
});

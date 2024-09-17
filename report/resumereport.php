<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
// hide all error
error_reporting(0);
if (!isset($_SESSION["mikhmon"])) {
  header("Location:../admin.php?id=login");
} else {
// load session MikroTik
$session = $_GET['session'];


// load config
  include('../include/config.php');
  include('../include/readcfg.php');

$idbl = $_GET['idbl'];
// new Date($idb1);
$thisM = substr($idbl,0,2);
$thisY = substr($idbl,-4);

$ms = array(1 => "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
$mn = array_search($thisM, $ms);

// https://secure.php.net/manual/en/function.cal-days-in-month.php#38666
function days_in_month($month, $year) 
{ 
// calculate number of days in a month 
return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31); 
} 


if ($mn == date("n")){
  $totD =  (date('d') +1);
}else{
  $totD = (days_in_month($mn, $thisY)+ 1);
}

// var_dump($_SESSION['dataresume']);

function resume_per_day($date){
$evalue =  explode($date,$_SESSION['dataresume']);
$x = count($evalue);
			for ($i = 0; $i < $x; $i++) {
				$result += (int) $evalue[$i];
			}
			return ($x-1).'-'.$result;
}

$totalvrc =  explode("/",$_SESSION['totalresume'])[0];
$totalincome = explode("/",$_SESSION['totalresume'])[1];


if ($currency == in_array($currency, $cekindo['indo'])) {
  $totalreport = "Total " . $totalvrc . " voucher : " . $currency . " " . number_format((float)$totalincome, 0, ",", ".");

} else {
  $totalreport = "Total " . $totalvrc . " voucher : " . $currency . " " . number_format((float)$totalincome, 2);
}

$date_values = [];
$data = $_SESSION['dataresume'];
// Modify the regular expression to capture variable-length digits after the date
preg_match_all('/(\d{4}-\d{2}-\d{2})(\d+)(?=(\d{4}-\d{2}-\d{2})|$)/', $data, $matches, PREG_SET_ORDER);

foreach ($matches as $match) {
    $date = $match[1];  // The date in YYYY-MM-DD format
    $value = (int)$match[2];  // The corresponding numeric value (now variable in length)

    // If the date already exists, sum the values and increment the vcr count
    if (isset($date_values[$date])) {
        $date_values[$date] += $value;
        $date_values[$date.'_vcr'] = isset($date_values[$date.'_vcr']) ? $date_values[$date.'_vcr'] + 1 : 1;
    } else {
        // Initialize the date value and set the vcr count to 1
        $date_values[$date] = $value;
        $date_values[$date.'_vcr'] = 1;
    }
}

}
?>

          <div class="card">
            <div class="card-header"><h3><i class="fa fa-area-chart"></i> Resume Report </h3></div>
          
              <div class="card-body">
                <div class="row">

                  <script src="./js/highcharts/highcharts.js"></script>
                  <script src="./js/highcharts/themes/hc.<?= $theme; ?>.js"></script>


<div class="col-12" id="container"></div>

<script type="text/javascript">
Highcharts.chart('container', {
    chart: {
    height: 500,
    type: 'area',
    },
    title: {
        text: 'Selling Report <?= ucfirst($thisM)." ".$thisY;?>'
    },

    subtitle: {
        text: '<?= $totalreport;?>'
    },

    xAxis: {
        tickInterval: 1
    },

    yAxis: {
        title: {
            text: 'Total Sales'
        }
    },

    legend: {
        layout: 'horizontal',
        align: 'center',
        verticalAlign: 'bottom'
    },

    
    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 1
        }
    },

    series: [{
        name: 'Report',
        data: [
<?php

for ($i = 1; $i <= $totD-1; $i++) {
  if (strlen($i) == 1) {
      $thisD = "0" . $i;
  } else {
      $thisD = $i;
  }

  $dateIndex = $thisY . '-' . $thisM . '-' . $thisD;

  echo "['<b>" . $thisD . " " . ucfirst($thisM) . " " . ($date_values[$dateIndex.'_vcr'] ?? 0) . " voucher</b>'," . ($date_values[$dateIndex] ?? 0) . "],";
}


?>

        ]
    }],
    tooltip: {
        pointFormat: 'Total sales: <b>{point.y}</b>',
    },
    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});


</script>
                </div>
              </div>  
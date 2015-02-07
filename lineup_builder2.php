<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.png">

    <title>FD Analyzer</title>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <!--<link href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.css" rel="stylesheet">-->
    <link href="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <style type='text/css'>
      /* override datatable.bootstrap.css */
      table.dataTable thead > tr > th {
        padding-left: 4px;
        padding-right: -20px;
      }

      #divFilter {
        float: left;
        padding: 10px;
        background-color: #ecf0f1;
        border-radius: 10px;
        margin-right: 20px;
        border:1px solid gray;
      }
      #divPlayers {
        overflow: hidden;
      }
      .playerFilterDivs {
        overflow: auto;
        margin-bottom: 15px;
      }
      #pointsSlider .ui-slider-range { background: #2c3e50; }
      #minsSlider .ui-slider-range { background: #2c3e50; }
      #spreadSlider .ui-slider-range { background: #2c3e50; }
      /** #pointsSlider .ui-slider-handle { border-color: #f6931f; } **/
      .header { font-size: 25px; text-align: center;}
      .positive { color: #009966; }
      .negative { color: red; }
      .filter { color: #777; background-color: #eee; }
      .filter .text-success { color: #777; }
      .filter .text-danger { color: #777; }
      .exclude { color: #777; background-color: #ebccd1; }
      .exclude .text-success { color: #777; }
      .exclude .text-danger { color: #777; }
    </style>
  </head>

  <body> 
    <?php $page='lineup_builder'; include('navbar.php'); ?>

    <div id="main" class="container">
      <div id="divFilter">
        <p class="header">Player Filter</p>
        <div id="posFilter" class="btn-group playerFilterDivs" data-toggle="buttons">
          <label class="btn btn-sm btn-primary active">
            <input type="radio" value="ALL" checked>ALL
          </label>
          <label class="btn btn-sm btn-default">
            <input type="radio" value="PG">PG
          </label>
          <label class="btn btn-sm btn-default">
            <input type="radio" value="SG">SG
          </label>
          <label class="btn btn-sm btn-default">
            <input type="radio" value="SF">SF
          </label>
          <label class="btn btn-sm btn-default">
            <input type="radio" value="PF">PF
          </label>
          <label class="btn btn-sm btn-default">
            <input type="radio" value="C">C
          </label>                        
        </div>
        <div id="playerFilter" class="playerFilterDivs">
          <div>
            <input id="playerFilterInput" type="text" class="form-control input-sm" placeholder="Search by player name...">
          </div>
        </div>
        <div class="playerFilterDivs">
        <p style="font-size: 14px; text-align:center; margin-bottom:2px">Points Range</p>
        <div id="pointsSlider" style="width: 92%; margin: 0 auto;"></div>
        <input type="text" size=2 id="minPoints" readonly style="border:0; text-align: left; background-color: #ecf0f1; color:#2c3e50; float: left;">
        <input type="text" size=2 id="maxPoints" readonly style="border:0; text-align: right; background-color: #ecf0f1; color:#2c3e50; float: right;">
        </div>
        <div class="playerFilterDivs">
          <p style="font-size: 14px; text-align:center; margin-bottom:2px">Minutes Range</p>
          <div id="minsSlider" style="width: 92%; margin: 0 auto;"></div>
          <input type="text" size=2 id="minMins" readonly style="border:0; text-align: left; background-color: #ecf0f1; color:#2c3e50; float: left;">
          <input type="text" size=2 id="maxMins" readonly style="border:0; text-align: right; background-color: #ecf0f1; color:#2c3e50; float: right;">
        </div>
        <div class="playerFilterDivs">
          <p style="font-size: 14px; text-align:center; margin-bottom:2px">Spread Range</p>
          <div id="spreadSlider" style="width: 92%; margin: 0 auto;"></div>
          <input type="text" size=3 id="minSpread" readonly style="border:0; text-align: left; background-color: #ecf0f1; color:#2c3e50; float: left;">
          <input type="text" size=3 id="maxSpread" readonly style="border:0; text-align: right; background-color: #ecf0f1; color:#2c3e50; float: right;">
        </div>
        <div class="form-group playerFilterDivs" style="text-align: center">
          <button id="calcLineups" class="btn btn-success my_popup_open">Lineup Optimizer</button>
        </div>                
      </div>

      <div id="divPlayers">   
        <table id="players" class="table table-condensed table-bordered table-striped table-hover" width="100%">
          <thead>
            <tr>
              <th></th>
              <th></th>
              <th>Name</th>
              <th>Pos</th>
              <th>Val</th>
              <th>Price</th>
              <th><abbr style="border-bottom:none" title="Projected Points">PPt</abbr></th>
              <th><abbr style="border-bottom:none" title="Average Points (last 5 games)">APt</abbr></th>
              <th><abbr style="border-bottom:none" title="Projected Minutes">PMin</abbr></th>
              <th><abbr style="border-bottom:none" title="Average Points (last 5 games)">AMin</abbr></th>
              <th>Opp</th>
              <th>Line</th>
            </tr>          
          </thead>
          <tbody>

      <?php
      $json_string = file_get_contents('data/players.json');
      $players = json_decode($json_string, true);
      $positions = ["PG","SG","SF","PF","C"];

      foreach ($players as $player) {
        $name=$player["name"];
        if ($player["status"] == 'p')
          $name.=" <sup class=\"negative\">P</sup>";
        else if ($player["status"] == 'q')
          $name.=" <sup class=\"negative\">Q</sup>";

        $pos=$player["pos"];
        $salary='$'.$player["salary"];
        $value=intval($player["value"]);

        $points=intval($player["points"]);
        $points_avg=intval($player["points_avg"]);
        $points_arrow="";
        if ($points_avg == 0)
          $points_arrow="<span class=\"glyphicon glyphicon-thumbs-down text-danger\"></span>";
        else if(($points-$points_avg)/$points_avg > .15)
          $points_arrow="<span class=\"glyphicon glyphicon-circle-arrow-up text-success\" style=\"font-size:12px\"/>";
        else if(($points-$points_avg)/$points_avg < -.10)
          $points_arrow="<span class=\"glyphicon glyphicon-circle-arrow-down text-danger\" style=\"font-size:12px\"/>";

        $mins=intval($player["mins"]);
        $mins_avg=intval($player["mins_avg"]);
        $mins_arrow="";
        if(($mins-$mins_avg) > 3)
          $mins_arrow="<span class=\"glyphicon glyphicon-circle-arrow-up text-success\" style=\"font-size:12px\"/>";
        else if(($mins-$mins_avg) < -3)
          $mins_arrow="<span class=\"glyphicon glyphicon-circle-arrow-down text-danger\" style=\"font-size:12px\"/>";

        $opp=$player["opp"];
        if ($player["away"])  $opp='at '.$opp;
        if (!$player["away"]) $opp='vs '.$opp;
        $opp_adj=round($player["opp_adj"], 1);
        if ($opp_adj > 0) $opp_adj='+'.$opp_adj;

        $opp_class="";
        if ($opp_adj >= 2) $opp_class="text-success";
        if ($opp_adj <= -2) $opp_class="text-danger";

        $spread=$player["spread"];
        $spread_class="";
        if (abs($spread) < 4)  $spread_class="text-success";
        if (abs($spread) > 9) $spread_class="text-danger";

        $rest=$player["rest"];
        $rest_class="";
        if ($rest < 1) $rest_class="text-danger";
        if ($rest > 2) $rest_class="text-success";

        $lock='<span id="lockPlayer" class="glyphicon glyphicon-ok" style="font-size: 12px"></span>';
        $remove='<span id="removePlayer" class="glyphicon glyphicon-remove" style="font-size: 12px"></span>';

        echo "      <tr>\n";
        echo "        <td>$lock</td>";
        echo "        <td>$remove</td>";
        echo "        <td>$name</td>\n";            
        echo "        <td>$pos</td>\n";
        echo "        <td>$value</td>\n";
        echo "        <td>$salary</td>\n";
        echo "        <td>$points $points_arrow</td>\n";
        echo "        <td>$points_avg</td>\n";
        echo "        <td>$mins $mins_arrow</td>\n";
        echo "        <td>$mins_avg</td>\n";
        echo "        <td>$opp <i>(<span class=\"$opp_class\">$opp_adj</span>)</i></td>\n";
        echo "        <td><span class=\"$spread_class\">$spread</span></td>\n";
        // echo "        <td><span class=\"$rest_class\">$rest</span></td>\n";
        echo "      </tr>\n";
      }
      echo "      </tbody>\n";
      echo "    </table>\n";
      ?>

      <?php
        $current_time = time();
        $tokens = [86400 => 'day',3600 => 'hour',60 => 'minute'];
        clearstatcache();
        function time_in_text ($time_diff) {
          $text_time = ""; 
          if ($time_diff < 60) {
            return "Just now";
          }
        
          global $tokens;
          foreach ($tokens as $unit => $text) {
            if ($time_diff < $unit) continue;
            $numberOfUnits = floor($time_diff / $unit);
            $time_diff -= $numberOfUnits * $unit;
            $text_time .= $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ':' ');
          }
          return $text_time;
        }

        $filename = "data/lineups.json";
        $mtime = filemtime($filename);
        $time_diff = $current_time - $mtime;
        $time_in_text = time_in_text($time_diff);
      ?>
        <div style="overflow: auto; background-color: #ecf0f1; border-top: 2px solid gray">
          <span id="numPlayers" style="color:#2c3e50; float: left; margin: 5px"></span>
          <span style="color:#2c3e50; float: right; margin: 5px">Last Updated: <i><?php echo $time_in_text ?></i><span>
        </div>
      </div> <!-- players -->
    </div> <!-- main container -->

    <div id="my_popup" style="background-color: white; padding: 30px; border:1px solid; border-radius: 10px;">
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type='text/javascript' src='//code.jquery.com/jquery-1.9.1.js'></script>
    <script type='text/javascript' src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script type='text/javascript' src="js/bootstrap.min.js"></script>
    <script type='text/javascript' src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
    <script type='text/javascript' src="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script type='text/javascript' src="http://vast-engineering.github.io/jquery-popup-overlay/jquery.popupoverlay.js"></script>    
    <script type="text/javascript">
      function inViewport($el) {
        var H = $(window).height(),
            r = $el[0].getBoundingClientRect(), t=r.top, b=r.bottom;
        return Math.max(0, t>0? H-t : (b<H?b:H));  
      }

      function getLoadingHtml() {
        var html = '<div id="loading">';
        html += '<h3 style="text-align: center;">Calculating lineups...</h3>';
        html += '<img src="ajax-loader.gif" style="display: block; margin-left: auto; margin-right: auto;">';
        html += '</div>';

        return html;
      }

      $('#posFilter .btn').on('click', function() {
        var posCol = $("#players").DataTable().column(':contains(Pos)');
        var pos = $(this).find('input').attr('value');
        if (pos == "ALL")
          posCol.search('').draw();
        else
          posCol.search(pos).draw();
      });

      $('#playerFilterInput').keyup(function() {
        var text = $(this).val();
        var nameCol = $("#players").DataTable().column(':contains(Name)');
        nameCol.search(text).draw();
      });

      $(document).ready(function() {
        $("#pointsSlider").slider({
          range: true,
          animate: "fast",
          min: 0,
          max: 60,
          values: [0, 60],
          slide: function(event, ui) {
            $("#minPoints").val(ui.values[0]);
            $("#maxPoints").val(ui.values[1]);
          },
          stop: function(event, ui) {
            $("#players").DataTable().draw();
          }, 
        });
        $("#minPoints").val($("#pointsSlider").slider("values", 0));
        $("#maxPoints").val($("#pointsSlider").slider("values", 1));

        $("#minsSlider").slider({
          range: true,
          animate: "fast",
          min: 0,
          max: 48,
          values: [0, 48],
          slide: function(event, ui) {
            $("#minMins").val(ui.values[0]);
            $("#maxMins").val(ui.values[1]);
          },
          stop: function(event, ui) {
            $("#players").DataTable().draw();
          }, 
        });
        $("#minMins").val($("#minsSlider").slider("values", 0));
        $("#maxMins").val($("#minsSlider").slider("values", 1));

        $("#spreadSlider").slider({
          range: true,
          animate: "fast",
          min: -15,
          max: +15,
          values: [-15, +15],
          slide: function(event, ui) {
            $("#minSpread").val(ui.values[0]);
            $("#maxSpread").val('+' + ui.values[1]);
          },
          stop: function(event, ui) {
            $("#players").DataTable().draw();
          }, 
        });
        $("#minSpread").val($("#spreadSlider").slider("values", 0));
        $("#maxSpread").val('+' + $("#spreadSlider").slider("values", 1));

        var height = inViewport($("#divPlayers")) - 100;
        console.log(height);
        var table = $('#players').DataTable({
          "dom": '<"top">t<"bottom"><"clear">',
          "paging": false,            
          "scrollY": height+"px",
          "scrollCollapse": true,
          "order": [[2,"desc"]],
          "drawCallback": function(settings) {
            $("#numPlayers").text(this.api().page.info().recordsDisplay+" players shown");
          }
        });          
        $("#numPlayers").text(table.page.info().recordsDisplay+" players shown");                

        var pointsIndex = table.column(':contains(PPt)').index();
        var minsIndex = table.column(':contains(PMin)').index();
        var spreadIndex = table.column(':contains(Line)').index();
        $.fn.dataTable.ext.search.push(
          function(settings, data, dataIndex) {
            var minPoints = parseInt($('#minPoints').val());
            var maxPoints = parseInt($('#maxPoints').val());              
            var points = parseFloat(data[pointsIndex]) || 0; 

            var minMins = parseInt($('#minMins').val());
            var maxMins = parseInt($('#maxMins').val());
            var mins = parseFloat(data[minsIndex]) || 0; 

            var minSpread = parseInt($('#minSpread').val());
            var maxSpread = parseInt($('#maxSpread').val());
            var spread = parseFloat(data[spreadIndex]) || 0; 

            if (isNaN(minPoints) || isNaN(maxPoints) ||
              isNaN(minMins) || isNaN(maxMins) ||
              isNaN(minSpread) || isNaN(maxSpread)) {  
              return true;
            }

            if ((points >= minPoints && points <= maxPoints)  && 
              (mins >= minMins && mins <= maxMins) &&
              (spread >= minSpread && spread <= maxSpread)) {
              return true;
            }

            return false;
          }
        );

        $('#my_popup').html(getLoadingHtml());

        $('#my_popup').popup({
          onopen: function() {
            $.getJSON('data/players.json', function(players) {
              var table = $("#players").DataTable();
              var nameCol = table.column(':contains(Name)').index();
 
              var rows = table.rows({filter: 'applied'}).data();
              var playersOutput = {};
              for (var i = 0; i < rows.length; i++) {
                var name = rows[i][nameCol];
                var idx = name.indexOf('<');
                if (idx >= 0) { name = name.substr(0, idx-1); }
                playersOutput[name] = {
                  name: players[name].name,
                  pos: players[name].pos,
                  points: players[name].points,
                  salary: players[name].salary,
                  team: players[name].team,
                  opp: players[name].opp
                };
              }
            
              $.ajax({
                type: 'Post',
                url: 'cgi-bin/updater.cgi',
                data: {updateType: 'calcTeams', players: JSON.stringify(playersOutput)},
                timeout: 180000,
                success: function(lineups){
                  console.log(lineups);
                  lineups = JSON.parse(lineups);
                  var divHtml = '<table class="table table-condensed table-striped">';
                  divHtml += '<thead><tr><th></th>';
                  for (i=1; i <= lineups.length; ++i) {
                    divHtml += '<th>#' + i + '</th>';
                  }
                  divHtml += '</tr></thead><tbody>';
                  var positions = ['PG','PG','SG','SG','SF','SF','PF','PF','C'];
                  for (i=0; i < positions.length; ++i) {
                    divHtml += '<tr>';
                    divHtml += '<th>'+positions[i]+'</th>';
                    for (j=0; j < lineups.length; ++j) {
                      divHtml += '<td>' + lineups[j]['players'][i] + '</td>';
                    }
                    divHtml += '</tr>';
                  }
                  divHtml += '<tr><td></td>';
                  for (i=0; i < lineups.length; ++i) {
                    divHtml += '<td class="success"><b>' + Math.floor(lineups[i].points);
                    divHtml += '</b> ($' + lineups[i].salary + ')</td>';
                  }
                  divHtml += '</tr></tbody></table>';
                  console.log(divHtml);
                  $("#my_popup").html(divHtml);
                }
              });
            });
          },
          onclose: function() {
            $('#my_popup').html(getLoadingHtml());
          }
        }); // popup
      }); // document ready

      $(window).load(function() {
        $("#players").DataTable().draw();
      });

    </script>    
  </body>
</html>

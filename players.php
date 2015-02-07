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

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <style type='text/css'>
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
    <?php $page='players'; include('navbar.php'); ?>

    <div class="container">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#PG" data-toggle="tab">PG</a></li>
        <li>               <a href="#SG" data-toggle="tab">SG</a></li>
        <li>               <a href="#SF" data-toggle="tab">SF</a></li>
        <li>               <a href="#PF" data-toggle="tab">PF</a></li>
        <li>               <a href="#C"  data-toggle="tab">C</a></li>
      </ul>

      <div class="tab-content">
        <?php
        $json_string = file_get_contents('data/players.json');
        $players = json_decode($json_string, true);

        $positions = ["PG","SG","SF","PF","C"];

        foreach ($positions as $pos) {
          if ($pos == "PG") {
            echo "<div class=\"tab-pane active\" id=\"$pos\">\n";
          }
          else {
            echo "<div class=\"tab-pane\" id=\"$pos\">\n";
          }
          echo "  <div class=\"table-responsive\"><br>\n";
          echo "    <table class=\"table table-condensed\">\n";
          echo "      <thead>\n";
          echo "        <tr>\n";
          echo "          <th></th>\n";
          echo "          <th></th>\n";
          echo "          <th></th>\n";
          echo "          <th></th>\n"; 
          echo "          <th colspan=3 style=\"text-align:center;\">Points</th>\n";
          echo "          <th colspan=2 style=\"text-align:center;\">Minutes</th>\n";
          echo "          <th></th>\n";
          echo "          <th></th>\n";
          echo "          <th></th>\n";
          echo "        </tr>\n";
          echo "        <tr>\n";
          echo "          <th>#</th>\n";
          echo "          <th>Name</th>\n";
          echo "          <th>Value</th>\n";
          echo "          <th>Price</th>\n"; 
          echo "          <th>Proj</th>\n";
          echo "          <th>Last5</th>\n";
          echo "          <th>Ceil</th>\n";          
          echo "          <th>Proj</th>\n";
          echo "          <th>Last5</th>\n";
          echo "          <th>Opponent</th>\n";
          echo "          <th>Spread</th>\n";
          echo "          <th>Days Off</th>\n";
          echo "        </tr>\n";

          echo "      </thead>\n";
          echo "      <tbody>\n";
          $index=1;
          foreach ($players as $player) {
            if ($player["pos"] != $pos) continue;
            $class = "";
            if (array_key_exists("exclude", $player) && $player["exclude"]) {
              $class = 'class="exclude"';   
            }
            else if (array_key_exists("filter", $player) && $player["filter"]) {
              $class = 'class="filter"';
            }
            echo "      <tr $class>\n";
            echo "        <td>$index</td>\n";
            $name=$player["name"];
            if ($player["status"] == 'p')
              $name.=" <sup class=\"negative\">P</sup>";
            else if ($player["status"] == 'q')
              $name.=" <sup class=\"negative\">Q</sup>";

            $salary='$'.$player["salary"];
            $value=intval($player["value"]);

            $points=intval($player["points"]);
            $points_avg=intval($player["points_avg"]);
            $points_ceiling=intval($player["points_ceiling"]);
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

            echo "        <td>$name</td>\n";            
            echo "        <td>$value</td>\n";
            echo "        <td>$salary</td>\n";
            echo "        <td>$points $points_arrow</td>\n";
            echo "        <td>$points_avg</td>\n";
            echo "        <td>$points_ceiling</td>\n";            
            echo "        <td>$mins $mins_arrow</td>\n";
            echo "        <td>$mins_avg</td>\n";
            echo "        <td>$opp <i>(<span class=\"$opp_class\">$opp_adj</span>)</i></td>\n";
            echo "        <td><span class=\"$spread_class\">$spread</span></td>\n";
            echo "        <td><span class=\"$rest_class\">$rest</span></td>\n";
            echo "      </tr>\n";
            $index++;
          }
          echo "      </tbody>\n";
          echo "    </table>\n";
          echo "  </div>\n";
          echo "</div>\n";
        }
        ?>
      </div>


<!--       <div class="footer">
        <p>&copy; dmildagreat 2013</p>
      </div>
 -->
    </div> <!-- container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type='text/javascript' src='//code.jquery.com/jquery-1.9.1.js'></script>
    <script type='text/javascript' src="js/bootstrap.min.js"></script>
  </body>
</html>

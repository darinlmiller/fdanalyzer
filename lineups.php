<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>FD Analyzer</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
  </head>

  <body>
    <?php $page='lineups'; include('navbar.php'); ?>

    <div class="container">

    <p style="font-size: 25px">Expected</p>
    <div class="table-responsive">
      <table class="table table-condensed table-striped">
        <thead>
          <tr>
            <th></th>
            <th>#1</th>
            <th>#2</th>
            <th>#3</th>
            <th>#4</th>
            <th>#5</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $json_string = file_get_contents('data/lineups.json');
        $lineups = json_decode($json_string, true);

        $json_string = file_get_contents('data/players.json');
        $players = json_decode($json_string, true);

        $positions = ['PG','PG','SG','SG','SF','SF','PF','PF','C'];
        foreach (range(0,8) as $pos_index) {
          echo "<tr>";
          echo "  <th>".$positions[$pos_index]."</th>\n";
          foreach (range(0,4) as $lineup_index) {
            $name=$lineups[$lineup_index]["players"][$pos_index];
            if ($players[$name]["status"] == 'p')
              $name.=" <font color=\"red\"><sup>P</sup></font>";
            else if ($players[$name]["status"] == 'q')
              $name.=" <font color=\"red\"><sup>Q</sup></font>";
            echo "  <td>".$name."</td>\n";
          }
          echo "</tr>\n";
        }

        echo "<tr>\n";
        echo "  <td></td>\n";
        foreach (range(0,4) as $index) {
          $points = intval($lineups[$index]["points"]);
          $salary = "$".$lineups[$index]["salary"];
          echo "  <td class=\"success\"><b>$points</b> ($salary)</td>\n";
        }
        echo "</tr>\n";


        ?>
        </tbody>
      </table>
    </div>

    <hr>
    <p style="font-size: 25px">Ceiling <font style="color:red; font-size: 18px;"><sup>BETA</sup></font></p>
    <div class="table-responsive">
      <table class="table table-condensed table-striped">
        <thead>
          <tr>
            <th></th>
            <th>#1</th>
            <th>#2</th>
            <th>#3</th>
            <th>#4</th>
            <th>#5</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $json_string = file_get_contents('data/lineups_ceiling.json');
        $lineups = json_decode($json_string, true);

        $json_string = file_get_contents('data/players.json');
        $players = json_decode($json_string, true);

        $positions = ['PG','PG','SG','SG','SF','SF','PF','PF','C'];
        foreach (range(0,8) as $pos_index) {
          echo "<tr>";
          echo "  <th>".$positions[$pos_index]."</th>\n";
          foreach (range(0,4) as $lineup_index) {
            $name=$lineups[$lineup_index]["players"][$pos_index];
            if ($players[$name]["status"] == 'p')
              $name.=" <font color=\"red\"><sup>P</sup></font>";
            else if ($players[$name]["status"] == 'q')
              $name.=" <font color=\"red\"><sup>Q</sup></font>";
            echo "  <td>".$name."</td>\n";
          }
          echo "</tr>\n";
        }

        echo "<tr>\n";
        echo "  <td></td>\n";
        foreach (range(0,4) as $index) {
          $points = intval($lineups[$index]["points"]);
          $salary = "$".$lineups[$index]["salary"];
          echo "  <td class=\"success\"><b>$points</b> ($salary)</td>\n";
        }
        echo "</tr>\n";

        
        ?>
        </tbody>
      </table>
    </div>

    <div class="container">

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type='text/javascript' src='//code.jquery.com/jquery-1.9.1.js'></script>
    <script type='text/javascript' src="js/bootstrap.min.js"></script>    
  </body>
</html>

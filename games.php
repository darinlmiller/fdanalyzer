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
      .glyphicon-remove {
        color: gray;
      }
      .glyphicon-remove:hover {
        color: gray;
      }
      .excluded {
        color: red;
      }
      .excluded:hover {
        color: red;
      }      
    </style>

    <script>
      function excludeGame(element, game) {
        $(element).toggleClass('excluded');

        var input = {};
        input.updateType = 'excludeGame';
        input.game = game;

        $.ajax({
          type:'Get',
          url:'cgi-bin/updater.cgi',
          data:input,
          timeout:180000,
          success:function(result){  
          }
        })

      }
    </script>
  </head>
    <?php $page='games'; include('navbar.php'); ?>
    
    <div class="container">
    <div class="table-responsive">
      <table class="table table-condensed table-striped table-hover">
        <thead>
          <tr>
            <th>Time (ET)</th>
            <th>Away Team</th>
            <th>Home Team</th>
            <th>Spread</th>
            <th>Total</th>
            <th>Exclude</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $json_string = file_get_contents('data/games.json');
            $games = json_decode($json_string, true);
            $date = array_keys($games)[0];
            $games = $games[$date];

            foreach ($games as $game) {
              echo "<tr>";
              echo "<td>".$game["time"]."</td>";
              $away=$game["away"];
              echo "<td>".$away."</td>";
              $home=$game["home"];
              echo "<td>".$home."</td>";
              echo "<td>".$game["spread"]."</td>";
              echo "<td>".$game["total"]."</td>"; 
              $excluded = (array_key_exists('exclude', $game) && $game["exclude"]) ? " excluded" : "";
              $remove_icon="<a style=\"cursor: pointer;\" class=\"glyphicon glyphicon-remove".$excluded."\" onclick=\"excludeGame(this,'".$away.$home."')\"></a>";
              echo "<td>".$remove_icon."</td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type='text/javascript' src='//code.jquery.com/jquery-1.9.1.js'></script>
    <script type='text/javascript' src="js/bootstrap.min.js"></script>
  </body>
</html>

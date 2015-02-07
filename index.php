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

    <!-- Bootstrap core -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!-- For the spinner -->
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css">
    <style>
     .spinner {
      display: inline-block;
      opacity: 0;
      max-width: 0;
      -webkit-transition: opacity 0.25s, max-width 0.45s; 
      -moz-transition: opacity 0.25s, max-width 0.45s;
      -o-transition: opacity 0.25s, max-width 0.45s;
      transition: opacity 0.25s, max-width 0.45s; /* Duration fixed since we animate additional hidden width */
    }

    .has-spinner.active {
      cursor:progress;
    }

    .has-spinner.active .spinner {
      opacity: 1;
      max-width: 50px; /* More than it will ever come, notice that this affects on animation duration */
    }

    .header {
      font-size: 25px;
    }

    </style>

    <script type="text/javascript">
      function updateData(element, updateType) {
        $(element).toggleClass('active');

        var input = {};
        input.updateType = updateType;

        $.ajax({
          type:'Get',
          url:'cgi-bin/updater.cgi',
          data:input,
          timeout:180000,
          success:function(result){  
            $(element).toggleClass('active');
            if (result == 1) {
              $("#"+updateType).text("Just now");
            }
            else {
              $("#"+updateType).text(result).css('color','red');
            }
          }
        })
      }

      function updateMatchups() {
        $.ajax({
          type:'Get',
          dataType:'json',
          url: "data/weak_matchups.json",
          success: function (matchups) {
            var text = "";
            for (i = 0; i < matchups.length; ++i) {
              text += "<a href=\"" + matchups[i].url + "\" target=\"_blank\">";
              text += "H2H vs. " + matchups[i].username;
              text += " (" + matchups[i].wins + " wins)";
              text += " for " + matchups[i].entry_fee;
              text += "</a>";
              text += "<br>";
            }
            if (text) $("#matchups").html(text);
          }
        });
      }

      function findMatchups(element) {
        $(element).toggleClass('active');

        var intervalId = window.setInterval(function(){ updateMatchups() }, 250)

        $.ajax({
          type:'Get',
          url:'cgi-bin/find_matchups.cgi',
          complete:function(result){  
            window.clearInterval(intervalId);
            $(element).toggleClass('active');
          }
        })
      }

    </script>
  </head>

  <body>
    <?php $page='index'; include('navbar.php'); ?>

    <?php
      $current_time = time();

      $tokens = [
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
      ];

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
    ?>

    <div class="container">
      </p>

      <p class="header">Players & Lineups
        <a class="btn btn-success has-spinner" onclick="updateData(this,'playersAndLineups')">
          <span class="spinner"><i class="icon-spin icon-refresh"></i></span> Update
        </a>
      </p>
      <p>Last Updated:
        <i id="playersAndLineups">
        <?php       
          $filename = "data/lineups.json";
          $mtime = filemtime($filename);
          $time_diff = $current_time - $mtime;
          echo time_in_text($time_diff);
        ?> 
        </i>
      </p>

      <hr>

    </div> <!-- container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type='text/javascript' src="js/bootstrap.min.js"></script>    
  </body>
</html>

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
	
    <!-- On/off toggle switch -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.1/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">

	<style>
      .header { font-size: 25px; }
      .hide { position:absolute; top:-1px; left:-1px; width:1px; height:1px; }
    </style>
  </head>

  <body>
    <?php $page='settings'; include('navbar.php'); ?>

    <?php
      $json_string = file_get_contents('data/settings.json');
      $settings = json_decode($json_string, true);
      $min_mins = $settings["min_minutes_required"];
      $min_pts = $settings["min_points_required"];
      $max_per_team = $settings["max_players_per_team"];
      $max_per_game = $settings["max_players_per_game"];
      $nf_enable = $settings["noob_finder_enable"];
    ?>

    <div class="container">
      <form class="form-horizontal" role="form" action="cgi-bin/updater.cgi" method="GET" target="hiddenFrame">
        <p class="header">Players</p>
        <div class="form-group">
          <label class="col-sm-3 control-label" for="formGroupInputSmall">Minutes Required</label>
          <div class="col-sm-2">
            <input class="form-control" type="number" min="15" max="35" name="minMinsRequired" value=<?php echo $min_mins; ?>>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label" for="formGroupInputSmall">Points Required</label>
          <div class="col-sm-2">
            <input class="form-control" type="number" min="10" max="30" name="minPtsRequired" value=<?php echo $min_pts; ?>>
          </div>
        </div>
        <hr>
        <p class="header">Lineups</p>
        <div class="form-group">
          <label class="col-sm-3 control-label" for="formGroupInputSmall">Max Players Per Team</label>
          <div class="col-sm-2">
            <select class="form-control" name="maxPlayersPerTeam">
              <option <?php if ($max_per_team == 1) echo "selected"; ?>>1</option>
              <option <?php if ($max_per_team == 2) echo "selected"; ?>>2</option>
              <option <?php if ($max_per_team == 3) echo "selected"; ?>>3</option>
              <option <?php if ($max_per_team == 4) echo "selected"; ?>>4</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label" for="formGroupInputSmall">Max Players Per Game</label>
          <div class="col-sm-2">
            <select class="form-control" name="maxPlayersPerGame">
              <option <?php if ($max_per_game == 2) echo "selected"; ?>>2</option>
              <option <?php if ($max_per_game == 3) echo "selected"; ?>>3</option>
              <option <?php if ($max_per_game == 4) echo "selected"; ?>>4</option>
              <option <?php if ($max_per_game == 5) echo "selected"; ?>>5</option>
              <option <?php if ($max_per_game == 6) echo "selected"; ?>>6</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label" for="formGroupInputSmall">Salary</label>
          <div class="col-sm-3">
            <label class="radio-inline">
              <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="50000" disabled>$50,000
            </label>
            <label class="radio-inline">
              <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="60000" checked disabled>$60,000
            </label>
          </div>
        </div>
        <hr>
        <p class="header">Noob Finder</p>		  		  
	      <div class="form-group">
          <label class="col-sm-3 control-label" for="formGroupInputSmall">Enable</label>
          <div class="col-sm-3">
		      <input type="checkbox" data-on-color="info" id="nfEnableCB" name="nfEnable" value="1" <?php if ($nf_enable) echo "checked"; ?>>
		      </div>
        </div>
        <hr>
        <input type="hidden" name="updateType" value="updateSettings">
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-10">
            <button type="submit" class="btn btn-success" value="Submit">Save Changes</button>
          </div>
        </div>
      </form>
    </div>
    <iframe name="hiddenFrame" class="hide"></iframe>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type='text/javascript' src='//code.jquery.com/jquery-1.9.1.js'></script>
    <script type='text/javascript' src="js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.1/js/bootstrap-switch.min.js"></script>	  
    <script type="text/javascript">
      $("#nfEnableCB").bootstrapSwitch();
	</script>	
	  
  </body>
</html>

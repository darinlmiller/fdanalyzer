<div class="navbar navbar-default" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><img style="max-width: 180px; margin-top: -7px;" src="fd_logo.png"></a>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li <?php if ($page=='players') echo "class=\"active\"" ?> ><a href="players.php">Players</a></li>
        <li <?php if ($page=='lineups') echo "class=\"active\"" ?> ><a href="lineups.php">Lineups</a></li>
        <li <?php if ($page=='lineup_builder') echo "class=\"active\"" ?> ><a href="lineup_builder.php">Lineup Builder</a></li>
        <li <?php if ($page=='games') echo "class=\"active\"" ?> ><a href="games.php">Games</a></li>
        <li <?php if ($page=='settings') echo "class=\"active\"" ?> ><a href="settings.php">Settings</a></li>
        <li <?php if ($page=='research') echo "class=\"active\"" ?> ><a href="research.php">Research</a></li>
      </ul>
    </div>
  </div>
</div>

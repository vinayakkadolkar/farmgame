<?php

require_once "Game/Game.php";
require_once "Game/Loader.php";


$playing=false;
$loader;
$game;
if(isset($_GET['playing']) && $_GET['playing']==1){
    $playing=true;

    $game = new Game();

    $saveData=$game->read_save_file();
    if(!$saveData){
        $loader=new Loader();
        $new_game=$loader->create_new_game();

    }
}




?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
</head>

<body>
  <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="#">
          <img alt="FarmGame" src="img">
        </a>
      </div>
    </div>
  </nav>
  <!-- Add your site or application content here -->
  <div class="container-fluid">
    <h2>Farm Game</h2>
    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="
        <?php echo ($turns/$maxTurns)*100;?>
        " aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
        <?php echo $turns; ?>
      </div>
    </div>
    <div class="row">      
        <div class="col-xs-12 col-sm-6 col-md-10">
          <div class="col-xs-4 col-sm-2 col-md-4">
              <div class="panel panel-default">
                  <div class="panel-body">
                      <h4>Farmer</h4>
                  </div>
              </div>
          </div>
          <div class="col-xs-4 col-sm-2 col-md-4">
              <div class="panel panel-default">
                  <div class="panel-body">
                    <h4>Cow</h4>
                    
                  </div>
              </div>
          </div>
          <div class="col-xs-4 col-sm-2 col-md-4">
              <div class="panel panel-default">
                  <div class="panel-body">
                    <h4>Bunny</h4>
                    
                  </div>
              </div>
          </div>  

        </div>
        <div class="col-xs-3 col-md-2">
            <?php if($playing){ ?>
            <button type="button" class="btn btn-default btn-lg">
              <span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> End Turn
            </button>
            <?php }else{ ?>

            <a href="index.php?playing=1" class="btn btn-default btn-lg">
              <span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Start Game
            </a>  
            <?php } ?>  

            
          </div>
    </div>

  </div>

  <script src="js/vendor/modernizr-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <script src="js/plugins.js"></script>
  <script src="js/main.js"></script>

  <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
  <script>
    window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
    ga('create', 'UA-XXXXX-Y', 'auto'); ga('send', 'pageview')
  </script>
</body>

</html>
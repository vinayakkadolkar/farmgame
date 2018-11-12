<?php

require_once "Game/Game.php";
require_once "Game/Loader.php";


$playing=false;
$loader;
$game = new Game();
$game_data=array();
$result_data=array();
$game_end=false;
$end_message='';
$win=false;

if(isset($_GET['playing']) && $_GET['playing']==1){
    $playing=true;
    $saveData=$game->read_save_file();
    // Check if Game is running
    if(!$saveData){
        //Start new Game, Load New values to start
        if(isset($_GET['turn']) && $_GET['turn']==1){
          $game->redirect($_SERVER['PHP_SELF']);
        }
        $loader=new Loader();
        $new_game=$loader->create_new_game();
        $game_data=$game->read_save_file();
    }else{
        //Section for Running Game
        $game_data=$game->read_save_file();
        if(isset($_GET['turn']) && $_GET['turn']==1){
            if($game_data->max_turns<=$game_data->turn){
                $game_end = true;
                $game_data->end=true;
                $win=true;
                $end_message="Congratulations you won the game";
            }
            $living_characters=$game->get_live_characters($game_data);
            if(count($living_characters)<2){
                $game_end = true;
                $game_data->end=true;
                $end_message="Only one character left in Game resulted in your loss";
            }
            //echo "<pre>"; print_r($living_characters);exit;
            $feed_to = array_rand($living_characters);
            //echo $feed_to;exit;
            //Update Json Data
            $game_data->turn+=1;
             
            foreach ($game_data->characters as $gckey => $gcvalue) {
                //echo $gckey;
                if($feed_to==$gckey){
                    $game_data->characters->{$gckey}->tolarated=0;
                    //echo "Eating ".$gckey;
                }else{
                    $game_data->characters->{$gckey}->tolarated+=1;
                }
                if($game_data->characters->{$gckey}->tolarated==$game_data->characters->{$gckey}->tolarance)
                {
                    $game_data->characters->{$gckey}->dead=true;
                    $game_data->characters->{$gckey}->death_turn=$game_data->turn;
                    $game_data->characters->{$gckey}->status = "dead";
                    if($game_data->characters->{$gckey}->game_end_on_death){
                        $game_end = true;
                        $game_data->end=true;
                        $end_message="Death of ".$game_data->characters->{$gckey}->name." caused loss in Game";
                    }
                }else{
                    $char_status=(($game_data->characters->{$gckey}->tolarance-$game_data->characters->{$gckey}->tolarated)/$game_data->characters->{$gckey}->tolarance)*100;
                    $game_data->characters->{$gckey}->status=($char_status==100)?"Just ate":"Fresh";
                    $game_data->characters->{$gckey}->status=($char_status<50 && $char_status!=100)?"Hungry":$game_data->characters->{$gckey}->status;
                    $game_data->characters->{$gckey}->status=(($game_data->characters->{$gckey}->tolarance-$game_data->characters->{$gckey}->tolarated)<=0)?"Dead":$game_data->characters->{$gckey}->status;
                    
                }

            }
            
            if($game_data->end)
            {
                $result_data=$game_data;
                $game_data=array();
                $game->clean_save();
            }else{
                $game->write_save_file($game_data);
            }
        }
        //echo "<pre>"; print_r($game_data);exit;
    }
}else{
    $game->clean_save();
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
    <?php
    if($game_end){
      $result=($win)?"alert-success":"alert-warning";
      ?>
      <div class="alert <?php echo $result;?>" role="alert">
      <?php echo $end_message;?>
      </div>
      <?php
    }
    
    ?>
    <?php 
        if(isset($game_data) && !empty($game_data))
        {
    ?>
    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="
        <?php echo ($game_data->turn/$game_data->max_turns)*100;?>
        " aria-valuemin="0" aria-valuemax="100" style="width: 
        <?php echo ($game_data->turn/$game_data->max_turns)*100;?>%;">
        <?php echo $game_data->turn; ?>
      </div>
    </div>
    <?php 
        }
    ?> 
    <div class="row">      
        <div class="col-xs-12 col-sm-6 col-md-10">
          <?php 
          if(isset($game_data) && !empty($game_data))
          {
          ?>
          <div class="col-xs-4 col-sm-2 col-md-4">
          <?php 
            foreach ($game_data->characters as $ckey => $character) {
                if($character->type=="farmer")
                {
                    $life=(($character->tolarance-$character->tolarated) / $character->tolarance )*100;
                    $status=($life<30)? "progress-bar-danger": ($life<50)?"progress-bar-warning" : "progress-bar-success";
          ?>
            
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4><?php echo ucfirst($character->name) ?></h4>
                        <div class="progress">
                        <div class="progress-bar <?php echo $status; ?>" role="progressbar" aria-valuenow="
                        <?php 
                            echo $life;
                        ?>
                        " aria-valuemin="0" 
                        aria-valuemax="100" style="width: 
                          <?php 
                            echo $life."%";
                          ?>
                          ">
                          <span class="sr-only"><?php echo $character->tolarance-$character->tolarated?></span>
                        </div>
                      </div>
                      <h5><?php echo $character->status;?></h5>
                    </div>
                    
                </div>
          
          <?php 
              }
            }
          ?> 
          </div> 
          <div class="col-xs-4 col-sm-2 col-md-4">
          <?php 
          foreach ($game_data->characters as $ckey => $character) {
              if($character->type=="cow")
              {
                  $life=(($character->tolarance-$character->tolarated) / $character->tolarance )*100;
                  $status=($life<30)? "progress-bar-danger": ($life<50)?"progress-bar-warning" : "progress-bar-success";
          ?>
          
              <div class="panel panel-default">
                  <div class="panel-body">
                      <h4><?php echo ucfirst($character->name) ?></h4>
                      <div class="progress">
                      <div class="progress-bar <?php echo $status; ?>" role="progressbar" aria-valuenow="
                      <?php 
                          echo $life;
                      ?>
                      " aria-valuemin="0" 
                      aria-valuemax="100" style="width: 
                        <?php 
                          echo $life."%";
                        ?>
                        ">
                        <span class="sr-only"><?php echo $character->tolarance-$character->tolarated?></span>
                      </div>
                    </div>
                    <h5><?php echo $character->status;?></h5>
                  </div>
                  
              </div>
          
          <?php 
              }
            }
          ?> 
          </div> 
          <div class="col-xs-4 col-sm-2 col-md-4">
          <?php 
            foreach ($game_data->characters as $ckey => $character) {
                if($character->type=="bunny")
                {
                    $life=(($character->tolarance-$character->tolarated) / $character->tolarance )*100;
                    $status=($life<=20)? "progress-bar-danger": ($life<50)?"progress-bar-warning" : "progress-bar-success";
          ?>
            
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4><?php echo ucfirst($character->name) ?></h4>
                        <div class="progress">
                        <div class="progress-bar <?php echo $status; ?>" role="progressbar" aria-valuenow="
                        <?php 
                            echo $life;
                        ?>
                        " aria-valuemin="0" 
                        aria-valuemax="100" style="width: 
                          <?php 
                            echo $life."%";
                          ?>
                          ">
                          <span class="sr-only"><?php echo $character->tolarance-$character->tolarated?></span>
                        </div>
                      </div>
                      <h5><?php echo $character->status;?></h5>
                    </div>
                    
                </div>
          
          <?php 
              }
            }
          ?> 
          </div>  

        </div>
        <?php
        }
        ?>
        <div class="col-xs-3 col-md-2">
            <?php if($playing && !$game_end){ ?>
            <a href="index.php?playing=1&turn=1" class="btn btn-default btn-lg">
              <span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Next Turn
            </a>
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

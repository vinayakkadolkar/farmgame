<?php
//namespace Game;

require "Loader.php";

/**
 * Class Game
 * 
 * The class is used to mainly save game progress of the game and 
 * create necessory data to continue playing
 * 
 *
 */
class Game    
{
    
    var $game_loader;
    /**
     * variable $error_message to store errors 
     */
    public $error_message=false;

    public function __construct()
    {   
        //Initiate Loader
        $this->game_loader=new Loader();
    }

    /**
	  * Function read_save_file
      * function used to read save file
      *
      * Funtion will get contents of json save  file and returns the object 
	  *
	  * @package farmgame
      * @author Vinayak
      * @return object $save_data
	  *
	  */
    public function read_save_file()
    {
        $save_data=array();
        try{
            $save_data=json_decode(file_get_contents(dirname(dirname(__FILE__)).$this->game_loader->save_file));
        }catch(Exception $e){
            $this->error_message="failed to Get saved Game Configurations";
            return false;
        }
        return $save_data;        
    }

    /**
	  * Function write_save_file
      * function used to write save file
      *
      * Funtion will put contents into json save file 
	  *
	  * @package farmgame
      * @author Vinayak
      * @return object $save_data
	  *
	  */
    public function write_save_file($data)
    {
        try{
            file_put_contents(dirname(dirname(__FILE__)).$this->game_loader->save_file,json_encode($data));
        }catch(Exception $e){
            $this->error_message="Failed to save the Game";
            return false;
        }
    }

    /**
	  * Function redirect
      * function used redirect the request
      *
      * Funtion will redirect to specified url 
	  *
	  * @package farmgame
      * @author Vinayak
      * @param string $url
	  *
	  */
    public function redirect($url) {
        ob_start();
        header('Location: '.$url);
        ob_end_flush();
        die();
    }

    /**
	  * Function clean_save
      * function used to clean the save file
      *
      * Funtion will clear the contents of the save file resulting in request
      * to start new game 
	  *
	  * @package farmgame
      * @author Vinayak
      * @return boolean
	  *
	  */
    public function clean_save()
    {
        try{
            file_put_contents(dirname(dirname(__FILE__)).$this->game_loader->save_file,"");
        }catch(Exception $e){
            $this->error_message="Failed to clean the Game";
            return false;
        }
    }

    /**
	  * Function get_live_characters
      * function used to find list of chracters that are alive 
      *
      * Funtion will return all characters codes if they are alive 
	  *
	  * @package farmgame
      * @author Vinayak
      * @param object $data //all charcaters list from save file
      * @return array $codes
	  *
	  */
    public function get_live_characters($data)
    {
        $codes=array();
        foreach ($data->characters as $key => $value) {
            if(!$value->dead){
                $codes[$key]=$value->type;
            }
        }
        return $codes;
    }

}
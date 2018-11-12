<?php
//namespace Game;

require "Loader.php";

/**
 * Class Loader
 * 
 * The class is used to mainly load default settings before starting the gane and 
 * create necessory data to continue using
 * 
 *
 */
class Game    
{
    
    var $game_loader;

    public function __construct()
    {
        $this->game_loader=new Loader();
    }


    public function update_turn_data($data)
    {
        # code...
    }

    public function read_saved_data()
    {
        
    }

    public function read_save_file()
    {
        $save_data=array();
        try{
            //echo __DIR__ .$this->game_loader->save_file;
            $save_data=json_decode(file_get_contents(dirname(dirname(__FILE__)).$this->game_loader->save_file));
        }catch(Exception $e){
            $this->error_message[]="failed to Get saved Game Configurations";
            return false;
        }
        return $save_data;        
    }

    public function write_save_file($data)
    {
        try{
            file_put_contents(dirname(dirname(__FILE__)).$this->game_loader->save_file,json_encode($data));
        }catch(Exception $e){
            $this->error_message[]="Failed to save the Game";
            return false;
        }
    }

    public function redirect($url) {
        ob_start();
        header('Location: '.$url);
        ob_end_flush();
        die();
    }

    public function clean_save()
    {
        try{
            file_put_contents(dirname(dirname(__FILE__)).$this->game_loader->save_file,"");
        }catch(Exception $e){
            $this->error_message[]="Failed to clean the Game";
            return false;
        }
    }

    public function get_live_characters($data)
    {
        $codes=array();
        foreach ($data->characters as $key => $value) {
            if(!$value->dead){
                $codes[$key]=$key;
            }
        }
        //echo "<pre>"; print_r($codes);exit;
        return $codes;
    }

}
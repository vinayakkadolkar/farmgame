<?php
//namespace Game;

/**
 * Class Loader
 * 
 * The class is used to mainly load default settings before starting the gane and 
 * create necessory data to continue using
 * 
 *
 */
class Loader    
{   
    
    public $default_configs_file = "/settings/fgconfigs.json";
    public $save_file="/settings/fgsave.json";
    public $error_message=false ;
    
    /**
	  * Function load_defaults
	  * function used to load default values when we start new game
	  *
	  * @package farmgame
	  * @author Vinayak
	  * @return object $defaults
	  *
	  */
    private function load_defaults()
    {
        $defaults=array();
        try{
            $defaults=json_decode(file_get_contents(dirname(dirname(__FILE__)).$this->default_configs_file));
        }catch(Exception $e){
            $this->error_message="failed to Load default Game Configurations";
            return false;
        }
        return $defaults;
    }


    /**
	  * Function create_new_game
      * function used to create and load new game 
      *
      * Funtion will not take any argument and create new save file to start a game. 
	  *
	  * @package farmgame
	  * @author Vinayak
	  * @return boolean 
	  *
	  */
    public function create_new_game()
    {
        $gameSettings=array();
        $defaults=$this->load_defaults();
        $gameSettings['turn']=0;
        $gameSettings['turn_cost']=$defaults->turn_cost;
        $gameSettings['max_turns']=$defaults->max_turns;
        $gameSettings['end']=false;
        $charactersList=$this->create_characters($defaults->characters_list);
        if(!$charactersList){
            $this->error_message[]="Failed to Load Characters in Game";
            return false;
        }
        $gameSettings['characters']=$charactersList;
        try{
            file_put_contents(dirname(dirname(__FILE__)).$this->save_file,json_encode($gameSettings));
        }catch(Exception $e){
            $this->error_message="Failed to save the Game";
            return false;
        }

      
    }

    /**
	  * Function create_characters
      * function used to create all characters in the game 
      *
      * Funtion will get raw list of characters and result into simple list of objects
	  *
	  * @package farmgame
      * @author Vinayak
      * @param object $character_raw_list
	  * @return array $characters
	  *
	  */
    private function create_characters($character_raw_list)
    {   
        if(empty($character_raw_list)){
            return false;
        }
        $characters=array();
        foreach ($character_raw_list as $char_code  => $character) 
        {   
           
            $numbers=1;
            for ($numbers=1; $numbers <= $character->numbers; $numbers++) 
            { 
                $characters[$char_code."_".$numbers]=array(
                    'code'=>$char_code."_".$numbers,
                    'type'=>$char_code,
                    'name'=>$character->name." ".$numbers,
                    'tolarance'=>$character->tolarance,
                    'tolarated'=>0,
                    'game_end_on_death'=>$character->game_end_on_death,
                    'dead'=>false,
                    'death_turn'=>0,
                    'status'=>"idle"
                );
            }
        }
        return $characters;
    }
}



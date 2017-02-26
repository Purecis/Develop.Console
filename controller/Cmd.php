<?php
namespace App\Develop\Console\Controller;

use App\System\Controller;

class Cmd extends Controller{

    public static $commands = [];
    public static $options = [];
    
    public static function argv($idx){
        self::parse();
        return isset(self::$commands[$idx]) ? self::$commands[$idx] : false;
    }

    public static function onOption(){
        $opts = func_get_args();
        self::parse();
        foreach($opts as $opt){
            if(in_array($opt, self::$options)){
                return true;
            }
        }
        return false;
    }

    public static function allOption(){
        $opts = func_get_args();
        self::parse();
        foreach($opts as $opt){
            if(!isset(self::$options[$opt])){
                return false;
            }
        }
        return true;
    }

    private static function parse(){

        $options = [];
        $argv = $_SERVER['argv'];
        foreach($argv as $idx => $command){
            if(strpos($command, "-") === 0){
                array_push($options, $command);
                unset($argv[$idx]);
            }
        }

        self::$commands = array_values($argv);
        self::$options = $options;
    }

}
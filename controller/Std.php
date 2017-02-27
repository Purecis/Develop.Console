<?php
namespace App\Develop\Console\Controller;

use App\System\Controller;
use \App\System\Scope;

class Std extends Controller{
    public static $brand = "
       ██████╗ ██████╗ ██████╗ ███████╗██╗  ██╗██╗██╗   ██╗███████╗
      ██╔════╝██╔═══██╗██╔══██╗██╔════╝██║  ██║██║██║   ██║██╔════╝
      ██║     ██║   ██║██║  ██║█████╗  ███████║██║██║   ██║█████╗
      ██║     ██║   ██║██║  ██║██╔══╝  ██╔══██║██║╚██╗ ██╔╝██╔══╝
      ╚██████╗╚██████╔╝██████╔╝███████╗██║  ██║██║ ╚████╔╝ ███████╗
       ╚═════╝ ╚═════╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝  ╚═══╝  ╚══════╝";
    
    public static function header(){
        // self::br(50);
        $version = new Scope('config.version');
        self::clear();
        self::gray(self::$brand);
        self::br(2);
        self::green("  CodeHive Framework v" . $version->major . "." . $version->minor . " [" . $version->patch . "] " . $version->code);
        self::br(3);
        // for($i=0; $i<100; $i++)echo "\033[{$i}m" . $i . "\033[0m";
    }
    
    public static function output($txt){
        return print $txt;
    }
    
    public static function gray($txt){
        return print "\033[97m" . $txt . "\033[0m";
    }
    
    public static function green($txt){
        return print "\033[32m" . $txt . "\033[0m";
    }
    
    public static function blue($txt){
        return print "\033[34m" . $txt . "\033[0m";
    }
    
    public static function red($txt){
        return print "\033[31m" . $txt . "\033[0m";
    }
    
    public static function blink($txt){
        return print "\e[5m" . $txt . "\033[0m";
    }
    
    public static function light($txt){
        return print "\e[47m" . $txt . "\033[0m";
    }
    
    public static function highlight($txt){
        return print "\e[7m" . $txt . "\033[0m";
    }
    
    public static function bold($txt){
        return print "\e[1m" . $txt . "\033[0m";
    }

    public static function br($times = 1){
        return print str_repeat("\n", $times);
    }

    public static function space($times = 1){
        return print str_repeat(" ", $times);
    }

    public static function tab($times = 1){
        return print str_repeat("\t", $times);
    }

    public static function clearLine(){
        return print "\r";
    }

    public static function clear(){
        return print "\033c";
    }

    public static function listing($list){
        $prefix = 2;
        $tabSize = 8;
        $tabs = 4;
        foreach($list as $title => $commands){
            Std::br();
            Std::green($title);
            Std::br();
            foreach($commands as $command => $description){
                if(is_numeric($command)){
                    Std::space($prefix);

                }else{
                    Std::space($prefix);
                    Std::green($command);
                    Std::tab($tabs - intval((strlen($command) + $prefix) / $tabSize));
                }
                $description = preg_replace('/\s\s+|\n+/m', " ", $description);
                Std::output($description);
                Std::br();
            }
        }
    }

}
<?php
namespace App\Develop;
use \App\System\Scope;

class Line{
    public static $brand = "
       ██████╗ ██████╗ ██████╗ ███████╗██╗  ██╗██╗██╗   ██╗███████╗
      ██╔════╝██╔═══██╗██╔══██╗██╔════╝██║  ██║██║██║   ██║██╔════╝
      ██║     ██║   ██║██║  ██║█████╗  ███████║██║██║   ██║█████╗
      ██║     ██║   ██║██║  ██║██╔══╝  ██╔══██║██║╚██╗ ██╔╝██╔══╝
      ╚██████╗╚██████╔╝██████╔╝███████╗██║  ██║██║ ╚████╔╝ ███████╗
       ╚═════╝ ╚═════╝ ╚═════╝ ╚══════╝╚═╝  ╚═╝╚═╝  ╚═══╝  ╚══════╝";
    
    public static function header(){
        Line::br(50);
        $version = new Scope('config.version');
        
        Line::gray(self::$brand);
        Line::br(2);
        Line::green("\tCodeHive Framework v" . $version->major . "." . $version->minor . " [" . $version->patch . "] " . $version->code);
        Line::br(3);
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

    public static function br($times = 1){
        return print str_repeat("\n", $times);
    }

    public static function clear(){
        return print "\r";
    }
}
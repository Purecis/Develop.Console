<?php


namespace App\Develop;
use \App\System\Injectable;
use \App\System\Request;
use \App\System\FileSystem;
use \App\System\Loader;

require_once "Line.class.php";
require_once "Package.class.php";

class Console extends Injectable{


    function __bootstrap(){
        Line::header();
        
        if($_SERVER['argv'][0] == 'install'){
            self::install();
        };
        return;
        
        echo "hello console";

        if (!file_exists("assets/download")) {
            mkdir("assets/download", 0755, true);
        }
        Request::fetch("https://github.com/Purecis/codeHive/archive/v3.0.zip", "assets/download/3MB.zip", function ($current, $total) {
            $percent = $total == 0 ? 0 : round($current / $total * 100);
            echo "\rReceiving objects: {$percent}% (". FileSystem::format($current). "/" . FileSystem::format($total) . ") done ...";
        }, function ($e) use (&$errors, $zip) {
            echo "HTTP Error $e on $zip.";
            array_push($errors, "HTTP Error {$e} on {$zip}.");
        });

        // \App\System\Request::fetch("http://ipv4.download.thinkbroadband.com/5MB.zip", "5MB.zip", function($current, $total){
        //     echo "{$current} / {$total}<br>";
        // });

        if(!$this->isGitInstalled()){
            echo "you should install git before you can use console.";
        };
    }

    static function install(){
        $package = $_SERVER['argv'][1];
        $force = $_SERVER['argv'][2] == "--force";
        Package::install($package, $force);
    }


    function isGitInstalled(){
        $code = "git version";
        $run = `$code`;

        $len = strlen($code);
        return strncmp($code, $run, $len) === 0;
    }

    static function installCLI(){
        echo "install CLI Function called";
    }
}


/*

#!/bin/sh
/usr/bin/php /Volumes/SSD/www/framework/codeHive-v3.0/index.php _cli $@

*/
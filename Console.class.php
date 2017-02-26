<?php


namespace App\Develop;
use \App\System\Injectable;
use \App\System\Request;
use \App\System\FileSystem;

use App\Develop\Console\Controller\Std;
use App\Develop\Console\Controller\Package;
use App\Develop\Console\Controller\CLI;
use App\Develop\Console\Controller\Help;
use App\Develop\Console\Controller\Cmd;

class Console extends Injectable{
    
    function __bootstrap(){
        
        Std::header();

        switch (Cmd::argv(0)) {
            case 'new':
                Help::_new();
                break;
            
            case 'install':
                self::install();
                break;
            
            default:
                Help::_usage();
                break;
        }
    }

    static function install(){
        $package = Cmd::argv(1);
        if(!$package){
            // if no package then search for hive.json installer file
            Help::_install(); // just a demo test
            return;
        }

        $force = false;
        $global = false;
        
        Cmd::onOption('-f', '--force') && $force = "force";
        Cmd::onOption('-F', '--force-all') && $force = "force-all";
        Cmd::onOption('-g', '--global') && $global = true;
        
        Package::install($package, $force, $global, true);

        
    }





    function isGitInstalled(){
        $code = "git version";
        $run = `$code`;

        $len = strlen($code);
        return strncmp($code, $run, $len) === 0;
    }


    public static function installCLI(){
        CLI::install();
    }


}
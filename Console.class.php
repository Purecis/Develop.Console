<?php

namespace App\Develop;

use \App\System\Injectable;
use \App\System\Request;
use \App\System\FileSystem;

use App\Develop\Console\Controller\Std;
use App\Develop\Console\Controller\CLI;
use App\Develop\Console\Controller\Cmd;
use App\Develop\Console\Controller\Sys;
use App\Develop\Console\Controller\Help;

class Console extends Injectable{
    
    function __bootstrap(){
        
        Std::header();

        switch (Cmd::argv(0)) {
            case 'new':
                Help::_new();
                break;
            
            case 'serv':
                Sys::_serv();
                break;
            
            case 'install':
                Sys::_install();
                break;
            
            default:
                Help::_usage();
                break;
        }
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
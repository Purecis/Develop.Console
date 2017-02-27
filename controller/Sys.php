<?php
namespace App\Develop\Console\Controller;

use App\System\Controller;
use \App\System\Scope;

class Sys extends Controller{
    
    public static function _serv(){
        $port = 8000;
        Cmd::onOption('-p', '--port') && $port = Cmd::argv(1);

        Std::blue("  Development HTTP Server Started");
        Std::br(2);
        Std::output("  Document root is ");
        Std::blue(getcwd());
        Std::br();
        Std::output("  Listening on ");
        Std::blue("localhost:" . $port);
        Std::br(2);
        Std::output("  Press ");
        Std::red("Ctrl-C");
        Std::output(" to quit.");
        Std::br(2);
        shell_exec("php -S 0.0.0.0:" . $port);
    }
    
    static function _install(){
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
}
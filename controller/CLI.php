<?php
namespace App\Develop\Console\Controller;

use App\System\Controller;

class CLI extends Controller{
    /**
     * install CLI tools to device.
     *
     * @param	string 	$done     percentage complete
     * @param	string 	$total    total file size
     * @param	string 	$size     progress bar size
     *
     * @return content
     */
    public static function install(){
        Std::header();
        Std::green("CLI tools Installer");
        Std::br();

        // TODO : check platform if windows or unix
        $php = exec("whereis php");
        $php = self::ask($php, "defining PHP PATH", "OK PHP PATH defined as");
        // TODO : check if file exist

        $index = $_SERVER['PWD'] . "/" . $_SERVER['SCRIPT_NAME'];
        $index = self::ask($index, "defining codeHive index PATH", "OK codeHive index PATH is");
        // TODO : check if file exist

        $write = "/usr/local/bin/hive";
        $write = self::ask($write, "write codeHive CLI to", "Writeing codeHive to");

        // install codeHive cli to disk
        @unlink($write);
        file_put_contents($write, "#!/bin/sh\n{$php} {$index} _cli $@");
        chmod($write, 0755);
        
        Std::br(3);
        Std::output("Installation Completed, now you can use (");
        Std::green(basename($write));
        Std::output(") from command line to access codehive commands.");
        Std::br(2);
        Std::green(basename($write) . " install Container.Module\n");
        Std::green(basename($write) . " create module Container.Module\n");
        Std::green(basename($write) . " create controller Home\n");
        Std::green(basename($write) . " create model User\n");
        Std::green(basename($write) . " create view index\n");
        Std::green(basename($write) . " create controller@Container.Module");
        Std::br(2);
        Std::output(basename($write) . " and a lot of useful commands \n");
        Std::br(3);

        // install app _cli from internet to apps
    }

    public static function ask($default, $question, $OK="", $options="enter|path"){
        Std::output("{$question} ");
        Std::bold("({$default})");
        Std::output("?");
        Std::green(" [{$options}]");
        Std::br();
        $line = trim(fgets(STDIN));
        if($line != "y" and $line != ""){
            $default = $line;
        }
        Std::clear();
        Std::clear();
        Std::green("{$OK} ({$default})");
        Std::br();
        Std::gray(str_repeat("-", 60));
        Std::br();
        return $default;
    }
}
<?php
namespace App\Develop\Console\Controller;

use App\System\Controller;
use App\System\Request;

class Help extends Controller{
    
    public static function _usage(){
        $usage = [
            "Usage:" => [
                "hive command [options] [arguments]",
            ],
            "Global Commands:" => [
                'about'             => 'Short information about codeHive',
                'help'              => 'Displays help for a command',
                'configure'         => 'Fix all path and rewrite module in .htaccess file',
                'serv'              => 'start httpd server on port',
                'self-update'       => 'Updates codeHive to the latest version',
                'working-dir'       => 'Display codeHive working directory',
                'version'           => 'Display codeHive version',
            ],
            "Project Commands:" => [
                'create'            => 'Create new empty project.',
                'config'            => 'Set config options',
                'new'               => 'Make new option',
                'merge'             => 'Merge database changes',
                'init'              => 'Creates a basic hive.json file in current directory.',
                'exec'              => 'Execute (hive.json) script',
                'validate'          => 'Validates a hive.json',
            ],
            "Package Commands:" => [
                'list'              => 'Lists available packages in a container',
                'search'            => 'Search for packages',
                'install'           => 'Installs from package name if present, or falls back on the project dependencies to hive.json.',
                'update'            => 'Updates your dependencies to the latest version according to hive.json.',
                'remove'            => 'Removes a package from system and hive.json.',
                'info'              => 'Show information about packages',
                'browse'            => 'Opens the packages repository URL or homepage in your browser.',
                'depends'           => 'Shows which packages cause the given package to be installed',
                'license'           => 'Show information about licenses of dependencies',
                'outdated'          => 'Shows a list of installed packages that have updates available, including their latest version.',
                'clean'             => 'remove all installable packages from folders and keep them in hive.json',
            ],
            "Options:" => [
                '-s, --save'        => 'Save package to hive.json in main project',
                '-g, --global'      => 'run command to _global folder',
                '-h, --help'        => 'Display this help message',
                '-p, --port'        => 'Port to use for httpd server',
                '-m, --method'      => 'creator method (DI, IOC)',
                '-f, --force'       => 'Force command',
                '-F, --force-all'   => 'Force command and his all childs and dependencies',
                '-V, --version'     => 'Display this application version'
            ],
        ];
        
        Std::listing($usage);
    }


    public static function _new(){
        $usage = [
            "Usage:" => [
                "hive new sub-command [options] [arguments]",
            ],
            "(new) Sub Commands" => [
                'module'            => 'Create, Manage modules, call hive help module for internal commands',
                'model'             => 'Model commands',
                'view'              => 'Manage views',
                'controller'        => 'Create controller',
                'middleware'        => 'Create middleware',
                'directive'         => 'Create directive',
                'js'                => 'Create js',
                'css'               => 'Create css',
                'scss'              => 'Create scss',
                'less'              => 'Create less',
                'db:table'          => 'Database managment',
            ]
        ];
        Std::listing($usage);
    }

    public static function _install(){
        $usage = [
            "Usage:" => [
                "hive install Container.Module [options]",
            ],
            "Help:" => [
                "you can install modules from the packager in codehive,
                    this will help us to improve work stability and performance
                    and let us consitrate on the fun stuff"
            ]
        ];
        Std::listing($usage);
    }
}
<?php
namespace App\Develop;
use App\System\Scope;
use App\System\Request;
use App\System\FileSystem;

require_once "Line.class.php";

// TODO : fix if module not exists or something like that


class Package{

    /**
     * install
     *
     * @access public
     * @since release 3.0
     *
     * @param  string    $module
     */
    public static function install($package, $force = false)
    {
        ini_set("memory_limit","200M");

        $errors = [];
        $hive = new Scope('config.hive');

        $json = Request::fetch($hive->packager . $package);
        $json = json_decode($json);
        $json = $json->latest;
        
        list($container, $module) = explode(".", $package);

        $module_path = $hive->app_path . "/module/" . $container . "/" . $module;
        
        if (file_exists($module_path)) {
            if (!$force) {
                Line::output("Already Exists. you can try ");
                Line::green("hive update " . $package);
                Line::output(" or ");
                Line::green("hive install " . $package . " --force");
                Line::br();
                return;
            }
            FileSystem::rmdirRecursive($module_path);
        }

        // create folder if not exists
        FileSystem::mkdirRecursive($module_path);

        // check for git ..
        // check for files

        // check for zip ..
        if (isset($json->source->zip) && sizeof((array)$json->source->zip) > 0) {
            Line::blue("Downloading (" . sizeof((array)$json->source->zip) . ") Zip Files");
            Line::br(2);
            foreach ($json->source->zip as $zip => $folder) {
                $saveTo = $module_path . "/" . basename($zip);
                
                Line::gray(str_repeat("-", 60) . "\n");
                Line::gray("Fetching " . $zip . "\n");
                $downloaded = true;
                Request::fetch($zip, $saveTo, function ($current, $total) use($zip) {
                    $percent = $total == 0 ? 0 : round($current / $total * 100);
                    Line::clear();
                    Line::blue("Receiving objects: {$percent}% (". FileSystem::format($current). "/" . FileSystem::format($total) . ") done ...");
                }, function ($e) use (&$errors, $zip, &$downloaded) {
                    Line::br();
                    Line::red("HTTP Error $e on $zip.");
                    array_push($errors, "HTTP Error {$e} on {$zip}.");
                    $downloaded = false;
                });

                if (file_exists($saveTo) && $downloaded) {
                    Line::br();
                    $extractTo = $module_path . ($folder == "_empty_" ? "" : "/" . $folder);

                    FileSystem::mkdirRecursive($extractTo);
                    Line::green("Extracting ...");
                    Line::br();
                    if(FileSystem::unzip($saveTo, $extractTo)){
                        unlink($saveTo);
                        Line::green(basename($zip) . " Completed");
                    }else{
                        Line::red("Can't open zip file, try to extract it manually on {$saveTo}.");
                        array_push($errors, "Can't open zip file, try to extract it manually on {$saveTo}.");
                    }
                }
                Line::br();
            }
        }

        Line::br(2);
        Line::gray(str_repeat("*", 60) . "\n");
        Line::green("Module Located at : " . $module_path . "\n");
        Line::gray(str_repeat("*", 60) . "\n");
        Line::br(2);
        if (sizeof($errors)) {
            Line::red("Complete with Errors:");
            Line::br(2);
            foreach ($errors as $err) {
                Line::red("\t" . $err);
                Line::br();
            }
        } else {
            Line::green("COMPLETED");
            // now loader hive install
            // run package installer inside module .. 
        }
        Line::br(2);
    }


    /**
     * check for dependencies and install them
     *
     * @param  string   $package
     * @return boolean
     */
    public static function dependencies($package = NULL) {

        // install dependancies and check if installed before
        // 
        
        if(!is_null($package)) {
            echo "hello dependency";
        }
    }


    // packageConfigure
}
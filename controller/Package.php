<?php
namespace App\Develop\Console\Controller;

use App\System\Controller;
use App\System\Scope;
use App\System\Request;
use App\System\FileSystem;

class Package extends Controller{


    public static $packageList = [];
    /**
     * install
     *
     * @access public
     * @since release 3.0
     *
     * @param  string    $module
     */
    public static function install($package, $force = false, $global = false, $mainThread = true)
    {
        list($container, $module) = explode(".", $package);

        // check if allready in queue
        if(!self::$packageList[$container])self::$packageList[$container] = [];
        if(isset(self::$packageList[$container][$module])){
            return;
        }
        self::$packageList[$container][$module] = ['status' => true];

        ini_set("memory_limit","200M");

        $errors = [];
        $hive = new Scope('config.hive');

        $json = Request::fetch($hive->packager . $package);
        $json = json_decode($json);
        if(!$json instanceof \stdClass){
            array_push($errors, "Cant Access Packager Website");
            self::$packageList[$container][$module]['status'] = false;
            self::$packageList[$container][$module]['errors'] = $errors;
        }
        if(isset($json->status) && $json->status == false){
            array_push($errors, $json->error);
            self::$packageList[$container][$module]['status'] = false;
            self::$packageList[$container][$module]['errors'] = $errors;
        }
        
        $json = $json->latest;
        self::$packageList[$container][$module]['version'] = $json->version;

        // check for dependencies
        if(isset($json->dependancies)){
            foreach($json->dependancies as $dep){
                self::install($dep, $force == "force-all"?"force-all":false, $global, false);
            }
        }
        
        Std::highlight($package);
        Std::br(2);

        // display server or parse errors
        if(sizeof($errors)){
            Std::red(implode("\n", $errors));
            Std::br();
        }

        // check for global in fetched json
        if($json->global == true){
            $global = true;
        }
        $module_path = ($global == true ? $hive->glob_path : $hive->app_path) . "/module/" . $container . "/" . $module;
        self::$packageList[$container][$module]['path'] = $module_path;
        
        if (file_exists($module_path)) {
            if ($force != "force" && $force != "force-all") {
                array_push($errors, "Folder Already Exists. ");
                self::$packageList[$container][$module]['status'] = false;
                self::$packageList[$container][$module]['errors'] = $errors;
                Std::output("Already Exists. ");
                if($mainThread){
                    Std::output("you can try ");
                    Std::green("hive update " . $package);
                    Std::output(" or ");
                    Std::green("hive install " . $package . " --force");
                }else {
                    Std::green("Skipping " . $package);
                }
                Std::br();
                return;
            }
            FileSystem::rmdirRecursive($module_path);
        }

        // create folder if not exists
        FileSystem::mkdirRecursive($module_path);

        // check for git ..

        // check for files
        if (isset($json->source->file) && sizeof((array)$json->source->file) > 0) {
            Std::output("Downloading (" . sizeof((array)$json->source->file) . ") Files");
            Std::br();
            
            foreach ($json->source->file as $file => $folder) {
                Std::blue("Fetching : " . $file);
                $saveToFolder = $module_path . ($folder == "_empty_" ? "" : "/" . $folder);
                $saveTo = $saveToFolder . "/" . basename($file);
                FileSystem::mkdirRecursive($saveToFolder);
                
                $downloaded = true;
                Request::fetch($file, $saveTo, function ($current, $total) use($file) {
                    
                    Progress::percantage($current, $total);
                    
                }, function ($e) use (&$errors, $file, &$downloaded) {
                    Std::clearLine();
                    Std::red("HTTP Error $e on $file.");
                    array_push($errors, "HTTP Error {$e} on {$file}.");
                    $downloaded = false;
                });
                if($downloaded){
                    Std::clearLine();
                    Std::space(70);
                    Std::clearLine();
                    Std::green(basename($file) . " Completed");
                }
                Std::br();
            }
            Std::br();
        }

        // check for zip ..
        if (isset($json->source->zip) && sizeof((array)$json->source->zip) > 0) {
            Std::output("Downloading (" . sizeof((array)$json->source->zip) . ") Zip Files");
            Std::br();
            
            foreach ($json->source->zip as $zip => $folder) {
                Std::blue("Fetching : " . $zip);
                $saveTo = $module_path . "/" . basename($zip);
                
                // Std::gray(str_repeat("-", 60) . "\n");
                // Std::gray("Fetching " . $zip . "\n");
                $downloaded = true;
                Request::fetch($zip, $saveTo, function ($current, $total) use($zip) {
                    
                    Progress::percantage($current, $total);
                    
                }, function ($e) use (&$errors, $zip, &$downloaded) {
                    Std::clearLine();
                    Std::red("HTTP Error $e on $zip.");
                    array_push($errors, "HTTP Error {$e} on {$zip}.");
                    $downloaded = false;
                });

                if (file_exists($saveTo) && $downloaded) {
                    $extractTo = $module_path . ($folder == "_empty_" ? "" : "/" . $folder);

                    FileSystem::mkdirRecursive($extractTo);
                    Std::clearLine();
                    Std::green("Extracting ...");
                    Std::space(70);
                    if(FileSystem::unzip($saveTo, $extractTo)){
                        unlink($saveTo);
                        Std::clearLine();
                        Std::green(basename($zip) . " Completed");
                    }else{
                        Std::clearLine();
                        Std::red("Can't open zip file, try to extract it manually on {$saveTo}.");
                        array_push($errors, "Can't open zip file, try to extract it manually on {$saveTo}.");
                    }
                }
                Std::br();
            }
            Std::br();
        }

        Std::gray("Package Located at : " . $module_path . "\n");
        Std::gray(str_repeat("*", 60) . "\n");

        if (sizeof($errors)) {
            self::$packageList[$container][$module]['status'] = false;
            self::$packageList[$container][$module]['errors'] = $errors;
        } else {
            self::$packageList[$container][$module]['status'] = true;

            // now loader hive install
            // run package installer inside module .. 
        }
        
        // drow the tree
        if($mainThread){
            // Std::br(2);
            Std::output(".");
            Std::br();
            $count = [0,0];
            foreach(self::$packageList as $container => $modules){
                $count[0]++;
                if($count[0] < count(self::$packageList))Std::output("├── ");
                else Std::output("└── ");
                Std::bold($container);
                $count[1] = 0;
                foreach($modules as $module => $row){
                    Std::br();
                    if($count[0] < count(self::$packageList))Std::output("│");
                    else Std::output(" ");
                    Std::output("   ");

                    $count[1]++;
                    if($count[1] < count(self::$packageList[$container]))Std::output("├── ");
                    else Std::output("└── ");
                    
                    Std::bold($module);
                    if($row['version'])Std::output(" (".$row['version'].")");
                    Std::gray(" [" . $row['path'] . "]");

                    $spacer = "";
                    if($count[0] < count(self::$packageList))$spacer .= "\n│   ";
                    else $spacer .= "\n    ";
                    if($count[1] < count(self::$packageList[$container]))$spacer .= "│";
                    else $spacer .= " ";

                    Std::output($spacer);
                    Std::output("\t ");
                    if($row['status']){
                        Std::green("Success");
                    }else{
                        Std::red(implode($spacer . "\t ", $row['errors']));
                    }

                    Std::output($spacer);
                }
                Std::br();
            }
        }
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
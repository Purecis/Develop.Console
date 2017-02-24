<?php
namespace App\Develop\Console\Controller;

use App\System\Controller;
use App\System\FileSystem;

class Progress extends Controller{
    /**
     * progressbar text.
     * ██▓▒░-
     *
     */
     public static $progressbar = [
         "╢",      // start
         "█",      // fill
         "░",      // empty
         "",      // splitter
         "╟",      // end
         "in %d sec ..." // time message
     ];

     static $counter = 0;

     /**
     * CLI Percentage Progress Bar.
     *
     * @param	string 	$done     percentage complete
     * @param	string 	$total    total file size
     * @param	string 	$size     progress bar size
     *
     * @return content
     */
    public static function percantage($done, $total, $size=30) {



        // if we go over our bound, just ignore it
        if($done > $total || $total == 0) return;

        $perc=(double)($done/$total);
        $bar=floor($perc*$size);

        Std::clear();

        // generate bar
        $status_bar  = self::$progressbar[0];
        $status_bar .= str_repeat(self::$progressbar[1], $bar);
        if($bar < $size){
            $status_bar .= self::$progressbar[3];
            $status_bar .= str_repeat(self::$progressbar[2], $size-$bar);
        } else {
            $status_bar .= self::$progressbar[1];
        }
        $status_bar.= self::$progressbar[4];

        Std::green($status_bar);

        // generate percentage
        $disp   = number_format($perc*100, 0);
        $donev  = FileSystem::format($done);
        $totalv = FileSystem::format($total);

        Std::output(" {$disp}%  {$donev}/{$totalv} ");
        Std::space(5);
        
        /*
        // generate estimated time
        static $start_time;
        static $arr;
        static $counter;
        $counter++;

        if(empty($start_time)) $start_time=time();
        if(empty($arr)) $arr=array();
        
        if($counter >= 2000){
            $counter = 0;
            $now = time();
            // code counter 1
            // $rate = $done == 0 ? 0 : ($now-$start_time)/$done;
            // $left = $total - $done;
            // $eta = round($rate * $left, 2);
            // $elapsed = $now - $start_time;
            // array_push($arr, $eta);
            // $timer = sprintf(self::$progressbar[5], number_format($eta), number_format($elapsed));
            
            // code counter 2
            // $elapsedTime = $now - $start_time;
            // $estimatedRemaining = $elapsedTime * $total / $done;
            // $estimatedEndTime = $estimatedRemaining-$elapsedTime;
            // array_push($arr, $estimatedEndTime);
            // $timer = sprintf(self::$progressbar[5], $estimatedEndTime, $elapsedTime );

            if(sizeof($arr) >= 10){
                array_shift($arr);
            }

            $average = count($arr) != 0 ? array_sum($arr) / count($arr): 0;

            $timer = sprintf(self::$progressbar[5], $average );
        }
        
        Std::output($timer);
        */
    }

    public static function line($current, $total){
        $percent = $total == 0 ? 0 : round($current / $total * 100);
        Std::clear();
        Std::blue("Receiving objects: {$percent}% (". FileSystem::format($current). "/" . FileSystem::format($total) . ") done ...");
    }
}
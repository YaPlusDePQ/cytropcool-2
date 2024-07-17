<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class RateController extends Controller
{
    
    public static function getCurrentRateOfUser(array $drinks, float $sexe, float $weight, int $eat): array
    {
        $DECAY = config('cytropcool.constant.decay');

        $TIME_TO_MAX = $eat ? config('cytropcool.constant.time_to_max_eat') : config('cytropcool.constant.time_to_max_no_eat');

        $TIME_TO_MAX_BOTTOMS_UP = config('cytropcool.constant.time_to_max_bottoms_up');

        $TIME_TO_MAX_NO_BOTTOMS_UP = config('cytropcool.constant.time_to_max_no_bottoms_up');
        
        $TIME_FIRST_DRINK = strtotime($drinks[0]->drink_at);
        
        $current_date = strtotime(date('Y-m-d H:i:s'));
        
        $insynch_max_rate_current_time = 0;
        $insynch_max_rate_last_time = $TIME_FIRST_DRINK + $TIME_TO_MAX + ( $drinks[0]->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP );
        
        $rate = 0;
        $insynch_max_current_rate = 0;
        $max = ["rate"=>0, "time"=>$current_date];
        
        foreach($drinks as $drink){
            $ingestion_gap = ( $drink->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP );
            
            $tt = ( $drink->size * 10 * $drink->number * ($drink->alcool_quantity/100) * ($drink->alcool_degre/100) * 0.8) / ($sexe * $weight);
            
            //live rate
            if($current_date >= (strtotime($drink->drink_at)+ $TIME_TO_MAX + $ingestion_gap) ){
                $rate += $tt;
            }
            else if($current_date >= (strtotime($drink->drink_at) + $ingestion_gap) ){
                $rate += ($tt *( $current_date - (strtotime($drink->drink_at) + $ingestion_gap ))) / ($TIME_TO_MAX + $ingestion_gap);
            }
            
            //insynch max rate
            $insynch_max_rate_current_time = (strtotime($drink->drink_at)+ $TIME_TO_MAX + $ingestion_gap);
            $insynch_max_current_rate += $tt; 
            
            $insynch_max_reduction = $DECAY * ($insynch_max_rate_current_time - $insynch_max_rate_last_time); //get decay betwen two drink
            if($insynch_max_reduction > 0) $insynch_max_current_rate -= $insynch_max_reduction;
            
            if($max["rate"] < $insynch_max_current_rate){
                $max["rate"] = $insynch_max_current_rate;
                $max["time"] = $insynch_max_rate_current_time;
            }
            
            $insynch_max_rate_last_time = $insynch_max_rate_current_time;
        }
        
        $reduction = $DECAY * ($current_date - ($TIME_FIRST_DRINK + 30*60 + ( $drinks[0]->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP )));
        if($reduction > 0) $rate -= $reduction;
        if($rate < 0) $rate = 0;
        return ["rate"=>$rate, "max_rate"=>$max["rate"], "max_rate_time"=>$max["time"]];
    }

    
}
<?php

namespace App\Http\Controllers;

use App\BO\FlightBO;
use Exception;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    //
    public function getFlights()
    {
        try{
            $return = (new FlightBO)->makeGroups();
            if($return){
                return response($return,200,['Content-Type'=>'application/json']);
            }
            return response([],404,['Content-Type'=>'application/json']);
        }catch(Exception $e){
            return response(['erro' =>$e->getMessage(),'line'=>$e->getLine(),'file'=>$e->getFile()],500,['Content-Type'=>'application/json']);
        }

    }
}

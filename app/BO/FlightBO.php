<?php

namespace App\BO;

use App\Utils\RequestUtils;

class FlightBO
{
    //
    private function getFlights(){
        return collect((new RequestUtils)->request('http://prova.123milhas.net/api/flights','GET'));
    }
    public function makeGroups()
    {
        $flights= $this->getFlights();
        $groups=collect();
        $cheapest=(object)['id'=>null,'price'=>999];
        $groups=collect();
        $flights->groupBy('fare')
        ->each(function($fligth) use($groups,$cheapest){
            $inbounds=        $fligth->where('inbound',1)->groupBy('price');
            $outbounds=       $fligth->where('outbound',1)->groupBy('price');
            $inbounds->map(function($inbound) use ($groups,$outbounds,$cheapest){
                $outbounds->map(function($outbound) use ($groups,$inbound,$cheapest){
                    $id=uniqid();
                    $total=$inbound->first()['price']+$outbound->first()['price'];
                    if($cheapest->price>$total){
                        $cheapest->id=$id;
                        $cheapest->price=$total;
                    }
                    $groups->add(['uniqueId'=>$id,'totalPrice'=>$total,
                    'inbounds'=>$inbound,'outbounds'=>$outbound]);
                });

            });

        });
        return [
            'flights'=>$flights,
            'groups'=>$groups->orderBy('totalPrice','ASC'),
            'totalGroups'=>$groups->count(),
            'totalFlights'=>$flights->count(),
            'cheapestPrice'=>$cheapest->price,
            'cheapestGroup'=>$cheapest->id,
        ];
    }
}

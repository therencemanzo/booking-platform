<?php

namespace App\Http\Controllers\API\v1;


use App\Http\Controllers\Controller;
use App\Http\Requests\API\GetParkingSpaceRequest;
use App\Http\Resources\API\ParkingSpaceResource;
use App\Models\ParkingSpace;
use App\Services\ParkingSpaceService;

class ParkingSpacesController extends Controller
{
    
    public function getParkingSpaces(GetParkingSpaceRequest $request, ParkingSpaceService $parkingSpaceService){

        $parkingSpaces = $parkingSpaceService->get($request->date_from, $request->date_to);
        
        return ParkingSpaceResource::collection($parkingSpaces);
        
    }

    public function getParkingSpaceDetails(GetParkingSpaceRequest $request, ParkingSpace $parkingSpace){
        
        return new ParkingSpaceResource($parkingSpace);
        
    }
}

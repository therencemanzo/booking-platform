<?php

namespace App\Services;
use App\Filters\DateRangeFilter;
use App\Http\Resources\ParkingSpaceResource;
use App\Models\ParkingSpace;
use Illuminate\Support\Facades\Pipeline;
class ParkingSpaceService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function get($dateFrom = null, $dateTo = null, $paginate = 50)
    {
        $pipes = [
            new DateRangeFilter($dateFrom, $dateTo),
        ];

        return Pipeline::send(ParkingSpace::query())
            ->through($pipes)
            ->thenReturn()
            ->with(['bookings'])
            ->paginate($paginate);

    }
}

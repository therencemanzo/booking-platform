<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class DateRangeFilter
{
    /**
     * Create a new class instance.
     */
    public function __construct(public $dateFrom = null, public $dateTo = null){ }
    
    
    public function __invoke(Builder $query, $next)
    {
        if($this->dateFrom != null && $this->dateTo != null){

            $query->whereDoesntHave('bookings', function($query){
                $query->where(function ($q)  {
                    $q->where('date_from', '<=', $this->dateTo)
                      ->where('date_to', '>=', $this->dateFrom);
                });
            });
            
        }
        else{

            $query->whereDoesntHave('bookings', function($query){
                $query->where('date_from', '<=', now())
                    ->where('date_to', '>=', now());
            });
        }   

        return $next($query);
    }
}

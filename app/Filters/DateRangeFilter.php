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

            $query->whereDoesntHave('bookings', function($q){
                $q->where('date_from', $this->dateFrom)
                    ->where('date_to', $this->dateTo);
            });
            
        }
        else{

            $query->whereDoesntHave('bookings', function($q){
                $q->where('date_from', '<=', now())
                    ->where('date_to', '>=', now());
            });
        }   

        return $next($query);
    }
}

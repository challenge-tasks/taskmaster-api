<?php

namespace App\Services\Task;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskQueryService
{
    public static function filter(Builder $query): QueryBuilder
    {
        return QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::callback('difficulty', function (Builder $query, mixed $value): Builder {
                    if (! is_array($value)) {
                        $value = [$value];
                    }

                    return $query->whereIn('difficulty', $value);
                })
            ])
            ->allowedSorts([
                'id', 'name', 'difficulty', 'created_at', 'updated_at'
            ]);
    }
}

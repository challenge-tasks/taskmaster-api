<?php

namespace App\Services\Task;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskQueryService
{
    public static function filter(BuilderContract $query): QueryBuilder
    {
        return QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::callback('difficulty', function (Builder $query, mixed $value): Builder {
                    if (! is_array($value)) {
                        $value = [$value];
                    }

                    return $query->whereIn('difficulty', $value);
                }),

                AllowedFilter::callback('tech_stacks', function (Builder $query, mixed $value): Builder {
                    if (! is_array($value)) {
                        $value = [$value];
                    }

                    return $query->whereHas('stacks', function (Builder $query) use ($value): Builder {
                        return $query->whereIn('slug', $value);
                    });
                }),

                AllowedFilter::callback('tags', function (Builder $query, mixed $value): Builder {
                    if (! is_array($value)) {
                        $value = [$value];
                    }

                    return $query->whereHas('tags', function (Builder $query) use ($value): Builder {
                        return $query->whereIn('slug', $value);
                    });
                }),
            ])
            ->allowedSorts([
                'id', 'name', 'difficulty', 'created_at', 'updated_at'
            ]);
    }
}

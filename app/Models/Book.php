<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|Book popular()
 * @method \Illuminate\Database\Eloquent\Builder|Book popular()
 */
class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ______________________________________________________________________
    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'like', '%'.$title.'%');
    }

    // ______________________________________________________________________
    /**
     * @param  Builder<Book>  $query
     */
    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withCount([
            'reviews' => fn (Builder $q) => self::dateRangeFilter($q, $from, $to),
        ])->orderBy('reviews_count', 'desc');
    }

    // ______________________________________________________________________
    /**
     * @param  Builder<Book>  $query
     */
    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn (Builder $q) => self::dateRangeFilter($q, $from, $to),
        ], 'rating')->orderBy('reviews_avg_rating', 'desc');
    }

    // ______________________________________________________________________
    /**
     * @param  Builder<Book>  $query
     */
    public function scopeMinReviews(Builder $query, int $minReviews): Builder|QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    // ______________________________________________________________________
    private static function dateRangeFilter(Builder $query, $from = null, $to = null): void
    {
        if ($from && ! $to) {
            $query->where('created_at', '>=', $from);
        } elseif (! $from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    // ______________________________________________________________________
    /**
     * @param  Builder<Book>  $query
     */
    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }

    // ______________________________________________________________________
    /**
     * @param  Builder<Book>  $query
     */
    public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }

    // ______________________________________________________________________
    /**
     * @param  Builder<Book>  $query
     */
    public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);
    }

    // ______________________________________________________________________
    /**
     * @param  Builder<Book>  $query
     */
    public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(5);
    }
}

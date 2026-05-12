<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Book extends Model
{
     use HasFactory;

     public function reviews()
     {
        return $this->hasMany(Review::class);
     }

     public function scopeTitle (Builder $query, string $title) : Builder
     {
           return $query->where('title', 'LIKE', '%' . $title . '%');
     }


        public function scopeWithReviewsCount(Builder $querry, $from = null, $to = null): Builder | QueryBuilder
        {
            return $querry->withCount(['reviews' => fn(Builder $q) => $this
            ->dateRangedFilter($q, $from, $to)]);

        }


       public function scopeWithAvgRating(Builder $querry, $from = null, $to = null): Builder | QueryBuilder
        {
            return $querry->withAvg(['reviews' => fn(Builder $q) => $this
            ->dateRangedFilter($q, $from, $to)], 'rating');

        }

     public function scopePopular(Builder $querry, $from = null, $to = null): Builder | QueryBuilder
     {
        return $querry->withReviewsCount($from, $to)
            ->orderBy('reviews_count', 'desc');
     }

     public function scopeHighestRated(Builder $querry, $from = null, $to = null): Builder | QueryBuilder
     {
        return $querry->withAvgRating($from, $to)
            ->orderBy('reviews_avg_rating','desc');
     }

     public function scopeMinReviews(Builder $querry, int $minReviews): Builder | QueryBuilder
     {
        return $querry->having('reviews_count', '>=', $minReviews);
     }

     private function dateRangedFilter(Builder $query, $from = null, $to = null)
     {
         if ($from && !$to) {
                $query->where('created_at', '>=', $from);
         } elseif (!$from && $to) {
                $query->where('created_at', '<=', $to);
         } elseif ($from && $to) {
                $query->whereBetween('created_at', [$from, $to]);
         }
     }

     public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder
     {
        return $query->popular(now()->subMonth(), now())
        ->highestRated(now()->subMonth(), now())
        ->minReviews(2);
     }

      public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder
     {
        return $query->popular(now()->subMonths(6), now())
        ->highestRated(now()->subMonths(6), now())
        ->minReviews(5);
     }

      public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder
     {
        return $query->highestRated(now()->subMonth(), now())
        ->popular(now()->subMonth(), now())
        ->minReviews(2);
     }

      public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder
     {
        return $query->highestRated(now()->subMonths(6), now())
        ->popular(now()->subMonths(6), now())
        ->minReviews(5);
     }

       protected static function booted()
    {
        static::updated(fn (Book $book) => cache()
        ->forget('book:'. $book->id));
        static::deleted(fn (Book $book) => cache()
        ->forget('book:'. $book->id));
    }
}

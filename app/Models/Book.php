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

     public function scopePopular(Builder $querry, $from = null, $to = null): Builder | QueryBuilder
     {
        return $querry->withCount(['reviews' => fn(Builder $q) => $this
        -> dateRangedFilter($q, $from, $to)])
        ->orderBy('reviews_count', 'desc');
     }

     public function scopeHighestRated(Builder $querry, $from = null, $to = null): Builder | QueryBuilder
     {
        return $querry->withAvg(['reviews' => fn(Builder $q) => $this
        -> dateRangedFilter($q, $from, $to)], 'rating')
        ->orderBy('reviews_avg_rating','desc');
     }

     public function scopeMinReviews(Builder $querry, int $minReviews): Builder | QueryBuilder
     {
        return $querry->having('reviews_count', '>=', $minReviews);
     }

     private function  dateRangedFilter(Builder $query, $from = null, $to = null)
     {
         if ($from && !$to) {
                $query->where('creatde_at', '>=', $from);
             } elseif ($from && $to) {
                $query->where('created_at', '<=', $to);
             } elseif($from && $to) {
                   $query->whereBetween('created_at', [$from, $to]);
             }
     }
}

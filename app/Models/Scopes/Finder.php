<?php

namespace App\Models\Scopes;

trait Finder
{
    public function scopeNearest($query, $latitude, $longitude)
    {
        return $query->orderByRaw('ST_DISTANCE_SPHERE(point(longitude, latitude), point(?, ?))', [$longitude, $latitude]);
    }

    public function scopeFarthest($query, $latitude, $longitude)
    {
        return $query->orderByRaw('ST_DISTANCE_SPHERE(point(longitude, latitude), point(?, ?)) DESC', [$longitude, $latitude]);
    }
}

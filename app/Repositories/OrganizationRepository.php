<?php

namespace App\Repositories;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class OrganizationRepository
{
    public function getAll(): Collection
    {
        return Organization::all();
    }

    public function findById($id): Model|Collection|Builder|array
    {
        $organization = Organization::with(['building', 'activities'])->find($id);
        if (!$organization) {
            throw new ModelNotFoundException('Организация с ID ' . $id . ' не найдена.');
        }
        return $organization;
    }

    public function findByBuilding($buildingId)
    {
        return Organization::where('building_id', $buildingId)->get();
    }

    public function findByActivity($activityId)
    {
        return Organization::whereHas('activities', function ($query) use ($activityId) {
            $query->where('activities.id', $activityId);
        })->get();
    }

    public function searchByName($name)
    {
        return Organization::where('name', 'LIKE', "%{$name}%")->get();
    }

    public function findByRadius($latitude, $longitude, $radius)
    {
        return Organization::whereHas('building', function ($query) use ($latitude, $longitude, $radius) {
            $query->whereRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?",
                [$latitude, $longitude, $latitude, $radius]
            );
        })->get();
    }

    public function searchByActivityName($activityName)
    {
        $activity = Activity::where('name', 'LIKE', "%{$activityName}%")->first();
        if (!$activity) {
            throw new ModelNotFoundException("Вид деятельности '{$activityName}' не найден.");
        }

        $allActivityIds = $this->getAllChildrenIds($activity);
        return Organization::whereHas('activities', function ($query) use ($allActivityIds) {
            $query->whereIn('activities.id', $allActivityIds);
        })->with(['activities', 'building'])->get();
    }

    private function getAllChildrenIds(Activity $activity)
    {
        $ids = collect([$activity->id]);
        foreach ($activity->children as $child) {
            $ids = $ids->merge($this->getAllChildrenIds($child));
        }
        return $ids;
    }
}

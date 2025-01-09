<?php

namespace App\Actions\Organization;

use App\Repositories\OrganizationRepository;

class SearchActivitiesNameAction
{
    protected $repository;

    public function __construct(OrganizationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($activityName)
    {
        return $this->repository->searchByActivityName($activityName);
    }
}

<?php

namespace App\Actions\Organization;

use App\Repositories\OrganizationRepository;

class GetOrganizationByActivityAction
{
    protected OrganizationRepository $repository;

    public function __construct(OrganizationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($activityId)
    {
        return $this->repository->findByActivity($activityId);
    }
}

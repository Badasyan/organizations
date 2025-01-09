<?php

namespace App\Actions\Organization;

use App\Repositories\OrganizationRepository;

class GetOrganizationByBuildingAction
{
    protected OrganizationRepository $repository;

    public function __construct(OrganizationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($buildingId)
    {
        return $this->repository->findByBuilding($buildingId);
    }
}

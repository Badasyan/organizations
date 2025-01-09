<?php

namespace App\Actions\Organization;

use App\Repositories\OrganizationRepository;

class GetOrganizationsByRadiusAction
{
    protected OrganizationRepository $repository;

    public function __construct(OrganizationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($latitude, $longitude, $radius)
    {
        return $this->repository->findByRadius($latitude, $longitude, $radius);
    }
}

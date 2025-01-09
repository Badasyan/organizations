<?php

namespace App\Actions\Organization;

use App\Repositories\OrganizationRepository;
use Illuminate\Database\Eloquent\Collection;

class SearchOrganizationAction
{
    protected OrganizationRepository $repository;

    public function __construct(OrganizationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($name): Collection
    {
        return $this->repository->searchByName($name);
    }
}

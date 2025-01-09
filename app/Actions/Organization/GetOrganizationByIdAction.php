<?php


namespace App\Actions\Organization;

use App\Repositories\OrganizationRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GetOrganizationByIdAction
{
    protected OrganizationRepository $repository;

    public function __construct(OrganizationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($id): Model|Collection|Builder|array|null
    {
        return $this->repository->findById($id);
    }
}

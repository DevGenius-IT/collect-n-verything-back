<?php

namespace App\Services;

class CrudService
{
    protected $repository;
    protected PaginationService $pagination;

    public function __construct($repository, PaginationService $pagination)
    {
        $this->repository = $repository;
        $this->pagination = $pagination;
    }

    /**
     * Retourne la pagination formatée des données.
     *
     * @param array $params
     * @param string $url
     * @return array
     */
    public function getPaginated(array $params, string $url, ?array $selectedFields = null): array
    {
        return $this->pagination->paginate(
            $params,
            $this->repository,
            $url,
            $selectedFields
        );
    }
}

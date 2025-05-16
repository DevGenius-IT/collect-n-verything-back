<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class PaginationService
{
    /**
     * Paginate les résultats selon les paramètres validés.
     * 
     * @param array $params paramètres validés
     * @param mixed $repository repository avec méthode query() retournant un Builder
     * @param string $url URL de la requête (optionnel)
     * @return array structure paginée personnalisée
     */
    public function paginate(array $params, $repository, string $url, ?array $selectedFields = null): array
    {
        $query = $repository->query();

        $perPage = $params['per_page'] ?? 10;
        $page = $params['page'] ?? 1;

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $items = collect($paginator->items());

        if ($selectedFields) {
            $items = $items->map(function ($item) use ($selectedFields) {
                return collect($item)->only($selectedFields);
            });
        }

        return [
            'items' => $items->values(),
            'meta' => [
                'total' => $paginator->total(),
                'pages_count' => $paginator->lastPage(),
                'current_page' => $paginator->currentPage(),
                'limit' => $paginator->perPage(),
                'selected_fields' => $selectedFields,
            ],
        ];
    }
}

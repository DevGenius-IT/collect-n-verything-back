<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class PaginationService
{
    public function paginate(array $params, $repository, ?string $url = null, ?array $selectedFields = null): array
    {
        $query = $repository->query();

        $perPage = $params['limit'] ?? 10;
        $page = $params['page'] ?? 1;

        $orderBy = $params['orderBy'] ?? 'id';
        $order = $params['order'] ?? 'asc';
        $query->orderBy($orderBy, $order);

        $trash = $params['trash'] ?? null;
        if ($trash === 'only' && method_exists($query->getModel(), 'trashed')) {
            $query->onlyTrashed();
        } elseif ($trash === 'with' && method_exists($query->getModel(), 'trashed')) {
            $query->withTrashed();
        }

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
                'url' => $url,
            ],
        ];
    }
}

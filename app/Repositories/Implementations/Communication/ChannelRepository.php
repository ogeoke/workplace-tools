<?php

namespace App\Repositories\Implementations\Communication;

use App\Models\Communication\Channel;
use App\Repositories\Implementations\BaseRepository;

class ChannelRepository extends BaseRepository
{
    public function __construct(Channel $model)
    {
        parent::__construct($model);
    }

    public function getByOrganization(int $organizationId, array $relations = [])
    {
        $query = $this->model->where('organization_id', $organizationId)->where('is_archived', false);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    public function getPublicChannels(int $organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('type', 'public')
            ->where('is_archived', false)
            ->get();
    }

    public function searchByName(string $search, int $organizationId)
    {
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('name', 'LIKE', '%' . $search . '%')
            ->where('is_archived', false)
            ->limit(10)
            ->get();
    }
}

<?php

namespace App\Services\Communication;

use App\Models\Communication\Channel;
use App\Models\Communication\Message;
use App\Models\User;
use Illuminate\Pagination\Paginator;

class ChannelService
{
    public function createChannel(array $data): Channel
    {
        $data['slug'] = str($data['name'])->slug();
        return Channel::create($data);
    }

    public function updateChannel(Channel $channel, array $data): Channel
    {
        if (isset($data['name'])) {
            $data['slug'] = str($data['name'])->slug();
        }
        $channel->update($data);
        return $channel;
    }

    public function archiveChannel(Channel $channel): bool
    {
        return $channel->update(['is_archived' => true]);
    }

    public function addMember(Channel $channel, User $user, string $role = 'member'): void
    {
        if (!$channel->members()->where('user_id', $user->id)->exists()) {
            $channel->members()->attach($user->id, ['role' => $role]);
        }
    }

    public function removeMember(Channel $channel, User $user): bool
    {
        return (bool) $channel->members()->detach($user->id);
    }

    public function getChannels(int $organizationId, ?int $userId = null)
    {
        $channels = Channel::where('organization_id', $organizationId)
            ->where('is_archived', false)
            ->get();

        if ($userId) {
            return $channels->filter(function ($channel) use ($userId) {
                return $channel->members()->where('user_id', $userId)->exists() || $channel->type === 'public';
            });
        }

        return $channels;
    }

    public function searchChannels(string $query, int $organizationId)
    {
        return Channel::where('organization_id', $organizationId)
            ->where('is_archived', false)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get();
    }
}

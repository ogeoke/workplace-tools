<?php

namespace App\Repositories\Implementations\Communication;

use App\Models\Communication\Message;
use App\Repositories\Implementations\BaseRepository;

class MessageRepository extends BaseRepository
{
    public function __construct(Message $model)
    {
        parent::__construct($model);
    }

    public function getChannelMessages(int $channelId, int $limit = 50)
    {
        return $this->model
            ->where('channel_id', $channelId)
            ->whereNull('parent_id')
            ->with(['user', 'reactions', 'attachments', 'replies'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();
    }

    public function getThreadReplies(int $parentId)
    {
        return $this->model
            ->where('parent_id', $parentId)
            ->with(['user', 'reactions', 'attachments'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function searchInChannel(string $query, int $channelId, int $limit = 20)
    {
        return $this->model
            ->where('channel_id', $channelId)
            ->where('content', 'LIKE', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPinnedMessages(int $channelId)
    {
        return $this->model
            ->where('channel_id', $channelId)
            ->where('is_pinned', true)
            ->with(['user', 'reactions'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

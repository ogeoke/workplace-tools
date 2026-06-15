<?php

namespace App\Services\Communication;

use App\Models\Communication\Channel;
use App\Models\Communication\Message;
use App\Events\Communication\MessageCreated;
use Illuminate\Database\Eloquent\Collection;

class MessageService
{
    public function createMessage(Channel $channel, int $userId, string $content): Message
    {
        $message = Message::create([
            'channel_id' => $channel->id,
            'user_id' => $userId,
            'content' => $content,
            'created_by' => $userId,
        ]);

        MessageCreated::dispatch($message);
        return $message->load(['user', 'reactions', 'attachments']);
    }

    public function updateMessage(Message $message, string $content): Message
    {
        $message->update([
            'content' => $content,
            'is_edited' => true,
        ]);
        return $message->refresh();
    }

    public function deleteMessage(Message $message): bool
    {
        return (bool) $message->delete();
    }

    public function pinMessage(Message $message): Message
    {
        $message->update(['is_pinned' => true]);
        return $message->refresh();
    }

    public function unpinMessage(Message $message): Message
    {
        $message->update(['is_pinned' => false]);
        return $message->refresh();
    }

    public function getChannelMessages(Channel $channel, int $limit = 50): Collection
    {
        return $channel->messages()
            ->whereNull('parent_id')
            ->with(['user', 'reactions', 'attachments', 'replies'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();
    }

    public function searchMessages(Channel $channel, string $query): Collection
    {
        return $channel->messages()
            ->where('content', 'LIKE', '%' . $query . '%')
            ->with(['user', 'reactions'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Communication;

use App\Http\Controllers\Controller;
use App\Models\Communication\Channel;
use App\Models\Communication\Message;
use App\Services\Communication\MessageService;
use App\Http\Requests\Communication\StoreMessageRequest;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function __construct(private MessageService $service) {}

    public function index(Channel $channel): JsonResponse
    {
        $messages = $this->service->getChannelMessages($channel);
        return response()->json([
            'success' => true,
            'data' => $messages,
        ]);
    }

    public function store(StoreMessageRequest $request, Channel $channel): JsonResponse
    {
        $message = $this->service->createMessage($channel, auth()->id(), $request->content);
        return response()->json([
            'success' => true,
            'data' => $message,
            'message' => 'Message sent successfully',
        ], 201);
    }

    public function show(Message $message): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $message->load(['user', 'reactions', 'attachments', 'replies']),
        ]);
    }

    public function update(StoreMessageRequest $request, Message $message): JsonResponse
    {
        $this->authorize('update', $message);
        $updated = $this->service->updateMessage($message, $request->content);
        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Message updated successfully',
        ]);
    }

    public function destroy(Message $message): JsonResponse
    {
        $this->authorize('delete', $message);
        $this->service->deleteMessage($message);
        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Communication;

use App\Http\Controllers\Controller;
use App\Models\Communication\Channel;
use App\Services\Communication\ChannelService;
use App\Http\Requests\Communication\StoreChannelRequest;
use Illuminate\Http\JsonResponse;

class ChannelController extends Controller
{
    public function __construct(private ChannelService $service) {}

    public function index(): JsonResponse
    {
        $channels = $this->service->getChannels(auth()->user()->organizations()->first()->id);
        return response()->json([
            'success' => true,
            'data' => $channels,
        ]);
    }

    public function store(StoreChannelRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $channel = $this->service->createChannel($data);
        
        return response()->json([
            'success' => true,
            'data' => $channel,
            'message' => 'Channel created successfully',
        ], 201);
    }

    public function show(Channel $channel): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $channel->load(['members', 'messages']),
        ]);
    }

    public function update(StoreChannelRequest $request, Channel $channel): JsonResponse
    {
        $updated = $this->service->updateChannel($channel, $request->validated());
        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Channel updated successfully',
        ]);
    }

    public function destroy(Channel $channel): JsonResponse
    {
        $channel->delete();
        return response()->json([
            'success' => true,
            'message' => 'Channel deleted successfully',
        ]);
    }
}

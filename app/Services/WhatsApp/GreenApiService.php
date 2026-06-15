<?php

namespace App\Services\WhatsApp;

use App\Models\Notifications\WhatsAppLog;
use App\Models\User;
use Exception;

class GreenApiService
{
    private string $instanceId;
    private string $token;
    private string $baseUrl;

    public function __construct()
    {
        $this->instanceId = config('services.green_api.instance_id');
        $this->token = config('services.green_api.token');
        $this->baseUrl = config('services.green_api.base_url', 'https://api.green-api.com');
    }

    public function sendMessage(string $phoneNumber, string $message, ?User $user = null, ?int $organizationId = null): WhatsAppLog
    {
        try {
            $response = $this->makeRequest('POST', '/sendMessage', [
                'chatId' => $phoneNumber . '@c.us',
                'message' => $message,
            ]);

            $log = WhatsAppLog::create([
                'organization_id' => $organizationId,
                'user_id' => $user?->id,
                'phone_number' => $phoneNumber,
                'message' => $message,
                'delivery_status' => 'sent',
                'green_api_message_id' => $response['idMessage'] ?? null,
                'sent_at' => now(),
            ]);

            return $log;
        } catch (Exception $e) {
            WhatsAppLog::create([
                'organization_id' => $organizationId,
                'user_id' => $user?->id,
                'phone_number' => $phoneNumber,
                'message' => $message,
                'delivery_status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function getMessageStatus(string $messageId): array
    {
        return $this->makeRequest('GET', '/getMessage', [
            'idMessage' => $messageId,
        ]);
    }

    private function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . '/waInstance' . $this->instanceId . $endpoint;

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        if ($method === 'GET') {
            $url .= '?apitoken=' . $this->token;
            $url .= '&' . http_build_query($data);
        } else {
            $data['apitoken'] = $this->token;
            $options['json'] = $data;
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->request($method, $url, $options);

        return json_decode($response->getBody(), true);
    }
}

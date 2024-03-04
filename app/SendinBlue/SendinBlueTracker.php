<?php

namespace App\SendinBlue;

use App\User;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class SendinBlueTracker
{
    protected $client;

    public function __construct()
    {
        $this->setupClient();
    }

    protected function setupClient()
    {
        $this->client = new Client([
            'base_uri' => config('sendinblue.routes.tracker'),
            'http_errors' => false,
            'headers' => [
                'Ma-Key' => config('sendinblue.tracker_id'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    protected function request($verb, $uri, array $payload = [])
    {
        $response = $this->client->request(
            $verb,
            $uri,
            empty($payload) ? [] : ['json' => $payload]
        );
        if ($response->getStatusCode() != 204) {
            return $this->handleRequestError($response);
        }
        $responseBody = (string) $response->getBody();
        return json_decode($responseBody, true) ?: $responseBody;
    }

    protected function handleRequestError(ResponseInterface $response)
    {
        throw new \Exception((string) $response->getBody());
        Log::debug('SENDINBLUE TRACKER: ' . (string) $response->getBody());
    }

    public function event($event, User $user, array $properties = [], array $eventData = [])
    {
        $data = [
            'email' => $user->email,
            'event' => $event,
        ];

        if (!empty($properties)) {
            $data['properties'] = $properties;
        }

        if (!empty($eventData)) {
            $data['eventdata'] = $eventData;
        }
        $result = $this->request('POST', 'trackEvent', $data);
        return $result;
    }
}

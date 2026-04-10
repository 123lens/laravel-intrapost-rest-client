<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost;

use Budgetlens\Intrapost\Exceptions\IntrapostApiException;
use Budgetlens\Intrapost\Exceptions\IntrapostAuthenticationException;
use Budgetlens\Intrapost\Exceptions\IntrapostException;
use Budgetlens\Intrapost\Resources\MailPieceResource;
use Budgetlens\Intrapost\Resources\OrderResource;
use Budgetlens\Intrapost\Resources\TrackTraceResource;
use Budgetlens\Intrapost\Resources\UtilityResource;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class IntrapostClient
{
    private GuzzleClient $httpClient;
    private string $apiKey;
    private string $accountNumber;
    private string $baseUrl;

    public function __construct(
        string $apiKey,
        string $accountNumber,
        string $baseUrl = 'https://api.intrapost.nl',
        ?GuzzleClient $httpClient = null,
    ) {
        $this->apiKey = $apiKey;
        $this->accountNumber = $accountNumber;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->httpClient = $httpClient ?? new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    public function mailPiece(): MailPieceResource
    {
        return new MailPieceResource($this);
    }

    public function order(): OrderResource
    {
        return new OrderResource($this);
    }

    public function trackTrace(): TrackTraceResource
    {
        return new TrackTraceResource($this);
    }

    public function utility(): UtilityResource
    {
        return new UtilityResource($this);
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    /**
     * @throws IntrapostException
     */
    public function post(string $endpoint, array $data = []): array
    {
        $payload = array_merge([
            'ApiKey' => $this->apiKey,
        ], $data);

        try {
            $response = $this->httpClient->post($endpoint, [
                'json' => $payload,
            ]);

            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new IntrapostException('Invalid JSON response: ' . json_last_error_msg());
            }

            if (!empty($decoded['Errors'])) {
                throw new IntrapostApiException(
                    $decoded['Errors'],
                    $response->getStatusCode()
                );
            }

            return $decoded;
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();

            if ($statusCode === 401 || $statusCode === 403) {
                throw new IntrapostAuthenticationException(
                    'Authentication failed. Check your API key.',
                    $statusCode,
                    $e
                );
            }

            $body = $e->getResponse()->getBody()->getContents();
            $decoded = json_decode($body, true);

            if (is_array($decoded) && !empty($decoded['Errors'])) {
                throw new IntrapostApiException($decoded['Errors'], $statusCode, $e);
            }

            throw new IntrapostException(
                'API request failed: ' . $e->getMessage(),
                $statusCode,
                $e
            );
        } catch (GuzzleException $e) {
            throw new IntrapostException(
                'HTTP request failed: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
}

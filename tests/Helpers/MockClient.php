<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Tests\Helpers;

use Budgetlens\Intrapost\IntrapostClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class MockClient
{
    public static function create(array $responses = []): IntrapostClient
    {
        $mock = new MockHandler(
            array_map(
                fn (array $body) => new Response(200, ['Content-Type' => 'application/json'], json_encode($body)),
                $responses
            )
        );

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new GuzzleClient(['handler' => $handlerStack]);

        return new IntrapostClient(
            apiKey: 'test-api-key',
            accountNumber: 'test-account',
            baseUrl: 'https://api.intrapost.nl',
            httpClient: $httpClient,
        );
    }

    public static function withError(int $statusCode = 400, array $errors = ['Something went wrong']): IntrapostClient
    {
        $mock = new MockHandler([
            new Response($statusCode, ['Content-Type' => 'application/json'], json_encode(['Errors' => $errors])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new GuzzleClient(['handler' => $handlerStack]);

        return new IntrapostClient(
            apiKey: 'test-api-key',
            accountNumber: 'test-account',
            baseUrl: 'https://api.intrapost.nl',
            httpClient: $httpClient,
        );
    }
}

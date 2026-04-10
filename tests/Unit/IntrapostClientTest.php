<?php

declare(strict_types=1);

use Budgetlens\Intrapost\Exceptions\IntrapostApiException;
use Budgetlens\Intrapost\Exceptions\IntrapostAuthenticationException;
use Budgetlens\Intrapost\Exceptions\IntrapostException;
use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Resources\MailPieceResource;
use Budgetlens\Intrapost\Resources\OrderResource;
use Budgetlens\Intrapost\Resources\TrackTraceResource;
use Budgetlens\Intrapost\Resources\UtilityResource;
use Budgetlens\Intrapost\Tests\Helpers\MockClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

it('creates a client with default settings', function () {
    $client = new IntrapostClient(
        apiKey: 'my-key',
        accountNumber: 'my-account',
    );

    expect($client->getApiKey())->toBe('my-key')
        ->and($client->getAccountNumber())->toBe('my-account');
});

it('provides resource accessors', function () {
    $client = new IntrapostClient('key', 'acc');

    expect($client->mailPiece())->toBeInstanceOf(MailPieceResource::class)
        ->and($client->order())->toBeInstanceOf(OrderResource::class)
        ->and($client->trackTrace())->toBeInstanceOf(TrackTraceResource::class)
        ->and($client->utility())->toBeInstanceOf(UtilityResource::class);
});

it('throws IntrapostApiException on API errors', function () {
    $client = MockClient::withError(400, ['Field X is required', 'Invalid product']);

    $client->post('/mail-piece/create', []);
})->throws(IntrapostApiException::class);

it('includes all error messages in exception', function () {
    $client = MockClient::withError(400, ['Error 1', 'Error 2']);

    try {
        $client->post('/test', []);
    } catch (IntrapostApiException $e) {
        expect($e->getErrors())->toBe(['Error 1', 'Error 2'])
            ->and($e->getMessage())->toBe('Error 1; Error 2');
    }
});

it('throws IntrapostAuthenticationException on 401', function () {
    $mock = new MockHandler([
        new Response(401, [], json_encode(['message' => 'Unauthorized'])),
    ]);
    $httpClient = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new IntrapostClient('bad-key', 'acc', httpClient: $httpClient);

    $client->post('/test', []);
})->throws(IntrapostAuthenticationException::class);

it('throws IntrapostException on network error', function () {
    $mock = new MockHandler([
        new \GuzzleHttp\Exception\ConnectException(
            'Connection refused',
            new \GuzzleHttp\Psr7\Request('POST', '/test')
        ),
    ]);
    $httpClient = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new IntrapostClient('key', 'acc', httpClient: $httpClient);

    $client->post('/test', []);
})->throws(IntrapostException::class);

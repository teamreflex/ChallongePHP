<?php

namespace Reflex\Challonge;

use JsonException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Reflex\Challonge\Exceptions\InvalidFormatException;
use Reflex\Challonge\Exceptions\NotFoundException;
use Reflex\Challonge\Exceptions\ServerException;
use Reflex\Challonge\Exceptions\UnauthorizedException;
use Reflex\Challonge\Exceptions\UnexpectedErrorException;
use Reflex\Challonge\Exceptions\ValidationException;

class ClientWrapper
{
    protected ClientInterface $client;
    protected string $key;
    protected string $version;
    protected bool $mapOptions;

    /**
     * ClientWrapper constructor.
     * @param ClientInterface $client
     * @param string $key
     * @param string $version
     * @param bool $mapOptions
     */
    public function __construct(ClientInterface $client, string $key, string $version, bool $mapOptions = true)
    {
        $this->client = $client;
        $this->key = $key;
        $this->version = $version;
        $this->mapOptions = $mapOptions;
    }

    /**
     * Make a request to Challonge via the HTTP client.
     * @param string $method
     * @param string $uri
     * @param array $content
     * @return array
     * @throws InvalidFormatException
     * @throws JsonException
     * @throws NotFoundException
     * @throws ServerException
     * @throws UnauthorizedException
     * @throws UnexpectedErrorException
     * @throws ValidationException
     */
    public function request(string $method, string $uri, array $content = []): array
    {
        $base_uri = "https://api.challonge.com/v1/{$uri}.json";

        $response = $this->client->request($method, $base_uri, [
            'query'         => ['api_key' => $this->getKey()],
            'form_params'   => $content,
            'headers'       => $this->buildHeaders(),
            'http_errors'   => false,
            'verify'        => false,
        ]);

        return $this->handleErrors($response);
    }

    /**
     * Build any headers the requests need.
     * @return array
     */
    protected function buildHeaders(): array
    {
        return [
            'User-Agent' => "ChallongePHP/{$this->version} ChallongePHP (https://github.com/teamreflex/ChallongePHP, {$this->version})"
        ];
    }

    /**
     * Handles the response and throws errors accordingly.
     * @param ResponseInterface $response
     * @return array
     * @throws JsonException
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws InvalidFormatException
     * @throws ValidationException
     * @throws ServerException
     * @throws UnexpectedErrorException
     */
    protected function handleErrors(ResponseInterface $response): array
    {
        $statuscode = $response->getStatusCode();
        switch ($statuscode) {
            case 200:
                return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                break;
            case 401:
                throw new UnauthorizedException('Unauthorized (Invalid API key or insufficient permissions)');
                break;
            case 404:
                throw new NotFoundException('Object not found within your account scope');
                break;
            case 406:
                throw new InvalidFormatException('Requested format is not supported - request JSON or XML only');
                break;
            case 422:
                throw new ValidationException('Validation error(s) for create or update method');
                break;
            case ($statuscode >= 500 && $statuscode <= 511) :
                throw new ServerException('Something went wrong on Challonge\'s end. Error:'. $statuscode);
                break;
            default:
                $decodedResponse = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                throw new UnexpectedErrorException($decodedResponse);
                break;
        }
    }

    /**
     * Challonge requires input in a format such as ["tournament[name]" => "test tournament"].
     * This allows us to just do ["name" => "test tournament"].
     * @param array $options
     * @param string $scope
     * @return array
     */
    public function mapOptions(array $options, string $scope): array
    {
        if (! $this->mapOptions) {
            return $options;
        }

        $keys = array_map(fn (string $key) => "{$scope}[{$key}]", array_keys($options));
        return array_combine($keys, array_values($options));
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Get the underlying client.
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}

<?php

namespace Reflex\Challonge\Helpers;

use GuzzleHttp\Client;
use Reflex\Challonge\Exceptions\ServerException;
use Reflex\Challonge\Exceptions\NotFoundException;
use Reflex\Challonge\Exceptions\ValidationException;
use Reflex\Challonge\Exceptions\UnauthorizedException;
use Reflex\Challonge\Exceptions\InvalidFormatException;
use Reflex\Challonge\Exceptions\UnexpectedErrorException;

class Guzzle
{
    /**
     * Handles dynamic calls to the class.
     *
     * @param string $path
     * @param array $params
     * @return GuzzleHttps\Psr7\Response
     */
    public static function __callStatic($name, $params)
    {
        $path = $params[0];
        $content = @$params[1];

        if (is_null($content)) {
            $content = [];
        }

        if (empty(CHALLONGE_KEY)) {
            throw new UnauthorizedException('Must set an API key.');
        }

        $content['api_key'] = CHALLONGE_KEY;

        $base_uri = "https://api.challonge.com/v1/{$path}.json";
        $client = new Client();

        $response = $client->request($name, $base_uri, [
            'query'         => $content,
            'headers'       => self::buildHeaders(),
            'http_errors'   => false,
        ]);

        return self::handleErrors($response);
    }

    /**
     * Build any headers the requests need.
     *
     * @return array
     */
    private static function buildHeaders()
    {
        return [
            'User-Agent' => 'ChallongePHP/' . CHALLONGE_VERSION . ' ChallongePHP (https://github.com/teamreflex/ChallongePHP, ' . CHALLONGE_VERSION . ')'
        ];
    }

    /**
     * Handles the response and throws errors accordingly.
     *
     * @param $response GuzzleHttp\Psr7\Response
     * @return stdClass
     */
    private static function handleErrors($response)
    {
        switch ($response->getStatusCode()) {
            case 200:
                return json_decode($response->getBody());
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
            case 500:
                throw new ServerException('Something went wrong on Challonge\'s end');
                break;
            default:
                $errors = json_decode($response->getBody())->errors;
                throw new UnexpectedErrorException($errors);
                break;
        }
    }
}

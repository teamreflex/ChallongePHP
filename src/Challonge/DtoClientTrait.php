<?php

namespace Reflex\Challonge;

use Reflex\Challonge\DTO\BaseDto;

trait DtoClientTrait
{
    protected ClientWrapper $client;

    /**
     * Kinda gross but oh well.
     * @param ClientWrapper $client
     * @param array $data
     * @return static
     */
    public static function fromResponse(ClientWrapper $client, array $data): self
    {
        $dto = new self($data);
        $dto->setClient($client);
        return $dto;
    }

    /**
     * @param ClientWrapper $client
     */
    public function setClient(ClientWrapper $client): void
    {
        $this->client = $client;
    }
}

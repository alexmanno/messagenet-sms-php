<?php

declare(strict_types=1);

namespace AlexManno\Messagenet\Model;

class SmsMessage
{
    /** @var array|string[] */
    private $destinations;
    /** @var string */
    private $text;

    /**
     * @param array|string[] $destinations
     */
    public function __construct(array $destinations, string $text)
    {
        $this->destinations = $destinations;
        $this->text = $text;
    }

    /**
     * @return array|string[]
     */
    public function getDestinations(): array
    {
        return $this->destinations;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
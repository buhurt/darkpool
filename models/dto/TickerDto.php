<?php

namespace models\dto;

final class TickerDto
{
    private string $name;
    private string $dateFrom;

    public function __construct(array $params)
    {
        $this->name = $params['@attributes']['ticker'] ?? '';
        $this->dateFrom = $params['@attributes']['from'] ?? '';
    }

    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
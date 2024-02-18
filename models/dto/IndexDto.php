<?php

namespace models\dto;

final class IndexDto
{
    private string $moexId;
    private string $shortName;
    private string $dateFrom;

    public function __construct(array $params)
    {
        $this->moexId = $params['@attributes']['indexid'] ?? '';
        $this->shortName = $params['@attributes']['shortname'] ?? '';
        $this->dateFrom = $params['@attributes']['from'] ?? '';
    }

    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getMoexId(): string
    {
        return $this->moexId;
    }
}
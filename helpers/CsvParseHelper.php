<?php

namespace app\helpers;

class CsvParseHelper
{

    /**
     * @var string[] Заголовок из csv-файла
     */
    protected array $header;

    /**
     * Запоминание первой строки csv-файла (для корректной работы
     * TempLoadFromMailLoader)
     *
     * @param string[] $data
     */
    protected function setHeader($data): void
    {
        $this->header = $data;
    }

    /**
     * Получение данных из строки согласно заголовкам модели
     *
     * @param string[] $data
     *
     * @return string[]
     */
    protected function processData(array $data): array
    {
        return array_combine($this->header, \array_slice($data, 0, \count($this->header)));
    }
}

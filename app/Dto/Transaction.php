<?php

namespace App\Dto;

class Transaction extends BaseDto
{
    public string $id;
    public string $time;
    public string $description;
    public int $mcc;
    public bool $hold;
    public int $amount;
    public int $operationAmount;
    public int $currencyCode;
    public int $commissionRate;
    public int $cashbackAmount;
    public int $balance;
    public string $comment;

    public function getAmountText(): string
    {
        return abs($this->amount / 100) . ' гривен';
    }
}

<?php

namespace Src\Services;

class AlertService
{
    private TelegramService $telegram;
    private MonobankService $monobank;

    public const TYPE_ALERT_LIMIT = 1;

    public function __construct(TelegramService $telegram, MonobankService $monobankService)
    {
        $this->telegram = $telegram;
        $this->monobank = $monobankService;
    }

    public function sendLimitAlert(): bool
    {
        $transactions = $this->monobank->getTransactionsThisMonth();

        usort($transactions, function ($a, $b) {
            if ($a->amount === $b->amount) {
                return 0;
            }
            return ($a->amount < $b->amount) ? -1 : 1;
        });

        $sum = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->amount > 0) {
                $sum += abs(($transaction->amount / 100));
            }
        }

        $this->telegram->sendAlertMessage($sum, array_slice($transactions, 0, 5));

        return true;
    }
}

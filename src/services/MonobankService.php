<?php

namespace Src\Services;

use App\Dto\Transaction;
use Src\Components\MonoApi;

class MonobankService
{
    private MonoApi $monoApi;

    public function __construct(MonoApi $monoApi)
    {
        $this->monoApi = $monoApi;
    }

    public function getTransactionsThisMonth(): array
    {
        return $this->getTransactions(strtotime(date('Y-m-01 00:00:00')), time(), true);
    }

    public function getTransactions(int $from, int $to, $group = false): array
    {
        $response = $this->monoApi->getStatement($from, $to);
        $transactions = [];
        foreach ($response as $item) {
            $transactions[] = new Transaction($item);
        }

        if ($group) {
            foreach ($transactions as $key => $transaction) {
                foreach ($transactions as $key2 => $transaction2) {
                    if ($key !== $key2 && $transaction->description === $transaction2->description) {
                        $transactions[$key]->amount += $transaction2->amount;
                        unset($transactions[$key2]);
                    }
                }
            }
        }

        return $transactions;
    }

    public function calculate($any)
    {
        return clone $any;
    }
}

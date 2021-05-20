<?php

namespace Src\Services;

use App\Dto\Transaction;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class TelegramService
{
    public function __construct()
    {
        Request::initialize(new Telegram(env('TELEGRAM_API_KEY'), env('TELEGRAM_BOT_USERNAME')));
    }

    public function sendAlertMessage(float $sum, array $topSpends): bool
    {
        $message = 'За ' . date('d') . ' дней ты успел потратить ' . $sum . ' гривен' . PHP_EOL;
        $message .= 'Больше всего ты потратила на ... (барабанная дробь):' . PHP_EOL . PHP_EOL;
        /** @var Transaction $spend */
        foreach ($topSpends as $index => $spend) {
            $message .= $index + 1 . ')' . $spend->description . ' - ' . $spend->getAmountText() . PHP_EOL;
        }

        Request::sendMessage(['text' => $message, 'chat_id' => env('TELEGRAM_CHAT_ID')]);

        return true;
    }
}

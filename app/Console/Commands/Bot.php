<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Src\Components\MonoApi;

class Bot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $telegram = new Telegram(env('TELEGRAM_API_KEY'), env('TELEGRAM_BOT_USERNAME'));
//        $telegram->setWebhook('https://024862b2721a.ngrok.io/webhook');
//
//        Request::initialize($telegram);
//        $response = Request::sendMessage(['text' => 'test', 'chat_id' => '337680584']);
//        var_dump($response);
        $transactions = (new MonoApi())->getStatement(strtotime(date('Y-m-01 00:00:00')), time());

        usort($transactions, function ($a, $b) {
            if ($a->amount == $b->amount) {
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

        $telegram = new Telegram(env('TELEGRAM_API_KEY'), env('TELEGRAM_BOT_USERNAME'));
        Request::initialize($telegram);
        $message = 'Слышь, долбоеб, ты за 8 дней успел потратить ' . $sum . ' гривен' . PHP_EOL;
        $message .= 'Больше всего ты потратила на ... (барабанная дробь):' . PHP_EOL . PHP_EOL . PHP_EOL;
        $message .= '1) ' . $transactions[0]->description . ' - ' . abs($transactions[0]->amount / 100) . ' гривен' . PHP_EOL;
        $message .= '2) ' . $transactions[1]->description . ' - ' . abs($transactions[1]->amount / 100) . ' гривен' . PHP_EOL;
        $message .= '3) ' . $transactions[2]->description . ' - ' . abs($transactions[2]->amount / 100) . ' гривен' . PHP_EOL;
        Request::sendMessage(['text' => $message, 'chat_id' => 337680584]);

        return 0;
    }
}

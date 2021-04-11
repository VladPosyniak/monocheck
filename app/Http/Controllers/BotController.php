<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Telegram;

class BotController extends Controller
{
    public function index()
    {
        $telegram = new Telegram(env('TELEGRAM_API_KEY'), env('TELEGRAM_BOT_USERNAME'));

        // Set webhook
        $result = $telegram->setWebhook('https://024862b2721a.ngrok.io');
        if ($result->isOk()) {
            echo $result->getBotUsername();
        }
    }

    public function webhook(Request $request)
    {
//        Log::log('warning', print_r($request, true));
    }
}

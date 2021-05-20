<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Src\Components\MonoApi;
use Src\Services\AlertService;
use Src\Services\MonobankService;
use Src\Services\TelegramService;

class Bot extends Command
{
    protected $signature = 'command:bot';

    protected $description = 'Alerts from monobank';

    public function handle()
    {
        $alertService = new AlertService(
            new TelegramService(),
            new MonobankService(new MonoApi())
        );
        $alertService->sendLimitAlert();

        return 0;
    }
}

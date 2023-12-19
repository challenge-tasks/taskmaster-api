<?php

namespace App\Services\Report;

use Illuminate\Support\Facades\Http;

class ReportService
{
    public function sendReportToTelegram(string $text): void
    {
        $chatId = config('telegram-report.chat_id');
        $token = config('telegram-report.token');

        Http::get('https://api.telegram.org/bot' . $token . '/sendMessage', [
            'text' => $text,
            'chat_id' => $chatId
        ]);
    }
}

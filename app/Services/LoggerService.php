<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class LoggerService
{
    public function log(Throwable $e): void
    {
        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        $url = request()->url();

        $text = "```json\n{\n\t\"message\": \"$message\",\n\t\"file\": \"$file\",\n\t\"line\": \"$line\",\n\t\"url\": \"$url\"\n}```";

        $token = config('logger.token');
        $chatId = config('logger.chat_id');

        Http::get('https://api.telegram.org/bot' . $token . '/sendMessage', [
            'text' => $text,
            'chat_id' => $chatId,
            'parse_mode' => 'Markdown'
        ]);
    }
}

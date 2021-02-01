<?php
/**
 * Данный скрипт является примером Webhook
 */

use Tbot\TestApp\AppKeyboardMap;
use Tbot\TestApp\StartController;

$tbotKey = 'Ваш BOT API ключ';

$message_data = file_get_contents('php://input');
if (!empty($message_data)) {
    $keyboardMap = AppKeyboardMap::getKeyboardMap(); // Получаем карту кнопок
    if ($app = new StartController($message_data, $tbotKey, $keyboardMap))
    {
        $app->run();
    }
}
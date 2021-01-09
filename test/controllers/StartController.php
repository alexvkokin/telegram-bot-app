<?php

namespace Tbot\App;

use Tbot\Base\Controller;
use Tbot\Base\Helper;
use Tbot\Message\ApiRequest;
use Tbot\Message\Message;

class StartController extends Controller
{
    /**
     * Когда пользователь запускает бот<br>
     * Команда /start
     */
    public function actionStart()
    {
        $keyboard = [
            "keyboard" => [
                Helper::keyboard(static::$keyboardMap, get_class($this), 'actionStart'),
            ],
            "one_time_keyboard" => false, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
            "resize_keyboard" => true // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
        ];
        $api = new ApiRequest($this->token);
        $api->sendButtons($this->message->getChatId(), $keyboard, 'Добро пожаловать в наш бот');
    }


    /**
     * Желаем удачи
     * Команда /goodluck
     */
    public function actionGoodluck()
    {

        $api = new ApiRequest($this->token);
        $api->sendMessage($this->message->getChatId(),'Удачи в разработке');
    }

}
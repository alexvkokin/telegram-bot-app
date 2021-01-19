<?php


namespace Tbot\TestApp;


use Tbot\Base\Controller;
use Tbot\Base\Helper;
use Tbot\Message\ApiRequest;

class ProfileController extends Controller
{
    public function actionIndex()
    {
        $keyboard = [
            "keyboard" => [
                Helper::keyboard(static::$keyboardMap, get_class($this), 'actionIndex'),
            ],
            "one_time_keyboard" => false, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
            "resize_keyboard" => true // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
        ];
        $api = new ApiRequest($this->token);
        $api->sendButtons($this->message->getChatId(), $keyboard, 'Добро пожаловать в наш бот');
    }

    public function actionData()
    {
        $text = "Это ваш аккаунт, что с ним делать";
        $keyboard = [
            "inline_keyboard" => [
                [
                    [
                        "text" => "Вывести мои данные",
                        "callback_data" => "Tbot/App/StartController|actionStart|id=5|parent_id=1",
                    ],
                ]
            ]
        ];
        $api = new ApiRequest($this->token);
        $api->sendButtons($this->message->getChatId(), $keyboard, $text);
    }
}
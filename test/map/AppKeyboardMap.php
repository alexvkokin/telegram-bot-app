<?php

namespace Tbot\App;

use Tbot\Base\KeyboardMap;

class AppKeyboardMap extends KeyboardMap
{
    protected static $keyboardMap =
    [
        'Tbot/App/StartController' => [
            'actionStart' => [
                "setButton" =>[
                    ["icon" => 'E284B9', "text" => 'Информация', "controller" => "Tbot/App/InformationController", "action" => "actionIndex"],
                    ["icon" => 'F09F91A4', "text" => 'Профайл', "controller" => "Tbot/App/ProfileController", "action" => "actionIndex"],
                    ["icon" => 'F09F92BC', "text" => 'Корзина', "controller" => "Tbot/App/CartController", "action" => "actionIndex"]
                ],
                "inline" => [
                    ["command" => '/start']
                ]
            ],
            'actionGoodluck' => [
                "inline" => [
                    ["command" => '/goodluck']
                ]
            ]
        ],
        'Tbot/App/ProfileController' => [
            'actionIndex' => [
                "setButton" =>[
                    ["icon" => 'E284B9', "text" => 'Информация о профайле', "controller" => "Tbot/App/ProfileController", "action" => "actionData"],
                    ["icon" => 'E2988E', "text" => 'Мой контакт', "controller" => "Tbot/App/ProfileController", "action" => "actionContact", 'params' => ["request_contact" => true]],
                    ["icon" => 'E29780', "text" => 'Назад', "controller" => "Tbot/App/StartController", "action" => "actionStart"],
                ],
                "inline" => [
                    ["command" => '/profile']
                ]
            ]
        ],
    ];
}
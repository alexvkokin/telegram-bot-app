<?php

namespace Tbot\TestApp;

use Tbot\Base\KeyboardMap;

class AppKeyboardMap extends KeyboardMap
{
    protected static $keyboardMap =
    [
        'Tbot/TestApp/StartController' => [
            'actionStart' => [
                "setButton" =>[
                    ["icon" => 'F09F91A4', "text" => 'Профайл', "controller" => "Tbot/TestApp/ProfileController", "action" => "actionIndex"],
                ],
                "inline" => [
                    ["command" => '/start']
                ]
            ],
            'actionGoodluck' => [
                "inline" => [
                    ["command" => '/goodluck']
                ]
            ],
        ],
        'Tbot/TestApp/ProfileController' => [
            'actionIndex' => [
                "setButton" =>[
                    ["icon" => 'E284B9', "text" => 'Информация о профайле', "controller" => "Tbot/TestApp/ProfileController", "action" => "actionData"],
                    ["icon" => 'E2988E', "text" => 'Мой контакт', "controller" => "Tbot/TestApp/ProfileController", "action" => "actionContact", 'params' => ["request_contact" => true]],
                    ["icon" => 'E29780', "text" => 'Назад', "controller" => "Tbot/TestApp/StartController", "action" => "actionStart"],
                ],
                "inline" => [
                    ["command" => '/profile'],
                    ["callback" => 'profileGet']
                ]
            ]
        ],
    ];
}
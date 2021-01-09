<?php

namespace Tbot\Base;

use Tbot\Message\Callback;
use Tbot\Message\IKeyboard;
use Tbot\Message\InlineKeyboard;
use Tbot\Message\Keyboard;
use Tbot\Message\Message;

class KeyboardMap
{

    /**
     * Карта кнопок и команд для контроллеров и экшенов.
     * Описание экшена может подразделяться на две части:
     * - setButton - Кнопки устанавливаются в бот телеграма при выполнении данного экшена. Метод для сборки кнопок Helper::keyboard()<br>
     *  Ключи:<br>
     *  <b>icon</b> - код иконки кнопки, <a href='https://apps.timwhitlock.info/emoji/tables/unicode'>источник</a><br>
     *  <b>text</b> - Название кнопки<br>
     *  <b>controller</b> - Какой контроллер вызывается, формат Tbot/App/StartController<br>
     *  <b>action</b> - Какой экшен вызывается<br>
     *  <b>params</b> - Дополнительные параметры для кнопки<br>
     *
     * - inline - команды при выполнении которых из бота телеграм, запускается указанный экшен<br>
     *  <b>command</b> - Команад, например /start<br>
     * @var array
     */
    protected static $keyboardMap =
    [
        'Tbot/App/StartController' => [
            'actionStart' => [
                "setButton" =>[
                    ["icon" => 'E284B9', "text" => 'Информация', "controller" => "Tbot/App/InformationController", "action" => "actionIndex", "params" => ["request_contact" => true, "request_location" => false]],
                    ["icon" => 'F09F91A4', "text" => 'Профайл', "controller" => "Tbot/App/ProfileController", "action" => "actionIndex"],
                ],
                "inline" => [
                    ["command" => '/start'],
                ]
            ]
        ],
        'Tbot/App/InformationController' => [
            'actionIndex' => [
                "setButton" =>[
                ],
                "inline" => [
                    ["command" => '/information']
                ]
            ]
        ],
        'Tbot/App/CartTestController' => [

        ],
    ];


    /**
     * Метод возвращает карту кнопок для контроллеров
     * @return array
     */
    public static function getKeyboardMap()
    {
        return static::$keyboardMap;
    }


    //нужно проверить карту на совпадение названий кнопок
    public static function debag(){

    }


    /**
     * Метод возвращаем в виде массива контроллер и экшен, который соответствует команде $command в карте кнопок и команд $keyboardMap<br>
     * Пример<br>
     * list($controller, $action) = KeyboardMap::getControllerAction(static::$keyboardMap, $command);
     * @param array $keyboardMap <p>Карта кнопок и команд</p>
     * @param IKeyboard $message <p>Объект сообщения полученного от бота</p>
     * @return array|false<p>
     * Результатом будет массив состоящий из трех элементов<br>
     * [0 => controllerName, 1 => actionName, 2 => params]
     * </p>
     */
    public static function getControllerAction(array $keyboardMap, IKeyboard $message)
    {

        if ($message instanceof Callback) {
            $items = explode("|", $message->getCommand());
            if (2 <= count($items)) {
                return [
                    static::backSlashesPathController(array_shift($items)),
                    array_shift($items),
                ];
            }
        }
        else {
            foreach ($keyboardMap as $controller_key => $controller_data) {
                if (!empty($controller_data)) {
                    foreach ($controller_data as $action_key => $action_data) {

                        if (!empty($action_data['setButton'])){
                            foreach ($action_data['setButton'] as $button) {
                                if($message->getCommand() == static::getCommand($button, 'button')){
                                    return [
                                        static::backSlashesPathController($button['controller']),
                                        $button['action'],
                                    ];
                                }
                            }
                        }

                        if (!empty($action_data['inline'])){
                            foreach ($action_data['inline'] as $inline) {
                                if($message->getCommand() == static::getCommand($inline, 'inline')){
                                    return [
                                        static::backSlashesPathController($controller_key),
                                        $action_key,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * Метод заменяет прямые слеши на обратные в пути контроллера
     * @param string $str
     * @return string
     */
    public static function backSlashesPathController(string $str)
    {
        return str_replace('/', '\\', $str);
    }

    /**
     * Метод заменяет обратные слеши на прямые в пути контроллера
     * @param string $str
     * @return string
     */
    public static function rightSlashesPathController(string $str)
    {
        return str_replace('\\', '/', $str);
    }

    /**
     * Метод возвращаем массив из всех кнопок и команд которые будут найдены в карте кнопок и команд
     * @param array $keyboardMap <p>Карта кнопок и команд</p>
     * @return array
     */
    public static function getCommands(array $keyboardMap)
    {
        $commands = [];

        foreach ($keyboardMap as $controller_key => $controller_data) {
            if (!empty($controller_data)) {
                foreach ($controller_data as $action_key => $action_data) {

                    if (!empty($action_data['setButton'])){
                        foreach ($action_data['setButton'] as $button) {
                            if($command = static::getCommand($button, 'button')){
                                $commands[] = $command;
                            }
                        }
                    }

                    if (!empty($action_data['inline'])){
                        foreach ($action_data['inline'] as $inline) {
                            if($command = static::getCommand($inline, 'inline')){
                                $commands[] = $command;
                            }

                        }
                    }
                }
            }
        }

        return $commands;
    }


    /**
     * Метод возвращает команду в едином формате, из полученных данных
     * @param array $keyboard <p>Массив содержащий данные кнопки или команды</p>
     * @param string $type <p>Тип команды:<br>
     *  - button, кнопка<br>
     *  - inline, команда<br>
     * </p>
     * @return false|string
     */
    public static function getCommand(array $keyboard, string $type)
    {
        if ($type == 'button' && !empty($keyboard['icon']) && !empty($keyboard['text'])) {
            return trim(hex2bin($keyboard['icon']) . ' ' . $keyboard['text']);
        }
        elseif ($type == 'inline' && !empty($keyboard['command'])) {
             return trim($keyboard['command']);
        }

        return false;
    }
}
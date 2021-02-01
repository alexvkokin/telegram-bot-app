<?php

namespace Tbot\Base;

class Helper
{

    /**
     * Метод позволяет из всей карты команд и кнопок, выбрать необходимые для текущего контроллера и экшена,
     * чтобы отправить их в бот
     * @param array $keyboardMap <p>Карта команд и кнопок, описана в Контроллере</p>
     * @param string $controller <p>Контроллер для которого нужно собрать кнопки</p>
     * @param string $action <p>Экшен для которого нужно собрать кнопки</p>
     * @return array
     */
    public static function keyboard($keyboardMap, $controller, $action)
    {
        // когда просматриваю массив надо смотреть принадлежит ли контроллер текепму классу
        $items = [];
        $controller = KeyboardMap::rightSlashesPathController($controller);
        if(isset($keyboardMap[$controller][$action]['setButton'])){
            foreach ($keyboardMap[$controller][$action]['setButton'] as $button) {
                $params = ['text' => KeyboardMap::getCommand($button, 'button')];
                if (isset($button['params'])){
                    $params = array_merge($button['params'], $params);
                }
                $items[] = $params;
            }
        }

        return $items;
    }

    /**
     * Метод расшифровываем json строку
     * @param string $str
     * @return mixed
     */
    public static function jsonDecode($str)
    {
        //print $str;
        $arr = json_decode($str, true);

        $error = json_last_error();
        if ($error){
            switch ($error) {
                case JSON_ERROR_NONE:
                    $error .= ' - Ошибок нет';
                    break;
                case JSON_ERROR_DEPTH:
                    $error .= ' - Достигнута максимальная глубина стека';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error .= ' - Некорректные разряды или несоответствие режимов';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $error .= ' - Некорректный управляющий символ';
                    break;
                case JSON_ERROR_SYNTAX:
                    $error .= ' - Синтаксическая ошибка, некорректный JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $error .= ' - Некорректные символы UTF-8, возможно неверно закодирован';
                    break;
                default:
                    $error .= ' - Неизвестная ошибка';
                    break;
            }
        }

        return $arr;
    }

}
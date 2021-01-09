<?
/*
Текстовые сообщения
*/

namespace Tbot\Message;


class Keyboard extends Message implements IKeyboard
{
    /**
     * Получить bot_command
     * @return string|bool
     * Возвращает строку вида /start или false
     */
    public function getCommand() : string
    {
        if (isset($this->data['message']['text'])) {
            return $this->data['message']['text'];
        }
        return false;
    }
}
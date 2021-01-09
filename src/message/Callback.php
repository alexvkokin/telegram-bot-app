<?
/*
Текстовые сообщения
*/

namespace Tbot\Message;


class Callback extends Message implements IKeyboard
{

	/**
     * Получить message_id
     * @return string|bool
     */
	public function getMessageId()
	{
        if (isset($this->data['message']['text'])) {
            return $this->data['callback_query']['message']['message_id'];
        }
		return false;
	}


    /**
     * Получить callback_data
     * @return string
     * Возвращает массив вида ["controller"=>"account","action"=>"edit',"params"=>["id"=>1]]
     */
	public function getCommand() : string
	{
		return $this->data['callback_query']['data'];
	}


	/**
     * Получить пользователя отправителя
     * @return string
     */
	public function getChatId() : string
	{
		return $this->data['callback_query']['from']['id'];
	}


	/**
     * Текст сообщения
     * @return string/boolean
     */
	public function getText()
	{
		$text = false;
		if(isset($this->data['callback_query']['message']['text'])){
			$text = $this->data['callback_query']['message']['text'];
		}
		return $text;
	}

    /**
     * Устанавливаем текст сообщения
     * @param string $value
     */
    public function setText(string $value)
    {
        $this->data['message']['text'] = $value;
    }


}
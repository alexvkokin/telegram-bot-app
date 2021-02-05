<?
/*
Текстовые сообщения
*/

namespace Tbot\Message;


class Callback extends Message implements IKeyboard
{

	/**
     * Получить message_id
     * @return string
     */
	public function getMessageId()
	{
        if (isset($this->data['callback_query']['message']['message_id'])) {
            return $this->data['callback_query']['message']['message_id'];
        }
		return '';
	}


    /**
     * Получить callback_data
     * @return string
     * Возвращает массив вида ["controller"=>"account","action"=>"edit',"params"=>["id"=>1]]
     */
	public function getCommand()
	{
		return $this->data['callback_query']['data'];
	}


	/**
     * Получить пользователя отправителя
     * @return string
     */
	public function getChatId()
	{
		return $this->data['callback_query']['from']['id'];
	}


	/**
     * Текст сообщения
     * @return string
     */
	public function getText()
	{
		if(isset($this->data['callback_query']['message']['text'])){
			return $this->data['callback_query']['message']['text'];
		}
		return '';
	}

    /**
     * Устанавливаем текст сообщения
     * @param string $value
     */
    public function setText($value)
    {
        $this->data['message']['text'] = $value;
    }


}
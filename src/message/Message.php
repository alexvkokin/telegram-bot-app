<?
/*
Распределяет полученное сообщения нужному классу
*/

namespace Tbot\Message;


use Tbot\Base\Helper;


class Message implements IMessage
{
    /**
     * Переменная хранит массив данных сообщения
     * @var array
     */
	protected $data;


    /**
     * Перменная хранит сообщение в сыром виде, т.е. в виде строки
     * @var string
     */
	protected $data_raw;

	
	/**
     * Метод принимает полученное сообщение от телеграм бота и возвращает объект класса к типу которого относится данное сообщение<br>
     * <b>ВАЖНО: перед отправление данных обязательно пропустить их через санитизатор, чтобы устранить уязвимый код, например HTMLPurifier</b>
     * @param string $data_raw <p>Сообщение полученное от бота в виде строки JSON формата </p>
     * @param null|array $keyboardMap <p>Массив состоящий из списка команд (в виде строк), если текст
     * сообщения (в первом параметре $data_raw) совпадает с одной из команд списка, будем считать,
     * что это сообщение типа Keyboard</p>
     * @return Text|Keyboard|Callback|bool
     */
	public static function get(string $data_raw, array $keyboardMap = null)
	{
	    if ($data_raw) {

            $data = Helper::jsonDecode($data_raw);

            if ($data) {
                if (isset($data['message']))
                {
                    //Текстовое сообщение
                    if (isset($data['message']['text']) && ! isset($data['message']['entities']))
                    {
                        //Если к контроллере прописаны кнопки, то смотрим есть ли в них пришедшее сообщение
                        if (!empty($keyboardMap) && in_array(trim($data['message']['text']), $keyboardMap)) {
                            return new Keyboard($data_raw);
                        }
                        else {
                            return new Text($data_raw);
                        }
                    }

                    //Inline команды в боте /start
                    if (isset($data['message']['text']) && isset($data['message']['entities'][0]['type']) && $data['message']['entities'][0]['type'] == 'bot_command') {
                        return new Keyboard($data_raw);
                    }
                }

                //callback запросы
                elseif (isset($data['callback_query'])) {
                    return new Callback($data_raw);
                }
            }
        }
		
		return false;
	}
	
	
	/**
     * Конструктор
     * @param string $data <p>Сообщение из телеграм бота в виде строки</p>
     * @return boolean
     */
	public function __construct(string $data)
	{
	    if ($data) {
            $this->data_raw = $data;
            $this->data = json_decode($data, true);
            return true;
        }

		return true;
	}

	
	/**
     * Получить message_id
     * @return string
     */
	public function getMessageId()
	{
		return $this->data['message']['message_id'];
	}

	
	/**
     * Получить пользователя отправителя
     * @return string
     */
	public function getChatId() : string
	{
		return $this->data['message']['from']['id'];
	}

	
	/**
     * Текст сообщения
     * @return string|boolean
     */
	public function getText()
	{
		$text = false;
		if(isset($this->data['message']['text'])){
			$text = $this->data['message']['text'];
		}
		return $text;
	}

	/**
     * Устанавливаем текст сообщения
     * @param string $value
     * @return bool
     */
	public function setText(string $value)
	{
        $this->data['message']['text'] = $value;
	}


	/**
     * Текст сообщения
     * @return string
     */
	public function getDataRaw()
	{
		return $this->data_raw;
	}
}
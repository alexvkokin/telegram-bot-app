<?
namespace Tbot\Message;

class ApiRequest
{
	protected $url_prefix = 'https://api.telegram.org/bot';
	protected $token;

    /**
     * ApiRequest constructor.
     * @param string $token
     */
	public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Метод выполняет запрос к api telegram bot
     * @param string $method <p>Принимает название метода запроса к api, например sendMessage/sendVideo/getUpdates/editMessageText и др.</p>
     * @param null|array $params <p>Дополнительные параметры передаваемые к api</p>
     * @return array
     */
	protected function query(string $method, array $params = null) : array
	{
		$url = "{$this->url_prefix}";

		$url .= "{$this->token}";

		$url .= "/{$method}";

		if ( ! is_null($params)) {
			$url .= "?" . http_build_query($params);
		}

		//print  $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);

        return array(
            "content" => curl_exec($ch),
            "http_code" => curl_getinfo($ch, CURLINFO_HTTP_CODE)
        );
	}

    /**
     * Запрос к telegram api на получение последних сообщений из бота для указанного пользователя
     * @param int $chat_id
     * @param int|null $offset
     * @param int|null $limit
     * @return array
     */
	public function getUpdates(int $chat_id, $offset = null, int $limit = null) : array
	{
		$result = [];
		$params = [];

		if($offset){
			$params['offset'] = $offset;
		}
		if($limit){
			$params['limit'] = $limit;
		}
		
		$response = $this->query('getUpdates', $params);
		
		if($response->result){
			foreach($response->result as $item){
				if($chat_id && $item->message->chat->id == $chat_id){
					$result[] = $item;
				}elseif(! $chat_id){
					$result[] = $item;
				}
			}
		}
		
		return $result;
	}

    /**
     * Метод отправляет запрос к api телеграм бот, <b>на редактирование указанного в параметрах сообщения</b> для указанного пользователя
     * @param int $chat_id <p>Уникальный id чата пользователя</p>
     * @param int $message_id <p>Уникальный id сообщения которое необходимо изменить</p>
     * @param string $text <p>Текст сообщения на которое нужно заменить</p>
     * @param string $parse_mode <p>Тип форматирования текста:<b>html</b>, <b>markdown</b></p>
     * @return array|false
     */
	public function editMessage(int $chat_id, int $message_id, string $text, string $parse_mode = '')
	{
		if($chat_id && $message_id && $text){
			return  $this->query('editMessageText', [
				'message_id' => $message_id,
				'chat_id' => $chat_id,
				'text' => $text,
				'parse_mode' => $parse_mode,
			]);
		}
		
		return false;
	}

    /**
     * Метод отправляет запрос к api телеграм бот <b>на отправку сообщения</b> для указанного пользователя
     * @param int $chat_id <p>Уникальный id чата пользователя </p>
     * @param string $text <p>Текст посылаемый пользователю</p>
     * @param string|null $parse_mode <p>Тип форматирования текста:<b>html</b>, <b>markdown</b></p>
     * @return array|false
     */
	public function sendMessage(int $chat_id, string $text, string $parse_mode = null)
	{
		if($chat_id && $text){
			return $this->query('sendMessage', [
				'chat_id' => $chat_id,
				'text' => $text,
				'parse_mode' => $parse_mode,
			]);
		}
		
		return false;
	}

    /**
     * Метод отправляет запрос к api телеграм бот на <b>установку кнопок</b> для указанного пользователя
     * @param int $chat_id <p>Уникальный id чата пользователя </p>
     * @param array $keyboard <p>Массив состоящий из параметров для вывода кнопок у пользователя бота</p>
     * @param string $text <p>Текст</p>
     * @param string $parse_mode <p>Тип форматирования текста: <b>html</b>, <b>markdown</b></p>
     * @return array|false
     */
	public function sendButtons(int $chat_id, array $keyboard, string $text = 'setButton', string $parse_mode = '')
	{
		if($chat_id && $keyboard){
			$encodedKeyboard = json_encode($keyboard);
			return $this->query('sendMessage', [
				'chat_id' => $chat_id,
				'reply_markup' => $encodedKeyboard,
				'text' => $text,
				'parse_mode' => $parse_mode,
			]);
		}
		
		return false;
	}

    /**
     * Метод отправляет запрос к api телеграм бот на <b>Отправку видео</b> для указанного пользователя
     * @param int $chat_id <p>Уникальный id чата пользователя </p>
     * @param string $video_url <p>URL на видео, которое нужно передать </p>
     * @return array|false
     */
	public function sendVideo(int $chat_id, string $video_url)
	{
		if($chat_id && $video_url){
			return $this->query('sendVideo', [
				'chat_id' => $chat_id,
				'video' => $video_url,
			]);
		}
		
		return false;
	}
}
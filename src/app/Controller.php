<?
namespace Tbot\Base;

use Tbot\Message\IKeyboard;
use Tbot\Message\IMessage;
use Tbot\Message\Keyboard;
use Tbot\Message\Message;
use Tbot\Message\Callback;
use Tbot\Message\Text;

class Controller
{
    /**
     * Статическая переменная содержит карту команд и кнопок:<br>
     * @var array
     */
    protected static $keyboardMap;


    /**
     * Токен для работы с api
     * @var string
     */
    protected $token;


    /**
     * Переменная содержит принятое сообщение от бота телеграм в виде объекта
     * @var Message
     */
    protected $message;


    /**
     * Controller constructor.
     * @param string|Message $message <p>принятое сообщение от бота телеграм, может быть в виде строки или объекта класса</p>
     * @param string $token <p>Ключ для бота</p>
     * @param null|array $keyboardMap <p>Переменная содержит описание кнопок под полем ввода</p>
     */
    public function __construct($message, $token, array $keyboardMap = null)
    {
        if (!empty($token)) {
            $this->token = $token;
        }

        //Сбор $keyboardMap
        if (!empty($keyboardMap)) {
            static::$keyboardMap = $keyboardMap;
        }

        //Получаем сообщение в виде объекта класса, просто приравниваем как есть
        if($message instanceof Message){
            $this->message = $message;
        }
        //Получаем сообщение в виде строки, надо преобразовать в объект
        elseif (!empty($message)){
            $this->message = \Tbot\Message\Message::get($message, KeyboardMap::getCommands(static::$keyboardMap));
        }
    }


    /**
     * Запуск контроллера и экшена, достаем из сообщения полученного от телеграм бота
     * Т.е. по сути это и есть роутинг, при нахождении нужного контроллера и экшена,
     * создается экземпляр данного контроллера, который наследует все поля от стартового контроллера
     * @return bool
     */
    public function run()
    {
        if ($this->message instanceof Message)
        {
            list($controller, $action, $callbackParams) = KeyboardMap::getControllerAction(static::$keyboardMap, $this->message);

            // Запуск
            if ($controller && class_exists($controller)) {
                $class_run =  new $controller($this->message, $this->token);
                if ($action && method_exists($class_run, $action)) {
                    $class_run->$action($callbackParams);
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Метод возвращает объект сообщение полученного от бота телеграм
     * @return bool|callable|Callback|Keyboard|Message|Text
     */
    public function getMessage()
    {
        return $this->message;
    }
}
# Конструктор для telegram ботов

Для установки, запустите команду в командной строке 

`composer require alexvkokin/telegram-bot-app`

## Маршрутизатор

### KeyboardMap
Маршрутизатором в проекте служит статическая переменная `keyboardMap` класса  `KeyboardMap`, далее будем называть ее `картой кнопок`, она описывает полные пути до контроллеров и методов, а также команды которые их запускают.

Например

```php
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
        ]
    ],
    'Tbot/TestApp/ProfileController' => [
        'actionIndex' => [
            "setButton" =>[
                ["icon" => 'E284B9', "text" => 'Информация о профайле', "controller" => "Tbot/TestApp/ProfileController", "action" => "actionData"],
                ["icon" => 'E2988E', "text" => 'Мой контакт', "controller" => "Tbot/TestApp/ProfileController", "action" => "actionContact", 'params' => ["request_contact" => true]],
                ["icon" => 'E29780', "text" => 'Назад', "controller" => "Tbot/TestApp/StartController", "action" => "actionStart"],
            ],
            "inline" => [
                ["command" => '/profile']
            ]
        ]
    ],
];
```

Как видно из примера, каждый элемент массива, это описание контроллера:

`'Tbot/TestApp/StartController'` - полный путь до контроллера. Обязательно используйте namespace при описании своих классов, чтобы избежать конфликтов

`'actionStart'` - название метода в классе `'Tbot/TestApp/StartController'`

`'setButton'` - описание кнопок `keyboard` которые будут установлены в боте, при выполнении метода `'actionStart'`. Однако для установки кнопок не достаточно просто описать из в карте кнопок, нужно также явно задать запрос на их установку из метода класса (для более детальной информации, смотрите код метода `'Tbot/TestApp/StartController'->'actionStart'`). Формат кнопки:
 - icon - код иконки кнопки, <a href='https://apps.timwhitlock.info/emoji/tables/unicode'>кода кнопок</a>. Кода берем из столбца Bytes, также из кода необходимо удалить все `\x`, например код `\xC2\xAE`, должен быть преобразован в `C2AE`
 - text - Название кнопки
 - controller - Какой контроллер вызывается, при клике по этой кнопке в боте
 - action - Какой экшен вызывается, при клике по этой кнопке в боте
 - params - Дополнительные параметры для кнопки, например если мы хотим, чтобы при клике на кнопку в боте отправлялся контакт пользователя, в параметрах указываем `'params' => ["request_contact" => true]`


`'inline'` - описываются inline команды, при выполнении из телеграм бота которых, будет запускаться метод `'actionStart'` соответствующего класса


**Чтобы описать собственную карту кнопок**, создайте новый класс, допустим `AppKeyboardMap`, расширьте его базовым классом `KeyboardMap` и в нем опишите все классы и методы вашего приложения. Смотрите пример в папке `test/map/AppKeyboardMap.php`


### Работа с контроллером
Выше мы создали собственный класс `AppKeyboardMap` с описанием наших контроллеров и методов, а также кнопок и команд с ними связанных. Далее нам нужно создать все эти контроллеры и методы.

Создадим класс `StartController` расширим его базовым классом `Controller`, далее создадим метод `actionStart()`. При срабатывании данного метода, будем устанавливать кнопки в телеграм боте пользователя. Какие кнопки будут устанавливаться уже описано в нашей карте кнопок (смотрите разделом выше). Пример класса StartController смотрите в папке `test/controllers/StartController.php`

```php
public function actionStart()
{
    $keyboard = [
        "keyboard" => [
            Helper::keyboard(static::$keyboardMap, get_class($this), 'actionStart'),
        ],
        "one_time_keyboard" => false,
        "resize_keyboard" => true
    ];
    $api = new ApiRequest($this->token);
    $api->sendButtons($this->message->getChatId(), $keyboard, 'Добро пожаловать в наш бот');
}
```

Как вы видите, выполняется запрос к api телеграм бота на установку кнопок. Конструирование кнопок проходит с помощью хелпера `Helper::keyboard`

```php
Helper::keyboard(static::$keyboardMap, get_class($this), 'actionStart'),
```

В него мы отправляем нашу карту, название нашего класса и метода. В результате если в карте кнопок по указанному адресу присутствует опция установки кнопок `setButton`, то все описанные в ней кнопки будут установлены в бот пользователя


## Запуск проекта

Первым делом необходимо настроить скрипт через Webhook, который будет отлавливать сообщения от телеграм бота. В данный скрипт прописываем следующий код

```php
$message_data = file_get_contents('php://input'); // получаем сообщение от телеграм бота
$tbot_key = 'API Ключ от вашего бота';

$keyboardMap = \Tbot\TestApp\AppKeyboardMap::getKeyboardMap(); // Получаем вашу карту кнопок
if ($app = new \Tbot\TestApp\StartController($message_data, $tbot_key, $keyboardMap))
{
    $app->run();
}
```

`\Tbot\TestApp\AppKeyboardMap` - Ваша карта, как создать описано в разделе **KeyboardMap**
`\Tbot\TestApp\StartController` - Ваш стартовый контроллер, как создать описано в разделе **Работа с контроллером**

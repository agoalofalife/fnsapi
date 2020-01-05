# Fns API SOAP
Пакет предоставляет возможность работы по проверке чеков через [официальное API](https://www.nalog.ru/files/kkt/pdf/%D0%A2%D0%B5%D1%85%D0%BD%D0%B8%D1%87%D0%B5%D1%81%D0%BA%D0%B8%D0%B5%20%D1%83%D1%81%D0%BB%D0%BE%D0%B2%D0%B8%D1%8F%20%D0%B8%D1%81%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F.pdf) ФНС (Федеральной Налоговая службы России).


- [Установка](#Installation)
- [Аутентификация](#auth)
- [Создать Ticket (кассовый чек)](#create_ticket)
- [Создание SOAP Client](#create_soap_client)
- [Проверка или информация по чеку](#check_or_info_ticket)
- [Алгоритмы обработки timeout](#strategy_timout)
- [Дополнительная информация от автора](#note_from_author)
- [Угостить чаем 😌](#donate)

<a name="Installation"></a>
## Установка
`composer require agoalofalife/fnsapi`

<a name="auth"></a>

## Аутентификация
Для начала работы с сервисом, вам необходимо получить временный токен.
На момент написания документации, время его жизни был один час.

```php
$cachePSR16 = new Sarahman\SimpleCache\FileSystemCache(__DIR__);
$master_token = 'ourMasterToken';
$auth = new Fns\Auth\AuthRequest($master_token, $cachePSR16);
$auth->authenticate();
```

Вам надо передать мастер токен, который выдали в ФНС и  хранилище, которое реализует PSR-16.

В хранилище будет своевременно обновляться новый токен,  [который реализуется в PSR-16](https://github.com/php-fig/simple-cache/blob/master/src/CacheInterface.php#L34) параметром ttl.

PSR-16 позволяет использовать пакет во многих современных фреймворках и т.д

Класс имеет исключение UnexpectedValueException  в случае если токен не будет получен.

#### Временная зона
Время жизни токена устанавливается в соответствии с вашей настройкой [PHP: Настройка во время выполнения - Manual](https://www.php.net/manual/ru/datetime.configuration.php#ini.date.timezone)
date.timezone
Рекомендуется проверить ваш файл php.ini и установить соответствующую временную зону.

<a name="create_ticket"></a>
##  Создать Ticket (кассовый чек)
Для того чтобы получить информацию по чеку, вам надо создать объект класса `Ticket`
Подробнее о данных написано в коде и официальной документации ФНС.

```php
$ticket = new \Fns\Ticket();
$ticket->setDate('2019-12-16T12:22:00');
$ticket->setFiscalDocumentId(11222);
$ticket->setFiscalSign(111111122);
$ticket->setFn(1234000123440000);
$ticket->setSum(11220);
$ticket->setTypeOperation(1);
// случайные данные
```
<a name="create_soap_client"></a>
## Создание SOAP Client
Отлично мы создали чек и заполнили его данными. 
Теперь надо создать SOAP client.
Это очень осведомленный клиент, он знает что мы работает с ФНС, поэтому он требует:
- Уникальную строку, для сессии. Это строка будет кодироваться  в base64(который примерно на 30% увеличивают длину), не должна превышать 160 символов.
- И то же хранилище данных, PSR-16, тот созданный объект, который передавался для получения временного токена.

```php
$client = new \Fns\ClientSoap('uniqueStringOrNumber', $cache);
```
<a name="check_or_info_ticket"></a>
## Проверка или информация по чеку
Отлично, вы создали чек и клиента.
И уже все готово для работы!
Осталось уже выбрать:
- Проверить чек `\Fns\GetMessage\CheckTicketRequest`
- Получить по нему подробную информацию `\Fns\GetMessage\GetTicketRequest`


```
Для справки
API ФНС реализованно через асинхроннсть.
Для начала вам надо отправить запрос для получения `MessageId`
с информацией по чеку и типом(проверка или полной информацией по чеку)
Далее новым запросом с  `MessageId` получить информацию.

Нюанс заключается в том, что последующий запрос, имеет время исполнения и сетевые задержки.
К примеру вы можете получить информацию по `MessageId` в течении 60 секунд.
Для своей реализации обработки timeout читайте в разделе  Алгоритмы обработки timeout
```

- создание обьекта с типом запроса
- Внедрить свою стратегию обработки timeout реализующий интерфейс  `Fns\Contracts\TimeoutStrategyHandler`

- Создать объект `SendMessageRequest` передать клиента которого мы создали ранее и объект конкретного запроса.
- Передать чек
- И выполнить запрос `execute` который возвращает интерфейс `ResponseSendMessage`
- Получить информацию по запросу код и тело ответа.

```php
$message = new \Fns\GetMessage\GetTicketRequest();
$message->setTimeoutStrategy(new ExponentialBackoff($message));

$request = new SendMessageRequest($client, $message);
$request->setTicket($ticket);
$response = $request->execute();

if ($response->getCode() === 200) {
    dump(json_decode($response->getBody()));
}
```

Ответ возвращается в json.

####  Проверка чека  CheckTicketRequest
Для получения информации что чек корректен, достаточно сравнить код ответа с кодом 200.

```
Из документации
если 200, то "Отправленные данные корректны"
если 400, то "Формат отправленных данных некорректен"
если 406, то "Данные не прошли проверку"
если 503, то "Сервис недоступен".
```

#### Получении информации по чеку GetTicketRequest

```
Из документации
Содержимое ФД, если код возврата равен 200
если 400, то "Формат отправленных данных некорректен"
если 404, то "Чек не найден"
если 406, то "Данные не прошли проверку"
если 503, то "Сервис недоступен".
```
<a name="strategy_timout"></a>
## Алгоритмы обработки timeout
Чтобы получить информацию по чеку, надо сделать запрос `SendMessageRequest` c параметром `messageId`
При первом(вторым и т.д) запросе, сразу после получения `messageId`, может отсутствовать информацию по чеку.
По-умолчанию в пакете реализовывается [Exponential Backoff](https://ru.wikipedia.org/wiki/%D0%9D%D0%BE%D1%80%D0%BC%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D0%B5_%D1%80%D0%B0%D1%81%D0%BF%D1%80%D0%B5%D0%B4%D0%B5%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5)

Вы можете реализовать свой алгоритм реализуя интерфейс `Fns\Contracts\TimeoutStrategyHandler\TimeoutStrategyHandler`

Для контроля процесса выполнения запроса и его результата, передайте в конструктор `Fns\Contracts\RequestsManager`

Для примера вы можете реализовать свой алгоритм `Interval`
Который будет опрашивать сервер через константный промежуток времени.

<a name="note_from_author"></a>
## Дополнительная информация от автора
В данный момент все выполняется в синхронном режиме.
 Можно разделить процесс получения `messageId` и информации по нему, на два различных процесса.
Для начальной версии пакета, пока остаётся так.

<a name="donate"></a>
##  Угостить чаем 😌
Этот пакет был создан для экономии вашего времени на безвозмездной основе.
Надеюсь у меня получилось это сделать и я буду рад любым формам спасибо.
Звезда или скромный донат - окажут мне поддержку и веру в людей.
https://money.yandex.ru/to/410019109036855

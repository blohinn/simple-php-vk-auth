# Простая авторизация через ВКонтакте для php приложений

<p>Для работы с VK API существует масса библиотек, но порой все, что нужно - авторизировать пользователя, а запросы писать ручками. Это бывает полезно для одностраничного приложения, которым будете пользоваться только вы и ваш друг. В любом случае, если вы на этой странице - значит вам нужна авторизация и не нужны классы для работы с самими методами (вы слишком круты и можете написать их сами).</p>

<hr>

1. Регистрируем **web-приложение (сайт)** на https://vk.com/editapp?act=create
2. В настройках приложения (на том же vk.com) получаем **ID приложения**, **защищённый ключ** и **сервисный ключ доступа**.
3. Там же указываем **базовый домен** вашего сайта (yourapp.com) и доверенный **redirect URI** (https://yourapp.com/libs/simple-php-vk-auth/auth.php).
4. Настраиваем файл **config.php**:
<pre>
//Права, которые мы хотим получить (https://vk.com/dev/permissions)
$scope = array(
    'nohttps', 'groups', 'photos', 'friends', 'offline'
);
$protocol = 'https://'; //Если у вас есть SSL-сертификат, то оставляем, иначе меняем на http://

define('APP_URL', "$protocol" . $_SERVER['HTTP_HOST']); //Ваш сайт/web-приложение
define('REDIRECT_URL', "$protocol" . $_SERVER['HTTP_HOST'] . '/libs/simple-php-vk-auth/auth.php'); //Ссылка на скрипт авторизации через вк (файл auth.php, не путать с главной страницей вашего сайта, или той, на которой расположена кнопка 'Авторизоваться'. Ссылка на сам скрипт.)
define('APP_ID', '1234'); //ID приложения (выдает ВК)
define('APP_SECRET', 'aaabbbccc'); //Защищённый ключ (выдает ВК)
define('APP_SERVICE_KEY', 'aaabbbccc'); //Сервисный ключ доступа (выдает ВК)
define('ACCESS_TOKEN_URL', 'https://oauth.vk.com/access_token'); //не трогать
define('AUTH_URL', 'https://oauth.vk.com/authorize'); ////не трогать
define('AUTH_DIALOG_URL', AUTH_URL . '?' . 'client_id=' . APP_ID . '&redirect_uri=' . REDIRECT_URL . '&response_type=code&display=page&scope=' . implode(',', $scope)); //Вызов диалога авторизации через ВК

</pre>
5. Делаем ссылку на авторизацию в духе:
<pre>
< a href='https://yourapp.ru/libs/simple-php-vk-auth/auth.php'>Авторизация через ВК< a/>
</pre>
6. Если все прошло успешно, то попадаем на главную страницу сайта, а в сессии у нас теперь есть:
<pre>
$_SESSION['token']  //Сам токен
$_SESSION['secret'] //Секретка для выполнения некоторых методов (не заморачивайтесь, когда она будет нужна - поймете сами)
$_SESSION['uid']    //id авторизовавшегося пользователя
</pre>
7. Ну а если нет, то смотрим ошибки, которые возвращает ответом vk api

Ессена это не будет работать на localhost.
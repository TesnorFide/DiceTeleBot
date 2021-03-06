<?php

include 'func.php'; // Функции
include 'text.php'; // Текста
require 'db.php'; // База данных
header('Content-Type: text/html; charset=utf-8'); // на всякий случай досообщим PHP, что все в кодировке UTF-8

$site_dir = dirname(dirname(__FILE__)).'/'; // корень сайта
$bot_token = $token; // токен бота
$data = json_decode(file_get_contents('php://input'), true); // весь ввод перенаправляем в $data и декодируем json-закодированные-текстовые данные в PHP-массив

// =============================================
// ====== ВСЁ ЧТО ВЫШЕ НЕ ТРОГАТЬ! ============
// =============================================

// ====== Наши переменные ============
$chat_id = $data['message']['from']['id']; // Узнаем ID пользователя, кто написал нам
$user_name = $data['message']['from']['username']; // Username пользователя
$first_name = $data['message']['from']['first_name']; // Имя
$last_name = $data['message']['from']['last_name']; // Фамилия
$get_user = R::findOne('tgdice', 'user_id = ?', [$chat_id]); // Ищем пользователя в БД
$nick = $get_user['nick']; // Берём из БД его ник
if(!$nick){
	$nick = $user_name; // Если ника нет, то делаем ником имя пользователя
}
// ====== *************** ============

// ====== Сообщение ============
$message = trim($data['message']['text']); // Тело сообщения
$text_array = explode(" ", $message); // Массив с сообщением
$cmd = $text_array[0]; // Первое слово сообщения
$args = array_slice($text_array, 1); // Массив с остальной частью сообщения
$msg = implode(" ", $args); // Весь остальной массив текстом
$comd = mb_substr($cmd,0,3);
$ti = mb_substr($cmd,-2);
$neti = mb_substr($cmd, 0, -2);
$y = GetInteger($message);
// ====== ********* ============

// =============================================
// ====== Тут мы получили сообщение ============
// =============================================
if (!empty($data['message']['text'])) {

	// ====== База данных ============
		$get_user = R::findOne('tgdice', 'user_id = ?', [$chat_id]);
		if (!$get_user)
		{
	  	NewUser($chat_id, $first_name, $user_name);
		}
		// ====== *********** ============

    // ====== Помощь ============
        if ($message =="/help" || $message =="/меню" || $message =="/помощь" || $message =="/menu")
        {
          sendMessage($bot_token, $chat_id, "$help");
          exit;
        }
    // ====== ****** ============

    // ====== Стандартный кубик, кубик со свободным количеством граней ============
    else if ($cmd == '/d'|| $cmd == '/к' || $cmd == '/k' || $cmd == '/д' || $cmd == "/d$y[0]"|| $cmd == "/к$y[0]" || $cmd == "/k$y[0]" || $cmd == "/д$y[0]")
    {
      if ($y[0] == null)
      {
        $x = rand (1, 20); // Создаём рандомное число
        sendMessage($bot_token, $chat_id, "@$nick, d20 = $x"); // Пишем пользователю ответ
      }
      else
      {
        if ($y[0] > 999999999) { // Если число больше миллиарда
                  sendMessage($bot_token, $chat_id, "@$nick, ваше число слишком большое и, к сожалению, может поломать бота. Введите число меньше миллиарда"); // Пишем, чтобы пользователь ввёл число поменьше
              }
              else // В остальных случаях
              {
                  $x = rand (1, $y[0]); // Создаем рандомное число
                  sendMessage($bot_token, $chat_id, "@$nick, d$y[0] = $x"); // Пишем пользователю ответ
              }
      }
    }
    // ====== *************** ============

      // ====== Случайный процент ============
      else if ($cmd == '/p' ||  $cmd == '/п' || $cmd == '/%')
      {
        $x = rand (1, 100); // Создаем рандомное число
        sendMessage($bot_token, $chat_id, "@$nick, d% = $x%"); // Пишем ответ пользователю
      }
      // ====== ***************** ============

      // ====== Случайное число в заданном пользователем диапозоне ============
        else if ($cmd == "/$y[0]р$y[1]" ||  $cmd == "/$y[0]r$y[1]")
        {
                $x = rand ($y[0], $y[1]); // Создаем рандомное число
                sendMessage($bot_token, $chat_id, "@$nick, random ($y[0] - $y[1]) = $x"); // Пишем ответ пользователю
          exit;
        }
        // ====== ************************************************** ============

      // ====== Бросок кубиков с количеством граней, заданным пользователем, заданное пользователем количество раз ============
        else if ($cmd == "/$y[0]к$y[1]" ||  $cmd == "/$y[0]d$y[1]" || $cmd == "/$y[0]k$y[1]" || $cmd == "/$y[0]д$y[1]")
        {
            if ($y[1] == 0) $y[1]=20; //Если $y[1] не задана пользователем, то она выставляется в стандартное значение
            if ($y[1] > 999999999 || $y[0] > 999999999) { // Если число больше миллиарда
          sendMessage($bot_token, $chat_id, "@$nick, ваше число слишком большое и, к сожалению может поломать бота. Введите число меньше миллиарда"); // Пишем, чтобы пользователь ввёл число поменьше
            }
            else // В других случаях
            {
                $t = array(); // Создаём некий массив
                for ($i=0; $i++<$y[0];) // Цикл, который прокручивается заданное пользователем количество раз
                {
                    $t[$i+1]=rand (1, $y[1]); // Каждый цикл мы бросаем кубики с количеством граней, заданным пользователем
                }
                $sum = implode("+", $t); // Записываем в строку все броски, поставив между числами "+"
                $int = array_sum($t); // Считаем сумму массива и записываем в переменную
                sendMessage($bot_token, $chat_id, "@$nick, $y[0]d$y[1] = $int [$sum]"); // Пишем пользователю ответ
            }
        exit;
        }
        // ====== ************************************************************************************************ ============

      // ====== Результат кубика + заданное пользователем число ============
        else if ($cmd == "/$y[0]+$y[1]")
        {
            if ($y[0] == 0) $y[0] = 20; // Если пользователь не задал количество граней, то ставим их по стандарту
            $x = rand (1, $y[0]); // Создаем рандомное число
            $z = $x+$y[1]; // Прибавляем заданное пользователем число
            sendMessage($bot_token, $chat_id, "@$nick, d$y[0]+$y[1] = $z ($x + $y[1])"); // Пишем пользователю ответ
        exit;
        }
        // ====== *********************************************** ============

      // ====== Результат кубика - заданное пользователем число ============
        else if ($cmd == "/$y[0]-$y[1]")
        {
            if ($y[0] == 0) $y[0] = 20; // Если пользователь не задал количество граней, то ставим их по стандарту
            $x = rand (1, $y[0]); // Создаем рандомное число
            $z = $x-$y[1]; // Вычитаем заданное пользователем число
            sendMessage($bot_token, $chat_id, "@$nick, d$y[0]-$y[1] = $z ($x - $y[1])"); // Пишем пользователю ответ
        exit;
        }
        // ====== *********************************************** ============

      // ====== Результат кубика * заданное пользователем число ============
        else if ($cmd == "/$y[0]*$y[1]")
        {
            if ($y[0] == 0) $y[0] = 20; // Если пользователь не задал количество граней, то ставим их по стандарту
            $x = rand (1, $y[0]); // Создаем рандомное число
            $z = $x*$y[1]; // Умножаем на заданное пользователем число
            sendMessage($bot_token, $chat_id, "@$nick, d$y[0]*$y[1] = $z ($x * $y[1])"); // Пишем пользователю ответ
        exit;
        }
        // ====== *********************************************** ============

        // ====== Результат кубика / заданное пользователем число ============
        else if ($cmd == "/$y[0]/$y[1]" || $cmd == "/$y[0]di$y[1]")
        {
            if ($y[0] == 0) $y[0] = 20; // Если пользователь не задал количество граней, то ставим их по стандарту
            $x = rand (1, $y[0]); // Создаем рандомное число
            $z = $x/$y[1]; // Делим на заданное пользователем число
            sendMessage($bot_token, $chat_id, "@$nick, d$y[0]/$y[1] = $z ($x / $y[1])"); // Пишем пользователю ответ
        exit;
        }
        // ====== *********************************************** ============

        // ====== Репорт ============
        else if ($cmd == "/compl")
        {
            sendMessage($bot_token, 698551696, "@$nick\n$first_name $last_name:\n$msg"); // Пишем пользователю ответ
        exit;
        }
        // ====== ****** ============

} // Получение сообщения

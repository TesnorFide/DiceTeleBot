<?php

// ====== Отправка сообщения ============
function sendMessage($bot_token, $chat_id, $text, $reply_markup = '')
{
    $ch = curl_init();
    $ch_post = [
        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chat_id,
            //'parse_mode' => 'HTML',
            'text' => $text,
            'reply_markup' => $reply_markup,
        ]
    ];

    curl_setopt_array($ch, $ch_post);
    curl_exec($ch);
}
// ====== ****************** ============

// ====== Отправка изображения ============
function sendPhoto($bot_token, $chat_id, $photo, $reply_markup = '')
{
    $ch = curl_init();
    $ch_post = [
        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendPhoto',
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chat_id,
            'photo'     => new CURLFile(realpath($photo)),
            'reply_markup' => $reply_markup,
        ]
    ];

    curl_setopt_array($ch, $ch_post);
    curl_exec($ch);
}
// ====== ******************** ============

// ====== Отправка кнопок ============
function mesButton($bot_token, $chat_id, $text, $keyboard)
{
    $key = json_encode(array('inline_keyboard' => $keyboard));
    sendMessage($bot_token, $chat_id, $text, $key);
}
// ====== *************** ============

// ====== Регистрация нажатия ============
function ansButton($bot_token, $chat_id, $id, $text)
{
    $ch = curl_init();
    $ch_post = [
        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/answerCallbackQuery',
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'callback_query_id' => $id,
            'text' => $text,
        ]
    ];

    curl_setopt_array($ch, $ch_post);
    curl_exec($ch);
}
// ====== ******************* ============

// ====== Редактирование сообщений ============
function editMes($bot_token, $chat_id, $message_id, $txt, $reply_markup = '')
{
  if (!empty($reply_markup)) {$reply_markup = json_encode(array('inline_keyboard' => $reply_markup));}
  $url = 'https://api.telegram.org/bot' . $bot_token . '/editMessageText?chat_id=' . $chat_id . '&message_id=' . $message_id . '&text=' . urlencode($txt) . '&reply_markup=' . $reply_markup;
  file_get_contents($url);
}
// ====== ************************ ============

// ====== Отбираем числа ======
function GetInteger($mes)
{
  $y = preg_replace('/\D/', '~', $mes); // Заменяем не числа на пробелы
  $y = explode("~", $y);
  $yyy = array();
  $x = count($y);
  $uka = 0;
  for ($i=-1; $i++<$x;) // Цикл, который прокручивается заданное пользователем количество раз
  {
    if ($y[$i] !== '')
    {
      $yyy[$uka] = $y[$i];
      $uka++;
    }
  }
  $y = $yyy;
  return($y);
}
// ====== ************** ======

// ====== Создание нового юзера ============
function NewUser($id, $name, $nick)
{
  $new_user = R::dispense('tgdice');
  $new_user->user_id = $id;
  $new_user->name = $name;
  $new_user->nick = $nick;
  $new_user->reputation = 0;
  R::store($new_user);
}
// ====== ********************* ============

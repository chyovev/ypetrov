<?php
switch ($type) {
    case 'comment':
        $message =  '<p>Коментарът ви е добавен успешно!</p>';
        break;
    default:
        $message = '<p>Съобщението ви е изпратено успешно!</p>';
        break;
}

echo $message;

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./PHPMailer/Exception.php";
require "./PHPMailer/PHPMailer.php";
require "./PHPMailer/SMTP.php";

header("Access-Control-Allow-Origin: *");
header('Content-type:application/json;charset=utf-8');
$data = file_get_contents("php://input");

if (!$data) {
    die("Get out of here!");
}

$order = json_decode($data);

$to = "uragorin@yandex.ru";

//$name = strip_tags($order["name"]);
//$phone = strip_tags($order["phone"]);
//$cart = nl2br(strip_tags($order["cart"]));

$name = "test";
$phone = 123123123;
$cart = "....";
$date = date("d.m.Y в H:i");

if ($name != "" && $phone != "" && $cart !== "") {
    $text = "
        <h3>Оформлен заказ на сайте</h3>
        Пользователь отправил заявку: <br />
        <p>
            <b>Имя:</b><br /> {$name}<br />
            <b>Телефон:</b><br /> {$phone}<br />
        </p>
        <p>Содержание заказа:</p> 
        {$cart}
        <p><i>Заявка сформирована {$date}</i></p>
    ";

    try {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->setFrom("vuetest@yandex.ru", "MySuperShop");
        $mail->addAddress($to);
        $mail->CharSet = "UTF-8";
        $mail->Host = "ssl://smtp.yandex.ru";
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = "vuetest@yandex.ru";
        $mail->Password = "vue123test123";

        $mail->isHTML(true);
        $mail->Subject = "На сайте оформлен заказ";
        $mail->Body = $text;
        $mail->send();

        echo json_encode([
            "hasError" => false,
            "message" => "Заказ успешно оформлен",
        ]);
    } catch (\Exception $e) {
        echo json_encode([
            "hasError" => true,
            "message" => "Произошла ошибка, попробуйте ещё раз!",
        ]);
    }

} else {
    echo json_encode([
        "hasError" => true,
        "message" => "Заполните все поля",
    ]);
}

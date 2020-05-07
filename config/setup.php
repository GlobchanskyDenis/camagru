<?php
//	A config/setup.php file, capable of creating or re-creating 
//	the database schema, by using the info cintained in the file 
//	config/database.php.
echo "Пытаюсь отправить письмо" . PHP_EOL;

$headers = array(
    'From' => 'bsabre.cat@gmail.com',
    'Reply-To' => 'bsabre.cat@gmail.com',
    'X-Mailer' => 'PHP/' . phpversion()
);

$header = "From: bsabre.cat@gmail.com\r\n";

$ret = mail("skinnyman23@yandex.ru", 'subject1', "Message for send!!!\nport 993");

echo $ret . PHP_EOL;

// May  6 17:48:10 skinny-pc sendmail[15817]: 046EmAix015817: from=skinny, size=74, class=0, nrcpts=1, 
//     msgid=<202005061448.046EmAix015817@skinny-pc.beeline>, relay=skinny@localhost
// May  6 17:48:10 skinny-pc sendmail[15817]: 046EmAix015817: to=skinnyman23@yandex.ru, 
//     ctladdr=skinny (1000/1000), delay=00:00:00, xdelay=00:00:00, mailer=relay, pri=30074, 
//     relay=[127.0.0.1] [127.0.0.1], dsn=4.0.0, stat=Deferred: Connection refused by [127.0.0.1]

?>
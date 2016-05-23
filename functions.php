<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 23.05.2016
 * Time: 23:54
 */
function clean($value = "") {
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);

    return $value;
}
// Registration procedure
function generate_pass($number)
{
    $arr = array('a','b','c','d','e','f',
        'g','h','i','j','k','l',
        'm','n','o','p','r','s',
        't','u','v','x','y','z',
        'A','B','C','D','E','F',
        'G','H','I','J','K','L',
        'M','N','O','P','R','S',
        'T','U','V','X','Y','Z',
        '1','2','3','4','5','6',
        '7','8','9','0');

    // Генерируем пароль для смс
    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
        // Вычисляем произвольный индекс из массива
        $index = rand(0, count($arr) - 1);
        $pass .= $arr[$index];
    }
    return $pass;
}
function dberror($medoo)
{
    return is_object($medoo)?array_merge(['error'=>'db_error'],$medoo->error()):false;
}
// Period is minutes
function checkDDOS($method,$medoo,$limit=1,$period='10')
{
    $ip = getIP();
    // Putting current query to table
    $medoo->insert('ddos_protection',[
        'ip'=>$ip,
        'method'=>$method
    ]);
    // XSS
    $period = (int)$period;
    $count = (int)$medoo->query("SELECT COUNT(id) as count FROM ddos_protection WHERE datetime>now()-interval {$period} minute;")->fetch()[0];
    if($count>(int)$limit)
    {
        exit(json_encode(['error'=>"You have more than {$limit} {$method} attempts per {$period} minutes"]));
    }
    return true;
}
function getIP()
{
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
    {
        if (array_key_exists($key, $_SERVER) === true)
        {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip)
            {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                {
                    return $ip;
                }
            }
        }
    }
}
?>
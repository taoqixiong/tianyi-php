<?php

//接收表单传递的用户名,密码,验证码和 ip
$name = $_POST['username'];
$pwd = $_POST['password'];

//获取客户端 ip 地址
if(getenv('HTTP_CLIENT_IP')) {
    $onlineip = getenv('HTTP_CLIENT_IP');
} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
    $onlineip = getenv('HTTP_X_FORWARDED_FOR');
} elseif(getenv('REMOTE_ADDR')) {
    $onlineip = getenv('REMOTE_ADDR');
} else {
    $onlineip = $_SERVER['REMOTE_ADDR'];
}

//设置常量
define('U', $name);
define('P', $pwd);
define('CLINENTIP', $onlineip);
define('MAC', 'FF-FF-FF-FF-FF-FF');
define('NASIP', '113.98.10.136');
define('TIME', number_format(microtime(true),3,'',''));
define('WIFI', '1050');
define('WIFI2', '4060');
define('TOKEN', '');
define('SECRET', 'Eshore!@#');

function post_j($url, $jsonStr) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($jsonStr)
        )
    );
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    return array($httpCode, $response);
}

function login() {
    $str = CLINENTIP . NASIP . MAC . TIME . TOKEN . SECRET;
    $auth = strtoupper(md5($str));

    $url = "http://enet.10000.gd.cn:10001/client/login";
    $jsonStr_m = json_encode(array(
            "username" => U,
            "password" => P,
            "verificationcode" => "",
            "clientip" => CLINENTIP,
            "nasip" => NASIP,
            "mac" => MAC,
            "iswifi" => WIFI2,
            "timestamp" => TIME,
            "authenticator" => $auth
        )
    );
    return $msg = list($returnCode, $returnContent) = post_j($url, $jsonStr_m);
}

$msg = login();

if (strpos($msg[1], 'login success') !== false) {
    $log = "登录成功";
}
elseif (strpos($msg[1], 'login fail') !== false) {
    $log = "登录失败";
}
elseif (strpos($msg[1], 'BAS设备有一个用户正在认证过程中,PortalServer请稍后再试') !== false) {
    $log = "请稍后再试";
}
elseif (strpos($msg[1], 'Password Error') !== false) {
    $log = "密码错误";
}
?>
<html>
<head>
    <title>登录器</title>
    <meta charset="utf-8">
</head>

<body>
<h1 align="center">登录</h1>
<hr>
<form method="post" action="login.php">
    <table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
        <TBODY>
        <TR><TD width="100%" height="100%" align="center" valign="top">
                <TABLE class="lodininto" border="0" cellspacing="7" cellpadding="0">
                    <TBODY>
                    <TR><TD><?php echo $log; ?></TD></TR>
                </TABLE>
</form>
</body>
</html>

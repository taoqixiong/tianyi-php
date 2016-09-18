<?php
header("content-type:text/html;charset=utf-8");

//获取当前时间毫秒数
function getMillisecond() {
    list($s1, $s2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}

//设置time参数
$time = getMillisecond();

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

//获取 Cookie 并存入临时文件
$cookie_file = tempnam('./temp','ck');
$edubas = "113.98.10.136";
$url = "http://125.88.59.131:10001/login.jsp?eduuser=$onlineip&edubas=$edubas";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
$content = curl_exec($ch);
curl_close($ch);

//获取验证码并存入临时文件
$yzmurl = "http://125.88.59.131:10001/common/image.jsp";
$yzm_file = tempnam('./temp','yzm');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $yzmurl);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$rs = curl_exec($ch);
@file_put_contents($yzm_file, $rs);
curl_close($ch);

//验证码图片复制出来并重命名
copy("$yzm_file","/tmp/$time.jpg");

//验证码的识别
include ('shibie.php');
$valite = new Valite();
$valite->setImage("/tmp/$time.jpg");
$valite->getHec();
$ert = $valite->run();

//接收表单传递的用户名,密码,验证码和 ip
$name = $_POST['username'];
$pwd = $_POST['password'];


//POST 提交并把返回值写入参数
$post = "userName1=$name&password1=$pwd&rand=$ert&eduuser=$onlineip&edubas=$edubas";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://125.88.59.131:10001/login.do");
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$result=curl_exec($ch);
curl_close($ch);

//判断返回值并显示信息
if (strpos($result, 'success.jsp') !== false) {
    echo 登录成功;
}
elseif (strpos($result, 'failed.jsp') !== false) {
    echo 登录失败;
}

//清理临时文件
unlink("/tmp/$time.jpg");
unlink("$yzm_file");
unlink("$cookie_file");
?>
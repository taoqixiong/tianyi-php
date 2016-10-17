<?php
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
                    <TR><TD>请输入账号：　<INPUT name="username" style="width: 150px;" type="text"></TD></TR>
                    <TR><TD>请输入密码：　<INPUT name="password" style="width: 150px;" type="password"></TD></TR>
                    <TR><TD>当前登录IP：　<?php echo $onlineip; ?></TD></TR>
                    <TR><td align="center"><input type="submit" value="登录">　　 <input type="reset" value="重置"></td></TR>
                </TABLE>
</form>
</body>
</html>

<?

//
// 실제 서버구동 시
//
$server_name = $_SERVER['SERVER_NAME'];
$remote_addr = $_SERVER['REMOTE_ADDR'] ;

if  ($server_name=="www.mtns7.co.kr")
{
    Header("Location:./index_cokr.php") ;
}

if  ($server_name=="www.mtns7.com")
{
    Header("Location:./index_com.php") ;
}

if  ($server_name=="www.megacue.co.kr")
{
    Header("Location:./index_cokr.php") ;
}

if  ($server_name=="www.megacue.com")
{
    Header("Location:./index_com.php") ;
}

//
// 로컬 서버구동시
//
/**************************
if  ($server_name=="127.0.0.1")
{
    if  ($dir == "net")
    {
        echo "<script language='JavaScript'>window.location = './index_net.php'</script>";
    }
    else if  ($dir == "com")
    {
        echo "<script language='JavaScript'>window.location = './index_com.php'</script>";
    }
    else
    {
        echo "dir 을 지정하세요 [ http://127.0.0.1/jinbo/index.php?dir= ] " ;
    }
}
**************************/
?>
<html>

    <head>

        <title>여기가 루트 - index.php</title>

    </head>

    <body bgcolor="white" text="black" link="blue" vlink="purple" alink="red">

        <p><b><?=$server_name?>의 index.php를 확인해 보아요!! 당신의 도메인이름은<?=$server_name?></b></p>

        <iframe name="payed" src="phpinfo.php" FRAMEBORDER="1"  width="99%" height="90%"></iframe>

    </body>

</html>


<?

//
// ���� �������� ��
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
// ���� ����������
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
        echo "dir �� �����ϼ��� [ http://127.0.0.1/jinbo/index.php?dir= ] " ;
    }
}
**************************/
?>
<html>

    <head>

        <title>���Ⱑ ��Ʈ - index.php</title>

    </head>

    <body bgcolor="white" text="black" link="blue" vlink="purple" alink="red">

        <p><b><?=$server_name?>�� index.php�� Ȯ���� ���ƿ�!! ����� �������̸���<?=$server_name?></b></p>

        <iframe name="payed" src="phpinfo.php" FRAMEBORDER="1"  width="99%" height="90%"></iframe>

    </body>

</html>


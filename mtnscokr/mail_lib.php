<?
function GetTimeStamp($date) 
{
    // 인자 형식
    //YYYY-MM-DD
    //YYYY-MM-DD HH:mm:ss  
    
    if  (strlen($DATE) == 10) 
    {
        $time = mktime(0,0,0,(int)substr($date,5,2),(int)substr($date,8,2),(int)substr($date,0,4) );
    } 
    else 
    {
        $time = mktime((int)substr($date,11,2),(int)substr($date,14,2),(int)substr($date,17,2),
                       (int)substr($date,5,2),(int)substr($date,8,2),(int)substr($date,0,4)    );
    }
    return $time;
}

function HtmlHeader($strTitle) 
{ 
    ?> 
    <html>
    <head>
    <meta http-equiv=Content-Type content=text/html; charset=euc-kr>
    <title><?echo $strTitle;?></title>
    <link rel="stylesheet" href="web_mail.css" type="text/css">
    </head>
    <?
}

function DisplayCopyRight() 
{
    global $C_TABLE_SIZE; 
    ?>
    <table width="<?echo $C_TABLE_SIZE;?>" cellspacing=0 cellpadding=0 border=0>
    <tr>
      <td>
      <hr width=100% size=2 color=#007394>
      </td>
    </tr>
    <tr>
      <td align=center><b><font face=굴림 size=2 color=#003354>
      Copyright ⓒ 2000<img src="/img/ihelpers.gif" border=0 
      align=absmiddle> All rights reserved.</font></b></td>
    </tr>
    </table>
    <br>
   <?
}

function HtmlTail()
{
    echo "</body></html>";  
}

function PrintMsg($strMessage)
{
    ?>
    <script language="javascript">
    <!--
      alert("<?echo $strMessage;?>");
    //-->
    </script>
    <?
}

function PrintMsgBack($strMessage) 
{
    ?>
    <script language="javascript">
    <!--
      alert("<?echo $strMessage;?>");
      history.back();
    //-->
    </script>
    <?
    exit;
}

function GoUrl($strUrl)
{
    ?>
    <script language="javascript">
    <!--
      varUrl = '<?echo $strUrl;?>';
      if (varUrl !="") {
        document.location.replace(varUrl);
      }
    //-->  
    </script>
    <?
}

function RedirectTarget($url,$target)
{ 
    ?>
    <html>
    <body onLoad="document.form1.submit()";>
    <form action="<?echo $url;?>" target="<?echo $target;?>" name=form1 method=post>
    <input type=hidden name=name value="">
    </form>
    </body>
    </html>
    <?
}

function CheckBroswer($num, $num2)
{
    global $HTTP_USER_AGENT;

    if (strpos($HTTP_USER_AGENT, "MSIE")) 
    {
      return $num;
    } 
    else 
    {
      return $num2;
    }
}

function CompStr($buffer, $value) 
{
    if (strlen($buffer) <= strlen($value)) return false;
    
    if (substr($buffer, 0, strlen($value)) == $value) 
    {
        return true;
    } 
    else 
    {
        return false;
    }
}

function Decode($val) 
{
    if  (substr($val,0,2) == "=?") //인코딩 여부 확인
    {   
        $code = strpos($val, "?", 3);
        $code = strpos($val, "?", $code+1);
        $val = substr($val, $code+1, strlen($val) - $code -3);
        return imap_base64($val);
    } 
    else 
    {
        return $val;
    }
}

function printOutLook($val) 
{
    $line = split("\n", $val);

    $val = "";
    $cnt = 0;

    for($i=0;$i<count($line);$i++) 
    {
       if  ($line[$i]=="\r") $cnt++;    
       if  ($cnt == 1)       $val .= $line[$i] ;   
    }
    echo imap_base64($val);
}

function getOutLook($val) 
{
    $line = split("\n", $val);

    $val = "";
    $cnt = 0;

    for($i=0;$i<count($line);$i++) 
    {
       if  ($line[$i]=="\r") $cnt++;    
       if  ($cnt == 1)       $val .= $line[$i] ;   
    }
    return imap_base64($val);
}

?>

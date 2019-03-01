<?
    include "inc/config.php";       // {[데이터 베이스]} : 환경설정
    $connect = dbconn() ;           // {[데이터 베이스]} : 연결
    mysql_select_db($cont_db) ;     // {[데이터 베이스]} : 디비선택
?>
<!DOCTYPE html>
<html lang="kr">
<head>
    <? include "inc/Head.inc"; ?>    
</head>
<body>
    <div class="t3" >
    <table border="1">
    <tr>
        <th colspan="3">수신내역</th>
    </tr>
    <tr>
        <th>IP</th>        
        <th>시작일자</th>
        <th>종료일자</th>
    </tr>
    <?      
// http://ip-api.com/json/14.63.41.182
// https://www.google.co.kr/maps/place/37.5985,126.9783

        $sQuery = "SELECT * FROM wrk_history  
                        ORDER BY StatTime DESC
                           LIMIT 100
                " ; //echo "<br>".iconv("EUC-KR","UTF-8",$sQuery); ; 
        $QryHistory = mysql_query($sQuery,$connect) ;
        while  ($ArrHistory = mysql_fetch_array($QryHistory))
        {
            $IP = $ArrHistory["IP"] ;
            $StatTime = $ArrHistory["StatTime"] ;
            $EndTime  = $ArrHistory["EndTime"] ;
            ?>
            <tr>
                <td><?=$IP?>&nbsp;</td>                
                <td><?=$StatTime?>&nbsp;</td>
                <td><?=$EndTime?>&nbsp;</td>
            </tr>
            <?
        }
    ?>
    </table>      
    </div>

</body>
</html>

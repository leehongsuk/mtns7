<?
header("Content-Type: text/html; charset=euc-kr");

set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

include "config.php";

$connect = dbconn();

mysql_select_db($cont_db) ;

//$WorkDate = strtotime(substr($SingoDate,0,4)."-".substr($SingoDate,4,2)."-".substr($SingoDate,6,2)."") ;
$WorkDate = $SingoDate ;

$Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...
?>

       <script type="text/javascript">
        <!--

            $(document).ready(function()
            {
            });

            // alert("___")  ;

            // 영화명 갱신 버튼
            $("input.FilmtitleUpdate").click(function()
            {
                var index = $('input.FilmtitleUpdate').index(this);

                var options = {
                    _Type: 'FilmtitleUpdate',
                    _mtnsName: $('input.FilmtitleUpdate').eq(index).attr( "mtnsName" ),
                    _lotteName: $('input.FilmtitlelotteName').eq(index).attr( "value" )
                } ;

                $.post("./wrk_filmsupply_Link_DnExcel_Lotte_UD.php", options, function(data)
                {
                    //alert(data) ;
                });
            });

            // 영화명 삭제 버튼
            $("input.FilmtitleDelete").click(function()
            {
                var index = $('input.FilmtitleDelete').index(this);

                $('input.FilmtitlelotteName').eq(index).val( "" );

                var options = {
                    _Type: 'FilmtitleDelete',
                    _mtnsName: $('input.FilmtitleDelete').eq(index).attr( "mtnsName" )
                } ;

                $.post("./wrk_filmsupply_Link_DnExcel_Lotte_UD.php", options, function(data)
                {
                    //alert(data) ;
                });
            });

            // 극장명 갱신 버튼
            $("input.TheatherUpdate").click(function()
            {
                var index = $('input.TheatherUpdate').index(this);

                var options = {
                    _Type: 'TheatherUpdate',
                    _mtnsName: $('input.TheatherUpdate').eq(index).attr( "mtnsName" ),
                    _lotteName: $('input.TheatherlotteName').eq(index).attr( "value" )
                } ;

                $.post("./wrk_filmsupply_Link_DnExcel_Lotte_UD.php", options, function(data)
                {
                    //alert(data) ;
                });
            });

            // 극장명 삭제 버튼
            $("input.TheatherDelete").click(function()
            {
                var index = $('input.TheatherDelete').index(this);

                $('input.TheatherlotteName').eq(index).val( "" );

                var options = {
                    _Type: 'TheatherDelete',
                    _mtnsName: $('input.TheatherDelete').eq(index).attr( "mtnsName" )
                } ;

                $.post("./wrk_filmsupply_Link_DnExcel_Lotte_UD.php", options, function(data)
                {
                    //alert(data) ;
                });
            });

        //-->
        </script>


        <BR>
        <table border="1" cellpadding="0" cellspacing="0" align="center">
        <tr>
             <td>MTNS</td><td>LOTTE</td><td colspan="2">&nbsp;</td>
        </tr>
        <?
        $i = 0 ;
        $sQuery = "Select * From bas_filmtitle   ".
                  " Where Finish <> 'Y'          ".
                  " Order By Name                " ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
             $i ++ ;
             $mtnsName = $ArrFilmtitle["Name"]  ;

             $lotteName = "" ;

             $sQuery = "Select * From xls_lotte_filmtitle \n".
                       " Where mtnsName = '$mtnsName'     \n" ;
             $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
             if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
             {
                  $lotteName = $ArrXlsFilmtitle["lotteName"]  ;
             }
             ?>
             <tr>
                 <td><?=$mtnsName?></td>
                 <td><input class="FilmtitlelotteName" type="text" size="50" name="" value="<?=$lotteName?>"></td>

                 <td><input class="FilmtitleUpdate" type="button" mtnsName='<?=$mtnsName?>' value='<?=iconv("utf-8","euc-kr","갱신")?>'></td>
                 <td><input class="FilmtitleDelete" type="button" mtnsName='<?=$mtnsName?>' value='<?=iconv("utf-8","euc-kr","삭제")?>'></td>
             </tr>

             <?
        }
        ?>
        </table>


        <BR>
        <table border="1" cellpadding="0" cellspacing="0" align="center">
        <tr>
             <td>MTNS</td><td>LOTTE</td><td colspan="2">&nbsp;</td>
        </tr>
        <?
        $temp = iconv("utf-8","euc-kr","롯데") ;
        $sQuery = "Select * FROM bas_theather      \n".
                  " Where Discript like '%$temp%'  \n".
                  " Order By Discript              \n" ;
        $QryTheather = mysql_query($sQuery,$connect) ;
        while ($ArrTheather = mysql_fetch_array($QryTheather))
        {
            $mtnsName  = $ArrTheather["Discript"]  ;

            $lotteName = "" ;

            $sQuery = "Select * From xls_lotte_theather \n".
                     " Where mtnsName = '$mtnsName'     \n" ;
            $QryXlsTheather = mysql_query($sQuery,$connect) ;
            if  ($ArrXlsTheather = mysql_fetch_array($QryXlsTheather))
            {
                $lotteName = $ArrXlsTheather["lotteName"]  ;
            }
            ?>
            <tr>
                <td><?=$mtnsName?></td>
                <td><input class="TheatherlotteName" type="text" size="50" name="" value="<?=$lotteName?>"></td>

                <td><input class="TheatherUpdate" type="button" mtnsName='<?=$mtnsName?>' value='<?=iconv("utf-8","euc-kr","갱신")?>'></td>
                <td><input class="TheatherDelete" type="button" mtnsName='<?=$mtnsName?>' value='<?=iconv("utf-8","euc-kr","삭제")?>'></td>
            </tr>
            <?
        }
        ?>
        </table>
        <BR>

<?
mysql_close($connect);
?>


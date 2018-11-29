<?
    session_start();

    $Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...

    $SingoDate = date("Ymd",$Today) ;
?>
<html>
    <head>

        <script type="text/javascript" src="./js/jquery-1.3.2.js"></script> <!-- http://visualjquery.com/ -->
        <script type="text/javascript" src="./js/jquery.form.js"></script>  <!-- http://www.malsup.com/jquery/form/ -->

        <title>엑셀스코어전송</title>

        <script type="text/javascript">
        <!--

            $(document).ready(function()
            {
                $("div#output").css("color","red") ;

                // 롯데씨네마
                $("#btnLotte").click(function()
                {
                    $("#Silmooja").val("555588") ;
                    $.post("./wrk_filmsupply_Link_DnExcel_Lotte_Set.php", function(data)
                    {
                        $('div#output').html(data) ;
                    });

                    clear() ;

                });
                // 메가박스
                $("#btnMagaBox").click(function()
                {
                    $("#Silmooja").val("555587") ;
                    //$('div#output').html("") ;

                    $.post("./wrk_filmsupply_Link_DnExcel_Megabox_Set.php", function(data)
                    {
                        $('div#output').html(data) ;
                    });

                    clear() ;
                });
                // 프리머스
                $("#btnPrimus").click(function()
                {
                    $("#Silmooja").val("555595") ;
                    $.post("./wrk_filmsupply_Link_DnExcel_Primus_Set.php", function(data)
                    {
                        $('div#output').html(data) ;
                    });

                    clear() ;

                });
                // CGV
                $("#btnCGV").click(function()
                {
                    $("#Silmooja").val("777777") ;
                    $('div#output').html("") ;

                    clear() ;
                });

                function clear()
                {
                    FormUpload.file.select(); // value를 강제 select 하자!
                    document.execCommand('Delete'); // 날려버리자!!
                }
           });


        //-->
        </script>

        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">

    </head>


    <script language="javascript">
    <!--
    function File_Up(form, file)
    {
        extArray = new Array(".xls");
        if (!file) return;

        while (file.indexOf("\\") != -1)
          file = file.slice(file.indexOf("\\") + 1);   //indexOf => 지정된 문자위치찾기.

        ext = file.slice(file.indexOf(".")).toLowerCase();   //toLowerCase => 소문자로 변환

        for (var i = 0; i < extArray.length; i++)
        {
            if (extArray[i] == ext) { allowSubmit = true; break; }   //allowSubmit => 파일 확장자 필터링
        }
        var bErr = false ;

        if (extArray[i] != ".xls" )
        {
            alert("확장자가 .xls 이 아닌 파일은 업로드 할 수 없습니다.");

            form.file.select(); // value를 강제 select 하자!
            document.execCommand('Delete'); // 날려버리자!!

            bErr = true ;
        }

        sGubun = "" ;
        for(i=0;i<FormUpload.gubun.length;i++)
        {
             if  (FormUpload.gubun[i].checked)
             {
                 sGubun =FormUpload.gubun[i].value ;
             }
        }
        if  (sGubun=="")
        {
            alert("구분을 먼저 선택하세요");

            bErr = true ;
        }
        else
        {
            if  (((sGubun=="메가박스") || (sGubun=="프리머스") || (sGubun=="CGV")) && ( file.length != 12))
            {
                alert("파일명이 날짜형태 이어야 합니다 yyyymmdd.xls  예) 20101231.xls");

                bErr = true ;
            }
        }
    }

    function onSubmit()
    {
        var sMsg = "" ;
        var sGubun = "" ;



        if  (FormUpload.file.value=="")
        {
            sMsg += "파일명이 없읍니다.\n" ;
        }
        for(i=0;i<FormUpload.gubun.length;i++)
        {
             if  (FormUpload.gubun[i].checked)
             {
                 sGubun =FormUpload.gubun[i].value ;
             }
        }
        if  (sGubun=="")
        {
            sMsg += "구분이 없읍니다.\n" ;
        }

        var nSelect = -1 ;
        for(var i=0; i<FormUpload.FilmType.length; i++)
        {
            if  (FormUpload.FilmType[i].checked)
            {
                nSelect = i ;
                break;
            }
        }
        if (nSelect == -1)
        {
            sMsg += "필름구분이 선택되지 않았습니다.\n" ;
        }


        if  (sMsg!="")
        {
            alert(sMsg) ;

            return false ;
        }
        else
        {
            <?
            if ( $digital ==  "yes" )
            {
                $Param  = "?digital=yes" ;
            }
            else
            {
                $Param = "" ;
            }
            ?>

            // 극장 구분 별로 action을 다른것을 호출한다.
            if  (sGubun=="롯데씨네마") FormUpload.action="./wrk_filmsupply_Link_DnExcel_Lotte.php<?=$Param?>" ;
            if  (sGubun=="메가박스")   FormUpload.action="./wrk_filmsupply_Link_DnExcel_Megabox.php<?=$Param?>" ;
            if  (sGubun=="프리머스")   FormUpload.action="./wrk_filmsupply_Link_DnExcel_Primus.php<?=$Param?>" ;
            if  (sGubun=="CGV")        FormUpload.action="./wrk_filmsupply_Link_DnExcel_CGV.php<?=$Param?>" ;

            return true ;
        }
    }
    //-->
    </script>



    <body bgcolor="#fafafa" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

        <BR><BR><BR>


        <table width="1000" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr>
            <td align="center" valign="middle">

                <form name="FormUpload" method="post"enctype="multipart/form-data" onsubmit="return onSubmit()">
					           <table>
                    <?
                    if ( $digital ==  "yes" )
                    {
                        ?>
                        <tr><td align="center" colspan=2>디지털 회차보고</td></tr>
                        <?
                    }
                    ?>
                    <tr><td bgcolor="black" colspan=2></td></tr>
                    <tr>
                     <td align="center" colspan=2>
                         신고일자:<input type="text" name="SingoDate" value=<?=$SingoDate?> maxlength="8" size="9">
                         실무자:<input type="text" id="Silmooja" name="Silmooja" value="777777" maxlength="6" size="7">
                     </td>
                    </tr>
                    <tr><td bgcolor="black" colspan=2></td></tr>
                    <tr>
                     <td align="right"><label for="gubun">구분:</label></td>
                     <td>
                         <input id="btnLotte"   type="radio" name="gubun" value="롯데씨네마">롯데씨네마
                         <input id="btnMagaBox" type="radio" name="gubun" value="메가박스">메가박스
                         <input id="btnPrimus"  type="radio" name="gubun" value="프리머스">프리머스
                         <input id="btnCGV"     type="radio" name="gubun" value="CGV">CGV
                    </td>
                    </tr>
                    <tr><td bgcolor="black" colspan=2></td></tr>
                    <tr>
                     <td align="right"><label for="gubun">필름구분:</label></td>
                     <td>
                         <table>
                         <tr>
                          <td valign="center">

                          </td>
                          <td valign="top">
                              <?
                              if ( $digital !=  "yes" )
                              {
                              ?>
                              <input type='radio' name='FilmType' value='35'>35mm
                              <?
                              }
                              ?>
                          </td>
                          <td valign="top">
                              <input type='radio' name='FilmType' value='2'>디지털2D<br>
                              <input type='radio' name='FilmType' value='3'>디지털3D
                          </td>
                          <td valign="top">
                              <?
                              if ( $digital !=  "yes" )
                              {
                              ?>
                              <input type='radio' name='FilmType' value='29'>아이맥스2D<br>
                              <input type='radio' name='FilmType' value='39'>아이맥스3D
                              <?
                              }
                              ?>
                          </td>
                          <td valign="top">
                              <input type='radio' name='FilmType' value='20'>디지털 더빙<br>
                              <input type='radio' name='FilmType' value='30'>디지털3D 더빙
                          </td>
                          <td valign="top">
                              <input type='radio' name='FilmType' value='24'>2D HFR<br>
                              <input type='radio' name='FilmType' value='34'>3D HFR
                          </td>
                          <td valign="top">
                              <input type='radio' name='FilmType' value='294'>2D IMAX HFR<br>
                              <input type='radio' name='FilmType' value='394'>3D IMAX HFR
                          </td>
                          <td valign="top">
                              <input type='radio' name='FilmType' value='4'>4D<br>
                          </td>
                         </tr>
                         </table>
                    </td>
                    </tr>
                    <tr><td bgcolor="black" colspan=2></td></tr>
                    </table>


                    <br><br>

                    <label for="file">파일명:</label>
                    <input type="file"  name="file" id="file" onChange="File_Up(this.form.file.value)">

                    <br><br>

                    <!-- 전송 버튼 -->
                    <input type="submit" name="submit" value="전송" />

                </form>

            </td>
        </tr>
        <tr>
            <td align="center" valign="middle">
                <div id="output"></div>
            </td>
        </tr>
        </table>


    </body>

</html>


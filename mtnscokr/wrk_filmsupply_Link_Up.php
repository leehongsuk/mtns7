<?
    session_start();

    if ($ToExel)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=excel_name.xls");
        header("Content-Description: GamZa Excel Data");
    }
?>

<html>
<?
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정

        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택

        $FilmOpen = substr($FilmTile,0,6) ;
        $FilmCode = substr($FilmTile,6,2) ;

        $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..

        if  ($FilmCode=='00')
        {
            $AddCond = "     Open = '".$FilmOpen."'  " ;
        }
        else
        {
            $AddCond = "     Open = '".$FilmOpen."'  ".
                       " And Film = '".$FilmCode."'  " ;
        }

        $sQuery = "Select * From bas_smsidchk        ".
                  " Where Id = '".$spacial_UserId."' " ;
        $QrySmsIdChk = mysql_query($sQuery,$connect) ;
        if  ($ArrSmsIdChk = mysql_fetch_array($QrySmsIdChk)) {   $TimJang =  true ;  } // 이부장..
        else                                                 {   $TimJang = false ;  }
?>

    <link rel=stylesheet href=./LinkStyle.css type=text/css>

    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

    <head>
        <title>일일 보고서</title>

        <script type="text/javascript" src="./js/jquery-1.8.3.js"></script>

        <script type="text/javascript">
        <!--
            $(document).ready(function()
            {
                $("#selFilmTile").change(function()
                {
                    //alert($("#selGubun").val());
                    //alert($(this).val());
                    //alert($(this).children("option:selected").text());
                });
            });

            function selFilmTileChange(OpenCode)
            {
                //alert($("#selGubun").val());
                //alert(a);
            }

            //
            // 엑셀 출력
            //
            function toexel_click()
            {
                // 사용자가 선택한날짜.
                sCurrDate = <?=$WorkDate?> ;


                location.href = 'wrk_filmsupply_Link_Up.php?'
                              + 'logged_UserId=<?=$logged_UserId?>&'
                              + 'WorkDate='+sCurrDate+'&'
                              + 'ZoneLoc='+write.selZoneLoc.value+'&'
                              + 'ToExel=Yes'
                              + botttomaddr ;
            }

            //---------------------------------------------------------------
            //
            // 월일을 2자리숫자로 만들때..  0으로채워서
            //
            function fn(m)
            {
                z = '00' ;

                return z.substr(0,z.length-String(m).length) + m ;
            }


            //---------------------------------------------------------------
            //
            //  검색을 누를 때......
            //
            //
            function click_search()
            {
                var topaddr ;

                if  ( (write.Gubun.value == 0) || (write.Gubun.value == -1) )
                {
                    alert("작업메뉴를 선택하세요") ;  return false ;
                }

                //--------------------------
                // 당일회차별 - 검색을 누를 때......
                //
                if  (
                      (write.Gubun.value ==  1) || (write.Gubun.value == 27) ||
                      (write.Gubun.value == 28) || (write.Gubun.value == 33) ||
                      (write.Gubun.value == 34) || (write.Gubun.value == 37) ||
                      (write.Gubun.value == 39) || (write.Gubun.value == 29) ||
                      (write.Gubun.value == 56)
                    )
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sCurrDate = write.CurrYear.value + fn(write.CurrMonth.value) + fn(write.CurrDay.value) ;

                    <?
                    if  (!$filmproduce) // 일반 배급사 권한으로 들어왔을때..
                    {
                    ?>
                       if  (write.FilmTile.value=='00000000') // 전체영화 (일반 배급사)
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'ZoneLoc='+write.selZoneLoc.value ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'ZoneLoc='+write.selZoneLoc.value+'&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'ZoneLoc='+write.selZoneLoc.value+'&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value ;
                           }
                       }
                       else // 특정영화 (일반 배급사) //////////////////////////////////
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneLoc='+write.selZoneLoc.value ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'ZoneLoc='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value ;


                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'ZoneLoc='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value ;
                           }
                       }
                    <?
                    }
                    else  // 영화사 권한으로 들어왔을때..
                    {
                    ?>
                       //alert("Test") ;

                       if  (write.FilmTile.value=='00000000') // 전체영화 (영화사)
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'ZoneLoc='+write.selZoneLoc.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'ZoneLoc='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'ZoneLoc='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;
                           }
                       }
                       else // 특정영화 (영화사)
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneLoc='+write.selZoneLoc.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'ZoneLoc='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               topaddr     = 'wrk_filmsupply_Link_Up.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'ZoneLoc='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value ;

                               botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;
                           }
                       }
                    <?
                    }

                    ?>

                    if ((write.Gubun.value ==  1) ||
                        (write.Gubun.value == 27) ||
                        (write.Gubun.value == 29) ||
                        (write.Gubun.value == 28) ||
                        (write.Gubun.value == 33) ||
                        (write.Gubun.value == 34) ||
                        (write.Gubun.value == 37) ||
                        (write.Gubun.value == 39) ||
                        (write.Gubun.value ==  7) ||
                        (write.Gubun.value == 26) ||
                        (write.Gubun.value == 45) ||
                        (write.Gubun.value == 47) ||
                        (write.Gubun.value == 51) ||
                        (write.Gubun.value == 52) ||
                        (write.Gubun.value == 56))
                    {
                        var sample = document.getElementsByName('bFilmTypeNo');

                        nFilmTypeNo = 0;

                        for (var i=0;i<sample.length;i++)
                        {
                            if  (sample[i].checked == true)
                            {
                                nFilmTypeNo = sample[i].value ;
                            }
                        }

                        topaddr     += ('&'+'nFilmTypeNo='+nFilmTypeNo) ;
                        botttomaddr += ('&'+'nFilmTypeNo='+nFilmTypeNo) ;
                    }
                    else
                    {
                        topaddr     += ('&'+'nFilmTypeNo=') ;
                        botttomaddr += ('&'+'nFilmTypeNo=') ;
                    }


                    location.href                   = topaddr ;
                    top.frames.bottom.location.href = botttomaddr ;
                }



                //-------------------------------
                // 일자별 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 2)
                {
                    if  (((write.FilmTile.value == 0) || (write.FilmTile.value == -1)) &&
                        ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)))
                    {
                        alert("영화와 지역을 선택하세요") ;   return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                    <?
                    if  (!$filmproduce) // 일반 배급사 권한으로 들어왔을때..
                    {
                    ?>

                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    else  // 영화사 권한으로 들어왔을때..
                    {
                    ?>
                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else // 구역 선택한 경우
                           {
                              botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                                          + 'FilmTile='+write.FilmTile.value+'&'
                                          + 'filmproduce=<?=$filmproduce?>&'
                                          + 'logged_UserId=<?=$logged_UserId?>&'
                                          + 'ZoneCode='+write.selZoneLoc.value+'&'
                                          + 'FromDate='+sFromDate+'&'
                                          + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    ?>
                }

                //-------------------------------
                // 일자별2 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 9)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;   return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                    <?
                    if  (!$filmproduce) // 일반 배급사 권한으로 들어왔을때..
                    {
                    ?>

                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnK.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnK.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnK.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnK.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnK.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnK.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    else  // 영화사 권한으로 들어왔을때..
                    {
                    ?>
                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnK.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnK.php?'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnK.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnK.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    ?>
                }

                //-------------------------------
                // 일일분석 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 3)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ( (write.FilmTile.value == 0) || (write.FilmTile.value == -1) )
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ( (write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1) )
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sCurrDate = write.CurrYear.value + fn(write.CurrMonth.value) + fn(write.CurrDay.value) ;

                    botttomaddr = 'wrk_filmsupply_Link_DnD.php?'
                                + 'FilmTile='+write.FilmTile.value+'&'
                                + 'logged_UserId=<?=$logged_UserId?>&'
                                + 'WorkDate='+sCurrDate ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //------------------------------
                // 주간단위 현황 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 4)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                    {
                        botttomaddr = 'wrk_filmsupply_Link_DnF.php?'
                                    + 'FilmTile='+write.FilmTile.value+'&'
                                    + 'logged_UserId=<?=$logged_UserId?>&'
                                    + 'YearDate='+write.CurrYear.value+'&'
                                    + 'MonthDate='+write.CurrMonth.value+'&'
                                    + 'WeekDate='+write.CurrWeek.value ;
                    }
                    else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                    {
                        botttomaddr = 'wrk_filmsupply_Link_DnF.php?'
                                    + 'FilmTile='+write.FilmTile.value+'&'
                                    + 'logged_UserId=<?=$logged_UserId?>&'
                                    + 'LocationCode='+write.selZoneLoc.value+'&'
                                    + 'YearDate='+write.CurrYear.value+'&'
                                    + 'MonthDate='+write.CurrMonth.value+'&'
                                    + 'WeekDate='+write.CurrWeek.value ;
                    }
                    else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                    {
                       botttomaddr = 'wrk_filmsupply_Link_DnF.php?'
                                   + 'FilmTile='+write.FilmTile.value+'&'
                                   + 'logged_UserId=<?=$logged_UserId?>&'
                                   + 'ZoneCode='+write.selZoneLoc.value+'&'
                                   + 'YearDate='+write.CurrYear.value+'&'
                                   + 'MonthDate='+write.CurrMonth.value+'&'
                                   + 'WeekDate='+write.CurrWeek.value ;
                    }

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //-------------------------------
                // 기간별현황 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 5)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                    <?
                    if  (!$filmproduce) // 일반 배급사 권한으로 들어왔을때..
                    {
                    ?>

                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnE.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnE.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnE.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                              botttomaddr = 'wrk_filmsupply_Link_DnE.php?'
                                          + 'FilmTile='+write.FilmTile.value+'&'
                                          + 'logged_UserId=<?=$logged_UserId?>&'
                                          + 'spacial_UserId=<?=$spacial_UserId?>&'
                                          + 'ZoneCode='+write.selZoneLoc.value+'&'
                                          + 'FromDate='+sFromDate+'&'
                                          + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href =botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnE.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnE.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    else  // 영화사 권한으로 들어왔을때..
                    {
                    ?>
                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnE.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnE.php?'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnE.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnE.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    ?>
                }

                //-------------------------------
                // 극장부금정산 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 6)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                    <?
                    if  (!$filmproduce) // 일반 배급사 권한으로 들어왔을때..
                    {
                    ?>

                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                              botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                                          + 'FilmTile='+write.FilmTile.value+'&'
                                          + 'logged_UserId=<?=$logged_UserId?>&'
                                          + 'FromDate='+sFromDate+'&'
                                          + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href =botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    else  // 영화사 권한으로 들어왔을때..
                    {
                    ?>
                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    ?>
                }

                //-------------------------------
                // 회계현황 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 7)
                {
                    if  (((write.FilmTile.value == 0) || (write.FilmTile.value == -1)) &&
                        ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)))
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                    <?
                    if  (!$filmproduce) // 일반 배급사 권한으로 들어왔을때..
                    {
                    ?>

                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnH.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnH.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnH.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                              botttomaddr = 'wrk_filmsupply_Link_DnH.php?'
                                          + 'FilmTile='+write.FilmTile.value+'&'
                                          + 'logged_UserId=<?=$logged_UserId?>&'
                                          + 'FromDate='+sFromDate+'&'
                                          + 'ToDate='+sToDate ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnH.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnH.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                       }
                    <?
                    }
                    else  // 영화사 권한으로 들어왔을때..
                    {
                    ?>
                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnH.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnH.php?'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnH.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnH.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                       }
                    <?
                    }
                    ?>

                    var sample = document.getElementsByName('bFilmTypeNo');

                    nFilmTypeNo = 0;

                    for (var i=0;i<sample.length;i++)
                    {
                        if  (sample[i].checked == true)
                        {
                            nFilmTypeNo = sample[i].value ;
                        }
                    }

                    botttomaddr += ('&'+'nFilmTypeNo='+nFilmTypeNo) ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //-------------------------------
                // 금액별 회계 현황 - 검색을 누를 때......
                //
                else if  ((write.Gubun.value == 26) ||  // 금액별 회계현황
				          (write.Gubun.value == 38) ||  // 금액별 회계현황 (롯데시네마)
						  (write.Gubun.value == 40) ||  // 금액별 회계현황 (메가박스)
						  (write.Gubun.value == 51) ||  // 금액별 회계현황 (CGV)
						  (write.Gubun.value == 52) )   // 금액별 회계현황 (기타)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                    <?
                    if  (!$filmproduce) // 일반 배급사 권한으로 들어왔을때..
                    {
                    ?>

                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                              botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                                          + 'WorkGubun='+write.Gubun.value+'&'
                                          + 'FilmTile='+write.FilmTile.value+'&'
                                          + 'logged_UserId=<?=$logged_UserId?>&'
                                          + 'FromDate='+sFromDate+'&'
                                          + 'ToDate='+sToDate ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                       }
                    <?
                    }
                    else  // 영화사 권한으로 들어왔을때..
                    {
                    ?>
                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;
                           }
                       }
                    <?
                    }
                    ?>
                    var sample = document.getElementsByName('bFilmTypeNo');

                    nFilmTypeNo = 0;

                    for (var i=0;i<sample.length;i++)
                    {
                        if  (sample[i].checked == true)
                        {
                            nFilmTypeNo = sample[i].value ;
                        }
                    }

                    botttomaddr += ('&'+'nFilmTypeNo='+nFilmTypeNo) ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //-------------------------------
                // 관객현황 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 8)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                    <?
                    if  (!$filmproduce) // 일반 배급사 권한으로 들어왔을때..
                    {
                    ?>

                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                              botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                                          + 'FilmTile='+write.FilmTile.value+'&'
                                          + 'logged_UserId=<?=$logged_UserId?>&'
                                          + 'FromDate='+sFromDate+'&'
                                          + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href =botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    else  // 영화사 권한으로 들어왔을때..
                    {
                    ?>
                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화
                       {
                           if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    ?>
                }

                //--------------------------
                // 당일회차별 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 10)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sCurrDate = write.CurrYear.value + fn(write.CurrMonth.value) + fn(write.CurrDay.value) ;


                    <?
                    if  (!$filmproduce) // 일반 배급사 권한으로 들어왔을때..
                    {
                    ?>
                       if  (write.FilmTile.value=='00000000') // 전체영화 (일반 배급사)
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화 (일반 배급사) //////////////////////////////////
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    else  // 영화사 권한으로 들어왔을때..
                    {
                    ?>
                       //alert("Test") ;

                       if  (write.FilmTile.value=='00000000') // 전체영화 (영화사)
                       {
                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                       else // 특정영화 (영화사)
                       {
                           //alert(write.selZoneLoc.value.length) ;

                           if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'LocationCode='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                           else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                           {
                               botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                                           + 'FilmTile='+write.FilmTile.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>&'
                                           + 'logged_UserId=<?=$logged_UserId?>&'
                                           + 'spacial_UserId=<?=$spacial_UserId?>&'
                                           + 'ZoneCode='+write.selZoneLoc.value+'&'
                                           + 'WorkDate='+sCurrDate+'&'
                                           + 'WorkGubun='+write.Gubun.value+'&'
                                           + 'filmproduce=<?=$filmproduce?>' ;

                               top.frames.bottom.location.href = botttomaddr ;
                           }
                       }
                    <?
                    }
                    ?>
                }
                //--------------------------
                // 선재현황(보고) - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 11)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                    {
                        botttomaddr = 'wrk_filmsupply_Link_DnR.php?'
                                    + 'FilmTile='+write.FilmTile.value+'&'
                                    + 'filmproduce=<?=$filmproduce?>&'
                                    + 'logged_UserId=<?=$logged_UserId?>&'
                                    + 'spacial_UserId=<?=$spacial_UserId?>&'
                                    + 'ZoneCode='+write.selZoneLoc.value+'&'
                                    + 'WorkGubun='+write.Gubun.value+'&'
                                    + 'filmproduce=<?=$filmproduce?>'+'&'
                                    + 'menu='+write.Gubun.value ;
                    }
                    else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                    {
                        botttomaddr = 'wrk_filmsupply_Link_DnR.php?'
                                    + 'FilmTile='+write.FilmTile.value+'&'
                                    + 'filmproduce=<?=$filmproduce?>&'
                                    + 'logged_UserId=<?=$logged_UserId?>&'
                                    + 'spacial_UserId=<?=$spacial_UserId?>&'
                                    + 'LocationCode='+write.selZoneLoc.value+'&'
                                    + 'WorkGubun='+write.Gubun.value+'&'
                                    + 'filmproduce=<?=$filmproduce?>'+'&'
                                    + 'menu='+write.Gubun.value ;
                    }
                    else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                    {
                        botttomaddr = 'wrk_filmsupply_Link_DnR.php?'
                                    + 'FilmTile='+write.FilmTile.value+'&'
                                    + 'filmproduce=<?=$filmproduce?>&'
                                    + 'logged_UserId=<?=$logged_UserId?>&'
                                    + 'spacial_UserId=<?=$spacial_UserId?>&'
                                    + 'ZoneCode='+write.selZoneLoc.value+'&'
                                    + 'WorkGubun='+write.Gubun.value+'&'
                                    + 'filmproduce=<?=$filmproduce?>'+'&'
                                    + 'menu='+write.Gubun.value ;
                    }


                    top.frames.bottom.location.href = botttomaddr ;
                }
                //--------------------------
                // 관리자설정 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 12)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    if  (prompt("암호를 입력하세요","")=="2120")
                    {
                        if  (write.selZoneLoc.value.length==4) // 전체 지역 선택한 경우
                        {
                            botttomaddr = 'wrk_filmsupply_Link_DnR.php?'
                                        + 'FilmTile='+write.FilmTile.value+'&'
                                        + 'filmproduce=<?=$filmproduce?>&'
                                        + 'logged_UserId=<?=$logged_UserId?>&'
                                        + 'spacial_UserId=<?=$spacial_UserId?>&'
                                        + 'ZoneCode='+write.selZoneLoc.value+'&'
                                        + 'WorkGubun='+write.Gubun.value+'&'
                                        + 'filmproduce=<?=$filmproduce?>'+'&'
                                        + 'menu='+write.Gubun.value ;
                        }
                        else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                        {
                            botttomaddr = 'wrk_filmsupply_Link_DnR.php?'
                                        + 'FilmTile='+write.FilmTile.value+'&'
                                        + 'filmproduce=<?=$filmproduce?>&'
                                        + 'logged_UserId=<?=$logged_UserId?>&'
                                        + 'spacial_UserId=<?=$spacial_UserId?>&'
                                        + 'LocationCode='+write.selZoneLoc.value+'&'
                                        + 'WorkGubun='+write.Gubun.value+'&'
                                        + 'filmproduce=<?=$filmproduce?>'+'&'
                                        + 'menu='+write.Gubun.value ;
                        }
                        else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                        {
                            botttomaddr = 'wrk_filmsupply_Link_DnR.php?'
                                        + 'FilmTile='+write.FilmTile.value+'&'
                                        + 'filmproduce=<?=$filmproduce?>&'
                                        + 'logged_UserId=<?=$logged_UserId?>&'
                                        + 'spacial_UserId=<?=$spacial_UserId?>&'
                                        + 'ZoneCode='+write.selZoneLoc.value+'&'
                                        + 'WorkGubun='+write.Gubun.value+'&'
                                        + 'filmproduce=<?=$filmproduce?>'+'&'
                                        + 'menu='+write.Gubun.value ;
                        }


                        top.frames.bottom.location.href = botttomaddr ;
                    }
                }

                //--------------------------
                // 집계현황(그래프) - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 13)
                {
                    if  ( (write.FilmTile.value == 0) || (write.FilmTile.value == -1) )
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sCurrDate = write.CurrYear.value + fn(write.CurrMonth.value) + fn(write.CurrDay.value) ;

                    // 특정영화
                    botttomaddr = 'Flex_filmsupply_Link_DnB.php?'
                                + 'FilmTitle='+write.FilmTile.value+'&'
                                + 'logged_UserId=<?=$logged_UserId?>&'
                                + 'filmsupplyCode=<?=$filmsupplyCode?>&'
                                + 'WorkDate='+sCurrDate ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // AutoLogin - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 14)
                {
                    botttomaddr = "AutoLogin.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 개별지역설정 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 15)
                {
                    botttomaddr = "wrk_filmsupply_2.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 복합지역설정 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 16)
                {
                    botttomaddr = "wrk_filmsupply_3.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 실무자공지발송 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 17)
                {
                    botttomaddr = "wrk_film_Gongji.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 등록된실무자 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 18)
                {
                    botttomaddr = "wrk_filmsupply_5.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 실무자영화배정 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 19)
                {
                    if  ( (write.FilmTile.value == 0) || (write.FilmTile.value == -1) )
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    botttomaddr = "wrk_filmsupply_S2.php?FilmTitle="+write.FilmTile.value ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // SMS관리극장 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 20)
                {
                    if  ( (write.FilmTile.value == 0) || (write.FilmTile.value == -1) )
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    botttomaddr = "SmsChkTheather.php?FilmTitle="+write.FilmTile.value ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // PHP수정일자 설정 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 21)
                {
                    botttomaddr = "MofidyLimitDate.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 메모리비우기 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 22)
                {
                    if  ( (write.FilmTile.value == 0) || (write.FilmTile.value == -1) )
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    topaddr = "<?=$PHP_SELF?>?ClearAcc=Yes&"
                            + 'WorkGubun='+write.Gubun.value+'&'
                            + 'FilmTile='+write.FilmTile.value ;

                    botttomaddr = "nosingo.htm" ;


                    location.href                   = topaddr ;
                    top.frames.bottom.location.href = botttomaddr ;
                }
                //--------------------------
                // 상대영화 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 23)
                {
                    botttomaddr = "wrk_sangdae.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 상대영화 등록/삭제 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 24)
                {
                    botttomaddr = "wrk_sangdae_RD.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 극장순서변경 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 25)
                {
                    if  (((write.FilmTile.value == 0) || (write.FilmTile.value == -1)) &&
                        ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)))
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    if  (write.selZoneLoc.value.length==4) // 전체 지역(4) 선택한 경우
                    {
                        botttomaddr = "wrk_singoordering.php?"
                                    + 'FilmTile='+write.FilmTile.value ;
                    }
                    else if  (write.selZoneLoc.value.length==3) // 지역(3) 선택한 경우
                    {
                        botttomaddr = "wrk_singoordering.php?"
                                    + 'FilmTile='+write.FilmTile.value+'&'
                                    + 'LocCode='+write.selZoneLoc.value ;
                    }
                    else if  (write.selZoneLoc.value.length==2) // 구역(2) 선택한 경우
                    {
                        botttomaddr = "wrk_singoordering.php?"
                                    + 'FilmTile='+write.FilmTile.value+'&'
                                    + 'ZoneCode='+write.selZoneLoc.value ;
                    }

                    top.frames.bottom.location.href = botttomaddr ;
                }
                //--------------------------
                // 단문자전송 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 30)
                {
                    botttomaddr = "wrk_filmsupply_Link_DnSMS.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }
                //--------------------------
                // 그룹별현황(일별) - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 31)
                {
                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sCurrDate = write.CurrYear.value + fn(write.CurrMonth.value) + fn(write.CurrDay.value) ;

                    botttomaddr = "wrk_filmsupply_Link_DnT.php?"
                                + 'FilmTile='+write.FilmTile.value+'&'
                                + 'WorkDate='+sCurrDate ;

                    top.frames.bottom.location.href = botttomaddr ;
                }
                //--------------------------
                // 그룹별현황(기간별) - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 32)
                {
                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                    botttomaddr = "wrk_filmsupply_Link_DnT.php?"
                                + 'FilmTile='+write.FilmTile.value+'&'
                                + 'FromDate='+sFromDate+'&'
                                + 'ToDate='+sToDate ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //-------------------------------
                // 부율 입력
                //
                else if  (write.Gubun.value == 35)
                {
                    if  (((write.FilmTile.value == 0) || (write.FilmTile.value == -1)) &&
                        ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)))
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sCurrDate = write.CurrYear.value + fn(write.CurrMonth.value) + fn(write.CurrDay.value) ;

                    if  (write.selZoneLoc.value.length==4) // 전체 지역(4) 선택한 경우
                    {
                        botttomaddr = 'wrk_TheatherRate_Write.php?'
                                    + 'logged_UserId=<?=$logged_UserId?>&'
                                    + 'WorkDate='+sCurrDate+'&'
                                    + 'WorkGubun='+write.Gubun.value+'&'
                                    + 'FilmTile='+write.FilmTile.value ;
                    }
                    else if  (write.selZoneLoc.value.length==3) // 지역(3) 선택한 경우
                    {
                        botttomaddr = 'wrk_TheatherRate_Write.php?'
                                    + 'logged_UserId=<?=$logged_UserId?>&'
                                    + 'WorkDate='+sCurrDate+'&'
                                    + 'WorkGubun='+write.Gubun.value+'&'
                                    + 'FilmTile='+write.FilmTile.value+'&'
                                    + 'LocCode='+write.selZoneLoc.value;
                    }
                    else if  (write.selZoneLoc.value.length==2) // 구역(2) 선택한 경우
                    {
                        botttomaddr = 'wrk_TheatherRate_Write.php?'
                                    + 'logged_UserId=<?=$logged_UserId?>&'
                                    + 'WorkDate='+sCurrDate+'&'
                                    + 'WorkGubun='+write.Gubun.value+'&'
                                    + 'FilmTile='+write.FilmTile.value+'&'
                                    + 'ZoneCode='+write.selZoneLoc.value ;
                    }


                    top.frames.bottom.location.href = botttomaddr ;
                }

                //-------------------------------
                // 부율 보기
                //
                else if  (write.Gubun.value == 36)
                {
                    if  (((write.FilmTile.value == 0) || (write.FilmTile.value == -1)) &&
                        ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)))
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                    botttomaddr = 'wrk_TheatherRate_View.php?'
                                + 'logged_UserId=<?=$logged_UserId?>&'
                                + 'FromDate='+sFromDate+'&'
                                + 'ToDate='+sToDate+'&'
                                + 'WorkGubun='+write.Gubun.value+'&'
                                + 'FilmTile='+write.FilmTile.value+'&'
                                + 'LocationCode='+write.selZoneLoc.value;

                    top.frames.bottom.location.href = botttomaddr ;
                }
                //--------------------------
                // 엑셀스코어일괄전송 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 41)
                {
                    botttomaddr = "wrk_filmsupply_Link_DnExcel.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 엑셀스코어일괄전송 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 46)
                {
                    botttomaddr = "wrk_filmsupply_Link_DnExcel.php?digital=yes" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 영화별 자료정리 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 42)
                {
                    botttomaddr = "wrk_filmsupply_Link_DnFilmAdjust.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 상영관별 필름종류 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 43)
                {

                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sCurrDate = write.CurrYear.value + fn(write.CurrMonth.value) + fn(write.CurrDay.value) ;

                    if  (write.selZoneLoc.value.length==4)  // 전체 지역 선택한 경우
                    {
                         botttomaddr = 'wrk_filmsupply_Link_DnFilmType.php?'
                                     + 'ZoneCode='+write.selZoneLoc.value+'&'
                                     + 'FilmTile='+write.FilmTile.value+'&'
                                     + 'WorkDate='+sCurrDate ;
                    }
                    else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                    {
                         botttomaddr = 'wrk_filmsupply_Link_DnFilmType.php?'
                                     + 'LocationCode='+write.selZoneLoc.value+'&'
                                     + 'FilmTile='+write.FilmTile.value+'&'
                                     + 'WorkDate='+sCurrDate ;
                    }
                    else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                    {
                         botttomaddr = 'wrk_filmsupply_Link_DnFilmType.php?'
                                     + 'FilmTile='+write.FilmTile.value+'&'
                                     + 'ZoneCode='+write.selZoneLoc.value+'&'
                                     + 'WorkDate='+sCurrDate ;
                    }

                    top.frames.bottom.location.href = botttomaddr ;
                }
                //--------------------------
                // 당일 구분별 현황 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 44)
                {
                    if  ( (write.FilmTile.value == 0) || (write.FilmTile.value == -1) )
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sCurrDate = write.CurrYear.value + fn(write.CurrMonth.value) + fn(write.CurrDay.value) ;

                    // 특정영화
                    botttomaddr = 'wrk_filmsupply_Link_DnU.php?'
                                + 'FilmTile='+write.FilmTile.value+'&'
                                + 'logged_UserId=<?=$logged_UserId?>&'
                                + 'filmsupplyCode=<?=$filmsupplyCode?>&'
                                + 'WorkDate='+sCurrDate ;

                    top.frames.bottom.location.href = botttomaddr ;
                }
                //--------------------------
                // 디지털 회차체크보고서 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 45)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }


                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                       botttomaddr = 'wrk_filmsupply_Link_DnDigi.php?'
                                   + 'WorkGubun='+write.Gubun.value+'&'
                                   + 'logged_UserId=<?=$logged_UserId?>&' ;

                       if  (write.FilmTile.value=='00000000') // 전체영화
                       {

                               botttomaddr += 'FromDate='+sFromDate+'&'
                                            + 'ToDate='+sToDate ;

                       }
                       else // 특정영화
                       {

                              botttomaddr += 'FilmTile='+write.FilmTile.value+'&'
                                           + 'FromDate='+sFromDate+'&'
                                           + 'ToDate='+sToDate ;

                       }

                    var sample = document.getElementsByName('bFilmTypeNo');

                    nFilmTypeNo = 0;

                    for (var i=0;i<sample.length;i++)
                    {
                        if  (sample[i].checked == true)
                        {
                            nFilmTypeNo = sample[i].value ;
                        }
                    }

                    botttomaddr += ('&'+'nFilmTypeNo='+nFilmTypeNo) ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 영화별 타입제한 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 47)
                {
                    botttomaddr = "wrk_filmsupply_Link_DnFilmTypeLimit.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //--------------------------
                // 금액별 누계 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 48)
                {
                    if  ( ((write.FilmTile.value   == 0) || (write.FilmTile.value   == -1)) &&
                          ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1)) )
                    {
                        alert("영화와 지역을 선택하세요") ;  return false ;
                    }

                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

                    if  (write.selZoneLoc.value.length==4)  // 전체 지역 선택한 경우
                    {
                         botttomaddr = 'wrk_filmsupply_Link_DnSumAmt.php?'
                                     + 'FilmTile='+write.FilmTile.value+'&'
                                     + 'FromDate='+sFromDate+'&'
                                     + 'ToDate='+sToDate ;
                    }
                    else if  (write.selZoneLoc.value.length==3) // 지역 선택한 경우
                    {
                         botttomaddr = 'wrk_filmsupply_Link_DnSumAmt.php?'
                                     + 'LocationCode='+write.selZoneLoc.value+'&'
                                     + 'FilmTile='+write.FilmTile.value+'&'
                                     + 'FromDate='+sFromDate+'&'
                                     + 'ToDate='+sToDate ;
                    }
                    else if  (write.selZoneLoc.value.length==2) // 구역 선택한 경우
                    {
                         botttomaddr = 'wrk_filmsupply_Link_DnSumAmt.php?'
                                     + 'ZoneCode='+write.selZoneLoc.value+'&'
                                     + 'FilmTile='+write.FilmTile.value+'&'
                                     + 'FromDate='+sFromDate+'&'
                                     + 'ToDate='+sToDate ;
                    }


                    top.frames.bottom.location.href = botttomaddr ;
                }
				//--------------------------
                // 전국극장 순위 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 50)
                {
                    if  ((write.FilmTile.value == 0) || (write.FilmTile.value == -1))
                    {
                        alert("영화를 선택하세요") ;  return false ;
                    }

                    // 사용자가 선택한날짜.
                    sFromDate = write.FromYear.value + fn(write.FromMonth.value) + fn(write.FromDay.value) ;
                    sToDate   = write.ToYear.value   + fn(write.ToMonth.value)   + fn(write.ToDay.value) ;

					 botttomaddr = 'wrk_filmsupply_Link_DnX.php?'
								 + 'FilmTile='+write.FilmTile.value+'&'
								 + 'FromDate='+sFromDate+'&'
								 + 'ToDate='+sToDate ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

				//--------------------------
                // 좌석수 지정 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 53)
                {
                    if  ((write.selZoneLoc.value == 0) || (write.selZoneLoc.value == -1))
                    {
                        alert("지역을 선택하세요") ;  return false ;
                    }

					botttomaddr = 'wrk_filmsupply_Link_DnSetSeat.php?'
							  + 'ZoneCode='+write.selZoneLoc.value ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

				//--------------------------
                // 토털스코어 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 54)
                {
					 window.open("http://www.mtns7.co.kr/totalscore/");
                }

				//--------------------------
                // 극장 기금 적용 - 검색을 누를 때......
                //
                else if  (write.Gubun.value == 55)
                {
                    botttomaddr = "wrk_filmsupply_Link_DnTheatherInfo.php" ;

                    top.frames.bottom.location.href = botttomaddr ;
                }

                //alert(write.Gubun.value);

            }





            //---------------------------------------------------------------
            //
            // 작업구분 콤보박스를 선택할 때..
            //
            //
            function select_Gubun(item)
            {
                now = new Date() ;  //

                sYear  = now.getFullYear() ;  // 현재 년도
                sMonth = now.getMonth() + 1 ; // 현재 월
                sDate  = now.getDate() ;      // 현재 일자

				var space = "" ;

                strScript = "";

                if  (
                      (item.value=="11") || (item.value=="12") || (item.value=="13") ||
                      (item.value=="17") || (item.value=="22") || (item.value=="23") ||
                      (item.value=="24") || (item.value=="25") || (item.value=="30") ||
                      (item.value=="41") || (item.value=="42") || (item.value=="44") ||
                      (item.value=="46") || (item.value=="45") || (item.value=="47") || (item.value=="55")
                    )
                {
                    strScript = "";// divdate.innerHTML = "" ;
                }

                //------------------------------------------
                // 당일회차별현황, 일자별 현황 - 콤보박스를 선택할 때..
                //
                if  (
                      (item.value== "1") || (item.value== "3") || (item.value=="10") ||
                      (item.value=="13") || (item.value=="27") || (item.value=="28") ||
                      (item.value=="29") || (item.value=="33") || (item.value=="34") ||
                      (item.value=="37") || (item.value=="39") || (item.value=="31") ||
                      (item.value=="35") || (item.value=="43") || (item.value=="44") ||
                      (item.value=="45") || (item.value=="48") || (item.value=="50") ||
                      (item.value=="56")
                    )
                {
                    strScript   = "<select name=CurrYear>" ;
					for (var i = 2004 ; i <= (sYear+1) ; i++ )
					{
						if  (i == sYear) strScript  += "<option value="+i+" selected>"+i+"</option>" ;
						else 	         strScript  += "<option value="+i+">"+i+"</option>";
					}
                    strScript  += "</select>년" ;

                    strScript  += "<select name=CurrMonth>" ;
					for (var i = 1 ; i <= 12 ; i++ )
					{
						if ( i < 10) space = "&nbsp;&nbsp;" ; else  space = "" ;

						if  (i == sMonth) strScript  += "<option value="+i+" selected>&nbsp;"+space+i+"</option>" ;
						else  	          strScript  += "<option value="+i+">&nbsp;"+space+i+"</option>";
					}
                    strScript  += "</select>월" ;

                    strScript  += "<select name=CurrDay>" ;
					for (var i = 1 ; i <= 31 ; i++ )
					{
						if ( i < 10) space = "&nbsp;&nbsp;" ; else  space = "" ;

						if  (i == sDate) strScript  += "<option value="+i+" selected>&nbsp;"+space+i+"</option>" ;
						else  	         strScript  += "<option value="+i+">&nbsp;"+space+i+"</option>";
					}
                    strScript  += "</select>일" ;
                }

                //-------------------
                // 주간단위 현황 - 콤보박스를 선택할 때..
                //
                if  ( item.value== "4" )
                {
                    strScript   = "<select name=CurrYear>" ;
                    for (var i = 2004 ; i <= (sYear+1) ; i++ )
					{
						if  (i == sYear) strScript  += "<option value="+i+" selected>"+i+"</option>" ;
						else 	         strScript  += "<option value="+i+">"+i+"</option>";
					}
                    strScript  += "</select>년" ;

                    strScript  += "<select name=CurrMonth>" ;
                    for (var i = 1 ; i <= 12 ; i++ )
					{
						if ( i < 10) space = "&nbsp;&nbsp;" ; else  space = "" ;

						if  (i == sMonth) strScript  += "<option value="+i+" selected>&nbsp;"+space+i+"</option>" ;
						else  	          strScript  += "<option value="+i+">&nbsp;"+space+i+"</option>";
					}
                    strScript  += "</select>월" ;

                    strScript  += "<select name=CurrWeek>" ;
					for (var i = 1 ; i <= 5 ; i++ )
					{
						if ( i < 10) space = "&nbsp;&nbsp;" ; else  space = "" ;

						if  (i == sDate) strScript  += "<option value="+i+" selected>&nbsp;"+space+i+"</option>" ;
						else  	         strScript  += "<option value="+i+">&nbsp;"+space+i+"</option>";
					}
                    strScript  += "</select>주" ;

                    //divdate.innerHTML = strScript ;
                }

                if  ( (item.value=="14") || (item.value=="15") || (item.value=="16") ||
                      (item.value=="18") || (item.value=="21") || (item.value=="23") ||
                      (item.value=="24") || (item.value=="30") || (item.value=="41") ||
                      (item.value=="42") || (item.value=="46") || (item.value=="47") ||
					  (item.value=="53") || (item.value=="54") || (item.value=="55")
                    )
                {
                    chungfilm.innerHTML = "" ;
                }
                else
                {
                    strChungfilm  = "<select name='FilmTile' id='selFilmTile' onchange='selFilmTileChange(this.options[this.selectedIndex].value);'>\n" ;
                    strChungfilm += "<option value=0 selected>영화선택</option>\n" ;
                    strChungfilm += "<option value=-1>------------</option>\n" ;
                    <?
                    if  ($filmproduce) // $filmproduce 영화사 코드가 있는 경우는 해당영화사의 영화만 option으로 나와야 한다.
                    {
                        $sQuery = "Select * From bas_filmproduce      ".
                                  " Where UserId = '".$filmproduce."' " ;
                        $qryfilmproduce = mysql_query($sQuery,$connect) ;
                        if  ($filmproduce_data = mysql_fetch_array($qryfilmproduce))
                        {
                            $filmproduceCode = $filmproduce_data["Code"] ;
                        }

                        $sQuery = "Select * From bas_filmtitle                 ".
                                  " Where FilmProduce = '".$filmproduceCode."' ".
                                  "   And Finish      <> 'Y'                   ".
                                  "   And FilmProduce = '400001' ".
                                  " Order By Open desc                         " ;
                    }
                    else  // 배급사 권한으로 들어온경우 배급사가 관리하는(선택한) 모든 영화를 option으로 나와야 한다.
                    {
                        $sQuery = "Select * From bas_filmtitle   ".
                                  " Where Finish <> 'Y'          ".
                                  " Order By Open desc          " ;
                        if  ($logged_UserId == "bros5656")
                        {
                            $sQuery = "Select * From bas_filmtitle   ".
                                      " Where Finish <> 'Y'          ".
                                      "   And FilmProduce = '400001' ".
                                      " Order By Open desc               " ;
                        }
                        if  ($logged_UserId == "test")
                        {
                            $sQuery = "Select * From bas_filmtitle   ".
                                      " Where Finish <> 'Y'          ".
                                      "   And FilmProduce = '999999' ".
                                      " Order By Open desc           " ;
                        }
                    }

                    $qrysingotitle = mysql_query($sQuery,$connect) ;
                    while ($singotitle_data = mysql_fetch_array($qrysingotitle))
                    {
                         if  ($FilmTile == $singotitle_data["Open"].$singotitle_data["Code"])
                         {
                         ?>
                             strChungfilm += "<option value=<?=$singotitle_data["Open"].$singotitle_data["Code"]?> selected><?=$singotitle_data["Name"]?></option>";
                         <?
                         }
                         else
                         {
                         ?>
                             strChungfilm += "<option value=<?=$singotitle_data["Open"].$singotitle_data["Code"]?>><?=$singotitle_data["Name"]?></option> ";
                         <?
                         }
                    }
                    ?>
                    strChungfilm += "</select>" ;

                    chungfilm.innerHTML = strChungfilm ;
                }


                //---------------------------------------------------------------
                // 일자별 현황, 기간별 현황, 극장별부금정산, 회계현황, 관객현황 - 콤보박스를 선택할 때..
                //

                if  ( (item.value=="13") || (item.value=="14") || (item.value=="15") ||
                      (item.value=="16") || (item.value=="17") || (item.value=="18") ||
                      (item.value=="19") || (item.value=="20") || (item.value=="21") ||
                      (item.value=="22") || (item.value=="23") || (item.value=="24") ||
                      (item.value=="30") || (item.value=="41") || (item.value=="42") ||
                      (item.value=="31") || (item.value=="32") || (item.value=="44") ||
                      (item.value=="46") || (item.value=="45") || (item.value=="47") ||
					  (item.value=="50") || (item.value=="54") || (item.value=="55")
                    )
                {
                    chungsecter.innerHTML = "" ;   // 지역을 나타내지 않는다.
                }
                else
                {
                    strChungsecter  = "\n<select name=selZoneLoc>" ;
                    strChungsecter  += "\n<option value=0 selected>지역선택</option>" ;
                    strChungsecter  += "\n<option value=-1>--------</option>" ;
                    <?
                    if  ($TimJang == false) // 이부장
                    {
                        if  ($ZoneLoc == "100")  echo "\n strChungsecter  += \"<option value=100 selected>서울</option>\"" ;
                        else                     echo "\n strChungsecter  += \"<option value=100>서울</option>\"" ;
                        if  ($ZoneLoc == "04")   echo "\n strChungsecter  += \"<option value=04 selected>경기</option>\"" ;
                        else                     echo "\n strChungsecter  += \"<option value=04>경기</option>\"" ;
                    }
                    if  ($ZoneLoc == "200")  echo "\n strChungsecter  += \"<option value=200 selected>부산</option>\"" ;
                    else                     echo "\n strChungsecter  += \"<option value=200>부산</option>\"" ;
                    if  ($ZoneLoc == "99")   echo "\n strChungsecter  += \"<option value=99 selected>지방</option>\"" ;
                    else                     echo "\n strChungsecter  += \"<option value=99>지방</option>\"" ;
                    if  ($ZoneLoc == "9999") echo "\n strChungsecter  += \"<option value=9999 selected>전체</option>\"" ;
                    else                     echo "\n strChungsecter  += \"<option value=9999>전체</option>\"" ;
                    ?>

                    strChungsecter  += "\n</select>" ;

                    chungsecter.innerHTML = strChungsecter ;
                }

                if  ( (item.value== "2") || (item.value== "9") || (item.value=="5") ||
                      (item.value== "6") || (item.value== "7") || (item.value=="8") ||
                      (item.value=="26") || (item.value=="38") || (item.value=="40") ||
                      (item.value=="32") || (item.value=="36") || (item.value=="45") ||
					  (item.value=="48") || (item.value=="50") || (item.value=="51")  || (item.value=="52") )
                {
                    strScript   = "<select name=FromYear>" ;
					for (var i = 2004 ; i <= (sYear+1) ; i++ )
					{
						if  (i == sYear) strScript  += "<option value="+i+" selected>"+i+"</option>" ;
						else 	         strScript  += "<option value="+i+">"+i+"</option>";
					}
                    strScript  += "</select>년" ;

                    strScript  += "<select name=FromMonth>" ;
                    for (var i = 1 ; i <= 12 ; i++ )
					{
						if ( i < 10) space = "&nbsp;&nbsp;" ; else  space = "" ;

						if  (i == sMonth) strScript  += "<option value="+i+" selected>&nbsp;"+space+i+"</option>" ;
						else  	          strScript  += "<option value="+i+">&nbsp;"+space+i+"</option>";
					}
                    strScript  += "</select>월" ;

                    strScript  += "<select name=FromDay>" ;
                    for (var i = 1 ; i <= 31 ; i++ )
					{
						if ( i < 10) space = "&nbsp;&nbsp;" ; else  space = "" ;

						if  (i == sDate) strScript  += "<option value="+i+" selected>&nbsp;"+space+i+"</option>" ;
						else  	         strScript  += "<option value="+i+">&nbsp;"+space+i+"</option>";
					}
                    strScript  += "</select>일" ;

                    strScript  += "&nbsp;~&nbsp;" ;

                    strScript  += "<select name=ToYear>" ;
					for (var i = 2004 ; i <= (sYear+1) ; i++ )
					{
						if  (i == sYear) strScript  += "<option value="+i+" selected>"+i+"</option>" ;
						else 	         strScript  += "<option value="+i+">"+i+"</option>";
					}
                    strScript  += "</select>년" ;

                    strScript  += "<select name=ToMonth>" ;
					for (var i = 1 ; i <= 12 ; i++ )
					{
						if ( i < 10) space = "&nbsp;&nbsp;" ; else  space = "" ;

						if  (i == sMonth) strScript  += "<option value="+i+" selected>&nbsp;"+space+i+"</option>" ;
						else  	          strScript  += "<option value="+i+">&nbsp;"+space+i+"</option>";
					}
                    strScript  += "</select>월" ;

                    strScript  += "<select name=ToDay>" ;
                    for (var i = 1 ; i <= 31 ; i++ )
					{
						if ( i < 10) space = "&nbsp;&nbsp;" ; else  space = "" ;

						if  (i == sDate) strScript  += "<option value="+i+" selected>&nbsp;"+space+i+"</option>" ;
						else  	         strScript  += "<option value="+i+">&nbsp;"+space+i+"</option>";
					}
                    strScript  += "</select>일" ;

                    //divdate.innerHTML = strScript ;
                }

                // all 35 2 3 29 39
                if  (( item.value=="1")  ||
                     ( item.value=="27") ||
                     ( item.value=="29") ||
                     ( item.value=="28") ||
                     ( item.value=="33") ||
                     ( item.value=="34") ||
                     ( item.value=="37") ||
                     ( item.value=="39") ||
                     ( item.value=="7")  ||
                     ( item.value=="26") ||
                     ( item.value=="38") ||
                     ( item.value=="40") ||
                     ( item.value=="45") ||
                     ( item.value=="51") ||
                     ( item.value=="52") ||
                     ( item.value=="56"))

                {
                    //alert($("#selFilmTile").val());

                    strScript  += "<br>";

                    sChk = <?if  ($nFilmTypeNo==0) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='0' "+sChk+">ALL" ;

                    sChk = <?if  ($nFilmTypeNo==35) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='35' "+sChk+">35" ;

                    sChk = <?if  ($nFilmTypeNo==2) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='2' "+sChk+">2" ;

                    sChk = <?if  ($nFilmTypeNo==20) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='20' "+sChk+">20" ;

                    sChk = <?if  ($nFilmTypeNo==3) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='3' "+sChk+">3" ;

                    sChk = <?if  ($nFilmTypeNo==30) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='30' "+sChk+">30" ;

                    sChk = <?if  ($nFilmTypeNo==29) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='29' "+sChk+">29" ;

                    sChk = <?if  ($nFilmTypeNo==39) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='39' "+sChk+">39" ;

                    sChk = <?if  ($nFilmTypeNo==24) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='24' "+sChk+">24" ;

                    sChk = <?if  ($nFilmTypeNo==34) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='34' "+sChk+">34" ;

                    sChk = <?if  ($nFilmTypeNo==294) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='294' "+sChk+">294" ;

                    sChk = <?if  ($nFilmTypeNo==394) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='394' "+sChk+">394" ;

                    sChk = <?if  ($nFilmTypeNo==4) {?>"checked" ;<?} else {?> "";<?}?>
                    strScript  += "<input type='radio' name='bFilmTypeNo' value='4' "+sChk+">4" ;

                    // 35mm는 35, 디지털 투디는 2 디지털 더빙은 20 디지털 쓰리디는 3 디지털 쓰리디 더빙은 30 아이맥스 투디는 29 아이맥스 쓰리디는 39 -
                }
                divdate.innerHTML = strScript; //+" "+item.value ;

            }

        //-->
        </script>

    </head>


    <body bgcolor=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>


    <center>

      <form method=post name=write>


          <table cellpadding=0 cellspacing=0 border=0 width="100%">
          <tr valign=bottom>

               <?
               if  (!$ToExel)  // 엑셀출력시
               {
               ?>

               <td width="50%" align=center>


                    <?
                    if  ($TimJang == false)  // 이부장..
                    {
                        ?>
                        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                                codebase="http://active.macromedia.com/flash4/cabs/swflash.cab#version=4,0,0,0"
                                width="400"
                                height="100">
                           <param name="movie" value="main.swf">
                           <param name="play" value="true">
                           <param name="loop" value="true">
                           <param name="quality" value="high">
                           <embed src="main.swf"
                                  play="true"
                                  loop="true"
                                  quality="high"
                                  pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"
                                  width="800"
                                  height="200">
                           </embed>
                        </object>
                        <?
                    }
                    ?>


                    <?
                    if  ($TimJang == false)  // 이부장..
                    {
                        ?>
                        <br><br>

                        <table border=0>
                        <tr>
                            <td bgcolor=#483d8b align=center>
                               <font color=white><b>
                               <?
                               $WeekDay = date( "w", mktime(0, 0, 0, substr($WorkDate,4,2), substr($WorkDate,6,2), substr($WorkDate,0,4)) );
                               if  ($WeekDay==0)  $sWeekDay = "일요일" ;
                               if  ($WeekDay==1)  $sWeekDay = "월요일" ;
                               if  ($WeekDay==2)  $sWeekDay = "화요일" ;
                               if  ($WeekDay==3)  $sWeekDay = "수요일" ;
                               if  ($WeekDay==4)  $sWeekDay = "목요일" ;
                               if  ($WeekDay==5)  $sWeekDay = "금요일" ;
                               if  ($WeekDay==6)  $sWeekDay = "토요일" ;

                               echo substr($WorkDate,0,4)."년".substr($WorkDate,4,2)."월".substr($WorkDate,6,2)."일".$sWeekDay ;
                               ?>
                               </b></font>
                            </td>
                        </tr>
                        </table>

                        <br>
                        <?
                    }

                    //echo "<center><b>".date("Y년m월d일 H시i분",strtotime("now"))." 현재</b></center><br>\n";   ?>

                    <table cellpadding=1 cellspacing=1  border=0 bordercolor="#ffffff">
                    <tr>

                        <td align=center>
                            <!-- 메뉴선택 -->
                            <select id="selGubun" name="Gubun" onchange='select_Gubun(this);' >
                                <option value=0 selected>메뉴선택</option>
                                <option value=-1>------------</option>
                                <?
                                  if  ($WorkGubun ==  1) echo "<option value=1 selected>당일회차별현황</option>" ;
                                  else                   echo "<option value=1>당일회차별현황</option>" ;

                                  if  ($WorkGubun == 27) echo "<option value=27 selected>당일회차별현황(기금적용)</option>" ;
                                  else                   echo "<option value=27>당일회차별현황(기금적용)</option>" ;

                                  if  ($WorkGubun == 29) echo "<option value=29 selected>당일회차별현황(기금적용다운용)</option>" ;
                                  else                   echo "<option value=29>당일회차별현황(기금적용다운용)</option>" ;

                                  if  ($WorkGubun == 28) echo "<option value=28 selected>당일회차별현황(CGV)</option>" ;
                                  else                   echo "<option value=28>당일회차별현황(CGV)</option>" ;

                                  if  ($WorkGubun == 33) echo "<option value=33 selected>당일회차별현황(프리머스)</option>" ;
                                  else                   echo "<option value=33>당일회차별현황(프리머스)</option>" ;

                                  if  ($WorkGubun == 34) echo "<option value=34 selected>당일회차별현황(씨너스)</option>" ;
                                  else                   echo "<option value=34>당일회차별현황(씨너스)</option>" ;

                                  if  ($WorkGubun == 37) echo "<option value=37 selected>당일회차별현황(롯데씨네마)</option>" ;
                                  else                   echo "<option value=37>당일회차별현황(롯데씨네마)</option>" ;

                                  if  ($WorkGubun == 39) echo "<option value=39  selected>당일회차별현황(메가박스)</option>" ;
                                  else                   echo "<option value=39>당일회차별현황(메가박스)</option>" ;

                                  if  ($WorkGubun == 56) echo "<option value=56 selected>당일회차별현황(기타)</option>" ;
                                  else                   echo "<option value=56>당일회차별현황(기타)</option>" ;

                                  if  ($TimJang == false) // 이부장..
                                  {
                                      if  ($WorkGubun == 2) echo "<option value=2 selected>일자별 현황</option>" ;
                                      else                  echo "<option value=2>일자별 현황</option>" ;

                                      if  ($WorkGubun == 9) echo "<option value=9 selected>일자별 현황2</option>" ;
                                      else                  echo "<option value=9>일자별 현황2</option>" ;

                                      if  ($WorkGubun == 4) echo "<option value=4 selected>주간단위 현황</option>" ;
                                      else                  echo "<option value=4>주간단위 현황</option>" ;
                                  }

                                  if  ($WorkGubun == 5) echo "<option value=5 selected>기간별 현황</option>" ;
                                  else                  echo "<option value=5>기간별 현황</option>" ;

                                  if  ($TimJang == false) // 이부장..
                                  {
                                      if  (!$filmproduce) // 일반 배급사 권한으로 들어왔을때..
                                      {
                                          // 영화사는 부금정산이 보이지 않는다.
                                          if  ($WorkGubun == 6) echo "<option value=6 selected>극장별부금정산</option>" ;
                                          else                  echo "<option value=6>극장별부금정산</option>" ;
                                      }
                                      if  ($WorkGubun ==  7) echo "<option value=7 selected>회계현황</option>" ;
                                      else                   echo "<option value=7>회계현황</option>" ;

                                      if  ($WorkGubun == 26) echo "<option value=26 selected>금액별 회계 현황</option>" ;
                                      else                   echo "<option value=26>금액별 회계 현황</option>" ;

                                      if  ($WorkGubun == 38) echo "<option value=38 selected>금액별 회계 현황(롯데씨네마)</option>" ;
                                      else                   echo "<option value=38>금액별 회계 현황(롯데씨네마)</option>" ;

                                      if  ($WorkGubun == 40) echo "<option value=40 selected>금액별 회계 현황(메가박스)</option>" ;
                                      else                   echo "<option value=40>금액별 회계 현황(메가박스)</option>" ;

                                      if  ($WorkGubun == 51) echo "<option value=51 selected>금액별 회계 현황(CGV)</option>" ;
                                      else                   echo "<option value=51>금액별 회계 현황(CGV)</option>" ;

                                      if  ($WorkGubun == 52) echo "<option value=52 selected>금액별 회계 현황(기타)</option>" ;
                                      else                   echo "<option value=52>금액별 회계 현황(기타)</option>" ;

                                      if  ($WorkGubun == 48) echo "<option value=48 selected>금액별 누계</option>" ;
                                      else                   echo "<option value=48>금액별 누계</option>" ;

                                      if  ($WorkGubun ==  8) echo "<option value=8 selected>관객현황</option>" ;
                                      else                   echo "<option value=8>관객현황</option>" ;
                                  }

                                  //if  ($logged_UserId != "bros5656")
                                  //{
                                      //if  ($WorkGubun == 10) echo "<option value=10 selected>SMS현황</option>" ;
                                      //else                   echo "<option value=10>SMS현황</option>" ;
                                  //}

                                  if  ($TimJang == false)  // 이부장..
                                  {
                                      if  ($WorkGubun == 13) echo "<option value=13 selected>집계현황(그래프)</option>" ;
                                      else                   echo "<option value=13>집계현황(그래프)</option>" ;
                                  }

                                  if  ($WorkGubun == 12) echo "<option value=12 selected>관리자설정</option>" ;
                                  else                   echo "<option value=12>관리자설정</option>" ;

                                  if  (($logged_UserId == "bros56") && ($TimJang == false))  // 이부장..
                                  {

                                      if  ($WorkGubun == 14) echo "<option value=14 selected>AutoLogin</option>" ;
                                      else                   echo "<option value=14>AutoLogin</option>" ;

                                      /*
                                      if  ($WorkGubun == 15) echo "<option value=15 selected>개별지역설정</option>" ;
                                      else                   echo "<option value=15>개별지역설정</option>" ;
                                      if  ($WorkGubun == 16) echo "<option value=16 selected>복합지역설정</option>" ;
                                      else                   echo "<option value=16>복합지역설정</option>" ;
                                      */

                                      if  ($WorkGubun == 17) echo "<option value=17 selected>실무자공지발송</option>" ;
                                      else                   echo "<option value=17>실무자공지발송</option>" ;

                                      if  ($WorkGubun == 18) echo "<option value=18 selected>등록된실무자</option>" ;
                                      else                   echo "<option value=18>등록된실무자</option>" ;

                                      if  ($WorkGubun == 19) echo "<option value=19 selected>실무자영화배정</option>" ;
                                      else                   echo "<option value=19>실무자영화배정</option>" ;

                                      if  ($WorkGubun == 20) echo "<option value=20 selected>SMS 관리극장</option>" ;
                                      else                   echo "<option value=20>SMS 관리극장</option>" ;

                                      if  ($WorkGubun == 21) echo "<option value=21 selected>PHP수정일자 설정</option>" ;
                                      else                   echo "<option value=21>PHP수정일자 설정</option>" ;

                                      if  ($WorkGubun == 22) echo "<option value=22 selected>메모리 비우기</option>" ;
                                      else                   echo "<option value=22>메모리 비우기</option>" ;

                                      if  ($WorkGubun == 23) echo "<option value=23 selected>상대영화</option>" ;
                                      else                   echo "<option value=23>상대영화</option>" ;

                                      if  ($WorkGubun == 24) echo "<option value=24 selected>상대영화 등록/삭제</option>" ;
                                      else                   echo "<option value=24>상대영화 등록/삭제</option>" ;

                                      if  ($WorkGubun == 25) echo "<option value=25 selected>극장순서변경</option>" ;
                                      else                   echo "<option value=25>극장순서변경</option>" ;

                                      if  ($WorkGubun == 30) echo "<option value=30 selected>단문자전송</option>" ;
                                      else                   echo "<option value=30>단문자전송</option>" ;
                                  }
                                  if  ($WorkGubun == 31) echo "<option value=31 selected>그룹별현황(일별)</option>" ;
                                  else                   echo "<option value=31>그룹별현황(일별)</option>" ;

                                  if  ($WorkGubun == 32) echo "<option value=32 selected>그룹별현황(기간별)</option>" ;
                                  else                   echo "<option value=32>그룹별현황(기간별)</option>" ;

                                  if  ($WorkGubun == 35) echo "<option value=35 selected>부율 입력</option>" ;
                                  else                   echo "<option value=35>부율 입력</option>" ;

                                  if  ($WorkGubun == 36) echo "<option value=36 selected>부율 보기</option>" ;
                                  else                   echo "<option value=36>부율 보기</option>" ;

                                  if  (($logged_UserId == "bros56") && ($TimJang == false))  // 이부장..
                                  {
                                      if  ($WorkGubun == 41) echo "<option value=41 selected>엑셀스코어일괄업로드</option>" ;
                                      else                   echo "<option value=41>엑셀스코어일괄업로드</option>" ;

                                      if  ($WorkGubun == 46) echo "<option value=46 selected>엑셀스코어일괄업로드-체크보고</option>" ;
                                      else                   echo "<option value=46>엑셀스코어일괄업로드-체크보고</option>" ;

                                      if  ($WorkGubun == 45) echo "<option value=45 selected>디지털 회차체크보고서</option>" ;
                                      else                   echo "<option value=45>디지털 회차체크보고서</option>" ;

                                      if  ($WorkGubun == 42) echo "<option value=42 selected>영화별 자료정리</option>" ;
                                      else                   echo "<option value=42>영화별 자료정리</option>" ;

                                      if  ($WorkGubun == 47) echo "<option value=47 selected>영화별 타입제한</option>" ;
                                      else                   echo "<option value=47>영화별 타입제한</option>" ;

                                      if  ($WorkGubun == 43) echo "<option value=43 selected>상영관별 필름종류</option>" ;
                                      else                   echo "<option value=43>상영관별 필름종류</option>" ;

									  if  ($WorkGubun == 44) echo "<option value=44 selected>당일 구분별 현황</option>" ;
									  else                   echo "<option value=44>당일 구분별 현황</option>" ;

									  if  ($WorkGubun == 53) echo "<option value=53 selected>좌석수 지정</option>" ;
									  else                   echo "<option value=53>좌석수 지정</option>" ;

									  if  ($WorkGubun == 54) echo "<option value=54 selected>토털 스코어</option>" ;
									  else                   echo "<option value=54>토털 스코어</option>" ;
			                      }

								  if  ($WorkGubun == 50) echo "<option value=50 selected>전국극장 순위</option>" ;
								  else                   echo "<option value=50>전국극장 순위</option>" ;

								  if  ($WorkGubun == 55) echo "<option value=55 selected>극장 기금 적용</option>" ;
                                  else                   echo "<option value=55>극장 기금 적용</option>" ;


                                ?>
                            </select>
                        </td>

                        <td align=center>   <!--영화이름들-->
                            <div id=chungfilm>

                                <!-- 영화선택 -->
                                <select name="FilmTile" id="selFilmTile"  onchange="selFilmTileChange(this.options[this.selectedIndex].text);">
                                    <option value=0 selected>영화선택</option>
                                    <option value=-1>------------</option>
                                    <?
                                    if  ($filmproduce) // $filmproduce 영화사 코드가 있는 경우는 해당영화사의 영화만 option으로 나와야 한다.
                                    {
                                        $sQuery = "Select * From bas_filmproduce      ".
                                                  " Where UserId = '".$filmproduce."' " ;
                                        $qryfilmproduce = mysql_query($sQuery,$connect) ;
                                        if  ($filmproduce_data = mysql_fetch_array($qryfilmproduce))
                                        {
                                            $filmproduceCode = $filmproduce_data["Code"] ;
                                        }

                                        $sQuery = "Select * From bas_filmtitle                 ".
                                                  " Where FilmProduce = '".$filmproduceCode."' ".
                                                  "   And Finish      <> 'Y'                   ".
                                                  " Order By Open desc                        " ;
                                    }
                                    else  // 배급사 권한으로 들어온경우 배급사가 관리하는(선택한) 모든 영화를 option으로 나와야 한다.
                                    {                               //OPEN DESC
                                        $sQuery = "Select * From bas_filmtitle   ".
                                                  " Where Finish <> 'Y'          ".
                                                  " Order By Open desc          " ;
                                        if  ($logged_UserId == "bros5656")
                                        {
                                            $sQuery = "Select * From bas_filmtitle   ".
                                                      " Where Finish <> 'Y'          ".
                                                      "   And FilmProduce = '400001' ".
                                                      " Order By Open desc             " ;
                                        }
                                        if  ($logged_UserId == "test")
                                        {
                                            $sQuery = "Select * From bas_filmtitle   ".
                                                      " Where Finish <> 'Y'          ".
                                                      "   And FilmProduce = '999999' ".
                                                      " Order By Open desc             " ;
                                        }
                                    }
                                    $qrysingotitle = mysql_query($sQuery,$connect) ;
                                    while ($singotitle_data = mysql_fetch_array($qrysingotitle))
                                    {
                                         if  ($FilmTile == $singotitle_data["Open"].$singotitle_data["Code"])
                                         {
                                         ?>
                                         <option value=<?=$singotitle_data["Open"].$singotitle_data["Code"]?> selected><?=$singotitle_data["Name"]?></option>
                                         <?
                                         }
                                         else
                                         {
                                         ?>
                                         <option value=<?=$singotitle_data["Open"].$singotitle_data["Code"]?>><?=$singotitle_data["Name"]?></option>
                                         <?
                                         }
                                    }
                                    ?>
                                </select>

                            </div>
                        </td>

                        <td align=center>
                            <div id=chungsecter>

                                <!-- 지역선택 -->
                                <select name=selZoneLoc>
                                <option value=0 selected>지역선택</option>
                                <option value=-1>--------</option>
                                  <?
                                  if  ($TimJang == false) // 이부장
                                  {
                                      if  ($ZoneLoc == "100")  echo "<option value=100 selected>서울</option>" ;
                                      else                     echo "<option value=100>서울</option>" ;
                                      if  ($ZoneLoc == "04")   echo "<option value=04 selected>경기</option>" ;
                                      else                     echo "<option value=04>경기</option>" ;
                                  }
                                  if  ($ZoneLoc == "200")  echo "<option value=200 selected>부산</option>" ;
                                  else                     echo "<option value=200>부산</option>" ;
                                  if  ($ZoneLoc == "99")   echo "<option value=99 selected>지방</option>" ;
                                  else                     echo "<option value=99>지방</option>" ;
                                  if  ($ZoneLoc == "9999") echo "<option value=9999 selected>전체</option>" ;
                                  else                     echo "<option value=9999>전체</option>" ;
                                  ?>
                                </select>

                            </div>
                        </td>

                        <td width=70 align=center valign=bottom rowspan=2>
                            <!--
                            "검색"
                            -->
                            <input type=button name=Search value="검색" onclick="click_search()">
                        </td>


                    </tr>

                    <tr> <td></td> </tr>
                    <tr> <td></td> </tr>

                    <tr>
                    <td colspan="4" align=center>


                    <?
                    if  ($WorkGubun) // 검색을 마지막으로 한 날짜정보로 다시 화면구성을 한다.
                    {
                        //
                        $WGCurrYear  = substr($WorkDate,0,4) ;
                        $WGCurrMonth = substr($WorkDate,4,2) ;
                        $WGCurrDate  = substr($WorkDate,6,2) ;

                        $strScript   = "<select name=CurrYear>" ;
						for ($i = 2004 ; $i <= ($WGCurrYear+1) ; $i++ )
						{
							if  ($i == $WGCurrYear) $strScript  .= "<option value=".$i." selected>".$i."</option>" ;
							else 	                $strScript  .= "<option value=".$i.">".$i."</option>";
						}
                        $strScript  .= "</select>년" ;

                        $strScript  .= "<select name=CurrMonth>" ;
						for ($i = 1 ; $i <= 12 ; $i++ )
						{
							if  ($i < 10) $space = "&nbsp;&nbsp;" ; else  $space = "" ;

							if  ($i == $WGCurrMonth) $strScript  .= "<option value=".$i." selected>".$space.$i."</option>" ;
							else 	                 $strScript  .= "<option value=".$i.">".$space.$i."</option>";
						}
                        $strScript  .= "</select>월" ;

                        $strScript  .= "<select name=CurrDay>" ;
						for ($i = 1 ; $i <= 31 ; $i++ )
						{
							if  ($i < 10) $space = "&nbsp;&nbsp;" ; else  $space = "" ;

							if  ($i == $WGCurrDate) $strScript  .= "<option value=".$i." selected>".$space.$i."</option>" ;
							else 	                $strScript  .= "<option value=".$i.">".$space.$i."</option>";
						}
                        $strScript  .= "</select>일" ;

                        // all 35 2 3 29 39
                        if  ( ($WorkGubun==1) || // 당일 회차별 현황
                              ($WorkGubun==27) ||
                              ($WorkGubun==29) ||
                              ($WorkGubun==28) ||
                              ($WorkGubun==33) ||
                              ($WorkGubun==34) ||
                              ($WorkGubun==37) ||
                              ($WorkGubun==39) ||
                              ($WorkGubun==7) ||
                              ($WorkGubun==26)||
                              ($WorkGubun==38)||
                              ($WorkGubun==40)||
                              ($WorkGubun==56) )
                        {
                            $bChk0  = "" ;
                            $bChk35 = "" ;
                            $bChk2  = "" ;
                            $bChk20 = "" ;
                            $bChk3  = "" ;
                            $bChk30 = "" ;
                            $bChk29 = "" ;
                            $bChk39 = "" ;
                            $bChk24 = "" ;
                            $bChk34 = "" ;
                            $bChk294 = "" ;
                            $bChk394 = "" ;
                            $bChk4 = "" ;

                            if  ($nFilmTypeNo == "0"  )  $bChk0   = "checked" ; else $bChk0  = "" ;
                            if  ($nFilmTypeNo == "35" )  $bChk35  = "checked" ; else $bChk35 = "" ;
                            if  ($nFilmTypeNo == "2"  )  $bChk2   = "checked" ; else $bChk2  = "" ;
                            if  ($nFilmTypeNo == "20" )  $bChk20  = "checked" ; else $bChk20 = "" ;
                            if  ($nFilmTypeNo == "3"  )  $bChk3   = "checked" ; else $bChk3  = "" ;
                            if  ($nFilmTypeNo == "30" )  $bChk30  = "checked" ; else $bChk30 = "" ;
                            if  ($nFilmTypeNo == "29" )  $bChk29  = "checked" ; else $bChk29 = "" ;
                            if  ($nFilmTypeNo == "39" )  $bChk39  = "checked" ; else $bChk39 = "" ;
                            if  ($nFilmTypeNo == "24" )  $bChk24  = "checked" ; else $bChk24 = "" ;
                            if  ($nFilmTypeNo == "34" )  $bChk34  = "checked" ; else $bChk34 = "" ;
                            if  ($nFilmTypeNo == "294" ) $bChk294 = "checked" ; else $bChk294 = "" ;
                            if  ($nFilmTypeNo == "394" ) $bChk394 = "checked" ; else $bChk394 = "" ;
                            if  ($nFilmTypeNo == "4" )   $bChk4   = "checked" ; else $bChk4 = "" ;

                            $strScript  .= "<BR>";
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='0'   $bChk0>ALL" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='35'  $bChk35>35" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='2'   $bChk2>2" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='20'  $bChk20>20" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='3'   $bChk3>3" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='30'  $bChk30>30" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='29'  $bChk29>29" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='39'  $bChk39>39" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='24'  $bChk24>24" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='34'  $bChk34>34" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='294' $bChk294>294" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='394' $bChk394>394" ;
                            $strScript  .= "<input type='radio' name='bFilmTypeNo' value='4'   $bChk4>4" ;

                            // 35mm는 35, 디지털 투디는 2 디지털 더빙은 20 디지털 쓰리디는 3 디지털 쓰리디 더빙은 30 아이맥스 투디는 29 아이맥스 쓰리디는 39 -
                        }

                        echo "<div id=divdate>".$strScript."</div>" ;
                    }
                    else   // 처음화면구성인 경우는 현재날짜정보로 화면을 구성한다.
                    {
                        //
                        ?>
                        <div id=divdate></div>
                        <script type="text/javascript">
                        <!--
                            select_Gubun(write.Gubun);
                        //-->
                        </script>
                        <?
                    }
                    ?>

                    </td>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <font color="white">
                            <?
                            $df = disk_free_space("/usr");   $ds = disk_total_space("/usr");

                            echo "/usr : " . number_format($df  / $ds * 100. , 2, '.', '')."% Free ( ".number_format($df) ." / ". number_format($ds)." )";

                            echo "<br>";

                            $df = disk_free_space("/home");   $ds = disk_total_space("/home");

                            echo "/home : " . number_format($df  / $ds * 100. , 2, '.', '')."% Free ( ".number_format($df) ." / ". number_format($ds)." )";
                            ?>
                            </font>
                        </td>
                    </tr>
                    </table>
               </td>


               <?
               }
               ?>



               <!---------------------------------------------------------------------------------------------------------------------->
               <!--

                                                                 지역구역별 집계
                                                                                                                                     -->
               <!---------------------------------------------------------------------------------------------------------------------->


               <td width="50%" align=center>

                    <?
                    // 누계금액 부터 구한다. (신고일자, 배급사) // 영화 가 빠져있음 확인요
                    $SumTotAmount = 0 ;

                    if  ($sSingoName != "")
                    {
                        $sQuery = "Select Sum(TotAmount) As SumTotAmount  ".
                                  "  From ".$sSingoName."                 ".
                                  " Where SingoDate  <= '".$WorkDate."'   ".
                                  "   And ".$AddCond."                    " ;
                        $qrysingo3 = mysql_query($sQuery,$connect) ;
                        if  ($SumTotAmount_data = mysql_fetch_array($qrysingo3))
                        {
                            $SumTotAmount = $SumTotAmount_data["SumTotAmount"] ;
                        }
                    }

                    if  ($TimJang == false) // 이부장..
                    {
                        include "wrk_filmsupply_Link_DnZ.php";
                    }
                    ?>
               </td>
          </tr>
          </table>



      </form>

    </center>

           <?
           if  ($ClearAcc == "Yes") // 메모리 클리어
            {
                $sAccName = get_acctable($FilmOpen,$FilmCode,$connect)  ;

                $sQuery = "TRUNCATE TABLE  ".$sAccName."  " ;
                mysql_query($sQuery,$connect) ;
                ?>

                <script language="JavaScript">
                <!--
                     divdate.innerHTML = "" ;

                     chungsecter.innerHTML = "" ;

                     alert("메모리 크리어 완료!") ;
                //-->
                </script>

                <?
                $ClearAcc = null ;
            }
           ?>

    </body>

        <?


        mysql_close($connect);
    }
    else // 로그인하지 않고 바로들어온다면..
    {
        ?>

        <!-- 로그인하지 않고 바로들어온다면 -->
        <body>
            <script language="JavaScript">
            <!--
                window.top.location = '../index_cokr.php' ;
            //-->
            </script>
        </body>

        <?
    }
    ?>
</html>
<<<<<<< HEAD
=======

>>>>>>> 9f25376444ded85b668fb94c0eb38109faf93d19

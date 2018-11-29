<?
    session_start();

    //
    // 작업일자 설정 (일자설정후 해당작업을 할 수 있도록 한다.)
    //
    include "config.php";
    

    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        $cellh  = 30;             // Height & Width of a cell
        $cellw  = 35;
        $tablew = $cellw*7;

        //--------------------------------------------------------------------
        //  메시지를 출력하고 한단계 back
        //--------------------------------------------------------------------
        function ErrorMsg($msg)
        {
          echo " <script>                ";
          echo "   window.alert('$msg'); ";
          echo "   history.go(-1);       ";
          echo " </script>               ";

          exit;
        }

        function SkipOffset($height, $width, $no)
        {
          for ($i = 1; $i <= $no; $i++)
          {
            echo "  <TD height=$height width=$width class=date><p>&nbsp;</p></TD> \n";
          }
        }

        //---- 오늘 날짜
        $thisyear  = date('Y');  // 2000
        $thismonth = date('n');  // 1, 2, 3, ..., 12
        $today     = date('j');  // 1, 2, 3, ..., 31

        //------ $year, $month 값이 없으면 현재 날짜
        if (!$year)
        {
          $year = $thisyear;
        }

        if (!$month)
        {
          $month = $thismonth;
        }

        //------ 날짜의 범위 체크
        if ( ($year > 9999) or ($year < 0) )
        {
          ErrorMsg("연도는 0~9999년만 가능합니다.");
        }

        if ( ($month > 12) or ($month < 0) )
        {
          ErrorMsg("달은 1~12만 가능합니다.");
        }

        $maxdate = date(t, mktime(0, 0, 0, $month, 1, $year));   // the final date of $month

        $prevmonth = $month - 1;
        $nextmonth = $month + 1;
        $prevyear = $year;
        $nextyear = $year;
        if ($month == 1)
        {
          $prevmonth = 12;
          $prevyear = $year - 1;
        }
        elseif ($month == 12)
        {
          $nextmonth = 1;
          $nextyear = $year + 1;
        }

        // Style에서 띄어쓰면 안됨
        echo("
        <HTML>
        <HEAD>
        <link rel=stylesheet href=./style.css type=text/css>
        <title>기준일자지정</title>
        </HEAD>


        <BODY BGCOLOR='#666699' topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

        <b>".$UserName."</b>님을 환영합니다!
        <a href=\"index_com.php?actcode=logout\"><b>[LogOut]</b></a>
        <a href=\"".$BackAddr."\"><b>[X]</b></a>
        <DIV align=center>

        <TABLE cellSpacing=0 cellPadding=0 width=$tablew border=1 >

        <TR>
            <TD colspan=7 align=center class=$cstyle>일자선택</TD>
        </TR>
        <TR>
          <!-- 월 표시 및 이동 -->
          <TD align=center width=100% colspan=7 class=title>
            <a href=$PHP_SELF?year=$prevyear&month=$prevmonth&BackAddr=$BackAddr>
            <span class=smalltext>◀</span>
            </a>

            $year 년 $month 월

            <a href=$PHP_SELF?year=$nextyear&month=$nextmonth&BackAddr=$BackAddr>
            <span class=smalltext>▶</span>
            </a>
          </TD>

          <!-- 월 표시 및 이동 끝 -->
        </TR>


        <TR>
          <!-- 요일 헤더 -->
          <TD align=center width=$cellw class=sunday><p class=day>일</p></TD>
          <TD align=center width=$cellw class=weekday><p class=day>월</p></TD>
          <TD align=center width=$cellw class=weekday><p class=day>화</p></TD>
          <TD align=center width=$cellw class=weekday><p class=day>수</p></TD>
          <TD align=center width=$cellw class=weekday><p class=day>목</p></TD>
          <TD align=center width=$cellw class=weekday><p class=day>금</p></TD>
          <TD align=center width=$cellw class=saturday><p class=day>토</p></TD>
          <!-- 요일 헤더 끝 -->
        </TR>
        ");

        echo("
        <TR>
          <!-- 날짜 테이블 -->
        ");

        $date   = 1;
        $offset = 0;

        while ($date <= $maxdate)
        {
          if ( $date == $today  &&  $year == $thisyear &&  $month == $thismonth)
          {
            $cstyle = 'today'; // 노란색
          }
          else
          {
            $cstyle = 'date';
          }


          if ($date == '1')
          {
            $offset = date('w', mktime(0, 0, 0, $month, $date, $year));  // 0: sunday, 1: monday, ..., 6: saturday
            SkipOffset($cellh, $cellw, $offset);
          }
          echo "
               <TD align=center height=$cellh width=$cellw class=$cstyle><p class=date>
               <a href=\"wrk_silmooja.php?WorkDate=".date('Ymd', mktime(0, 0, 0, $month, $date, $year))."\">".$date."</a>
               </p></TD> \n";

          $date++;
          $offset++;

          if ($offset == 7)
          {
            echo "</TR> \n";
            if ($date <= $maxdate)
            {
              echo "<TR> \n";
            }
            $offset = 0;
          }

        } // end of while

        if ($offset != 0) {
          SkipOffset($cellh, $cellw, (7-$offset));
          echo "</TR> \n";
        }

        echo("
        <!-- 날짜 테이블 끝 -->
        </TABLE>
        </DIV>

        </BODY>
        </HTML>
        ") ;
    }
?>

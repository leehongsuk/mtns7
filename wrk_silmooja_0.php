<?
    session_start();

    //
    // �۾����� ���� (���ڼ����� �ش��۾��� �� �� �ֵ��� �Ѵ�.)
    //
    include "config.php";
    

    // ���������� �α��� �ߴ��� üũ�Ѵ�.
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
        //  �޽����� ����ϰ� �Ѵܰ� back
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

        //---- ���� ��¥
        $thisyear  = date('Y');  // 2000
        $thismonth = date('n');  // 1, 2, 3, ..., 12
        $today     = date('j');  // 1, 2, 3, ..., 31

        //------ $year, $month ���� ������ ���� ��¥
        if (!$year)
        {
          $year = $thisyear;
        }

        if (!$month)
        {
          $month = $thismonth;
        }

        //------ ��¥�� ���� üũ
        if ( ($year > 9999) or ($year < 0) )
        {
          ErrorMsg("������ 0~9999�⸸ �����մϴ�.");
        }

        if ( ($month > 12) or ($month < 0) )
        {
          ErrorMsg("���� 1~12�� �����մϴ�.");
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

        // Style���� ���� �ȵ�
        echo("
        <HTML>
        <HEAD>
        <link rel=stylesheet href=./style.css type=text/css>
        <title>������������</title>
        </HEAD>


        <BODY BGCOLOR='#666699' topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

        <b>".$UserName."</b>���� ȯ���մϴ�!
        <a href=\"index_com.php?actcode=logout\"><b>[LogOut]</b></a>
        <a href=\"".$BackAddr."\"><b>[X]</b></a>
        <DIV align=center>

        <TABLE cellSpacing=0 cellPadding=0 width=$tablew border=1 >

        <TR>
            <TD colspan=7 align=center class=$cstyle>���ڼ���</TD>
        </TR>
        <TR>
          <!-- �� ǥ�� �� �̵� -->
          <TD align=center width=100% colspan=7 class=title>
            <a href=$PHP_SELF?year=$prevyear&month=$prevmonth&BackAddr=$BackAddr>
            <span class=smalltext>��</span>
            </a>

            $year �� $month ��

            <a href=$PHP_SELF?year=$nextyear&month=$nextmonth&BackAddr=$BackAddr>
            <span class=smalltext>��</span>
            </a>
          </TD>

          <!-- �� ǥ�� �� �̵� �� -->
        </TR>


        <TR>
          <!-- ���� ��� -->
          <TD align=center width=$cellw class=sunday><p class=day>��</p></TD>
          <TD align=center width=$cellw class=weekday><p class=day>��</p></TD>
          <TD align=center width=$cellw class=weekday><p class=day>ȭ</p></TD>
          <TD align=center width=$cellw class=weekday><p class=day>��</p></TD>
          <TD align=center width=$cellw class=weekday><p class=day>��</p></TD>
          <TD align=center width=$cellw class=weekday><p class=day>��</p></TD>
          <TD align=center width=$cellw class=saturday><p class=day>��</p></TD>
          <!-- ���� ��� �� -->
        </TR>
        ");

        echo("
        <TR>
          <!-- ��¥ ���̺� -->
        ");

        $date   = 1;
        $offset = 0;

        while ($date <= $maxdate)
        {
          if ( $date == $today  &&  $year == $thisyear &&  $month == $thismonth)
          {
            $cstyle = 'today'; // �����
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
        <!-- ��¥ ���̺� �� -->
        </TABLE>
        </DIV>

        </BODY>
        </HTML>
        ") ;
    }
?>

<?
    session_start();

    //
    // �ǹ��� - ��ȭ��������
    //

    include "config.php";


    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        if (!$ShowroomCode)
        {
           echo "<script language='JavaScript'>window.location = 'wrk_silmooja.php'</script>";
        }

        $ssn_ShowroomCode = $ShowroomCode ;

        if (session_is_registered("ssn_ShowroomCode"))
            session_unregister("ssn_ShowroomCode");
        session_register("ssn_ShowroomCode");

        $Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...

        if (!$WorkDate)
        {
           $WorkDate = date("Ymd",$Today) ;
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>��ȭ��������</title>
</head>


<?
    $connect=dbconn();

    mysql_select_db($cont_db) ;

    $sQuery = "Select * From bas_showroom                        ".
              " Where Theather = '".substr($ShowroomCode,0,4)."' ".
              "   And Room     = '".substr($ShowroomCode,4,2)."' " ;
    $qry_showroom = mysql_query($sQuery,$connect) ;
    if  ($showroom_data = mysql_fetch_array($qry_showroom))
    {
        // �̺κ��� �����Ǿ�� �Ѵ�.
        $silmoojaSilmooja  = $showroom_data["Silmooja"] ;  // �󿵰��� ����ִ� �ǹ����ڵ�
        $silmoojaFilmTitle = $showroom_data["FilmTitle"] ; // �󿵰��� ����ִ� ��ȭ�ڵ�
    }

    // �ǹ��ڰ� �İߵ� ������ ������ �ǹ��� ����Ÿ���̽��� �����Ѵ�.
    $sQuery = "Select * From bas_silmooja     ".
              " Where UserId = '".$UserId."'  " ;
    $qry_silmooja = mysql_query($sQuery,$connect) ;
    if  ($silmooja_data = mysql_fetch_array($qry_silmooja))
    {
        $silmoojaCode = $silmooja_data["Code"] ; // �ǹ����ڵ�

        $sQuery = "Update bas_silmooja                                ".
                  "   Set Theather = '".substr($ShowroomCode,0,4)."', ".
                  "       Room     = '".substr($ShowroomCode,4,2)."'  ".
                  " Where Code = '".$silmoojaCode."'                  " ;
        mysql_query($sQuery,$connect) ;
    }

    $page_num = 10 ;

    if  ($Name) // �˻������� �ִٸ�..
    {
        $sQuery = "Select count(*) From bas_filmtitle ".
                  " Where Name like '%".$Name."%'     ".
                  " Order By Open Desc                ";
        $count_search = mysql_query($sQuery,$connect) ;
        $count_search_row = mysql_fetch_row($count_search);
        if  ( !$page ) { $page = 0; }
        $page_size = $page_num*$page;

        $sQuery = "Select * From bas_filmtitle    ".
                  " Where Name like '%".$Name."%' ".
                  " Order By Name                 ".
                  "limit $page_size,$page_num     " ;
        $qry_filmtitle = mysql_query($sQuery,$connect) ;
    }
    else
    {
        $sQuery = "Select count(*) From bas_filmtitle " ;
        $count_search = mysql_query($sQuery,$connect) ;
        $count_search_row = mysql_fetch_row($count_search);
        if  ( !$page ) { $page = 0; }
        $page_size = $page_num*$page;

        $sQuery = "Select * From bas_filmtitle  ".
                  " Order By Open Desc          ".
                  " limit $page_size,$page_num  " ;
        $qry_filmtitle = mysql_query($sQuery,$connect) ;
    }


    $page_1 = $count_search_row[0] / $page_num;
    $page_1 = intval($page_1);
    $page_2 = $count_search_row[0] % $page_num;

    if ( $page_2 > 0 ) { $page_1++; }

    $total_page = intval($page_1);
    $prev_page = $page - 1;
    $next_page = $page + 1;
    $now_page  = $page + 1;

    if  ( $page == 0 )
    {
        $prev_page_tag = "<A href=\"javascript:alert('���̻� �������� �����ϴ�.');\">[ ���� ]</A>";
    }
    else
    {
        $prev_page_tag = "<A href='".$PHP_SELF."?page=$prev_page&WorkDate=$WorkDate&ShowroomCode=$ShowroomCode&Name=$Name&BackAddr=wrk_silmooja.php'>[ ���� ]</A>";
    }

    if  ( $now_page == $total_page )
    {
        $next_page_tag = "<A href=\"javascript:alert('���̻� �������� �����ϴ�.');\">[ ���� ]</A>";
    }
    else
    {
        $next_page_tag = "<A href='".$PHP_SELF."?page=$next_page&WorkDate=$WorkDate&ShowroomCode=$ShowroomCode&Name=$Name&BackAddr=wrk_silmooja.php'>[ ���� ]</A>";
    }
?>

<script>
     <?
     if  ($silmoojaSilmooja!="")
     {
     ?>
     if  ((<?=$silmoojaSilmooja?>!="") && (<?=$silmoojaSilmooja?>!=<?=$silmoojaCode?>))
     {
         answer = confirm("�̹� �ٸ� �ǹ��ڰ� �󿵰��� �������� �Ϸ������ϴ�.!! �׷��� �Ͻð����ϱ�?") ;
         if   (answer==false)
         {
              location.href='<?=$BackAddr?>' ;
         }
     }
     <?
     }
     ?>
</script>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   <script>

         function check_submit()
         {
            if(!write.Name.value)
            {
              //alert("�˻������ �Է��� �ּ���");
              //write.Name.focus();
              //return false;
            }
            return true;
         }

         // �󿵰��� ������ ���..
         function check_filmtitle(sFilmTileCode)
         {
            location.href="wrk_silmooja_3.php?FilmOpenCode="+sFilmTileCode+"&ShowroomCode=<?=$ShowroomCode?>&WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php" ;
         }
   </script>

<? echo "<b>".$UserName . "</b>���� ȯ���մϴ�!" ; ?>
<a href="index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

   <br><b>*��ȭ��������(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>
<?

     $sQuery = "Select * From bas_showroom                        ".
               " Where Theather = '".substr($ShowroomCode,0,4)."' ".
               "   And Room     = '".substr($ShowroomCode,4,2)."' " ;
     $qry_showroom = mysql_query($sQuery,$connect) ;
     if  ($showroom_data = mysql_fetch_array($qry_showroom))
     {
         $showroom_Discript = $showroom_data["Discript"] ; // �󿵰� ��
         $showroom_Location = $showroom_data["Location"] ; // �󿵰� ����
     }
     else
     {
         $showroom_Discript = "" ;
         $showroom_Location = "" ;
     }

     // �󿵰��� ������ ������ ���Ѵ�. ($locationName)
     $sQuery = "Select * From bas_location             ".
               " Where Code = '".$showroom_Location."' " ;
     $query1 = mysql_query($sQuery,$connect) ;
     if  ($location_data = mysql_fetch_array($query1))
     {
         $locationName = $location_data["Name"] ; // �󿵰� ����������
     }

     echo $showroom_Discript ."-". $locationName ;
?>
   <form method=post name=write action="<?=$PHP_SELF?>?ShowroomCode=<?=$ShowroomCode?>&BackAddr=wrk_silmooja.php" onsubmit="return check_submit()">

   <table cellpadding=0 cellspacing=0 border=1>
   <tr>
          <td align=center>����</td>
   </tr>
<?
   while  ($base_data = mysql_fetch_array($qry_filmtitle)) // ��ȭ����Ʈ ...
   {
        $filmtitle_Open    = $base_data["Open"] ;
        $filmtitle_Code    = $base_data["Code"] ;
        $filmtitle_Name    = $base_data["Name"] ;

       // ��޻翡 ���� �㰡�����ʴ� �ǹ��ڴ� ��ȭ�� ������ �� �����
       $sQuery = "Select * From bas_filmtitlesilmooja       ".
                 " Where Silmooja = '".$silmoojaCode."'     ".
                 "   And Open     = '".$filmtitle_Open."'   ".
                 "   And Film     = '".$filmtitle_Code."'   " ;
//echo $sQuery ;
       $qry_filmsupplytitlesilmooja = mysql_query($sQuery,$connect) ;
       if  ($filmsupplytitlesilmooja_data = mysql_fetch_array($qry_filmsupplytitlesilmooja))
       {
           // �̹� ���õǾ��� �ִ°��� ���� ǥ���Ѵ�.
           if  ((substr($silmoojaFilmTitle,0,6)==$filmtitle_Open) && (substr($silmoojaFilmTitle,6,2)==$filmtitle_Code))
           {
               ?>
               <tr>
               <td height=20 align=left><B>
               <a href="wrk_silmooja_3.php?FilmOpenCode=<?=$filmtitle_Open?><?=$filmtitle_Code?>&ShowroomCode=<?=$ShowroomCode?>&WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php">
               <?=$filmtitle_Name?>
               </a></B>
               </td>
               </tr>
               <?
           }
           else
           {
               ?>
               <tr>
               <td height=20 align=left>
               <B><!-- <a OnClick="check_filmtitle('<?=$filmtitle_Open?><?=$filmtitle_Code?>');"> -->
               <a href="wrk_silmooja_3.php?FilmOpenCode=<?=$filmtitle_Open?><?=$filmtitle_Code?>&ShowroomCode=<?=$ShowroomCode?>&WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php">
               <?=$filmtitle_Name?></B>
               </a>
               </td>
               </tr>
               <?
           }
       }
       else
       {
           ?>
           <tr>
           <td height=20 align=left> <font color="silver"><?=$filmtitle_Name?></font> </td>
           </tr>
           <?
       }
   }

?>
   </table>

   <BR>
   <a><?=$prev_page_tag?></a>
   [<a><?=$now_page?></a>/<?=$total_page?>]
   <a><?=$next_page_tag?></a>

   <form>
</center>

</body>
</html>

<?
        mysql_close($connect);
    }
?>
<?
  session_start();

  if  ($ToExel)
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
        include "config.php";        // {[������ ���̽�]} : ȯ�漳��
                   
        $connect = dbconn() ;        // {[������ ���̽�]} : ����

        mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����

        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
        $dur_time2  = (time() - $timestamp2) / 86400;      

        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $dur_time1  = (time() - $timestamp1) / 86400;       

        $dur_day    = $dur_time2 - $dur_time1;  // �ϼ�
?>
<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>���� ��Ȳ</title>
</head>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
  
  <script>
     //
     // ���� ���
     //
     function toexel_click()
     {
          botttomaddr = 'wrk_filmsupply_Link_DnI.php?'
                      + 'FilmTile=<?=$FilmTile?>&'
                      + 'logged_UserId=<?=$logged_UserId?>&'
                      + 'FromDate=<?=$FromDate?>&'
                      + 'ToDate=<?=$ToDate?>&'
                      + 'ToExel=Yes' ;

          top.frames.bottom.location.href = botttomaddr ;
     }

  </script>

  <center>
  <br><br>
  <b>���� ��Ȳ</b> 
  <a href="javascript: window.print();"><img src="print.gif" width="32" height="32" border="0"></a> 
  <a href=# onclick="toexel_click();"><img src="exel.gif" width="32" height="32" border="0"></a>
  

  <form method=post name=write action="wrk_fiulmsupply_X.php?BackAddr=wrk_silmooja.php" onsubmit="return check_submit()">

  <br>
  <br>

   <!--                 -->
   <!-- ���ν��ھ� ���� -->
   <!--                 -->

  
	  <?
   $FilmOpen = substr($FilmTile,0,6) ;
   $FilmCode = substr($FilmTile,6,2) ;

   $sSingoName     = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..
   $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;  


	  $qryfilmtitle = mysql_query("Select * From bas_filmtitle    ".
                               " Where Open = '".$FilmOpen."'  ".
                               "   And Code = '".$FilmCode."'  ",$connect) ;

	  $filmtitle_data = mysql_fetch_array($qryfilmtitle) ;
	  if  ($filmtitle_data)
	  {
       // ��ȭ������� 
       ?>
       <center>

           <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor="#FFFFFF" width=100%>
           <tr>
           
           <td align=left class=textare>
           ������:(<?=substr($FilmTile,0,2)?>/<?=substr($FilmTile,2,2)?>/<?=substr($FilmTile,4,2)?>)
           </td>
           
           <td align=center >
           <!-- ��ȭ������� -->
           <b><?=$filmtitle_data["Name"]?></b>            
           </td>

           
           <?
        
           $Ttimestamp2 = mktime(0,0,0,substr($FilmTile,2,2),substr($FilmTile,4,2),"20".substr($FilmTile,0,2));
           $Tdur_time2  = (time() - $timestamp2) / 86400;      

           $Ttimestamp1 = mktime(0,0,0,substr($WorkDate,4,2),substr($WorkDate,6,2),substr($WorkDate,0,4));
           $Tdur_time1  = (time() - $timestamp1) / 86400;       

           $Tdur_day    = $Tdur_time2 - $Tdur_time1;  // �ϼ�

           ?>           
               
           <td align=right>
           �����Ϸ� ���� <?=($Tdur_day+1)?>��°..
           </td>
           
           </tr>
           </table>

		     </center>
       <?
	  }
	  ?>

   <br>
   <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
   
   <tr height=25>
   <td class=textarea bgcolor=#ffe4b5 width=50 align=center>
   ����
   </td>

   <td class=textarea bgcolor=#ffe4b5 width=120 align=center>
   �����
   </td>
   
   <td class=textarea bgcolor=#ffe4b5 width=50 align=center>
   ��ũ��
   </td>

   <td class=textarea bgcolor=#ffe4b5 width=50 align=center>
   �¼���
   </td>
   
   <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;������&nbsp;
   </td>

   <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;������&nbsp;
   </td>

   <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;�հ�&nbsp;
   </td>

   <? 
   for ($i=0 ; $i<=$dur_day ; $i++)
   {
   ?>
       <td class=textarea width=50 bgcolor=#ffe4b5 class=tbltitle align=center>
       &nbsp;<?=date("m/d",$timestamp2 + ($i * 86400)) ;?>&nbsp;
       </td>
   <?
   } 
   ?>
   <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;�հ�&nbsp;
   </td>

   <!-- 
   <td class=textarea width=80 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;�ݾ�&nbsp;
   </td>
   -->

   <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;�� ����&nbsp;
   </td>

   
   <!-- 
   <td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;�� �ݾ�&nbsp;
   </td>  
   -->
   

   </tr>

  
   <?
   $AddedCont = "" ;
   
   $SumSeat = 0 ;
   $AccNumPersons = 0 ;
   $AccTotAmount = 0 ;
   
   for ($i=0 ; $i<=($dur_day+2) ; $i++)
   {
       $arrySumNumPersons[$i] = 0 ;
   }

   if  ((!$LocationCode) && (!$ZoneCode))  // ��ü����
   {
       $TotSumScreen = 0 ;
       $TotSumSeat   = 0 ;

       //-----------
       // ���� ���
       //-----------
       $zoneName  = "����" ;
       $AddedCont = " And  Singo.Location = '100' " ;

       if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
       {
           $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                         " And Singo.Film = '".$FilmCode."'  " ;
       }
       
       $qry_singo = mysql_query("Select distinct                                    ".
                                "       ShowroomOrder.Seq,                          ".
                                "       Singo.Theather,                             ".
                                "       Singo.Open,                                 ".
                                "       Singo.Film                                  ".
                                "  From ".$sSingoName."   As Singo,                 ".
                                "       ".$sShowroomorder." As ShowroomOrder,       ".
                                "       bas_showroom      As Showroom               ".
                                " Where Singo.SingoDate  >= '".$FromDate."'         ".
                                "   And Singo.SingoDate  <= '".$ToDate."'           ".
                                "   And Singo.Theather   = Showroom.Theather        ".
                                "   And Singo.Theather   = ShowroomOrder.Theather   ".
                                $AddedCont                                           .
                                " Group By Singo.Theather,                          ".
                                "          Singo.Open,                              ".
                                "          Singo.Film                               ".
                                " Order By ShowroomOrder.Seq,                       ".
                                "          Showroom.Discript,                       ".
                                "          Singo.Open,                              ".
                                "          Singo.Film,                              ".
                                "          Singo.Theather                           ",$connect) ; 

       $affected_row = (mysql_affected_rows() + 1) ;
       
       include "wrk_filmsupply_Link_DnI1.php"; 

       
       
       //-----------
       // ������
       //-----------
       $zoneName   = "���" ;
       $qryzoneloc = mysql_query("select Location from bas_filmsupplyzoneloc ".
                                 " Where Zone = '04'                         ",$connect) ;

       $AddedLoc = " And " ;

       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {            
            if  ($AddedLoc == " And ")
                $AddedLoc .= "( Singo.Location = '".$zoneloc_data["Location"]."' "  ;
            else
                $AddedLoc .= " or Singo.Location = '".$zoneloc_data["Location"]."' "  ;
       }
       $AddedLoc .= ")" ;

       if  ($AddedLoc != "") // �ش��ϴ� �ڷᰡ �ִ°��..
       { 
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               $AddedLoc .= " And Singo.Open = '".$FilmOpen."'  ".
                            " And Singo.Film = '".$FilmCode."'  " ;
           }  
           

           $qry_singo = mysql_query("Select distinct                                    ".
                                    "       ShowroomOrder.Seq,                          ".
                                    "       Singo.Theather,                             ".
                                    "       Singo.Open,                                 ".
                                    "       Singo.Film                                  ".
                                    "  From ".$sSingoName."   As Singo,                 ".
                                    "       ".$sShowroomorder." As ShowroomOrder,       ".
                                    "       bas_showroom      As Showroom               ".
                                    " Where Singo.SingoDate  >= '".$FromDate."'         ".
                                    "   And Singo.SingoDate  <= '".$ToDate."'           ".
                                    "   And Singo.Theather   = Showroom.Theather        ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather   ".
                                    $AddedLoc                                            .
                                    " Group By Singo.Theather,                          ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film                               ".
                                    " Order By ShowroomOrder.Seq,                       ".
                                    "          Showroom.Discript,                       ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film,                              ".
                                    "          Singo.Theather                           ",$connect) ; 

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnI1.php"; 
       }
       

       
       
       //-----------
       // �λ� ���
       //-----------
       $zoneName  = "�λ�" ;
       $AddedCont = " And  Singo.Location = '200' " ;

       if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
       {
           $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                         " And Singo.Film = '".$FilmCode."'  " ;
       }

       $qry_singo = mysql_query("Select distinct                                    ".
                                "       ShowroomOrder.Seq,                          ".
                                "       Singo.Theather,                             ".
                                "       Singo.Open,                                 ".
                                "       Singo.Film                                  ".
                                "  From ".$sSingoName."   As Singo,                 ".
                                "       ".$sShowroomorder." As ShowroomOrder,       ".
                                "       bas_showroom      As Showroom               ".
                                " Where Singo.SingoDate  >= '".$FromDate."'         ".
                                "   And Singo.SingoDate  <= '".$ToDate."'           ".
                                "   And Singo.Theather   = Showroom.Theather        ".
                                "   And Singo.Theather   = ShowroomOrder.Theather   ".
                                $AddedCont                                           .
                                " Group By Singo.Theather,                          ".
                                "          Singo.Open,                              ".
                                "          Singo.Film                               ".
                                " Order By ShowroomOrder.Seq,                       ".
                                "          Showroom.Discript,                       ".
                                "          Singo.Open,                              ".
                                "          Singo.Film,                              ".
                                "          Singo.Theather                           ",$connect) ; 

       
       $affected_row = (mysql_affected_rows() + 1) ;
       
       include "wrk_filmsupply_Link_DnI1.php"; 
      

       //-----------
       // �氭 ���
       //-----------
       $zoneName  = "�氭" ;
       $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                " Where Zone  = '10'                   ",$connect) ;

       $AddedCont = " And " ;
       while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
       {
           if  ($AddedCont == " And ")
           {
               $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
           else
           {
               $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
       }

       if  ($AddedCont != " And ")
       {
           $AddedCont .= ")" ;
       }
       else
       {
           $AddedCont = "" ;
       }              

       if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
       { 
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }

           $qry_singo = mysql_query("Select distinct                                    ".
                                    "       ShowroomOrder.Seq,                          ".
                                    "       Singo.Theather,                             ".
                                    "       Singo.Open,                                 ".
                                    "       Singo.Film                                  ".
                                    "  From ".$sSingoName."   As Singo,                 ".
                                    "       ".$sShowroomorder." As ShowroomOrder,       ".
                                    "       bas_showroom      As Showroom               ".
                                    " Where Singo.SingoDate  >= '".$FromDate."'         ".
                                    "   And Singo.SingoDate  <= '".$ToDate."'           ".
                                    "   And Singo.Theather   = Showroom.Theather        ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather   ".
                                    $AddedCont                                           .
                                    " Group By Singo.Theather,                          ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film                               ".
                                    " Order By ShowroomOrder.Seq,                       ".
                                    "          Showroom.Discript,                       ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film,                              ".
                                    "          Singo.Theather                           ",$connect) ; 

           $affected_row = (mysql_affected_rows() + 1) ;
           
           include "wrk_filmsupply_Link_DnI1.php"; 
       }
       //-----------
       // ��û ���
       //-----------
       $zoneName  = "��û" ;
       $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                " Where Zone  = '35'                   ",$connect) ;

       $AddedCont = " And " ;
       while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
       {
           if  ($AddedCont == " And ")
           {
               $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
           else
           {
               $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
       }

       if  ($AddedCont != " And ")
       {
           $AddedCont .= ")" ;
       }
       else
       {
           $AddedCont = "" ;           
       }              

       if  ($AddedCont != "") // ��û������ �ش��ϴ� �ڷᰡ �ִ°��..
       { 
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }


           $qry_singo = mysql_query("Select distinct                                    ".
                                    "       ShowroomOrder.Seq,                          ".
                                    "       Singo.Theather,                             ".
                                    "       Singo.Open,                                 ".
                                    "       Singo.Film                                  ".
                                    "  From ".$sSingoName."   As Singo,                 ".
                                    "       ".$sShowroomorder." As ShowroomOrder,       ".
                                    "       bas_showroom As   Showroom                  ".
                                    " Where Singo.SingoDate  >= '".$FromDate."'         ".
                                    "   And Singo.SingoDate  <= '".$ToDate."'           ".
                                    "   And Singo.Theather   = Showroom.Theather        ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather   ".
                                    $AddedCont                                           .
                                    " Group By Singo.Theather,                          ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film                               ".
                                    " Order By ShowroomOrder.Seq,                       ".
                                    "          Showroom.Discript,                       ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film,                              ".
                                    "          Singo.Theather                           ",$connect) ; 

           $affected_row = (mysql_affected_rows() + 1) ;
           
           include "wrk_filmsupply_Link_DnI1.php"; 
       }
       //-----------
       // �泲 ���
       //-----------
       $zoneName  = "�泲" ;
       $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                " Where Zone  = '20'                   ",$connect) ;

       $AddedCont = " And " ;
       while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
       {
           if  ($AddedCont == " And ")
           {
               $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
           else                                                                
           {
               $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
       }

       if  ($AddedCont != " And ")
       {
           //$AddedCont .= " Or Singo.Location = '200' " ;
           $AddedCont .= ")" ;
       }
       else
       {
           $AddedCont = "" ;
       }              

       if  ($AddedCont != "") // �泲������ �ش��ϴ� �ڷᰡ �ִ°��..
       { 
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }


           $qry_singo = mysql_query("Select distinct                                    ".
                                    "       ShowroomOrder.Seq,                          ".
                                    "       Singo.Theather,                             ".
                                    "       Singo.Open,                                 ".
                                    "       Singo.Film                                  ".
                                    "  From ".$sSingoName."   As Singo,                 ".
                                    "       ".$sShowroomorder." As ShowroomOrder,       ".
                                    "       bas_showroom      As Showroom               ".
                                    " Where Singo.SingoDate  >= '".$FromDate."'         ".
                                    "   And Singo.SingoDate  <= '".$ToDate."'           ".
                                    "   And Singo.Theather   = Showroom.Theather        ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather   ".
                                    $AddedCont                                           .
                                    " Group By Singo.Theather,                          ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film                               ".
                                    " Order By ShowroomOrder.Seq,                       ".
                                    "          Showroom.Discript,                       ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film,                              ".
                                    "          Singo.Theather                           ",$connect) ; 

           $affected_row = (mysql_affected_rows() + 1) ;
           
           include "wrk_filmsupply_Link_DnI1.php"; 
       }
       //-----------
       // ��� ���
       //-----------
       $zoneName  = "���" ;
       $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                " Where Zone  = '21'                   ",$connect) ;

       $AddedCont = " And " ;
       while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
       {
           if  ($AddedCont == " And ")
           {
               $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
           else
           {
               $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
       }

       if  ($AddedCont != " And ")
       {
           $AddedCont .= ")" ;           
       }
       else
       {
           $AddedCont = "" ;
       }              

       if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
       { 
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }


           $qry_singo = mysql_query("Select distinct                                    ".
                                    "       ShowroomOrder.Seq,                          ".
                                    "       Singo.Theather,                             ".
                                    "       Singo.Open,                                 ".
                                    "       Singo.Film                                  ".
                                    "  From ".$sSingoName."   As Singo,                 ".
                                    "       ".$sShowroomorder." As ShowroomOrder,       ".
                                    "       bas_showroom      As Showroom               ".
                                    " Where Singo.SingoDate  >= '".$FromDate."'         ".
                                    "   And Singo.SingoDate  <= '".$ToDate."'           ".
                                    "   And Singo.Theather   = Showroom.Theather        ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather   ".
                                    $AddedCont                                           .
                                    " Group By Singo.Theather,                          ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film                               ".
                                    " Order By ShowroomOrder.Seq,                       ".
                                    "          Showroom.Discript,                       ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film,                              ".
                                    "          Singo.Theather                           ",$connect) ; 
           
           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnI1.php"; 
       }

       //-----------
       // ȣ�� ���
       //-----------
       $zoneName  = "ȣ��" ;
       $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                " Where Zone  = '50'                   ",$connect) ;

       $AddedCont = " And " ;
       while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
       {
           if  ($AddedCont == " And ")
           {
               $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
           else
           {
               $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
       }

       if  ($AddedCont != " And ")
       {
           $AddedCont .= ")" ;           
       }
       else
       {
           $AddedCont = "" ;
       }              

       if  ($AddedCont != "") // ȣ�������� �ش��ϴ� �ڷᰡ �ִ°��..
       { 
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }


           $qry_singo = mysql_query("Select distinct                                    ".
                                    "       ShowroomOrder.Seq,                          ".
                                    "       Singo.Theather,                             ".
                                    "       Singo.Open,                                 ".
                                    "       Singo.Film                                  ".
                                    "  From ".$sSingoName."   As Singo,                 ".
                                    "       ".$sShowroomorder." As ShowroomOrder,       ".
                                    "       bas_showroom      As Showroom               ".
                                    " Where Singo.SingoDate  >= '".$FromDate."'         ".
                                    "   And Singo.SingoDate  <= '".$ToDate."'           ".
                                    "   And Singo.Theather   = Showroom.Theather        ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather   ".
                                    $AddedCont                                           .
                                    " Group By Singo.Theather,                          ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film                               ".
                                    " Order By ShowroomOrder.Seq,                       ".
                                    "          Showroom.Discript,                       ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film,                              ".
                                    "          Singo.Theather                           ",$connect) ; 

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnI1.php"; 
       }

       

       
       
       //-----------
       // �������
       //-----------
       $zoneName   = "����" ;
       $qryzoneloc = mysql_query("select Location from bas_filmsupplyzoneloc  ".
                                 " Where Zone = '04'                          ",$connect) ;

       $AddedLoc = " And " ;

       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {            
            if  ($AddedLoc == " And ")
                $AddedLoc .= "( Singo.Location <> '".$zoneloc_data["Location"]."' "  ;
            else
                $AddedLoc .= " and Singo.Location <> '".$zoneloc_data["Location"]."' "  ;
       }
       $AddedLoc .= " and Singo.Location <> '100' "  ; // ����
       $AddedLoc .= " and Singo.Location <> '200' "  ; // �λ�
       $AddedLoc .= ")" ;

       // ��� + ���� + �λ� �� ������ �������� �������� �Ѵ�.           
       if  ($AddedLoc != "") // �ش��ϴ� �ڷᰡ �ִ°��..
       { 
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               $AddedLoc .= " And Singo.Open = '".$FilmOpen."'  ".
                            " And Singo.Film = '".$FilmCode."'  " ;
           }


           $qry_singo = mysql_query("Select distinct                                    ".
                                    "       ShowroomOrder.Seq,                          ".
                                    "       Singo.Theather,                             ".
                                    "       Singo.Open,                                 ".
                                    "       Singo.Film                                  ".
                                    "  From ".$sSingoName."   As Singo,                 ".
                                    "       ".$sShowroomorder." As ShowroomOrder,       ".
                                    "       bas_showroom      As Showroom               ".
                                    " Where Singo.SingoDate  >= '".$FromDate."'         ".
                                    "   And Singo.SingoDate  <= '".$ToDate."'           ".
                                    "   And Singo.Theather   = Showroom.Theather        ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather   ".
                                    $AddedLoc                                            .
                                    " Group By Singo.Theather,                          ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film                               ".
                                    " Order By ShowroomOrder.Seq,                       ".
                                    "          Showroom.Discript,                       ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film,                              ".
                                    "          Singo.Theather                           ",$connect) ; 

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnI1.php"; 
       }
       

       
       ?>
       
       <!--���հ�-->
       <tr>
            <td class=textarea bgcolor=#ffebcd align=center colspan=2>
            ���հ�
            </td>
            
            <td class=textarea bgcolor=#ffebcd align=center>
            <b><?=number_format($TotSumScreen)?></b>
            </td>

            <td class=textarea bgcolor=#ffebcd align=center>
            <b><?=number_format($TotSumSeat)?></b>
            </td>

            <td class=textarea bgcolor=#ffebcd align=center>
            <b>#������</b>
            </td>

            <td class=textarea bgcolor=#ffebcd align=center>
            <b>#������</b>
            </td>

            <?
            $qry_singoHap = mysql_query("Select Sum(NumPersons) As SumNumPersons   ".
                                      "  From ".$sSingoName." As Singo             ".
                                      " Where Singo.SingoDate  >= '".$FromDate."'  ".
                                      "   And Singo.SingoDate  <= '".$ToDate."'    ".
                                      "   And Singo.Open        = '".$FilmOpen."'  ".
                                      "   And Singo.Film        = '".$FilmCode."'  ",$connect) ;
            $singoHap_data = mysql_fetch_array($qry_singoHap) ;
            if  ($singoHap_data)
            {
                ?>
                <td class=textarea bgcolor=#ffebcd align=right><b><?=number_format($singoHap_data["SumNumPersons"])?></b></td>
                <?
            }
            else
            {               
                ?>
                <td class=textarea bgcolor=#ffebcd align=center>-</td>
                <?
            }
            ?>

            <? 
            for ($i=0 ; $i<=$dur_day ; $i++)
            {
                 $objDate = date("Ymd",$timestamp2 + ($i * 86400)) ;
                 
                 $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons    ".
                                           "  From ".$sSingoName." As Singo            ".
                                           " Where Singo.SingoDate  = '".$objDate."'   ".
                                           "   And Singo.Open       = '".$FilmOpen."'  ".
                                           "   And Singo.Film       = '".$FilmCode."'  ",$connect) ;
                 $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                 if  ($NumPersons_data)
                 {
                     ?>
                     <td class=textarea bgcolor=#ffebcd align=right><b><?=number_format($NumPersons_data["SumNumPersons"])?></b></td>
                     <?
                 }
                 else
                 {               
                     ?>
                     <td class=textarea bgcolor=#ffebcd align=center>-</td>
                     <?
                 }
            } 
            
            if  ($singoHap_data)
            {
                ?>
                <td class=textarea bgcolor=#ffebcd align=right><b><?=number_format($singoHap_data["SumNumPersons"])?></b></td>
                <?
            }
            else
            {               
                ?>
                <td class=textarea bgcolor=#ffebcd align=center>-</td>
                <?
            }
            ?>
            <td class=textarea bgcolor=#ffebcd align=right><b><?=number_format($AccNumPersons)?></b></td>
            <!-- <td class=textarea bgcolor=#ffebcd align=right>&nbsp;<?=number_format($AccTotAmount)?>&nbsp;</td> -->
       </tr>
   <?
   }

   else
   {
       // Ư�������� ���������� ������ �� ���
       if  (($LocationCode) && ($LocationCode!=""))
       {
           $qryzone = mysql_query("Select * From bas_location        ".
                                  " Where Code = '".$LocationCode."' ",$connect) ;


           $zone_data = mysql_fetch_array($qryzone) ;
           if  ($zone_data)
           { 
               $zoneName = $zone_data["Name"] ;
           }

           $AddedCont = " and  Singo.Location = '".$LocationCode."' " ;
       }

       // Ư�������� ���������� ������ �� ���
       if  (($ZoneCode) && ($ZoneCode!=""))
       {
           $qryzone = mysql_query("Select * From bas_zone          ".
                                  " Where Code = '".$ZoneCode."'   ",$connect) ;


           $zone_data = mysql_fetch_array($qryzone) ;
           if  ($zone_data)
           { 
               $zoneName = $zone_data["Name"] ;
           }

           $qryzone = mysql_query("Select * From bas_location        ".
                                  " Where Code = '".$LocationCode."' ",$connect) ;


           $zone_data = mysql_fetch_array($qryzone) ;
           if  ($zone_data)
           { 
               $zoneName = $zone_data["Name"] ;
           }

           $qryzone = mysql_query("Select * From bas_zone          ".
                                  " Where Code = '".$ZoneCode."'   ",$connect) ;


           $zone_data = mysql_fetch_array($qryzone) ;
           if  ($zone_data)
           { 
               $zoneName = $zone_data["Name"] ;
           }

           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc  ".
                                 "Where Zone  = '".$ZoneCode."'        ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               if  ($ZoneCode == '20') // �泲�ΰ�� �λ��� �����Ѵ�.
               {
                    $AddedCont .= " or Singo.Location = '200' " ;
               }
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }       
       }

       if  ($AddedCont != "") // �ش��ϴ� �ڷᰡ �ִ°��..
       {
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           } 
           
           $qry_singo = mysql_query("Select distinct                                    ".
                                    "       ShowroomOrder.Seq,                          ".
                                    "       Singo.Theather,                             ".
                                    "       Singo.Open,                                 ".
                                    "       Singo.Film                                  ".
                                    "  From ".$sSingoName."   As Singo,                 ".
                                    "       ".$sShowroomorder." As ShowroomOrder,       ".
                                    "       bas_showroom      As Showroom               ".
                                    " Where Singo.SingoDate  >= '".$FromDate."'         ".
                                    "   And Singo.SingoDate  <= '".$ToDate."'           ".
                                    "   And Singo.Theather   = Showroom.Theather        ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather   ".
                                    $AddedCont                                           .
                                    " Group By Singo.Theather,                          ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film                               ".
                                    " Order By ShowroomOrder.Seq,                       ".
                                    "          Showroom.Discript,                       ".
                                    "          Singo.Open,                              ".
                                    "          Singo.Film,                              ".
                                    "          Singo.Theather                           ",$connect) ; 

           $filmtitleNameTitle = "" ;

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnI1.php"; 
       }
   }
   ?>
   

   </table>   

   <br>
   <br>

   </form>

   </center>

</body>

</html>

        <?
        mysql_close($connect);
    }
    else // �α������� �ʰ� �ٷε��´ٸ�..
    {
        ?>
        
        <!-- �α������� �ʰ� �ٷε��´ٸ� -->
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

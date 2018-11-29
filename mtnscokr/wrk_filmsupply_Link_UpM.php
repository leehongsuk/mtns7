<?
    session_start();
?>
<html>
<?
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정

        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택
?>
<link rel=stylesheet href=../mtnscom/style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">
<head>
<title>스코어보고</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
<?
     // 회원 로그인 체크
     $sQuery = "Select * From cfg_user              ".
               " Where UserId ='".$logged_UserId."' " ;
     $result = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
     if  ($member_data = mysql_fetch_array($result))
     {
         $logged_SeqNo       = $member_data["SeqNo"] ;
         $logged_UserId      = $member_data["UserId"] ;
         $logged_UserPw      = $member_data["UserPw"] ;
         $logged_Name        = $member_data["Name"] ;
         $logged_eMail       = $member_data["eMail"] ;
         $logged_Homepage    = $member_data["Homepage"] ;
         $logged_Jumin       = $member_data["Jumin"] ;
         $logged_Discript    = $member_data["Discript"] ;
         $logged_Time        = time();

         session_register("logged_SeqNo");
         session_register("logged_UserId");
         session_register("logged_UserPw");
         session_register("logged_Name");
         session_register("logged_Silmooja");
         session_register("logged_FilmSupply");
         session_register("logged_Theather");
         session_register("logged_FilmProduce");
         session_register("logged_eMail");
         session_register("logged_Homepage");
         session_register("logged_Jumin");
         session_register("logged_Discript");
         session_register("logged_Time");
     }

     $silmoojaTheather = substr($ShowRoom,0,4) ;    // 상영관 코드
     $silmoojaRoom     = substr($ShowRoom,4,2) ;

     $silmoojatheatherOpen = substr($FilmTile,0,6) ;
     $silmoojatheatherFilm = substr($FilmTile,6,2) ;
     /*
     if  ($silmooja_Code == '111111')
     {

     }
     else
     {
         // 실무자가 잡고 있는 상영관-영화 정보를 구한다.
         $sQuery = "Select * From bas_silmoojatheatherpriv     ".
                   " Where Silmooja = '".$silmooja_Code."'     ".
                   "   And WorkDate = '".$WorkDate."'          ".
                   "   And Theather = '".$silmoojaTheather."'  ".
                   "   And Room     = '".$silmoojaRoom."'      " ;
         $query2 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
         if  ($silmoojatheather_data = mysql_fetch_array($query2))
         {
             $silmoojatheatherOpen = $silmoojatheather_data["Open"] ; // 영화코드
             $silmoojatheatherFilm = $silmoojatheather_data["Film"] ; //
         }
     }
     */
     $sSingoName = get_singotable($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;  // 신고 테이블 이름..
     $sAccName   = get_acctable($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;    // accumulate 이름..
     $sDgrName   = get_degree($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;
     $sDgrpName  = get_degreepriv($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;
     $sShowroomorder = get_showroomorder($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;

     $sQuery = "Select * From bas_filmtitle               ".
               " Where Open = '".$silmoojatheatherOpen."' ".
               "   And Code = '".$silmoojatheatherFilm."' " ;
     $query3 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
     if  ($filmtitle_data = mysql_fetch_array($query3))
     {
         $filmtitleName       = $filmtitle_data["Name"] ;
         $filmtitleFilmSupply = $filmtitle_data["FilmSupply"] ;
     }
     else
     {
         $filmtitleName = "영화[배급사]정보없음" ;
     }

     // 실무자가 파견된 상영관..
     $sQuery = "Select * From bas_showroom                ".
               " Where Theather = '".$silmoojaTheather."' ".
               "   And Room     = '".$silmoojaRoom."'     " ;
     $query2 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
     if  ($showroom_data = mysql_fetch_array($query2))
     {
         $showroomDiscript   = $showroom_data["Discript"] ;
         $showroomLocation   = $showroom_data["Location"] ;
         //$showroomFilmSupply = $showroom_data["FilmSupply"] ;

         // 상영관의 소재지 지역을 구한다. ($locationName)
         $sQuery = "Select * From bas_location            ".
                   " Where Code = '".$showroomLocation."' " ;
         $query3 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
         if  ($location_data = mysql_fetch_array($query3))
         {
             $locationName = $location_data["Name"] ;
         }
         else
         {
             $locationName = "지역없음" ;
         }
     }










     // 해당실무자를 구하고 ($silmoojaName) ..
     $sQuery = "Select * From bas_silmooja         ".
               " Where Code = '".$silmooja_Code."' " ;
     $query1 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
     if  ($silmooja_data = mysql_fetch_array($query1))
     {
         $silmoojaCode      = $silmooja_data["Code"] ;   // 실무자코드
         $silmoojaUserId    = $silmooja_data["UserId"] ; // 실무자 User ID
         $silmoojaTheather  = substr($ShowRoom,0,4) ;
         $silmoojaRoom      = substr($ShowRoom,4,2) ;
         $silmoojaName      = $silmooja_data["Name"] ;   // 실무자 이름


         //echo "++++".$silmoojaUserId ;

         // 실무자가 파견된 상영관..
         $sQuery = "Select * From bas_showroom                ".
                   " Where Theather = '".$silmoojaTheather."' ".
                   "   And Room     = '".$silmoojaRoom."'     " ;
         $query2 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
         if  ($showroom_data = mysql_fetch_array($query2))
         {
             $showroomDiscript   = $showroom_data["Discript"] ;
             $showroomLocation   = $showroom_data["Location"] ;
             $showroomFilmSupply = $showroom_data["FilmSupply"] ;

             // 상영관의 소재지 지역을 구한다. ($locationName)
             $sQuery = "Select * From bas_location            ".
                       " Where Code = '".$showroomLocation."' " ;
             $query3 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
             if  ($location_data = mysql_fetch_array($query3))
             {
                 $locationName = $location_data["Name"] ;
             }

             // 상영관에서 상영하는 영화정보를 구하고 ($filmtitleName)
             $sQuery = "Select * From bas_filmtitle               ".
                       " Where Open = '".$silmoojatheatherOpen."' ".
                       "   And Code = '".$silmoojatheatherFilm."' " ;
             $query3 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
             if  ($filmtitle_data = mysql_fetch_array($query3))
             {
                 $filmtitleName = $filmtitle_data["Name"] ;
             }
         }

         // 상영관 회차정보를 구하고 ($arryDegree[],$arryTime[])
         $sQuery = "Select * From ".$sDgrpName."              ".
                   " Where WorkDate = '".$WorkDate."'         ".
                   "   And Silmooja = '".$silmooja_Code."'    ".
                   "   And Open = '".$silmoojatheatherOpen."' ".
                   "   And Film = '".$silmoojatheatherFilm."' ".
                   "   And Theather = '".$silmoojaTheather."' ".
                   "   And Room     = '".$silmoojaRoom."'     ".
                   " Order by Degree                          " ;
         $qry_temp = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
         while ($degree_data = mysql_fetch_array($qry_temp))
         {
              $arryDegree[] = $degree_data["Degree"] ;
              $arryTime[]   = $degree_data["Time"] ;
         }

         // 편당 가격대를 구한다. ($arryUnitPrice[])
         $sQuery = "Select * From bas_unitprices              ".
                   " Where Theather = '".$silmoojaTheather."' ".
                   "   And Room     = '".$silmoojaRoom."'     ".
                   " Order By UnitPrice Desc                  " ;
         $query2 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
         while ($unitprices_data = mysql_fetch_array($query2))
         {
              $arryUnitPrice[] = $unitprices_data["UnitPrice"] ;
         }
     }
     if ((!$silmoojaUserId) || ($silmoojaUserId==""))
     {
         mysql_close($connect);

         echo "<script language='JavaScript'>window.location = 'index.php'</script>";
     }


     if  ($singoData!="") // 신고 데이타가 있는경우 (히든값..)
     {
         $sQuery = "Delete From ".$sSingoName."                    ".
                   " Where SingoDate = '".$WorkDate."'             ".
                   "   And Silmooja  = '".$silmoojaCode."'         ".
                   "   And Theather  = '".$silmoojaTheather."'     ".
                   "   And Room      = '".$silmoojaRoom."'         ".
                   "   And Open      = '".$silmoojatheatherOpen."' ".
                   "   And Film      = '".$silmoojatheatherFilm."' " ;
         $query1 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
         $sTemp1 = $singoData ;

         while (($i = strpos($sTemp1,'.')) > 0)
         {
             $sItem1 = substr($sTemp1,0,$i) ;

             $nCount = 0 ;

             $sTemp2 = $sItem1 ;

             while (($j = strpos($sTemp2 ,',')) > 0)
             {
                 $nCount++ ;

                 $sItem2 = substr($sTemp2,0,$j) ;

                 if  ($nCount==1)  $singoDegree     = $sItem2 ;
                 if  ($nCount==2)  $singoPrice      = $sItem2 ;
                 if  ($nCount==3)  $singoNumPerson  = $sItem2 ;

                 $sTemp2 = substr($sTemp2,$j+1) ;
             }

			 $GikumRate = 1.03 ;
			 $sQuery = "SELECT IF( '".$WorkDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate ".
					   "  FROM bas_theather                                                     ".
					   " WHERE code = ".$silmoojaTheather."                                        " ; //echo $sQuery ;
			 $qry_GikumRate = mysql_query($sQuery,$connect) ;
			 if  ($AryGikumRate = mysql_fetch_array($qry_GikumRate))
			 {
				 $GikumRate	= $AryGikumRate["GikumRate"]; // = 1.03
			 }

             $sQuery = "Select * From ".$sShowroomorder."              ".
                       " Where Theather   = '".$silmoojaTheather."'    ".
                       "   And Room       = '".$silmoojaRoom."'        " ;
             $QryShowroomorder = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
             if  ($AryShowroomorder = mysql_fetch_array($QryShowroomorder))
             {
                 $RoomOrder = $AryShowroomorder["Seq"] ;
             }
             else
             {
                 $RoomOrder = -1 ;
             }

             $sQuery = "Insert Into ".$sSingoName."             ".
                       "Values                                  ".
                       "(                                       ".
                       "  '".date("YmdHis")."',                 ".
                       "  '".$WorkDate."',                      ".
                       "  '".$silmoojaCode."',                  ".
                       "  '".$showroomLocation."',              ".
                       "  '".$silmoojaTheather."',              ".
                       "  '".$silmoojaRoom."',                  ".
                       "  '".$silmoojatheatherOpen."',          ".
                       "  '".$silmoojatheatherFilm."',          ".
                       "  '".$FilmType."',                      ".//////////// 9月5日 //////
                       "  '".$singoDegree."',                   ".
                       "  '".$singoPrice."',                    ".
                       "  '".$singoNumPerson."',                ".
                       "  '".$singoPrice * $singoNumPerson."',  ".
                       "  '".get_GikumAount2($singoPrice,$GikumRate,$singoNumPerson)."', ".
                       "  '',                                   ".
                       "  '".$RoomOrder."'                      ".
                       ")                                       " ;   //eq($sQuery);
             $query1 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;


             $sTemp1 = substr($sTemp1,$i+1) ;
         }
     }
?>


   <script>

         var nlstDegree, nlstPrice ;
         var nTotDegree ;
         var nTotal ;
         var picedSell ;
         var arry_degree = new Array(<?=count($arryDegree)?>) ;
         var arry_price  = new Array(<?=count($arryUnitPrice)?>) ;
         var arry_totdegree = new Array(<?=count($arryDegree)?>) ; // 회차별 합계
         var arry_totprice  = new Array(<?=count($arryUnitPrice)?>+1) ;

         function number_format(str) // 1234 -> 1,234 로 바꿔준다.
         {
             str = ""+str+"";

             var retValue = "";

             for(i=0; i<str.length; i++)
             {
                 if  (i > 0 && (i%3)==0)
                 {
                     retValue = str.charAt(str.length - i -1) + "," + retValue;
                 }
                 else
                 {
                     retValue = str.charAt(str.length - i -1) + retValue;
                 }
             }
             return retValue;
         }

         function number_string(str)// 1,234 -> 1234 로 바꿔준다.
         {
             str = ""+str+"";

             var retValue = "";

             for(i=0; i<str.length; i++)
             {
                 if  (str.charAt(i) != ",")
                 {
                     retValue = retValue + str.charAt(i) ;
                 }
             }
             return retValue;
         }


         // 자바스크립트에는 다차원 배열선언을 문법적으로 지원하지않는다...
         var arry_sel    = new Array(<?=count($arryDegree)?>) ;
         for (i=0;i< <?=count($arryDegree)?>;i++)
         {
            arry_sel[i]  = new Array(<?=count($arryUnitPrice)?>) ;
         }

         for (i=0;i< <?=count($arryDegree)?>;i++)
         {
             for (j=0;j< <?=count($arryUnitPrice)?>;j++)
             {
                arry_sel[i][j] = 0 ;
             }
         }

  <?
     echo "\n" ;
     for ($i=0;$i<count($arryDegree);$i++)
     {
        echo "arry_degree[".$i."] = \"".$arryDegree[$i]."\" ; \n" ;
     }
     for ($i=0;$i<count($arryUnitPrice);$i++)
     {
        echo "arry_price[".$i."] = \"".$arryUnitPrice[$i]."\" ; \n" ;
     }
  ?>

         picedSell = null ;

         //
         //   "전송"  을 눌렸을 때 ..
         //
         //
         //

         function check_submit()
         {
            var  singoUnit = "" ;
            var  singoData = "" ;

            if  ((picedSell!=null) && (nlstDegree!=null) && (nlstPrice!=null) && (write.score.value!="")) // 이전에 한번 선택되었고 가격이 입력되어있다면
            {
                if   (score_check()==false)  return false ;

                picedSell.innerHTML = number_format(write.score.value)  ;  // 확인버튼을 누른것과 같은 기능을 하도록한다.

                arry_sel[nlstDegree][nlstPrice] = eval(write.score.value) ;  // 2차원 배열에 가격사항을 배치한다.

                nTotDegree = 0 ;

                for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                {
                    nTotDegree = nTotDegree + (arry_sel[nlstDegree][i]) ;
                }

                //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                nTotal = 0 ;

                for (j=0;j<<?=count($arryDegree)?>;j++)
                {
                       nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                }
                //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;

                nTotal = 0 ;

                for (j=0;j<<?=count($arryDegree)?>;j++)
                {
                    for (i=0;i<<?=count($arryUnitPrice)?>;i++)
                    {
                       nTotal = nTotal + (arry_sel[j][i]) ;
                    }
                }

                //arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = nTotal ;
                arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = number_format(nTotal) ;
            }

            for (i=0;i< <?=count($arryDegree)?>;i++)
            {
                for (j=0;j< <?=count($arryUnitPrice)?>;j++)
                {
                   if   (arry_sel[i][j]!=null)
                   {
                        singoUnit  = arry_degree[i] +"," + arry_price[j]  +"," + arry_sel[i][j] +",."  ;

                        singoData = singoData +  singoUnit ;
                   }
                }
            }

            // action 을 넣고
            actAddr = "wrk_filmsupply_Link_UpM.php?"
                    + "silmooja_Code=<?=$silmooja_Code?>&"
                    + "ShowRoom=<?=$silmoojaTheather.$silmoojaRoom?>&"
                    + "WorkDate=<?=$WorkDate?>&"
                    + "BackAddr=wrk_filmsupply_6.php"

            write.singoData.value = singoData ;
            write.action = actAddr ;

            return true;
         }

         //
         //   "-" 혹은 특정요금을 찍었을 때 ..
         //
         //
         //

         function select_price(nDegree,nPrice,sell)
         {
            if  ((picedSell!=null) && (nlstDegree!=null) && (nlstPrice!=null) && (write.score.value!="")) // 이전에 한번 선택되었고 가격이 입력되어있다면
            {
                if   (score_check()==false)  return ;

                picedSell.innerHTML = number_format(write.score.value)  ;  // 확인버튼을 누른것과 같은 기능을 하도록한다.

                arry_sel[nlstDegree][nlstPrice] = eval(write.score.value) ;  // 2차원 배열에 가격사항을 배치한다.

                nTotDegree = 0 ;

                for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                {
                    nTotDegree = nTotDegree + (arry_sel[nlstDegree][i]) ;
                }

                //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                nTotal = 0 ;

                for (j=0;j<<?=count($arryDegree)?>;j++)
                {
                       nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                }
                //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;

                nTotal = 0 ;

                for (j=0;j<<?=count($arryDegree)?>;j++)
                {
                    for (i=0;i<<?=count($arryUnitPrice)?>;i++)
                    {
                       nTotal = nTotal + (arry_sel[j][i]) ;
                    }
                }

                //arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = nTotal ;
                arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = number_format(nTotal) ;
            }

            if  (sell.innerHTML=="-")
            {
                write.score.value = "" ;
            }
            else
            {
                write.score.value = number_string(sell.innerHTML) ; // 상황판의 숫치가 가격입력 박스로 들어가서 가격을 수정할 수있도록한다.
            }

            if  ((nlstDegree!=null) && (nlstPrice!=null))
            {
                if  (write.score.value == "")
                {
                    arry_sel[nDegree][nPrice] = 0 ;  // 2차원 배열에 가격사항을 배치한다.
                }
                else
                {
                    arry_sel[nDegree][nPrice] = eval(write.score.value) ;  // 2차원 배열에 가격사항을 배치한다.

                    nTotDegree = 0 ;

                    for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                    {
                        nTotDegree = nTotDegree + (arry_sel[nlstDegree][i]) ;
                    }

                    //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                    arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                    nTotal = 0 ;

                    for (j=0;j<<?=count($arryDegree)?>;j++)
                    {
                           nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                    }
                    //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                    arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;

                    nTotDegree = 0 ;

                    for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                    {
                        nTotDegree = nTotDegree + (arry_sel[nlstDegree][i]) ;
                    }

                    //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                    arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                    nTotal = 0 ;

                    for (j=0;j<<?=count($arryDegree)?>;j++)
                    {
                           nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                    }
                    //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                    arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;

                    nTotal = 0 ;

                    for (j=0;j<<?=count($arryDegree)?>;j++)
                    {
                        for (i=0;i<<?=count($arryUnitPrice)?>;i++)
                        {
                           nTotal = nTotal + (arry_sel[j][i]) ;
                        }
                    }

                    //arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = nTotal ;
                    arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = number_format(nTotal) ;
                }
            }

            nlstDegree = nDegree ;
            nlstPrice  = nPrice  ;

            picedSell = sell ; // 마지막으로 선택된 셀의 정보..

            write.score.focus() ;
            write.score.select();
         }

         //
         //   "확인"  을 눌렸을 때 ..
         //
         //
         //

         function click_update()
         {
            if  (picedSell==null)
            {
                alert("먼저 수정할 스코어를 선택하세요!") ;
                write.score.focus() ;
                write.score.select();
            }
            else
            {
                if  ((nlstDegree!=null) && (nlstPrice!=null))
                {
                    if  (write.score.value=="")
                    {
                        picedSell.innerHTML = "-" ;
                        arry_sel[nlstDegree][nlstPrice] = 0 ;  // 2차원 배열에 가격사항을 배치한다.
                    }
                    else
                    {
                        if   (score_check()==false)  return ;

                        picedSell.innerHTML = number_format(write.score.value) ;
                        arry_sel[nlstDegree][nlstPrice] = eval(write.score.value) ;  // 2차원 배열에 가격사항을 배치한다.
                    }

                    nTotDegree = 0 ;

                    for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                    {
                        nTotDegree = nTotDegree + arry_sel[nlstDegree][i] ;
                    }

                    //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                    arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                    nTotal = 0 ;

                    for (j=0;j<<?=count($arryDegree)?>;j++)
                    {
                           nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                    }
                    //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                    arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;
                }

                nTotal = 0 ;

                for (j=0;j<<?=count($arryDegree)?>;j++)
                {
                    for (i=0;i<<?=count($arryUnitPrice)?>;i++)
                    {
                       nTotal = nTotal + (arry_sel[j][i]) ;
                    }
                }

                //arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = nTotal ;
                arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = number_format(nTotal) ;
            }
         }


         //
         //   숫자만 입력 받도록 제한한다.
         //
         //
         //

         function score_check()
         {
            edit = write.score.value ;

            if ((edit !="") && (edit.search(/\D/) != -1))
            {
                alert("숫자만 입력시오!") ;

                write.score.value = "";

                edit = edit.replace(/\D/g, "")

                write.score.focus() ;
                write.score.select();

                return false ;
            }
            else
            {
                return true ;
            }
         }

   </script>


<center>

   <br><b>*스코어보고(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>

   <?
   echo $showroomDiscript . "-" . $locationName . "<br>" . $filmtitleName ;
   ?>

   <form method=post name=write onsubmit="return check_submit()">

   <input name=singoData type=hidden value=<?
   for ($i=0;$i<count($arryUnitPrice);$i++) // 편당가격의 리스트..
   {
       for ($j=0;$j<count($arryDegree);$j++)  // 회차 리스트..
       {
           // 신고내역중 해당신고건을 찾는다.
           $sQuery = "Select * From ".$sSingoName."                  ".
                     " Where SingoDate = '".$WorkDate."'             ".
                     "   And Silmooja  = '".$silmoojaCode."'         ".
                     "   And Theather  = '".$silmoojaTheather."'     ".
                     "   And Room      = '".$silmoojaRoom."'         ".
                     "   And Open      = '".$silmoojatheatherOpen."' ".
                     "   And Film      = '".$silmoojatheatherFilm."' ".
                     "   And ShowDgree = '".$arryDegree[$j]."'       ".
                     "   And UnitPrice = '".$arryUnitPrice[$i]."'    " ;
           $query1 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($singo_data = mysql_fetch_array($query1))
           {
               // 해당신고건..
               echo $arryDegree[$j].",".$arryUnitPrice[$i].",".$singo_data["NumPersons"].",." ;
           }
       }
   }
   ?>>           <!-- -->
<?
   $FilmType = "" ;

   $sQuery = "Select FilmType From ".$sSingoName."           ".
             " Where SingoDate = '".$WorkDate."'             ".
             "   And Silmooja  = '".$silmoojaCode."'         ".
             "   And Theather  = '".$silmoojaTheather."'     ".
             "   And Room      = '".$silmoojaRoom."'         ".
             "   And Open      = '".$silmoojatheatherOpen."' ".
             "   And Film      = '".$silmoojatheatherFilm."' " ; // echo $sQuery ;
   $QryWorkDate = mysql_query($sQuery,$connect) ;
   if  ($ArrWorkDate = mysql_fetch_array($QryWorkDate))
   {
       $FilmType = $ArrWorkDate["FilmType"] ;
   }
   if  ($FilmType == "")
   {
       $sQuery = "Select FilmType From ".$sSingoName."           ".
                 " Where SingoDate = '".$AgoDate."'              ".
                 "   And Silmooja  = '".$silmoojaCode."'         ".
                 "   And Theather  = '".$silmoojaTheather."'     ".
                 "   And Room      = '".$silmoojaRoom."'         ".
                 "   And Open      = '".$silmoojatheatherOpen."' ".
                 "   And Film      = '".$silmoojatheatherFilm."' " ; // echo $sQuery ;
       $QryAgoDate = mysql_query($sQuery,$connect) ;
       if  ($ArrAgoDate = mysql_fetch_array($QryAgoDate))
       {
           $FilmType = $ArrAgoDate["FilmType"] ;
       }
   }

   $bChk35 = "" ;
   $bChk2  = "" ;
   $bChk20 = "" ;
   $bChk3  = "" ;
   $bChk30 = "" ;
   $bChk29 = "" ;
   $bChk39 = "" ;

   if  ($FilmType == "35" ) $bChk35 = "checked" ; else $bChk35 = "" ;
   if  ($FilmType == "2"  ) $bChk2  = "checked" ; else $bChk2  = "" ;
   if  ($FilmType == "20" ) $bChk20 = "checked" ; else $bChk20 = "" ;
   if  ($FilmType == "3"  ) $bChk3  = "checked" ; else $bChk3  = "" ;
   if  ($FilmType == "30" ) $bChk30 = "checked" ; else $bChk30 = "" ;
   if  ($FilmType == "29" ) $bChk29 = "checked" ; else $bChk29 = "" ;
   if  ($FilmType == "39" ) $bChk39 = "checked" ; else $bChk39 = "" ;

   ?>
   <table>
   <tr>
    <td colspan=2 align=center><input type='radio' name='FilmType' value='35' <?=$bChk35?> >35mm</td>
   </tr>
   <tr>
    <td>
    <input type='radio' name='FilmType' value='2' <?=$bChk2?> >디지털2D<br>
    <input type='radio' name='FilmType' value='3' <?=$bChk3?> >디지털3D
    </td>
    <td>
    <input type='radio' name='FilmType' value='29' <?=$bChk29?> >아이맥스2D<br>
    <input type='radio' name='FilmType' value='39' <?=$bChk39?> >아이맥스3D
    </td>
   </tr>
   <tr>
    <td colspan=2 >
    <input type='radio' name='FilmType' value='20' <?=$bChk20?> >디지털 더빙
    <input type='radio' name='FilmType' value='30' <?=$bChk30?> >디지털3D 더빙
    </td>
   </tr>
   </table>

   <br>

   <?
   if  ($singoData!="") // 신고 데이타가 있는경우 (히든값..)
   {
       echo "<script>alert('스코어수정이 정상적으로 완료되었읍니다.');</script>" ;
   }
   ?>

   <input type="hidden" name="FilmTile" value="<?=$FilmTile?>">

   <input type=text name=score size=7 maxlength=6 class=input>
   <input type=button value="확인" OnClick="click_update()">
   <input type=submit value="전송"><BR>
   </form>




   <table cellpadding=0 cellspacing=0 border=1>
   <tr> <!-- 타이틀 -->
        <?
           echo "<td align=center>요금</td>" ;

           for ($i=0;$i<count($arryDegree);$i++)
           {


              if  ($arryDegree[$i]=="99")
              {
                  echo "<td align=center>심야<br>".
                       " ". substr($arryTime[$i],0,2).":".substr($arryTime[$i],2,2). "</td>" ;
              }
              else
              {
                  echo "<td align=center>".(int)$arryDegree[$i] ."회<br>".
                       " ". substr($arryTime[$i],0,2).":".substr($arryTime[$i],2,2). "</td>" ;
              }
           }
           echo "<td align=center>금일</td>" ;
           echo "<td align=center>누계</td>" ;
        ?>
   </tr>

   <?
   $TotSumNumPersons  = 0 ;
   $TotSumNumPersonsY = 0 ;
   $TotTotAmount      = 0 ;

   for ($i=0;$i<count($arryUnitPrice);$i++) // 편당가격의 리스트..
   {
      ?>
      <tr>
      <?
           if   ($arryUnitPrice[$i] > 0)
           {
               echo "<td align=center>".number_format($arryUnitPrice[$i])."</td>" ;
           }
           else
           {
               echo "<td align=center>미지정</td>" ;
           }


           $totPriceNumPersons = 0 ;

           for ($j=0;$j<count($arryDegree);$j++)  // 회차 리스트..
           {
               // 신고내역중 해당신고건을 찾는다.
               $sQuery = "Select * From ".$sSingoName."                  ".
                         " Where SingoDate = '".$WorkDate."'             ".
                         "   And Silmooja  = '".$silmoojaCode."'         ".
                         "   And Theather  = '".$silmoojaTheather."'     ".
                         "   And Room      = '".$silmoojaRoom."'         ".
                         "   And Open      = '".$silmoojatheatherOpen."' ".
                         "   And Film      = '".$silmoojatheatherFilm."' ".
                         "   And ShowDgree = '".$arryDegree[$j]."'       ".
                         "   And UnitPrice = '".$arryUnitPrice[$i]."'    " ;
               $query1 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
               if  ($singo_data = mysql_fetch_array($query1))
               {
                   // 해당신고건..
                   if  ($singo_data["NumPersons"] != "0")
                   {
                        $singoNumPersons = $singo_data["NumPersons"] ;
                        $totNumPersons[$j]  += $singoNumPersons ;
                        $totPriceNumPersons += $singoNumPersons ;
                   }
                   else
                   {
                       $singoNumPersons = "-" ;
                   }
               }
               else
               {
                   $singoNumPersons = "-" ;
               }
               ?>

               <td align=center>
               <a OnClick='select_price(<?=$j?>,<?=$i?>,sellp<?=$i?>d<?=$j?>)'>
               <div id="sellp<?=$i?>d<?=$j?>"><?=number_format($singoNumPersons)?></div>
               </a>
               </td>

               <?
               $PriceTotNumPersons[$i]  += $singoNumPersons ;
               ?>

               <?
               if  ($singoNumPersons!="-")
               {
                   ?>
                   <script>
                            arry_sel[<?=$j?>][<?=$i?>] = <?=$singoNumPersons?> ;
                   </script>
                   <?
               }
           }
           ?>

           <? // 금일 ?>
           <td align=center>
           <b><div id="PriceTot<?=$i?>"><?=number_format($totPriceNumPersons)?></div></b>
           </td>

           <script>
                    arry_totprice[<?=$i?>] = PriceTot<?=$i?>  ;
           </script>

           <?
           $MonthStart = substr($WorkDate,0,6) . "01" ; // 월초..

           $sQuery = "Select Sum(NumPersons) As SumNumPersons,        ".
                     "       Sum(TotAmount)  As SumTotAmount          ".
                     "  From ".$sSingoName."                          ".
                     " Where SingoDate >= '".$MonthStart."'           ".
                     "   And SingoDate <= '".$WorkDate."'             ".
                     "   And Theather  = '".$silmoojaTheather."'      ".
                     "   And Open      = '".$silmoojatheatherOpen."'  ".
                     "   And Film      = '".$silmoojatheatherFilm."'  ".
                     "   And UnitPrice = '".$arryUnitPrice[$i]."'     " ;
           $qry_singoYS = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($singoYS_data = mysql_fetch_array($qry_singoYS))
           {
               $TotSumNumPersonsY += $singoYS_data["SumNumPersons"] ; // 총합계 .
           }


           /////////
           $arrRet = update_AccTable( $sSingoName,
                                      $sAccName,
                                      $WorkDate,
                                      $silmoojaCode,
                                      $silmoojaTheather,
                                      $silmoojatheatherOpen,
                                      $silmoojatheatherFilm,
                                      $FilmType,
                                      $arryUnitPrice[$i],
                                      $connect) ;
           $TotSumNumPersons += $arrRet[0] ;
           $TotTotAmount     += $arrRet[1] ;

           /*

           */
           ?>
           <td align=right><?=number_format($NumPersons_data["SumNumPersons"])?></td>
      </tr>

   <?
   }

   delete_AccTable( $sAccName,
                    $WorkDate,
                    $silmoojaCode,
                    $silmoojaTheather,
                    $silmoojatheatherOpen,
                    $silmoojatheatherFilm,
                    $connect) ;

   ?>

       <tr>

           <td align=center>
           <B>합계</B>
           </td>
           <?

           $totTodayNumPersons = 0 ;

           for ($j=0;$j<count($arryDegree);$j++)  // 회차 리스트..
           {
           ?>

               <td align=center>
               <B><div id="totdrg<?=$j?>"><?=number_format($totNumPersons[$j])?></div></B>
               </td>

               <script>arry_totdegree[<?=$j?>] = totdrg<?=$j?> ;</script>
           <?
               $totTodayNumPersons += $totNumPersons[$j] ;
           }
           ?>

           <? // 당일 총 합계 ?>
           <td align=center>
           <b><div id="PriceTotTot"><?=number_format($totTodayNumPersons)?></div></b>
           </td>


           <script>arry_totprice[<?=$i?>] = PriceTotTot ;</script>

           <td align=right>
           <b><?=number_format($TotSumNumPersonsY)?></b>
           </td>

           <?
           $sQuery = "Update ".$sAccName."                            ".
                     "   Set TotAccu    = '".$TotSumNumPersons."',    ".
                     "       TotAcMoney = '".$TotTotAmount."'         ".
                     " Where WorkDate   = '".$WorkDate."'             ".
                     "   And Silmooja   = '".$silmoojaCode."'         ".
                     "   And Theather   = '".$silmoojaTheather."'     ".
                     "   And Open       = '".$silmoojatheatherOpen."' ".
                     "   And Film       = '".$silmoojatheatherFilm."' " ;
           mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           ?>
      </tr>
   </table>

</center>

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

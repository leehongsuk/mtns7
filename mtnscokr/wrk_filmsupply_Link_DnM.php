<?
  include ("config.php");

  $connect = dbconn();

  mysql_select_db($cont_db) ;




  include ("mail_lib.php");

  function checkstruct($mailstream, $subject, $MSG_NO,$conn)
  {
       $Body   = "" ;
       $Image1 = "" ;
       $Image2 = "" ;
       $Image3 = "" ;

       $Msg = "" ;

       $struct = imap_fetchstructure($mailstream, $MSG_NO) ;
       $type   = $struct->subtype ;





       $Theather = substr($subject,0,4) ; // 극장코드
       $sQuery = "Select * From bas_theather    ".
                 " Where Code = '".$Theather."' " ;
       $QryTheather = mysql_query($sQuery,$conn) or die(ee($sQuery)) ;
       if  ($ArrTheatherData = mysql_fetch_array($QryTheather)) // 해당극장에..
       {
           $TheatherName     = $ArrTheatherData["Discript"] ;
           $TheatherLocation = $ArrTheatherData["Location"] ;

           $FilmOpen = substr($subject,4,6) ;  // 영화 오픈 코드
           $FilmCode = substr($subject,10,2) ; // 영화 필름 코드

           $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$conn) ;


           $sQuery = "Select * From bas_filmtitle   ".
                     " Where Open = '".$FilmOpen."' ".
                     "   And Code = '".$FilmCode."' " ;
           $QryFilmtitle = mysql_query($sQuery,$conn) or die(ee($sQuery)) ;
           if  ($ArrFilmData = mysql_fetch_array($QryFilmtitle)) // 해당영화에..
           {
               $FilmCode = $ArrFilmData["Open"] . "" . (string)$ArrFilmData["Code"] ;
               $FilmName = $ArrFilmData["Name"] ;

               // 극장+영화 에 해당하는 메일자료 여부를 체크한다.
               $sQuery = "Select Count(*) As CntMail        ".
                         "  From wrk_maildata               ".
                         " Where Theather = '".$Theather."' ".
                         "   and FilmCode = '".$FilmCode."' " ;
               $QryCntMail = mysql_query($sQuery,$conn) or die(ee($sQuery)) ;
               if  ($ArrCntMail = mysql_fetch_array($QryCntMail))
               {
                   $CntMail = $ArrCntMail["CntMail"] ; // 메일자료의 갯수를 구한다.
               }


               $Degree = 0 ;

               if  ($CntMail > 0)  // 존재하는 메일자료가 있는 경우
               {
                   $sQuery = "Select Max(Degree) As MaxDegree   ".
                             "  From wrk_maildata               ".
                             " Where Theather = '".$Theather."' ".
                             "   and FilmCode = '".$FilmCode."' " ;
                   $QryMaxMail = mysql_query($sQuery,$conn) or die(ee($sQuery)) ;
                   if  ($ArrMaxMail = mysql_fetch_array($QryMaxMail))
                   {
                       $Degree = $ArrMaxMail["MaxDegree"] + 1 ; // 새로 생성할 Degree를 구한다...
                   }
               }














//echo "<br>[".$subject."=".$type."]<br>" ;

       switch($type)
       {
           case "PLAIN": // 일반텍스트 메일
                $Msg = imap_fetchbody($mailstream, $MSG_NO, "1") ;

                $Body   = $Msg ;
                $Image1 = "" ;
                $Image2 = "" ;
                $Image3 = "" ;
                break;

           case "MIXED": // 첨부파일 있는 메일
                for ($i=0; $i<count($struct->parts); $i++)
                {
                    $part      = $struct->parts[$i];
                    $param     = $part->dparameters[0];
                    $file_name = Decode($param->value);
                    $mime      = $part->subtype; // MIME 타입 혹은 메일의 종류가 리턴됩니다.
                    $encode    = $part->encoding; // encoding
//echo "<br>".$mime."<br>" ;

                    if  (($mime == "ALTERNATIVE") || ($mime == "HTML"))
                    {
                        $val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1));

                        $Msg = getOutLook($val);

                        $Body   = $Msg ;
                        $Image1 = "" ;
                        $Image2 = "" ;
                        $Image3 = "" ;
                    }
                    else
                    {
                        $Msg = printbody($mailstream, $subject, $MSG_NO, $Degree, $i, $encode, $mime, $file_name,$conn);

                        if  ($Image1 == "") $Image1 = $Msg ;
                        else if  ($Image2 == "") $Image2 = $Msg ;
                        else if  ($Image3 == "") $Image3 = $Msg ;
                    }
//echo str_replace("<", "*", $Msg)."<br>" ;
                }
                break;

           case "ALTERNATIVE": // outlook html
                for ($i=0; $i<count($struct->parts); $i++)
                {
                    $part      = $struct->parts[$i];
                    $param     = $part->parameters[0];
                    $file_name = Decode($param->value); // 첨부파일일 경우 파일명
                    $mime      = $part->subtype;
                    $encode    = $part->encoding;
//echo "<br>".$mime."<br>" ;
                    if  ($mime == "HTML")
                    {
                        $Msg = printbody($mailstream, $subject, $MSG_NO, $Degree, $i, $encode, $mime, $file_name);

                        $Body   = $Msg ;
                        $Image1 = "" ;
                        $Image2 = "" ;
                        $Image3 = "" ;
                    }
//echo str_replace("<", "*", $Msg)."<br>" ;
                }
                break;

           case "RELATED": // outlook 본문에 이미지 삽입
                for ($i=0; $i<count($struct->parts); $i++)
                {
                    $part      = $struct->parts[$i];
                    $param     = $part->parameters[0];
                    $file_name = Decode($param->value); // 첨부파일일 경우 파일명
                    $mime      = $part->subtype; // MIME 타입
                    $encode    = $part->encoding; // encoding
//echo "<br>".$mime."<br>" ;
                    if  ($mime == "ALTERNATIVE")
                    {
                        $val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1));

                        $Msg = getOutLook($val);

                        $Body   = $Msg ;
                        $Image1 = "" ;
                        $Image2 = "" ;
                        $Image3 = "" ;
                    }
                    else
                    {
                        $Msg = printbody($mailstream, $subject, $MSG_NO, $Degree, $i, $encode, $mime, $file_name);

                        if  ($Image1 == "") $Image1 = $Msg ;
                        else if  ($Image2 == "") $Image2 = $Msg ;
                        else if  ($Image3 == "") $Image3 = $Msg ;
                    }
//echo $Msg."<br>" ;
                }
                break;
       }

//echo "<font color=red><br>" ;
//echo str_replace("<", "*", $Body)."<br>" ;
//echo $Image1."<br>" ;
//echo $Image2."<br>" ;
//echo $Image3."<br></font>" ;





























               $RecvTime = date("YmdHis",time()) ;
               $NewBody  = str_replace("'", "\'", $Body) ;

               $sQuery = "Insert into wrk_maildata         ".
                         "Values                           ".
                         "(                                ".
                         "      '".$Theather."',           ".
                         "      '".$FilmCode."',           ".
                         "       ".$Degree.",              ".
                         "      '".$RecvTime."',           ".
                         "      '".$TheatherName."',       ".
                         "      '".$TheatherLocation."',   ".
                         "      '".$FilmName."',           ".
                         "      '".$NewBody."',            ".
                         "      '".$Image1."',             ".
                         "      '".$Image2."',             ".
                         "      '".$Image3."'              ".
                         ")                                " ;
               mysql_query($sQuery,$conn) or die(ee($sQuery)) ;
           }
       }
  }


function printbody($mailstream, $subject, $MSG_NO, $Degree, $numpart, $encode, $mime, $file_name)
  {
       $msg = "" ;
       $val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1));

       switch($encode)
       {
           case 0: // 7bit
           case 1: // 8bit
                   $val = imap_base64(imap_binary(imap_qprint(imap_8bit($val))));
                   break;
           case 2: // binary
                   $val = imap_base64(imap_binary($val));
                   break;
           case 3: // base64
                   $val = imap_base64($val);
                   break;
           case 4: // quoted-print
                   $val = imap_base64(imap_binary(imap_qprint($val)));
                   break;
           case 5: // other
                   echo "알수없는 Encoding 방식.";
                   exit;
       }

       // mime type 에 따라 출력합니다.
       switch($mime)
       {
           case "PLAIN":
                        //echo str_replace("\n", "<br>", $val);
                        $msg = str_replace("\n", "<br>", $val);
                        break;
           case "HTML":
                        //echo $val;
                        $msg = $val;
                        break;
           default:
                        $struct    = imap_fetchstructure($mailstream, $MSG_NO);
                        $part      = $struct->parts[$numpart];
                        $param     = $part->parameters[0];
                        $file_name = Decode($param->value); // 첨부파일일 경우 파일명
                        $mime      = $part->subtype; // MIME 타입
                        $encode    = $part->encoding; // encoding

                        $msg = getAttach($mailstream, $subject, $MSG_NO, $Degree, $numpart, $encode, $mime, $file_name) ;
                        break;
       }
       return $msg ;

  }


  function getAttach($mailstream, $subject, $MSG_NO, $Degree, $numpart, $encode, $mime, $file_name)
  {
      $val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1), FT_UID);

      switch ($encode)  // 이미지 자료를 얻는다.
      {
          case 0: // 7bit
              break;
          case 1: // 8bit
              $val = imap_base64(imap_binary(imap_qprint(imap_8bit($val))));
              break;
          case 2: // binary
              $val = imap_base64(imap_binary($val));
              break;
          case 3: // base64
              $val = imap_base64($val);
              break;
          case 4: // quoted-print
              $val = imap_base64(imap_binary(imap_qprint($val)));
              break;
          case 5: // other
              echo "알수없는 Encoding 방식.";
              exit;
      }

      $filename = "/home/realtimebox/www/mailfile/".$subject.$numpart.$Degree.".jpg" ;
      $urlname  = "http://www.realtimebox.com/mailfile/".$subject.$numpart.$Degree.".jpg" ;

      if  (!$handle = fopen($filename, 'a'))
      {
          exit;
      }

      if  (fwrite($handle, $val) === FALSE)
      {
          exit;
      }

      fclose($handle);


      return $urlname ;
  }












  include ("mail_config.php");


  $mailstream = imap_open($C_DOMAIN, $login, $pass);
  if  ($mailstream == 0)
  {
      echo "메일 개방실패!";
      exit;
  }

  $mailno = imap_sort($mailstream, SORTDATE, 1);
?>


<html>
     <SCRIPT LANGUAGE="JavaScript">
     <!--
        function view_click(jpgPath)
         {
             popupaddr = "wrk_filmsupply_Link_DnM_View.php?"
                       + "jpgPath="+jpgPath+"" ;

             popupoption = "status=0, "
                         + "menubar=0, "
                         + "scrollbars=yes, "
                         + "resizable=yes, "
                         + "width=400, "
                         + "height=400" ;

             window.open(popupaddr,'',popupoption) ;
         }
     //-->
     </SCRIPT>
<head>
      <link rel=stylesheet href=./LinkStyle.css type=text/css>
      <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">
</head>

<body bgcolor="#FFFFFF" leftmargin=5 topmargin=20 marginwidth=5 marginheight=20>
<?
  for  ($i=0; $i<count($mailno); $i++)  // 메일의 갯수만큼루프를 돕니다.
  {
       $no = $mailno[$i]; // 메일번호를 얻구요..

       $head      = imap_header($mailstream,$no);    // 얻어진 메일번호로 해당 메일의 헤더를 읽습니다.
       $recent    = $head->Recent;                   // 새메일 여부를 리턴해 줍니다.
       $unseen    = $head->Unseen;                   // 메일을 읽었는지 여부를 리턴해 주죠..
       $msgno     = trim($head->Msgno);              // 메일번호
       $date      = date("Y/m/d H:i", $head->udate); // 메일의 날짜를 얻고
       $subject   = $head->Subject;                  // 제목을 얻습니다.
       $subject   = Decode($subject);
       $from_obj  = $head->from[0];                  // 보낸 사람을 얻는 부분입니다. 그냥 아래처럼 사용하세요.
       $from_name = $from_obj->personal;
       $from_addr = substr($from_obj->mailbox . "@" . strtolower($from_obj->host), 0, 30);
       if  ($from_name == "")
       {
           $from_name = $from_addr;
       }
       $from_name = Decode($from_name);



       $nLengSubject = strlen($subject) ; // 제목의 길이..
       if  (($nLengSubject == 6) || ($nLengSubject == 7))  // 제목의 길이는 무조건 6 아니면 7
       {
           $Theather = substr($subject,0,4) ; // 극장코드 4자리..

           $sQuery = "Select * From bas_theather    ".
                     " Where Code = '".$Theather."' " ;

           $QryTheather = mysql_query($sQuery,$connect) or die(ee($sQuery)) ; // 극장을 찾는다.
           if  ($ArrTheatherData = mysql_fetch_array($QryTheather)) // 해당극장에..
           {
               $TheatherName     = $ArrTheatherData["Discript"] ; // 극장이름
               $TheatherLocation = $ArrTheatherData["Location"] ; // 극장위치

               if  (strlen($subject) == 6) // 띄우지 않고 그대로 쓴 제목
               {
                   $FilmCode = substr($subject,4,2) ; // 필름코드
               }
               if  (strlen($subject) == 7) // 띄워서 쓴 제목
               {
                   $FilmCode = substr($subject,5,2) ; // 필름코드
               }

               $sQuery = "Select * From bas_filmtitle   ".
                         " Where Code = '".$FilmCode."' ".
                         " Order By Open Desc           " ; // 가장최근의 영화정보를 구한다...
               $QryFilmtitle = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
               if  ($ArrFilmData = mysql_fetch_array($QryFilmtitle)) // 해당영화에..
               {
                   $FilmCode = $ArrFilmData["Open"] . "" . (string)$ArrFilmData["Code"] ;
                   $FilmName = $ArrFilmData["Name"] ;

                   $sShowroomorder = get_showroomorder($ArrFilmData["Open"],(string)$ArrFilmData["Code"],$connect) ;

                   // 새로운 제목 : 극장코드(4)+영화오픈(6)+영화코드(2) ..
                   $subject =  $Theather . $FilmCode ;

                   //
                   //
                   //echo  "<br>-----------------------------------------------------------<br>".
                   //      "제목:".$subject." 보낸사람:".$from_name."<br>" ;
                   checkstruct($mailstream, $subject, $msgno,$connect );
                   //
                   //
               }
           }
       }
  }

  for ($i=0; $i<count($mailno); $i++)  // 메일의 갯수만큼루프를 돕니다.
  {
      $no = $mailno[$i]; // 메일번호를 얻구요..

      $result = imap_delete($mailstream, $no);

      if  (!$result)
      {
          echo "삭제실패";
          imap_close($mailstream);
          exit;
      }
      imap_expunge($mailstream);
  }
  imap_close($mailstream);
?>

  <?
           // 특정지역만 선택적으로 보고자 할 경우
           if  (($LocationCode) && ($LocationCode!=""))
           {
               $sQuery = "Select * From bas_location        ".
                         " Where Code = '".$LocationCode."' " ;
               $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
               if  ($zone_data = mysql_fetch_array($qryzone))
               {
                   $zoneName = $zone_data["Name"] ;
               }

               if  ($LocationCode=="200")//  부산은 (부산+울산+김해+창원)
               {
                   $AddedCont = " And  (Singo.Location = '200'  ".
                                "    Or Singo.Location = '203'  ".
                                "    Or Singo.Location = '600'  ".
                                "    Or Singo.Location = '207'  ".
                                "    Or Singo.Location = '205'  ".
                                "    Or Singo.Location = '208'  ".
                                "    Or Singo.Location = '202'  ".
                                "    Or Singo.Location = '211'  ".
                                "    Or Singo.Location = '212'  ".
                                "    Or Singo.Location = '213'  ".
                                "    Or Singo.Location = '201') " ;
               }
               else
               {
                   $AddedCont = " And  Singo.Location = '".$LocationCode."'  ";
               }
           }

           // 특정구역만 선택적으로 보고자 할 경우
           if  (($ZoneCode) && ($ZoneCode!=""))
           {
               $sQuery = "Select * From bas_zone          ".
                         " Where Code = '".$ZoneCode."'   " ;
               $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
               if  ($zone_data = mysql_fetch_array($qryzone))
               {
                   $zoneName = $zone_data["Name"] ;
               }

               $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                         " Where Zone  = '".$ZoneCode."'        " ;
               $query1 = mysql_query($sQuery,$connect)  or die(ee($sQuery)) ;

               $AddedCont = "" ;
               while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
               {
                   if  ($AddedCont == "")
                   {
                       $AddedCont .= "( TheatherLocation = '".$filmsupplyzoneloc_data["Location"]."' " ;
                   }
                   else
                   {
                       $AddedCont .= " Or TheatherLocation = '".$filmsupplyzoneloc_data["Location"]."' " ;
                   }
               }

               if  ($AddedCont != "")
               {
                   if  ($ZoneCode == '20') // 경남인경우 부산을 포함한다.
                   {
                        $AddedCont .= " Or TheatherLocation = '200' " ;

                        // (울산 + 김해 + 창원 )

                        $AddedCont .= " Or TheatherLocation <> '203' ".  // 통영
                                      " Or TheatherLocation <> '600' ".  // 울산
                                      " Or TheatherLocation <> '207' ".  // 김해
                                      " Or TheatherLocation <> '205' ".  // 진주
                                      " Or TheatherLocation <> '208' ".  // 거제
                                      " Or TheatherLocation <> '202' ".  // 마산
                                      " Or TheatherLocation <> '211' ".  // 사천
                                      " Or TheatherLocation <> '212' ".  // 거창
                                      " Or TheatherLocation <> '213' ".  // 양산
                                      " Or TheatherLocation <> '201' " ; // 창원

                   }
                   $AddedCont .= ")" ;
               }
               else
               {
                   $AddedCont = "" ;
               }

               //$zoneName = $filmsupplyzoneloc_data["Name"]
           }


       if  ($ZoneCode=='9999')
       {
           $sQuery = "Select MailData.Theather,                     ".
                     "       MailData.TheatherName,                 ".
                     "       MailData.TheatherLocation,             ".
                     "       MailData.FilmCode,                     ".
                     "       Count(MailData.Degree) As CntDegree    ".
                     "  From wrk_maildata As MailData,              ".
                     "       ".$sShowroomorder." As RoomOrder       ".
                     " Where MailData.FilmCode = '".$FilmTile."'    ".
                     "   And MailData.Theather = RoomOrder.Theather ".
                     " Group By MailData.Theather,                  ".
                     "          MailData.TheatherName,              ".
                     "          MailData.TheatherLocation,          ".
                     "          MailData.FilmCode                   ".
                     " Order By RoomOrder.Seq,                      ".
                     "          RoomOrder.Discript                  " ;
       }
       else
       {
           $sQuery = "Select MailData.Theather,                     ".
                     "       MailData.TheatherName,                 ".
                     "       MailData.TheatherLocation,             ".
                     "       MailData.FilmCode,                     ".
                     "       Count(MailData.Degree) As CntDegree    ".
                     "  From wrk_maildata As MailData,              ".
                     "       ".$sShowroomorder." As RoomOrder       ".
                     " Where MailData.FilmCode = '".$FilmTile."'    ".
                     "   And MailData.Theather = RoomOrder.Theather ".
                     "   And ". $AddedCont . "                      ".
                     " Group By MailData.Theather,                  ".
                     "          MailData.TheatherName,              ".
                     "          MailData.TheatherLocation,          ".
                     "          MailData.FilmCode                   ".
                     " Order By RoomOrder.Seq,                      ".
                     "          RoomOrder.Discript                  " ;
       }
tmp_query($sQuery,$connect) ;
       $QryGrpMailData = mysql_query($sQuery,$connect)  or die(ee($sQuery)) ;
       while  ($ArrGrpMailData = mysql_fetch_array($QryGrpMailData))
       {
            $GrpTheather         = $ArrGrpMailData["Theather"] ;
            $GrpTheatherName     = $ArrGrpMailData["TheatherName"] ;
            $GrpTheatherLocation = $ArrGrpMailData["TheatherLocation"] ;
            $GrpFilmCode         = $ArrGrpMailData["FilmCode"] ;
            $GrpCntDegree        = $ArrGrpMailData["CntDegree"] ;
            ?>
            <TABLE class=textarea style='table-layout:fixed' width=90% cellpadding=0 cellspacing=0 border=1 bordercolor=#C0B0A0>
            <?




            $sQuery = "Select * From bas_filmsupplyzoneloc           ".
                      " Where Location = '".$GrpTheatherLocation."'  " ;
            $QrylocData = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
            if  ($loc_data = mysql_fetch_array($QrylocData))
            {
               $Zone = $loc_data["Zone"] ;

               if  ($$Zone!=0)
               {
                   $sQuery = "Select * From bas_zone       ".
                             " Where Code = '".$$Zone."'   " ;
                   $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
                   if  ($zone_data = mysql_fetch_array($qryzone))
                   {
                       $zoneName = $zone_data["Name"] ;
                   }
               }
               else
               {
                   $sQuery = "Select * From bas_location               ".
                             " Where Code = '".$GrpTheatherLocation."' " ;
                   $qrylocation = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
                   if  ($location_data = mysql_fetch_array($qrylocation))
                   {
                       $zoneName = $location_data["Name"] ;
                   }
               }
            }

            echo "<TR >" ;
            echo "<TD colspan=3 height=30 bgcolor=#b0c4de align=center><font color=#0000f0><B>".$zoneName."</B></font></TD>\n" ;
            echo "<TD colspan=3 height=30 bgcolor=#b0c4de align=center><B>".$GrpTheatherName."</B></TD>\n" ;
            echo "<TD height=30 bgcolor=#b0c4de align=center><B>비고</B></TD>\n" ;
            echo "</TR>\n" ;


            $FirstRow = false ;
            if  ($ZoneCode=='9999')
            {
                $sQuery = "Select * From wrk_maildata           ".
                          " Where Theather = '".$GrpTheather."' ".
                          "   And FilmCode = '".$GrpFilmCode."' ";
            }
            else
            {
                $sQuery = "Select * From wrk_maildata           ".
                          " Where Theather = '".$GrpTheather."' ".
                          "   And FilmCode = '".$GrpFilmCode."' ".
                          "   And ". $AddedCont . "             " ;
            }

            $QryMailData = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
            while  ($ArrMailData = mysql_fetch_array($QryMailData))
            {
                $MailData_FilmName = $ArrMailData["FilmName"] ;
                $MailData_RevTime  = $ArrMailData["RevTime"] ;
                $MailData_Message  = $ArrMailData["Message"] ;
                $MailData_Message  = $ArrMailData["Message"] ;
                $MailData_ImgPath1 = $ArrMailData["ImgPath1"] ;
                $MailData_ImgPath2 = $ArrMailData["ImgPath2"] ;
                $MailData_ImgPath3 = $ArrMailData["ImgPath3"] ;

                $sQuery = "Select * From wrk_maildata_bigo       ".
                          " Where Theather = '".$GrpTheather."'  ".
                          "   And FilmCode = '".$GrpFilmCode."'  " ;
                $QryMailDataBigo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
                if  ($ArrMailDataBigo = mysql_fetch_array($QryMailDataBigo))
                {
                    $MailDataBigo =  $ArrMailDataBigo["Bigo"] ;
                }
                else
                {
                    $MailDataBigo =  "&nbsp;" ;
                }

                if  ($menu==11)
                {
                    echo "<TR>" ;
                    /*****
                    if  ($FirstRow == false)
                    {
                        echo "<TD rowspan=".$GrpCntDegree." class=textarea bgcolor=#dcdcdc align=center width=200><B>현황보고</B></TD>\n" ;
                        echo "</TD>\n" ;

                        $FirstRow = True ;
                    }
                    *********/
                    echo "<TD class=textarea bgcolor=#dcdcdc align=center width=200 >" ;
                    echo substr($MailData_RevTime,0,4)."/".substr($MailData_RevTime,4,2)."/".substr($MailData_RevTime,6,2)." ".substr($MailData_RevTime,8,2).":".substr($MailData_RevTime,10,2) ;
                    echo "</TD>\n" ;

                    echo "<TD class=textarea bgcolor=#dcdcdc align=center width=200 colspan=5 >" ;
                    if  ($MailData_Message=="")
                    {
                        echo  "&nbsp;" ;
                    }
                    else
                    {
                        echo  $MailData_Message ;
                    }
                    echo "</TD>\n" ;

                    echo "<TD class=textarea bgcolor=#efefef>" ;
                    echo $MailDataBigo ;
                    echo "</TD>\n" ;

                    echo "</TR>\n" ;
                }
            }

            if  ($menu==12)
            {
                if  ($ZoneCode=='9999')
                {
                    $sQuery = "Select * From wrk_maildata           ".
                              " Where Theather = '".$GrpTheather."' ".
                              "   And FilmCode = '".$GrpFilmCode."' ".
                              " Order By Degree Desc                ".
                              " Limit 0,3                           " ;
                }
                else
                {
                    $sQuery = "Select * From wrk_maildata           ".
                              " Where Theather = '".$GrpTheather."' ".
                              "   And FilmCode = '".$GrpFilmCode."' ".
                              "   And ". $AddedCont . "             ".
                              " Order By Degree Desc                ".
                              " Limit 0,3                           " ;
                }

                $PathImage1 = "" ;
                $PathImage2 = "" ;
                $PathImage3 = "" ;

                $QryMailData = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
                while  ($ArrMailData = mysql_fetch_array($QryMailData))
                {
                      if  ($ArrMailData["ImgPath1"]!="")
                      {
                      if  ($PathImage1 == "")
                      {
                          $PathImage1 = $ArrMailData["ImgPath1"] ;
                      }
                      else
                      {
                          if  ($PathImage2 == "")
                          {
                              $PathImage2 = $ArrMailData["ImgPath1"] ;
                          }
                          else
                          {
                              if  ($PathImage3 == "")
                              {
                                  $PathImage3 = $ArrMailData["ImgPath1"] ;
                              }
                          }
                      }
                      }

                      if  ($ArrMailData["ImgPath2"]!="")
                      {
                      if  ($PathImage1 == "")
                      {
                          $PathImage1 = $ArrMailData["ImgPath2"] ;
                      }
                      else
                      {
                          if  ($PathImage2 == "")
                          {
                              $PathImage2 = $ArrMailData["ImgPath2"] ;
                          }
                          else
                          {
                              if  ($PathImage3 == "")
                              {
                                  $PathImage3 = $ArrMailData["ImgPath2"] ;
                              }
                          }
                      }
                      }

                      if  ($ArrMailData["ImgPath3"]!="")
                      {
                      if  ($PathImage1 == "")
                      {
                          $PathImage1 = $ArrMailData["ImgPath3"] ;
                      }
                      else
                      {
                          if  ($PathImage2 == "")
                          {
                              $PathImage2 = $ArrMailData["ImgPath3"] ;
                          }
                          else
                          {
                              if  ($PathImage3 == "")
                              {
                                  $PathImage3 = $ArrMailData["ImgPath3"] ;
                              }
                          }
                      }
                      }
                }

                echo "<TR>" ;
                echo "<TD class=textarea bgcolor=#efefef colspan=2 align=center>" ;
                if  ($PathImage1 != "")
                {
                    echo "<a href=# onclick=\"view_click('".$PathImage1."')\"><IMG SRC='".$PathImage1."' width=200 hieght=100 BORDER=0 ALT=''></a>" ;
                }
                else
                {
                    echo "&nbsp;" ;
                }
                echo "</TD>\n" ;
                echo "<TD class=textarea bgcolor=#efefef colspan=2 align=center>" ;
                if  ($PathImage2 != "")
                {
                    echo "<a href=# onclick=\"view_click('".$PathImage2."')\"><IMG SRC='".$PathImage2."' width=200 hieght=100 BORDER=0 ALT=''></a>" ;
                }
                else
                {
                    echo "&nbsp;" ;
                }
                echo "</TD>\n" ;
                echo "<TD class=textarea bgcolor=#efefef colspan=2 align=center>" ;
                if  ($PathImage3 != "")
                {
                    echo "<a href=# onclick=\"view_click('".$PathImage3."')\"><IMG SRC='".$PathImage3."' width=200 hieght=100 BORDER=0 ALT=''></a>" ;
                }
                else
                {
                    echo "&nbsp;" ;
                }
                echo "</TD>\n" ;

                echo "<TD class=textarea bgcolor=#efefef>" ;
                echo $MailDataBigo ;
                echo "</TD>\n" ;

                echo "</TR>\n" ;
            }

            ?>
            </TABLE>
            <?
       }

  ?>

</body>
</html>

<?
   mysql_close($connect) ;
?>

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





       $Theather = substr($subject,0,4) ; // �����ڵ�
       $sQuery = "Select * From bas_theather    ".
                 " Where Code = '".$Theather."' " ;
       $QryTheather = mysql_query($sQuery,$conn) or die(ee($sQuery)) ;
       if  ($ArrTheatherData = mysql_fetch_array($QryTheather)) // �ش���忡..
       {
           $TheatherName     = $ArrTheatherData["Discript"] ;
           $TheatherLocation = $ArrTheatherData["Location"] ;

           $FilmOpen = substr($subject,4,6) ;  // ��ȭ ���� �ڵ�
           $FilmCode = substr($subject,10,2) ; // ��ȭ �ʸ� �ڵ�

           $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$conn) ;


           $sQuery = "Select * From bas_filmtitle   ".
                     " Where Open = '".$FilmOpen."' ".
                     "   And Code = '".$FilmCode."' " ;
           $QryFilmtitle = mysql_query($sQuery,$conn) or die(ee($sQuery)) ;
           if  ($ArrFilmData = mysql_fetch_array($QryFilmtitle)) // �ش翵ȭ��..
           {
               $FilmCode = $ArrFilmData["Open"] . "" . (string)$ArrFilmData["Code"] ;
               $FilmName = $ArrFilmData["Name"] ;

               // ����+��ȭ �� �ش��ϴ� �����ڷ� ���θ� üũ�Ѵ�.
               $sQuery = "Select Count(*) As CntMail        ".
                         "  From wrk_maildata               ".
                         " Where Theather = '".$Theather."' ".
                         "   and FilmCode = '".$FilmCode."' " ;
               $QryCntMail = mysql_query($sQuery,$conn) or die(ee($sQuery)) ;
               if  ($ArrCntMail = mysql_fetch_array($QryCntMail))
               {
                   $CntMail = $ArrCntMail["CntMail"] ; // �����ڷ��� ������ ���Ѵ�.
               }


               $Degree = 0 ;

               if  ($CntMail > 0)  // �����ϴ� �����ڷᰡ �ִ� ���
               {
                   $sQuery = "Select Max(Degree) As MaxDegree   ".
                             "  From wrk_maildata               ".
                             " Where Theather = '".$Theather."' ".
                             "   and FilmCode = '".$FilmCode."' " ;
                   $QryMaxMail = mysql_query($sQuery,$conn) or die(ee($sQuery)) ;
                   if  ($ArrMaxMail = mysql_fetch_array($QryMaxMail))
                   {
                       $Degree = $ArrMaxMail["MaxDegree"] + 1 ; // ���� ������ Degree�� ���Ѵ�...
                   }
               }














//echo "<br>[".$subject."=".$type."]<br>" ;

       switch($type)
       {
           case "PLAIN": // �Ϲ��ؽ�Ʈ ����
                $Msg = imap_fetchbody($mailstream, $MSG_NO, "1") ;

                $Body   = $Msg ;
                $Image1 = "" ;
                $Image2 = "" ;
                $Image3 = "" ;
                break;

           case "MIXED": // ÷������ �ִ� ����
                for ($i=0; $i<count($struct->parts); $i++)
                {
                    $part      = $struct->parts[$i];
                    $param     = $part->dparameters[0];
                    $file_name = Decode($param->value);
                    $mime      = $part->subtype; // MIME Ÿ�� Ȥ�� ������ ������ ���ϵ˴ϴ�.
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
                    $file_name = Decode($param->value); // ÷�������� ��� ���ϸ�
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

           case "RELATED": // outlook ������ �̹��� ����
                for ($i=0; $i<count($struct->parts); $i++)
                {
                    $part      = $struct->parts[$i];
                    $param     = $part->parameters[0];
                    $file_name = Decode($param->value); // ÷�������� ��� ���ϸ�
                    $mime      = $part->subtype; // MIME Ÿ��
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
                   echo "�˼����� Encoding ���.";
                   exit;
       }

       // mime type �� ���� ����մϴ�.
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
                        $file_name = Decode($param->value); // ÷�������� ��� ���ϸ�
                        $mime      = $part->subtype; // MIME Ÿ��
                        $encode    = $part->encoding; // encoding

                        $msg = getAttach($mailstream, $subject, $MSG_NO, $Degree, $numpart, $encode, $mime, $file_name) ;
                        break;
       }
       return $msg ;

  }


  function getAttach($mailstream, $subject, $MSG_NO, $Degree, $numpart, $encode, $mime, $file_name)
  {
      $val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1), FT_UID);

      switch ($encode)  // �̹��� �ڷḦ ��´�.
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
              echo "�˼����� Encoding ���.";
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
      echo "���� �������!";
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
  for  ($i=0; $i<count($mailno); $i++)  // ������ ������ŭ������ ���ϴ�.
  {
       $no = $mailno[$i]; // ���Ϲ�ȣ�� �򱸿�..

       $head      = imap_header($mailstream,$no);    // ����� ���Ϲ�ȣ�� �ش� ������ ����� �н��ϴ�.
       $recent    = $head->Recent;                   // ������ ���θ� ������ �ݴϴ�.
       $unseen    = $head->Unseen;                   // ������ �о����� ���θ� ������ ����..
       $msgno     = trim($head->Msgno);              // ���Ϲ�ȣ
       $date      = date("Y/m/d H:i", $head->udate); // ������ ��¥�� ���
       $subject   = $head->Subject;                  // ������ ����ϴ�.
       $subject   = Decode($subject);
       $from_obj  = $head->from[0];                  // ���� ����� ��� �κ��Դϴ�. �׳� �Ʒ�ó�� ����ϼ���.
       $from_name = $from_obj->personal;
       $from_addr = substr($from_obj->mailbox . "@" . strtolower($from_obj->host), 0, 30);
       if  ($from_name == "")
       {
           $from_name = $from_addr;
       }
       $from_name = Decode($from_name);



       $nLengSubject = strlen($subject) ; // ������ ����..
       if  (($nLengSubject == 6) || ($nLengSubject == 7))  // ������ ���̴� ������ 6 �ƴϸ� 7
       {
           $Theather = substr($subject,0,4) ; // �����ڵ� 4�ڸ�..

           $sQuery = "Select * From bas_theather    ".
                     " Where Code = '".$Theather."' " ;

           $QryTheather = mysql_query($sQuery,$connect) or die(ee($sQuery)) ; // ������ ã�´�.
           if  ($ArrTheatherData = mysql_fetch_array($QryTheather)) // �ش���忡..
           {
               $TheatherName     = $ArrTheatherData["Discript"] ; // �����̸�
               $TheatherLocation = $ArrTheatherData["Location"] ; // ������ġ

               if  (strlen($subject) == 6) // ����� �ʰ� �״�� �� ����
               {
                   $FilmCode = substr($subject,4,2) ; // �ʸ��ڵ�
               }
               if  (strlen($subject) == 7) // ����� �� ����
               {
                   $FilmCode = substr($subject,5,2) ; // �ʸ��ڵ�
               }

               $sQuery = "Select * From bas_filmtitle   ".
                         " Where Code = '".$FilmCode."' ".
                         " Order By Open Desc           " ; // �����ֱ��� ��ȭ������ ���Ѵ�...
               $QryFilmtitle = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
               if  ($ArrFilmData = mysql_fetch_array($QryFilmtitle)) // �ش翵ȭ��..
               {
                   $FilmCode = $ArrFilmData["Open"] . "" . (string)$ArrFilmData["Code"] ;
                   $FilmName = $ArrFilmData["Name"] ;

                   $sShowroomorder = get_showroomorder($ArrFilmData["Open"],(string)$ArrFilmData["Code"],$connect) ;

                   // ���ο� ���� : �����ڵ�(4)+��ȭ����(6)+��ȭ�ڵ�(2) ..
                   $subject =  $Theather . $FilmCode ;

                   //
                   //
                   //echo  "<br>-----------------------------------------------------------<br>".
                   //      "����:".$subject." �������:".$from_name."<br>" ;
                   checkstruct($mailstream, $subject, $msgno,$connect );
                   //
                   //
               }
           }
       }
  }

  for ($i=0; $i<count($mailno); $i++)  // ������ ������ŭ������ ���ϴ�.
  {
      $no = $mailno[$i]; // ���Ϲ�ȣ�� �򱸿�..

      $result = imap_delete($mailstream, $no);

      if  (!$result)
      {
          echo "��������";
          imap_close($mailstream);
          exit;
      }
      imap_expunge($mailstream);
  }
  imap_close($mailstream);
?>

  <?
           // Ư�������� ���������� ������ �� ���
           if  (($LocationCode) && ($LocationCode!=""))
           {
               $sQuery = "Select * From bas_location        ".
                         " Where Code = '".$LocationCode."' " ;
               $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
               if  ($zone_data = mysql_fetch_array($qryzone))
               {
                   $zoneName = $zone_data["Name"] ;
               }

               if  ($LocationCode=="200")//  �λ��� (�λ�+���+����+â��)
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

           // Ư�������� ���������� ������ �� ���
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
                   if  ($ZoneCode == '20') // �泲�ΰ�� �λ��� �����Ѵ�.
                   {
                        $AddedCont .= " Or TheatherLocation = '200' " ;

                        // (��� + ���� + â�� )

                        $AddedCont .= " Or TheatherLocation <> '203' ".  // �뿵
                                      " Or TheatherLocation <> '600' ".  // ���
                                      " Or TheatherLocation <> '207' ".  // ����
                                      " Or TheatherLocation <> '205' ".  // ����
                                      " Or TheatherLocation <> '208' ".  // ����
                                      " Or TheatherLocation <> '202' ".  // ����
                                      " Or TheatherLocation <> '211' ".  // ��õ
                                      " Or TheatherLocation <> '212' ".  // ��â
                                      " Or TheatherLocation <> '213' ".  // ���
                                      " Or TheatherLocation <> '201' " ; // â��

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
            echo "<TD height=30 bgcolor=#b0c4de align=center><B>���</B></TD>\n" ;
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
                        echo "<TD rowspan=".$GrpCntDegree." class=textarea bgcolor=#dcdcdc align=center width=200><B>��Ȳ����</B></TD>\n" ;
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

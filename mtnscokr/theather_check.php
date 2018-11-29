<?
    $xw = new xmlWriter();
    $xw->openMemory();

    $xmlObj = new xmlWriter();

    $xmlObj->openMemory();

    $YearMonth = date("Ym",time()) ;    // 년월 ...
    $CurDay    = date("d",time()) ;     // 일 ..
    $AgoMonth  = date("Ym",strtotime("-1 month",strtotime(substr($YearMonth,0,4)."-".substr($YearMonth,4,2)."-01"))) ;





    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root');

    include "config.php";        // {[데이터 베이스]} : 환경설정

    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택


    ////////////////////////////////
    $bEq       = 0 ;
    //$bTmpQuery = true ;
    $bTmpQuery = false ;
    trace_init($connect) ;
    /////////////////////////////////////

    $TheatherName = "" ;




    $ObjectMonth = $AgoMonth ; // 무조건 전월..

    // 아이디에 해당하는 극장을 찾는다.
    $sQuery = "Select * From bas_theather    ".   // bas_theather
              " Where UserID = '".$UserID."' " ;  // UserID 가 같으면 여러극장이 있을 수 있다.
                                                  trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;  // echo $sQuery   ;
    $QryTheather = mysql_query($sQuery,$connect) ;
    while  ( $ArrTheather = mysql_fetch_array($QryTheather) )
    {
        $Theather     = $ArrTheather["Code"] ;     // 극장코드
        $TheatherName = $ArrTheather["Discript"] ; // 극장명을 구한다.


        // 전체영화를 탐색한다.
        $sQuery = "Select * From bas_filmtitle ". // bas_filmtitle
                  " Where Extension = 'Y'      ".
                  "   And Finish    = 'N'  "; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;   // echo $sQuery   ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $FilmOpen  = $ArrFilmtitle["Open"] ;
            $FilmCode  = $ArrFilmtitle["Code"] ;
            $FilmName  = $ArrFilmtitle["Name"] ;
            $SingoName = $ArrFilmtitle["SingoName"] ; // 신고테이블명.

            // 신고자료 전체를 탐색한다.
            $sQuery = "Select SingoDate, Count(*) As CntSingo    ".
                      "  From ".$SingoName."                     ".
                      " Where SingoDate  >= '".$ObjectMonth."01' ".
                      "   And SingoDate  <= '".$ObjectMonth."31' ".
                      "   And Open        = '".$FilmOpen."'      ".
                      "   And Film        = '".$FilmCode."'      ".
                      "   And Theather    = '".$Theather."'      ".
                      " Group By SingoDate                       " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;  //  echo $sQuery   ;
            $QryCntSingo = mysql_query($sQuery,$connect) ;
            while ( $ArrCntSingo = mysql_fetch_array($QryCntSingo) )
            {
                $ObjectDay = $ArrCntSingo["SingoDate"] ;
                $CntSingo  = $ArrCntSingo["CntSingo"] ;

                // 만일 해당신고 자료가 있다면.. 가라 chk_extension_day 데이터를 만든다.
                if  ( $CntSingo > 0 )
                {
                    $sQuery = "Insert  Into  chk_extension_day     ".
                              "Values                              ".
                              "(                                   ".
                              "       '".$Theather."',             ".
                              "       '".$ObjectDay."',            ".
                              "       '".$FilmOpen."',             ".
                              "       '".$FilmCode."',             ".
                              "       '".$TheatherName."',         ".
                              "       '".$FilmName."',             ".
                              "       '',                          ".
                              "       '',                          ".
                              "       ''                           ".
                              ")                                   " ;
                    mysql_query($sQuery,$connect) ;
                }
            }
        }




        // 만일 연습영화가 생기면 오동작할수있다.. 연습영화의 ok여부를 체크하도ㅗ록하고 무조건 ok를.. 하삼..
        $sQuery = "Select Count(*) As Cntextension        ".
                  "  From chk_extension_day               ".
                  " Where Theather    = '".$Theather."'   ".
                  "   And ObjectDay  >= '".$AgoMonth."01' ".
                  "   And ObjectDay  <= '".$AgoMonth."31' ".
                  "   And Gubun      <> 'Ok'              " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QryExtension = mysql_query($sQuery,$connect) ;
        $ArrExtension = mysql_fetch_array($QryExtension) ;

        if ($ArrExtension["Cntextension"] > 0)
        {
            $ObjectMonth = $AgoMonth ;
        }
        else
        {
            if  ($CurDay == 1)
            {
                $ObjectMonth = $AgoMonth ; // 월초 까지는 전월..
            }
            else
            {
                $ObjectMonth = $YearMonth ; // 그외는 금월..
            }
        }
        //$ObjectMonth = $AgoMonth ;//////////////////////////////////////////////////



        // 전체영화를 탐색한다.
        $sQuery = "Select * From bas_filmtitle ". // bas_filmtitle
                  " Where Extension = 'Y'      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $FilmOpen  = $ArrFilmtitle["Open"] ;
            $FilmCode  = $ArrFilmtitle["Code"] ;
            $FilmName  = $ArrFilmtitle["Name"] ;
            $SingoName = $ArrFilmtitle["SingoName"] ; // 신고테이블명.

            // 신고자료 전체를 탐색한다.
            $sQuery = "Select SingoDate, Count(*) As CntSingo    ".
                      "  From ".$SingoName."                     ".
                      " Where SingoDate  >= '".$ObjectMonth."01' ".
                      "   And SingoDate  <= '".$YearMonth."31'   ".
                      "   And Open        = '".$FilmOpen."'      ".
                      "   And Film        = '".$FilmCode."'      ".
                      "   And Theather    = '".$Theather."'      ".
                      " Group By SingoDate                       " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
            $QryCntSingo = mysql_query($sQuery,$connect) ;
            while ( $ArrCntSingo = mysql_fetch_array($QryCntSingo) )
            {
                $ObjectDay = $ArrCntSingo["SingoDate"] ;
                $CntSingo  = $ArrCntSingo["CntSingo"] ;

                // 만일 해당신고 자료가 있다면..
                if  ( $CntSingo > 0 )
                {
                    $sQuery = "Select * From chk_extension_day        ".
                              " Where Theather    = '".$Theather."'   ".
                              "   And ObjectDay   = '".$ObjectDay."'  ".
                              "   And Open        = '".$FilmOpen."'   ".
                              "   And Film        = '".$FilmCode."'   " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
                    $QryExtension = mysql_query($sQuery,$connect) ;
                    if  ($ArrExtension = mysql_fetch_array($QryExtension))
                    {
                        $Gubun = $ArrExtension["Gubun"] ;

                        if  ($Gubun=="Er")
                        {
                            $xmlObj->startElement('Extension');
                            $xmlObj->writeElement('Theather',     $Theather);
                            $xmlObj->writeElement('TheatherName', mb_convert_encoding($TheatherName,"UTF-8","EUC-KR"));
                            $xmlObj->writeElement('FilmOpen',     $FilmOpen);
                            $xmlObj->writeElement('FilmCode',     $FilmCode);
                            $xmlObj->writeElement('FilmName',     mb_convert_encoding($FilmName,"UTF-8","EUC-KR"));
                            $xmlObj->writeElement('ObjectDay',    $ObjectDay);
                            $xmlObj->writeElement('Gubun',        "Er");
                            $xmlObj->writeElement('FromDate',     $ObjectMonth."01");
                            $xmlObj->writeElement('ToDate',       $YearMonth."31");
                            $xmlObj->endElement();
                            /*
                            <Extension>
                                 <Theather>1936</Theather>
                                 <TheatherName>부산</TheatherName>
                                 <FilmOpen>070419</FilmOpen>
                                 <FilmCode>01</FilmCode>
                                 <FilmName>리핑 10가지의 재앙</FilmName>
                                 <ObjectDay>20070504</ObjectDay>
                                 <Gubun>Er</Gubun>
                                 <FromDate>20070501</FromDate>
                                 <ToDate>20070531</ToDate>
                            </Extension>
                            */
                        }
                        if  ($Gubun=="")
                        {
                            $xmlObj->startElement('Extension');
                            $xmlObj->writeElement('Theather',     $Theather);
                            $xmlObj->writeElement('TheatherName', mb_convert_encoding($TheatherName,"UTF-8","EUC-KR"));
                            $xmlObj->writeElement('FilmOpen',     $FilmOpen);
                            $xmlObj->writeElement('FilmCode',     $FilmCode);
                            $xmlObj->writeElement('FilmName',     mb_convert_encoding($FilmName,"UTF-8","EUC-KR"));
                            $xmlObj->writeElement('ObjectDay',    $ObjectDay);
                            $xmlObj->writeElement('Gubun',        "");
                            $xmlObj->writeElement('FromDate',     $ObjectMonth."01");
                            $xmlObj->writeElement('ToDate',       $YearMonth."31");
                            $xmlObj->endElement();
                            /*
                            <Extension>
                                 <Theather>1936</Theather>
                                 <TheatherName>부산</TheatherName>
                                 <FilmOpen>070419</FilmOpen>
                                 <FilmCode>01</FilmCode>
                                 <FilmName>리핑 10가지의 재앙</FilmName>
                                 <ObjectDay>20070504</ObjectDay>
                                 <Gubun></Gubun>
                                 <FromDate>20070501</FromDate>
                                 <ToDate>20070531</ToDate>
                            </Extension>
                            */
                        }
                    }
                    else
                    {
                        $xmlObj->startElement('Extension');
                        $xmlObj->writeElement('Theather',     $Theather);
                        $xmlObj->writeElement('TheatherName', mb_convert_encoding($TheatherName,"UTF-8","EUC-KR"));
                        $xmlObj->writeElement('FilmOpen',     $FilmOpen);
                        $xmlObj->writeElement('FilmCode',     $FilmCode);
                        $xmlObj->writeElement('FilmName',     mb_convert_encoding($FilmName,"UTF-8","EUC-KR"));
                        $xmlObj->writeElement('ObjectDay',    $ObjectDay);
                        $xmlObj->writeElement('Gubun',        "");
                        $xmlObj->writeElement('FromDate',     $ObjectMonth."01");
                        $xmlObj->writeElement('ToDate',       $YearMonth."31");
                        $xmlObj->endElement();
                        /*
                        <Extension>
                            <Theather>2492</Theather>
                            <TheatherName>부산 씨네</TheatherName>
                            <FilmOpen>070419</FilmOpen>
                            <FilmCode>01</FilmCode>
                            <FilmName>리핑 10가지의 재앙</FilmName>
                            <ObjectDay>20070522</ObjectDay>
                            <Gubun></Gubun>
                            <FromDate>20070501</FromDate>
                            <ToDate>20070531</ToDate>
                        </Extension>
                        */
                    }
                }
            }
        }
    }




    // 아이디에 해당하는 극장을 찾는다.
    $sQuery = "Select * From bas_theather    ".   // bas_theather
              " Where UserID = '".$UserID."' " ;  // UserID 가 같으면 여러극장이 있을 수 있다.
                                                  trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    $QryTheather = mysql_query($sQuery,$connect) ;
    while  ( $ArrTheather = mysql_fetch_array($QryTheather) )
    {
        $Theather     = $ArrTheather["Code"] ;     // 극장코드
        $TheatherName = $ArrTheather["Discript"] ; // 극장명을 구한다.

        // 전체영화를 탐색한다.
        $sQuery = "Select * From bas_filmtitle ". // bas_filmtitle
                  " Where Extension = 'Y'      " ;     trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $FilmOpen  = $ArrFilmtitle["Open"] ;
            $FilmCode  = $ArrFilmtitle["Code"] ;
            $FilmName  = $ArrFilmtitle["Name"] ;
            $SingoName = $ArrFilmtitle["SingoName"] ; // 신고테이블명.

            $OldSingoMonth = "" ;
            $ObjectDay = "" ;
            $CntSingo  = 0 ;

            // 신고자료 전체를 탐색한다.
            $sQuery = "Select SingoDate                          ".
                      "  From ".$SingoName."                     ".
                      " Where Open        = '".$FilmOpen."'      ".
                      "   And Film        = '".$FilmCode."'      ".
                      "   And Theather    = '".$Theather."'      ".
                      " Group By SingoDate                       ".
                      " Order By SingoDate                       " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
            $QryCntSingo = mysql_query($sQuery,$connect) ;
            while ( $ArrCntSingo = mysql_fetch_array($QryCntSingo) )
            {
                $ObjectDay = $ArrCntSingo["SingoDate"] ;

                if  ($OldSingoMonth == "")
                {
                    $OldSingoMonth = substr($ObjectDay,0,6) ;
                }

                if  ($OldSingoMonth != substr($ObjectDay,0,6) )
                {
                    $xmlObj->startElement('ExtensionAll');
                    $xmlObj->writeElement('Theather',     $Theather);
                    $xmlObj->writeElement('TheatherName', mb_convert_encoding($TheatherName,"UTF-8","EUC-KR"));
                    $xmlObj->writeElement('FilmOpen',     $FilmOpen);
                    $xmlObj->writeElement('FilmCode',     $FilmCode);
                    $xmlObj->writeElement('FilmName',     mb_convert_encoding($FilmName,"UTF-8","EUC-KR"));
                    $xmlObj->writeElement('ObjectDay',    $OldSingoMonth);
                    $xmlObj->writeElement('Gubun',        $CntSingo);
                    $xmlObj->writeElement('FromDate',     $OldSingoMonth."01");
                    $xmlObj->writeElement('ToDate',       $OldSingoMonth."31");
                    $xmlObj->endElement();

                    $OldSingoMonth = substr($ObjectDay,0,6) ;
                    $CntSingo = 0 ;
                }

                $CntSingo++ ;
            }

            if  ($ObjectDay != "")
            {
                $xmlObj->startElement('ExtensionAll');
                $xmlObj->writeElement('Theather',     $Theather);
                $xmlObj->writeElement('TheatherName', mb_convert_encoding($TheatherName,"UTF-8","EUC-KR"));
                $xmlObj->writeElement('FilmOpen',     $FilmOpen);
                $xmlObj->writeElement('FilmCode',     $FilmCode);
                $xmlObj->writeElement('FilmName',     mb_convert_encoding($FilmName,"UTF-8","EUC-KR"));
                $xmlObj->writeElement('ObjectDay',    $OldSingoMonth);
                $xmlObj->writeElement('Gubun',        $CntSingo);
                $xmlObj->writeElement('FromDate',     $OldSingoMonth."01");
                $xmlObj->writeElement('ToDate',       $OldSingoMonth."31");
                $xmlObj->endElement();
            }
        }
    }


    $sQuery = "Select * From bas_theather    ".   // bas_theather
              " Where UserID = '".$UserID."' " ;  // UserID 가 같으면 여러극장이 있을 수 있다.
                                                 trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    $QryTheather = mysql_query($sQuery,$connect) ;
    while  ( $ArrTheather = mysql_fetch_array($QryTheather) )
    {
         $Theather     = $ArrTheather["Code"] ;     // 극장코드
         $TheatherName = $ArrTheather["Discript"] ; // 극장명을 구한다.

         $sQuery = "Select * From chk_theather_rcvmsg  ".
                   " Where Theather = '".$Theather."'  ".
                   " Order By MsgNo Desc               ".
                   " Limit 0 , 20                      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
         $QryRcvMsg = mysql_query($sQuery,$connect) ;
         while  ( $ArrRcvMsg = mysql_fetch_array($QryRcvMsg) )
         {
              $Theather   = $ArrRcvMsg["Theather"] ;
              $MsgYear    = $ArrRcvMsg["MsgYear"] ;
              $MsgNo      = $ArrRcvMsg["MsgNo"] ;
              $Readed     = $ArrRcvMsg["Readed"] ;
              $MsgDate    = $ArrRcvMsg["MsgDate"] ;
              $MsgTime    = $ArrRcvMsg["MsgTime"] ;
              $MsgTitle   = $ArrRcvMsg["MsgTitle"] ;
              $MsgContent = $ArrRcvMsg["MsgContent"] ;
              $Attach1    = $ArrRcvMsg["Attach1"] ;
              $Attach2    = $ArrRcvMsg["Attach2"] ;
              $Attach3    = $ArrRcvMsg["Attach3"] ;
              $File1      = $ArrRcvMsg["File1"] ;
              $File2      = $ArrRcvMsg["File2"] ;
              $File3      = $ArrRcvMsg["File3"] ;


              $xmlObj->startElement('RcvMessage');
              $xmlObj->writeElement('Theather',     $Theather) ;
              $xmlObj->writeElement('MsgYear',      $MsgYear) ;
              $xmlObj->writeElement('MsgNo',        $MsgNo) ;
              $xmlObj->writeElement('Readed',       $Readed) ;
              $xmlObj->writeElement('TheatherName', mb_convert_encoding($TheatherName,"UTF-8","EUC-KR")) ;
              $xmlObj->writeElement('MsgDate',      $MsgDate) ;
              $xmlObj->writeElement('MsgTime',      $MsgTime) ;
              $xmlObj->writeElement('MsgTitle',     mb_convert_encoding($MsgTitle,"UTF-8","EUC-KR")) ;
              $xmlObj->writeElement('MsgContent',   mb_convert_encoding($MsgContent,"UTF-8","EUC-KR")) ;
              $xmlObj->writeElement('Attach1',      mb_convert_encoding($Attach1,"UTF-8","EUC-KR")) ;
              $xmlObj->writeElement('Attach2',      mb_convert_encoding($Attach2,"UTF-8","EUC-KR")) ;
              $xmlObj->writeElement('Attach3',      mb_convert_encoding($Attach3,"UTF-8","EUC-KR")) ;
              $xmlObj->writeElement('File1',        mb_convert_encoding($File1,"UTF-8","EUC-KR")) ;
              $xmlObj->writeElement('File2',        mb_convert_encoding($File2,"UTF-8","EUC-KR")) ;
              $xmlObj->writeElement('File3',        mb_convert_encoding($File3,"UTF-8","EUC-KR")) ;
              $xmlObj->endElement();
         }
    }
    mysql_close($connect);       // {[데이터 베이스]} : 단절


    $xmlObj->endElement();

    print $xmlObj->outputMemory(true);
?>
<?
    $xw = new xmlWriter();
    $xw->openMemory();

    $xmlObj = new xmlWriter();

    $xmlObj->openMemory();

    $YearMonth = date("Ym",time()) ;    // ��� ...
    $CurDay    = date("d",time()) ;     // �� ..
    $AgoMonth  = date("Ym",strtotime("-1 month",strtotime(substr($YearMonth,0,4)."-".substr($YearMonth,4,2)."-01"))) ;





    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root');

    include "config.php";        // {[������ ���̽�]} : ȯ�漳��

    $connect = dbconn() ;        // {[������ ���̽�]} : ����

    mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����


    ////////////////////////////////
    $bEq       = 0 ;
    //$bTmpQuery = true ;
    $bTmpQuery = false ;
    trace_init($connect) ;
    /////////////////////////////////////

    $TheatherName = "" ;




    $ObjectMonth = $AgoMonth ; // ������ ����..

    // ���̵� �ش��ϴ� ������ ã�´�.
    $sQuery = "Select * From bas_theather    ".   // bas_theather
              " Where UserID = '".$UserID."' " ;  // UserID �� ������ ���������� ���� �� �ִ�.
                                                  trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;  // echo $sQuery   ;
    $QryTheather = mysql_query($sQuery,$connect) ;
    while  ( $ArrTheather = mysql_fetch_array($QryTheather) )
    {
        $Theather     = $ArrTheather["Code"] ;     // �����ڵ�
        $TheatherName = $ArrTheather["Discript"] ; // ������� ���Ѵ�.


        // ��ü��ȭ�� Ž���Ѵ�.
        $sQuery = "Select * From bas_filmtitle ". // bas_filmtitle
                  " Where Extension = 'Y'      ".
                  "   And Finish    = 'N'  "; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;   // echo $sQuery   ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $FilmOpen  = $ArrFilmtitle["Open"] ;
            $FilmCode  = $ArrFilmtitle["Code"] ;
            $FilmName  = $ArrFilmtitle["Name"] ;
            $SingoName = $ArrFilmtitle["SingoName"] ; // �Ű����̺��.

            // �Ű��ڷ� ��ü�� Ž���Ѵ�.
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

                // ���� �ش�Ű� �ڷᰡ �ִٸ�.. ���� chk_extension_day �����͸� �����.
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




        // ���� ������ȭ�� ����� �������Ҽ��ִ�.. ������ȭ�� ok���θ� üũ�ϵ��Ƿ��ϰ� ������ ok��.. �ϻ�..
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
                $ObjectMonth = $AgoMonth ; // ���� ������ ����..
            }
            else
            {
                $ObjectMonth = $YearMonth ; // �׿ܴ� �ݿ�..
            }
        }
        //$ObjectMonth = $AgoMonth ;//////////////////////////////////////////////////



        // ��ü��ȭ�� Ž���Ѵ�.
        $sQuery = "Select * From bas_filmtitle ". // bas_filmtitle
                  " Where Extension = 'Y'      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $FilmOpen  = $ArrFilmtitle["Open"] ;
            $FilmCode  = $ArrFilmtitle["Code"] ;
            $FilmName  = $ArrFilmtitle["Name"] ;
            $SingoName = $ArrFilmtitle["SingoName"] ; // �Ű����̺��.

            // �Ű��ڷ� ��ü�� Ž���Ѵ�.
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

                // ���� �ش�Ű� �ڷᰡ �ִٸ�..
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
                                 <TheatherName>�λ�</TheatherName>
                                 <FilmOpen>070419</FilmOpen>
                                 <FilmCode>01</FilmCode>
                                 <FilmName>���� 10������ ���</FilmName>
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
                                 <TheatherName>�λ�</TheatherName>
                                 <FilmOpen>070419</FilmOpen>
                                 <FilmCode>01</FilmCode>
                                 <FilmName>���� 10������ ���</FilmName>
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
                            <TheatherName>�λ� ����</TheatherName>
                            <FilmOpen>070419</FilmOpen>
                            <FilmCode>01</FilmCode>
                            <FilmName>���� 10������ ���</FilmName>
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




    // ���̵� �ش��ϴ� ������ ã�´�.
    $sQuery = "Select * From bas_theather    ".   // bas_theather
              " Where UserID = '".$UserID."' " ;  // UserID �� ������ ���������� ���� �� �ִ�.
                                                  trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    $QryTheather = mysql_query($sQuery,$connect) ;
    while  ( $ArrTheather = mysql_fetch_array($QryTheather) )
    {
        $Theather     = $ArrTheather["Code"] ;     // �����ڵ�
        $TheatherName = $ArrTheather["Discript"] ; // ������� ���Ѵ�.

        // ��ü��ȭ�� Ž���Ѵ�.
        $sQuery = "Select * From bas_filmtitle ". // bas_filmtitle
                  " Where Extension = 'Y'      " ;     trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $FilmOpen  = $ArrFilmtitle["Open"] ;
            $FilmCode  = $ArrFilmtitle["Code"] ;
            $FilmName  = $ArrFilmtitle["Name"] ;
            $SingoName = $ArrFilmtitle["SingoName"] ; // �Ű����̺��.

            $OldSingoMonth = "" ;
            $ObjectDay = "" ;
            $CntSingo  = 0 ;

            // �Ű��ڷ� ��ü�� Ž���Ѵ�.
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
              " Where UserID = '".$UserID."' " ;  // UserID �� ������ ���������� ���� �� �ִ�.
                                                 trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    $QryTheather = mysql_query($sQuery,$connect) ;
    while  ( $ArrTheather = mysql_fetch_array($QryTheather) )
    {
         $Theather     = $ArrTheather["Code"] ;     // �����ڵ�
         $TheatherName = $ArrTheather["Discript"] ; // ������� ���Ѵ�.

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
    mysql_close($connect);       // {[������ ���̽�]} : ����


    $xmlObj->endElement();

    print $xmlObj->outputMemory(true);
?>
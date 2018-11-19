<?
    $xmlObj = new xmlWriter();
    $xmlObj->openMemory();


    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root');

    include "config.php";        // {[데이터 베이스]} : 환경설정

    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db,$connect) ;  // {[데이터 베이스]} : 디비선택

    ////////////////////////////////
    $bEq       = 0 ;
    //$bTmpQuery = true ;
    $bTmpQuery = false ;
    //trace_init($connect) ;
    /////////////////////////////////////

    $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..


    $sQuery = "Select Silmooja From bas_silmoojatheatherpriv  ".
              " Where Theather = '".$Theather."'              ".
              "   And Open     = '".$FilmOpen."'              ".
              "   And Film     = '".$FilmCode."'              ".
              " Order By WorkDate Desc                        ".
              " LIMIT 0 , 1                                   " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    $QrySilmoojaTheather = mysql_query($sQuery,$connect) ;
    if  ($ArrSilmoojaTheather = mysql_fetch_array($QrySilmoojaTheather))
    {
        $SilmoojaCode = $ArrSilmoojaTheather["Silmooja"] ;

        $sQuery = "Select Name From bas_silmooja      ".
                  " Where Code = '".$SilmoojaCode."'  " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QrySilmooja = mysql_query($sQuery,$connect) ;
        if  ($ArrSilmooja = mysql_fetch_array($QrySilmooja))
        {
            $Silmooja = $ArrSilmooja["Name"] ;
        }
    }

    $nUnitPrices = 0 ;

    $sQuery = "Select Distinct UnitPrice               ".
              "  From ".$sSingoName."                  ".
              " Where SingoDate  >= '".$FromDate."'    ".
              "   And SingoDate  <= '".$ToDate."'      ".
              "   And Open        = '".$FilmOpen."'    ".
              "   And Film        = '".$FilmCode."'    ".
              "   And Theather    = '".$Theather."'    ".
              " Order By UnitPrice Desc                " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    $QryUnitPrice = mysql_query($sQuery,$connect) ;
    while ($ArrUnitPrice = mysql_fetch_array($QryUnitPrice))
    {
        $ArrUnitPrices[$nUnitPrices] = $ArrUnitPrice["UnitPrice"] ;

        $nUnitPrices ++ ;
    }

    //
    // 타이틀 노드
    //
    $xmlObj->startElement('Record');
    $xmlObj->writeElement('WorkDate', "");
    for ($i = 0 ; $i < 20 ; $i++) // 8->10->20
    {
        if  ($i < $nUnitPrices)
        {
            $xmlObj->writeElement('Value'.($i+1), $ArrUnitPrices[$i]);
        }
        else
        {
            $xmlObj->writeElement('Value'.($i+1), "");
        }
    }
    $xmlObj->writeElement('ValueSum', "");
    $xmlObj->writeElement('ValueAcc', "");
    $xmlObj->writeElement('SignDay',  "");
    $xmlObj->writeElement('SignTime', "");
    $xmlObj->writeElement('ChkSign',  "");
    $xmlObj->endElement();

    $First = true ;



    $sQuery = "Select Distinct SingoDate               ".
              "  From ".$sSingoName."                  ".
              " Where SingoDate  >= '".$FromDate."'    ".
              "   And SingoDate  <= '".$ToDate."'      ".
              "   And SingoDate  <= '".$ObjectDay."'   ".
              "   And Open        = '".$FilmOpen."'    ".
              "   And Film        = '".$FilmCode."'    ".
              "   And Theather    = '".$Theather."'    ".
              " Order By SingoDate                     " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;

    $xmlObj->writeElement('sQuery', $sQuery);

    $QrySingoDate = mysql_query($sQuery,$connect) ;
    while  ($ArrSingoDate = mysql_fetch_array($QrySingoDate))
    {
        $SingoDate = $ArrSingoDate["SingoDate"] ;

        if  ($First==true)
        {
            $rFromDate = $SingoDate ; // 실제 시작일자
            $First = false ;
        }

        //
        // 실제 데이터 노드
        //
        $xmlObj->startElement('Record');
        $xmlObj->writeElement('WorkDate', $SingoDate);

        for ($i = 0 ; $i < 20 ; $i++) // 8->10->20
        {
            if  ($i < $nUnitPrices)
            {
                $sQuery = "Select Sum(NumPersons) As SumNumPersons       ".
                          "  From ".$sSingoName."                        ".
                          " Where SingoDate   = '".$SingoDate."'         ".
                          "   And UnitPrice   = '".$ArrUnitPrices[$i]."' ".
                          "   And Open        = '".$FilmOpen."'          ".
                          "   And Film        = '".$FilmCode."'          ".
                          "   And Theather    = '".$Theather."'          " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
                $QrySingo = mysql_query($sQuery,$connect) ;
                if  ($ArrSingo = mysql_fetch_array($QrySingo))
                {
                    $xmlObj->writeElement('Value'.($i+1), $ArrSingo["SumNumPersons"]);
                }
                else
                {
                    $xmlObj->writeElement('Value'.($i+1), "");
                }
            }
            else
            {
                $xmlObj->writeElement('Value'.($i+1), "");
            }
        }

        $sQuery = "Select Sum(NumPersons) As SumNumPersons    ".
                  "  From ".$sSingoName."                     ".
                  " Where SingoDate   = '".$SingoDate."'      ".
                  "   And Open        = '".$FilmOpen."'       ".
                  "   And Film        = '".$FilmCode."'       ".
                  "   And Theather    = '".$Theather."'       " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QrySingo = mysql_query($sQuery,$connect) ;
        if  ($ArrSingo = mysql_fetch_array($QrySingo))
        {
            $xmlObj->writeElement('ValueSum', $ArrSingo["SumNumPersons"]);
        }

        $StartDay = substr($ObjectDay,0,6) . "01" ;

        $sQuery = "Select Sum(NumPersons) As SumNumPersons    ".
                  "  From ".$sSingoName."                     ".
                  " Where SingoDate  >= '".$StartDay."'       ".
                  "   And SingoDate  <= '".$SingoDate."'      ".
                  "   And Open        = '".$FilmOpen."'       ".
                  "   And Film        = '".$FilmCode."'       ".
                  "   And Theather    = '".$Theather."'       " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QrySingo = mysql_query($sQuery,$connect) ;
        if  ($ArrSingo = mysql_fetch_array($QrySingo))
        {
            $xmlObj->writeElement('ValueAcc', $ArrSingo["SumNumPersons"]);
        }

        $sQuery = "Select * From chk_extension_day        ".
                  " Where Theather    = '".$Theather."'   ".
                  "   And ObjectDay   = '".$SingoDate."'  ".
                  "   And Open        = '".$FilmOpen."'   ".
                  "   And Film        = '".$FilmCode."'   ".
                  "   And Gubun       = 'Ok'              " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QryExtension = mysql_query($sQuery,$connect) ;
        if  ($ArrExtension = mysql_fetch_array($QryExtension))
        {
            $Damdangja = $ArrExtension["Damdangja"] ;
            $SignDay   = substr($ArrExtension["SignTime"],0,8) ;
            $SignTime  = substr($ArrExtension["SignTime"],9,6) ;

            $xmlObj->writeElement('SignDay',  $SignDay);
            $xmlObj->writeElement('SignTime', $SignTime);
            $xmlObj->writeElement('ChkSign',  mb_convert_encoding($Damdangja,"UTF-8","EUC-KR"));
        }
        else
        {
            $xmlObj->writeElement('SignDay',  "");
            $xmlObj->writeElement('SignTime', "");
            $xmlObj->writeElement('ChkSign',  "");
        }

        $xmlObj->endElement();
    }
    $rToDate = $SingoDate ; // 실제 종료일자

    mysql_close($connect);       // {[데이터 베이스]} : 단절



    $timestamp2 = mktime(0,0,0,substr($rFromDate,4,2),substr($rFromDate,6,2),substr($rFromDate,0,4));
    $dur_time2  = (time() - $timestamp2) / 86400;

    $timestamp1 = mktime(0,0,0,substr($rToDate,4,2),substr($rToDate,6,2),substr($rToDate,0,4));
    $dur_time1  = (time() - $timestamp1) / 86400;

    $dur_day    = ($dur_time2 - $dur_time1) + 1 ;  // 일수

    $xmlObj->startElement('Term');
    $xmlObj->writeElement('Silmooja', mb_convert_encoding($Silmooja,"UTF-8","EUC-KR")) ;
    $xmlObj->writeElement('FromDate', $rFromDate);
    $xmlObj->writeElement('ToDate', $rToDate);
    $xmlObj->writeElement('DurDay', $dur_day);
    $xmlObj->endElement();

    $xmlObj->endElement();

    print $xmlObj->outputMemory(true);
?>
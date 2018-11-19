<?
    $Now        = strtotime('now');
    $sCurrYear  = date("Y",$Now) ;    // 현재년..

    $xw = new xmlWriter();
    $xw->openMemory();

    $xmlObj = new xmlWriter();

    $xmlObj->openMemory();

    
    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root'); 
    
    include "config.php";        // {[데이터 베이스]} : 환경설정
                    
    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db,$connect) ;  // {[데이터 베이스]} : 디비선택

    ////////////////////////////////
    $bEq       = 0 ;
    $bTmpQuery = 1 ;
    trace_init($connect) ;  
    /////////////////////////////////////

    if  (($UserID) && ($MsgYear) && ($MsgNo))
    {
        // 전송된 메시지를 삭제한다.
        $sQuery = "Delete From chk_theather_sndmsg    ".
                  " Where UserID   = '".$UserID."'    ".
                  "   And MsgYear  = '".$MsgYear."'   ".
                  "   And MsgNo    = '".$MsgNo."'     " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        mysql_query($sQuery,$connect) ;
    }


    //전송된 메시지를 구한다.
    $sQuery = "Select * From chk_theather_sndmsg    ".
              " Where MsgYear  = '".$sCurrYear."'   ".
              " Order By MsgDate Desc,              ".
              "          MsgTime Desc               ".
              " Limit 0 , 20                        " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    $QryMessage = mysql_query($sQuery,$connect) ;
    while  ($ArrMessage = mysql_fetch_array($QryMessage))
    {
        $UserID     = $ArrMessage["UserID"] ;
        $MsgYear    = $ArrMessage["MsgYear"] ;
        $MsgNo      = $ArrMessage["MsgNo"] ;
        $Readed     = $ArrMessage["Readed"] ;
        $MsgDate    = $ArrMessage["MsgDate"] ;
        $MsgTime    = $ArrMessage["MsgTime"] ;
        $MsgTitle   = $ArrMessage["MsgTitle"] ;
        $MsgContent = $ArrMessage["MsgContent"] ;
        $Attach1    = $ArrMessage["Attach1"] ;
        $Attach2    = $ArrMessage["Attach2"] ;
        $Attach3    = $ArrMessage["Attach3"] ;
        $File1      = $ArrMessage["File1"] ;
        $File2      = $ArrMessage["File2"] ;
        $File3      = $ArrMessage["File3"] ;

        
        $Discript = "" ;

        $sQuery = "Select Location, Discript      ".
                  "  From bas_theather            ".
                  " Where UserID = '".$UserID."'  " ;  trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QryTheather = mysql_query($sQuery,$connect) ;
        while ($ArrTheather = mysql_fetch_array($QryTheather))
        {
            if  ($Discript != "" )
            {
                $Discript = $Discript . "," ;
            }
            $Discript = $Discript . $ArrTheather["Discript"] ;
            $Location = $ArrTheather["Location"] ;

              
            $sQuery = "Select Name                    ".
                      "  From bas_location            ".
                      " Where Code = '".$Location."'  " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
            $QryLocation = mysql_query($sQuery,$connect) ;
            if  ($ArrLocation = mysql_fetch_array($QryLocation))
            {
                $LocationName = $ArrLocation["Name"] ;
            }
        }

        $xmlObj->startElement('RcdMessage');     
        $xmlObj->writeElement('UserID',       $UserID) ;
        $xmlObj->writeElement('MsgYear',      $MsgYear) ;
        $xmlObj->writeElement('MsgNo',        $MsgNo) ;
        $xmlObj->writeElement('Readed',       $Readed) ;
        $xmlObj->writeElement('TheatherName', mb_convert_encoding($Discript,"UTF-8","EUC-KR")) ;
        $xmlObj->writeElement('TheatherLoc',  mb_convert_encoding($LocationName,"UTF-8","EUC-KR")) ;
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

    
    $sQuery = "Select * From chk_extension_day   ".
              " Where Gubun     = 'Er'           ".
              " Order By ObjectDay Desc          " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    $QryExtensionDay = mysql_query($sQuery,$connect) ;
    while  ($ArrExtensionDay = mysql_fetch_array($QryExtensionDay))
    {        
        $Theather     = $ArrExtensionDay["Theather"] ; 
        $ObjectDay    = $ArrExtensionDay["ObjectDay"] ; 
        $TheatherName = $ArrExtensionDay["TheatherName"] ; 
        $FilmName     = $ArrExtensionDay["FilmName"] ;     
        $Damdangja    = $ArrExtensionDay["Damdangja"] ;    
        $SignTime     = $ArrExtensionDay["SignTime"] ;            

        
        $sQuery = "Select location.Name As Location         ".
                  "  From bas_theather As theather          ".
                  "  Left Join bas_location As location     ".
                  "    On theather.Location = location.Code ".
                  " Where theather.Code = '".$Theather."'   " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QryTheather = mysql_query($sQuery,$connect) ;
        if  ($ArrTheather = mysql_fetch_array($QryTheather))
        {
            $Location = $ArrTheather["Location"] ;
        }
        
        
        $xmlObj->startElement('RcdError');     
        $xmlObj->writeElement('ObjectDay',    $ObjectDay) ;
        $xmlObj->writeElement('SignTime',     $SignTime) ;
        $xmlObj->writeElement('Location',     mb_convert_encoding($Location,"UTF-8","EUC-KR")) ;
        $xmlObj->writeElement('TheatherName', mb_convert_encoding($TheatherName,"UTF-8","EUC-KR")) ;
        $xmlObj->writeElement('FilmName',     mb_convert_encoding($FilmName,"UTF-8","EUC-KR")) ;
        $xmlObj->writeElement('Damdangja',    mb_convert_encoding($Damdangja,"UTF-8","EUC-KR")) ;
        $xmlObj->endElement(); 
    }

    
    mysql_close($connect);       // {[데이터 베이스]} : 단절    
    
    
    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       
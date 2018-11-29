<?
    $xw = new xmlWriter();
    $xw->openMemory();

    $xmlObj = new xmlWriter();

    $xmlObj->openMemory();

    $CurDay    = date("d",time()) ;     
    $YearMonth = date("Ym",time()) ;          // 년월 ...
    $AgoMonth  = date("Ym",strtotime("-1 month",strtotime(substr($YearMonth,0,4)."-".substr($YearMonth,4,2)."-01"))) ;

    if  ($CurDay == "01") 
    {
        $ObjectMonth = $AgoMonth ; // 월초 까지는 전월..
    }                
    else
    {
        $ObjectMonth = $YearMonth ; // 그외는 금월..
    }

     
    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root'); 
    
    include "config.php";        // {[데이터 베이스]} : 환경설정
                    
    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db,$connect) ;  // {[데이터 베이스]} : 디비선택


    ////////////////////////////////
    $bEq       = 0 ;
    $bTmpQuery = 0 ;
    trace_init($connect) ;  
    /////////////////////////////////////

    $sQuery = "Update chk_theather_rcvmsg         ". 
              "   Set Readed = 'Y'                ".
              " Where Theather = '".$Theather."'  ".
              "   And MsgYear  = '".$MsgYear ."'  ".
              "   And MsgNo    = '".$MsgNo   ."'  " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    mysql_query($sQuery,$connect) ;

    

    mysql_close($connect);       // {[데이터 베이스]} : 단절


    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       
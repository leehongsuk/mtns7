<?
    session_start();
    
    include "config.php";
    
    $connect = dbconn();
    mysql_select_db($cont_db) ; 

    $open    =  $_POST['open'];
    $code    =  $_POST['code'];
    $bChk35  =  $_POST['bChk35']=="true"?"Y":"N";
    $bChk2   =  $_POST['bChk2']=="true"?"Y":"N";
    $bChk20  =  $_POST['bChk20']=="true"?"Y":"N";
    $bChk3   =  $_POST['bChk3']=="true"?"Y":"N";
    $bChk30  =  $_POST['bChk30']=="true"?"Y":"N";
    $bChk29  =  $_POST['bChk29']=="true"?"Y":"N";
    $bChk39  =  $_POST['bChk39']=="true"?"Y":"N";
    $bChk24  =  $_POST['bChk24']=="true"?"Y":"N";
    $bChk34  =  $_POST['bChk34']=="true"?"Y":"N";
    $bChk294 =  $_POST['bChk294']=="true"?"Y":"N";
    $bChk394 =  $_POST['bChk394']=="true"?"Y":"N";
    $bChk4   =  $_POST['bChk4']=="true"?"Y":"N";

    $sQuery = "Update bas_filmtitle_typelimit  ".
              "   Set Type35  = '$bChk35',     ".
              "       Type2   = '$bChk2',      ".
              "       Type20  = '$bChk20',     ".
              "       Type3   = '$bChk3',      ".
              "       Type30  = '$bChk30',     ".
              "       Type29  = '$bChk29',     ".
              "       Type39  = '$bChk39',     ".
              "       Type24  = '$bChk24',     ".
              "       Type34  = '$bChk34',     ".
              "       Type294 = '$bChk294',    ".
              "       Type394 = '$bChk394',    ".
              "       Type4   = '$bChk4'       ".
              " Where Open = '$open'           ".
              "   And Code = '$code'           " ;              
    mysql_query($sQuery,$connect) ;   
//echo $sQuery;
?>
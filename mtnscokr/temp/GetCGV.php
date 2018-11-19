<?
    session_start();

    include "../config.php";

    $connect=dbconn();

    mysql_select_db($cont_db) ;

    $sQuery = "Update bas_theather
                  Set JikYong = 'N'
               " ;
    echo $sQuery  ."<br>"    ;
    mysql_query($sQuery,$connect) ;

    $sQuery = "Select * From bas_showroom
                Where MultiPlex = '2'
              " ;
    $QryShowroom = mysql_query($sQuery,$connect) ;
    while ($ArrShowroom = mysql_fetch_array($QryShowroom))
    {
         $theather = $ArrShowroom["Theather"] ;
         $Discript = $ArrShowroom["Discript"] ;


          echo $Discript."&nbsp;&nbsp;";

         $sQuery = "Update bas_theather
                      Set JikYong = 'Y'
                     Where Code = '$theather'
                   " ;
         echo $sQuery  ."<br>"    ;
         mysql_query($sQuery,$connect) ;

    }

    mysql_close($connect);
?>
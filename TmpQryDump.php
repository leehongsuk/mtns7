    <?
    set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....
    ?>

    <html>
    <head>
           <title>TmpQryDump</title>
    </head>

    <?
    
    include "config.php";        // {[������ ���̽�]} : ȯ�漳��
                   
    $connect = dbconn() ;        // {[������ ���̽�]} : ����

    mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����               


    
    $_P_Empty = $_POST["Empty"] ;

    if  ($_P_Empty == "Yes")
    {
        $sQuery = "TRUNCATE TABLE tmp_query " ;
        mysql_query($sQuery,$connect) ;
    }

    /***********
    $_P_RecoverSeat = $_GET["RecoverSeat"] ;

    if  ($_P_RecoverSeat == "Yes")
    {
        $sQuery = "Select * From bas_theatherseat_back  " ;
        $QrySeatBack = mysql_query($sQuery,$connect) ;
        while  ($ArrSeatBack = mysql_fetch_array($QrySeatBack))
        {
             $Theather = $ArrSeatBack["Theather"] ;
             $Room     = $ArrSeatBack["Room"] ;
             $Seat     = $ArrSeatBack["Seat"] ;

             $KorName = "" ;
             if  ($ArrTheather = jinbo_find_theather($Theather,$connect))
             {
                 $KorName = $ArrTheather["KorName"] ; // ����� ..
             }

             $sQuery = "Select * From bas_theatherseat      ". 
                       "  Where Theather = '".$Theather."'  ".
                       "    And Room     = '".$Room."'      " ;
             if  ($QrySeat = mysql_query($sQuery,$connect))
             {
                  $sQuery = "Update bas_theatherseat               ". 
                            "   Set Seat         = '".$Seat."',    ".
                            "       TheatherName = '".$KorName."'  ".
                            " Where Theather = '".$Theather."'     ".
                            "   And Room     = '".$Room."'         " ;
                  mysql_query($sQuery,$connect) ;
             }
             else
             {
                 $sQuery = "Insert Into  bas_theatherseat     ". 
                           "Values                            ".
                           "(                                 ".
                           "   '".$Theather."',               ".
                           "   '".$Room."',                   ".
                           "   '".date("Ymd",time())."',      ".
                           "   '".$Seat."',                   ".
                           "   '".$KorName."'                 ".
                           ")                                 " ;
                 mysql_query($sQuery,$connect) ;
             }
        }
    }
    ***********/
    ?>

    <body>

    <script language="javascript">
    <!--
         // onSubmit="" �� �Լ��� ���ϰ��� ������ �ʴ´�..
         // �׷��Ƿ� return ���� ���� ������ �����...
         function Submit()
         {
             form1.Empty.value = "Yes" ;
         }

         function reloading()
         {
             location.href = "<?=$PHP_SELF?>" ;
         }

         function reloading()
         {
             location.href = "<?=$PHP_SELF?>" ;
         }

         /*******
         function RecoverSeat()
         {
             location.href = "<?=$PHP_SELF?>?RecoverSeat=Yes" ;
         }
         *******/

         
    //-->
    </script>
    
    
    
    <CENTER>
    
    <form method=post name=form1 onSubmit="return Submit();">    

    <input type="hidden" name=Empty value="">
    <input type="submit" value="����">

    <input type="button" value="�ٽ��б�" onclick="reloading();">
    <!-- 
    <input type="button" value="�¼�����"" onclick="RecoverSeat();"> 
    -->
    <BR><BR>
    
    <?
    $sQuery = "Select * From tmp_query      ".
              " Order By SeqNo Desc         " ;
    $QryTmpQuery = mysql_query($sQuery, $connect) ;               
    while ( $ObjTmpQuery = mysql_fetch_object( $QryTmpQuery ) ) 
    {
        ?>
        <table border=1>
        <tr>
            <td valign=top>
                 [<?=number_format($ObjTmpQuery->SeqNo)?>]
            </td>    
            <td>
                 <textarea name="Content" cols="80"  rows="20">
                 <?=$ObjTmpQuery->Content?>
                 </textarea>
            </td>
        </tr>
        </table>    
        <?
    }
    ?>
    </form>

    </CENTER>

    <?    
    mysql_close($connect) ;      // {[������ ���̽�]} : ����
    ?>
    </body>

    </html>

<?
    session_start();
?>
<html>
<?
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[������ ���̽�]} : ȯ�漳��
                   
        $connect = dbconn() ;        // {[������ ���̽�]} : ����

        mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����

        // ��ȭŸ��Ʋ�� ���Ѵ�.
        $qryfilmtitle = mysql_query("Select * From bas_filmtitle               ".
                                    " Where Open = '".substr($FilmTile,0,6)."' ".
                                    "   and Code = '".substr($FilmTile,6,2)."' ") ;
        if  ($filmtitle_data = mysql_fetch_array($qryfilmtitle))
        {
            $filmtitleCode = $filmtitle_data["Open"].$filmtitle_data["Code"] ;
            $filmtitleName = $filmtitle_data["Name"] ;
        }


        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
        $dur_time2  = (time() - $timestamp2) / 86400;      

        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $dur_time1  = (time() - $timestamp1) / 86400;       

        $dur_day    = $dur_time2 - $dur_time1 + 1 ;  // �ϼ�
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>���庰�α�����</title>
</head>

<body>
   <script>
         //
         // �󿵰�����
         //
         function showroom_click()
         {
             popupaddr = "wrk_filmsupply_Link_DnG1.php?"
                       + "logged_UserId=<?=logged_UserId?>"  ;

             popupoption = "status=0, "
                         + "menubar=0, "
                         + "scrollbars=yes, "
                         + "resizable=yes, "
                         + "width=400, "
                         + "height=500" ;

             window.open(popupaddr,'',popupoption) ;
         }

         //
         //   ���ڸ� �Է� �޵��� �����Ѵ�.
         //
         //
         //

         function score_check()
         {
            edit = write.rate.value ;

            if ((edit !="") && (edit.search(/\D/) != -1))
            {
                alert("���ڸ� �Է½ÿ�!") ;

                write.score.value = "";

                edit = edit.replace(/\D/g, "")

                write.score.focus() ;
                write.score.select();

                return false ;
            }
            else
            {
                return true ;
            }
         }

         //
         //  "���" �� ��������..
         //

         function calc_click()
         {
             if  (write.rate.value == "")
             {
                 alert("������ �����ϴ�.!") ;

                 return false ;
             }
             
             if  (write.showroom.value == "")
             {
                 alert("�󿵰��� ���õ��� �ʾ����ϴ�.!") ;

                 return false ;
             }             

             if   (score_check()==false)  return false ;

             submitaddr = "<?=$PHP_SELF?>?"
                        + "logged_UserId=<?=$logged_UserId?>&"
                        + "FilmTile=<?=$FilmTile?>&"
                        + "FromDate=<?=$FromDate?>&"
                        + "ToDate=<?=$ToDate?>" ;

             write.action = submitaddr ;
             write.submit() ;
         }

    </script>
     
    <center>
    
    <form method=post name=write>

    <input name=showroom   type=hidden value='<?=$showroom?>'>       <!-- �󿵰� -->
    <input name=filmsupply type=hidden value='<?=$filmsupplyCode?>'> <!-- ��޻��ڵ� -->
    <input name=filmtitle  type=hidden value='<?=$filmtitleCode?>'>  <!-- ��ȭ���� -->
    <input name=fromdate   type=hidden value='<?=$FromDate?>'>       <!-- �ð��� -->
    <input name=todate     type=hidden value='<?=$ToDate?>'>         <!-- ������ -->
    
    <table width=90% border=1>    
    
    <tr>
    
    <td align=center colspan=3>��ȭ���ް��� �����꼭</td>

    <td align=center>
    <input type=text   name=rate size=4 maxlength=2 class=input value=<?=$rate?>>%
    <input type=button name=calc value="���" onclick="calc_click()">
    </td>
    
    </tr>

    <tr>
    <td width=25% align=center>��ȭ��</td>
    <td align=center colspan=3>
    <?
    if  ($showroom)
    {
        $qry_showroom = mysql_query("Select * From bas_showroom                    ".
                                    " Where Theather = '".substr($showroom,0,4)."' ".
                                    "   And Room     = '".substr($showroom,4,2)."' ",$connect) ;
        if  ($showroomData = mysql_fetch_array($qry_showroom))
        {
            ?>
            <div id=showroomname>
            <a href="#" onclick="showroom_click()"><?=$showroomData["Discript"]?></a>
            </div>
            <?
        }
    }
    else
    {
        ?>
        <div id=showroomname>
        <a href="#" onclick="showroom_click()">[�󿵰� ����]</a>
        </div>
        <?
    }
    ?>

    </td>
    </tr>
    
    <tr>
    <td width=25% align=center>��޻�</td>
    <?
        if  ($filmsupplyName) 
        {
            echo "<td colspan=3 align=center>".$filmsupplyName."</td>" ; 
        }
        else
        {
            echo "<td colspan=3 align=center>&nbsp;</td>" ;  
        }
    ?>    
    </tr>
    
    <tr>
    <td width=25% align=center>��ȭ��</td>
    <?
        if  ($filmtitleName) 
        {
            echo "<td colspan=3 align=center>".$filmtitleName."</td>" ;  
        }
        else
        {
            echo "<td colspan=3 align=center>&nbsp;</td>" ;  
        }
    ?>
    
    </tr>
    
    <tr>
    <td width=25% align=center>���ޱⰣ</td>
    <td colspan=3>
    <?=substr($FromDate,0,4)?>��<?=substr($FromDate,4,2)?>��<?=substr($FromDate,6,2)?>�� ~ 
    <?=substr($ToDate,0,4)?>��<?=substr($ToDate,4,2)?>��<?=substr($ToDate,6,2)?>�� (<?=$dur_day?>�ϰ�)
    </td>
    </tr>
    <!--
    <tr>
    <td align=center colspan=4>���Աݾ�</td>
    </tr>
    -->
    </table>

    </form>

    </center>
    
    

</body>

        <?
        mysql_close($connect);
    }
    else // �α������� �ʰ� �ٷε��´ٸ�..
    {
        ?>
        
        <!-- �α������� �ʰ� �ٷε��´ٸ� -->
        <body>
            <script language="JavaScript">
                <!-- 
                window.top.location = '../index_cokr.php' ; 
                //-->
            </script>
        </body>      
        
        <?
    }
    ?>
</html>

<?
    session_start();
?>
<html>
<?
    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";
        
        $connect=dbconn();

        mysql_select_db($cont_db) ; 
?>
  <link rel=stylesheet href=./LinkStyle.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">
  
  <head>
       <title>������������</title>
  
       <script>

       function check_submit()
       {
           var stemp = "" ;

           for (var i = 0; i < write.selLocation.length; i++ )
           {
               stemp += write.selLocation(i).text + ","
                      + write.selLocation(i).value + ",." ;
           }

           if  (stemp!="")
           {
               write.SelectedLocation.value = stemp ;
           }
           else
           {
               write.SelectedLocation.value = "����" ;
           }

           return true ;
       }

       function DataMoveleft()
       {
           var idx = write.selLocation.length;

           for (var i = 0; i < write.allLocation.length;  )
           {
               if (write.allLocation.options[i].selected == true)
               {
             			   write.selLocation.length += 1 ;
                   write.selLocation.options(idx).text  = write.allLocation(i).text ;
                   write.selLocation.options(idx).value = write.allLocation(i).value ;

                   for (var j = i; j < write.allLocation.length-1; j++ )
                   {
                       write.allLocation.options(j).selected = write.allLocation(j+1).selected ;
                       write.allLocation.options(j).text     = write.allLocation(j+1).text ;
                       write.allLocation.options(j).value    = write.allLocation(j+1).value ;
                   }
                   write.allLocation.length -= 1 ;

       			   idx += 1;
               }
               else
               {
                   i++ ;
               }
           }
           return true ;
       }

       function DataMoveright()
       {
           var idx = write.allLocation.length;

           for (var i = 0; i < write.selLocation.length;  )
           {
               if (write.selLocation.options[i].selected == true)
               {
       			   write.allLocation.length += 1 ;
                   write.allLocation.options(idx).text  = write.selLocation(i).text ;
                   write.allLocation.options(idx).value = write.selLocation(i).value ;

                   for (var j = i; j < write.selLocation.length-1; j++ )
                   {
                       write.selLocation.options(j).selected = write.selLocation(j+1).selected ;
                       write.selLocation.options(j).text     = write.selLocation(j+1).text ;
                       write.selLocation.options(j).value    = write.selLocation(j+1).value ;
                   }
                   write.selLocation.length -= 1 ;

       			   idx += 1;
               }
               else
               {
                   i++ ;
               }
           }
           return true ;
       }

       </script>
  </head>


  <body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >

<?
    if  ($SelectedLocation) // ���õ� ������ ���� ���
    {
        $sQuery = "Delete From bas_filmsupplyzoneloc   ".
                  " Where Zone = '00'                  " ;
        mysql_query($sQuery,$connect) ; // ���õ� ������ �����.

        $sTemp1 = $SelectedLocation ;

        while (($i = strpos($sTemp1,'.')) > 0)
        {
            $sItem1 = substr($sTemp1,0,$i) ;

            $nCount = 0 ;

            $sTemp2 = $sItem1 ;

            while (($j = strpos($sTemp2 ,',')) > 0)
            {
                $nCount++ ;

                $sItem2 = substr($sTemp2,0,$j) ;

                if  ($nCount==1)  $locationText  = $sItem2 ;
                if  ($nCount==2)  $locationValue = $sItem2 ;

                $sTemp2 = substr($sTemp2,$j+1) ;
            }

            $sQuery = "Insert Into bas_filmsupplyzoneloc ".
                      "Values (  '00',                   ".
                      "          '".$locationValue."',   ".
                      "          '".$locationText."'     ".
                      "       )                          " ;
            mysql_query($sQuery,$connect) ;


            $sTemp1 = substr($sTemp1,$i+1) ;
        }

        //echo "<script>alert('������ �Ϸ�Ǿ����ϴ�.')</script>\n" ;
        echo "<script>location.href='".$BackAddr."'</script>\n" ;
    }
?>


  <center>

    <br><b>*������������*</b><br>


   <form method=post action="wrk_filmsupply_2.php" name=write onsubmit="return check_submit()">

   <input type=hidden name=SelectedLocation value="">

   <table cellpadding=0 cellspacing=0 border=0>
         <tr>
            <td align=center>���õ�����</td>
            <td>

            </td>
            <td>

            </td>
            <td>

            </td align=center>
            <td>���ð�������</td>
         </tr>
         <tr>
            <td align=center>
                  <?
                  $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                            " Where Zone = '00'                   ".
                            " Order By Name                       " ;
                  $query1 = mysql_query($sQuery,$connect) ; // ���õ� ������ ���Ѵ�.
                  ?>
                <select size=15 name=selLocation MULTIPLE>
                  <?
                  while ($filmsupplyzoneloc_data1 = mysql_fetch_array($query1))
                  {
                     echo ("<option value=".$filmsupplyzoneloc_data1["Location"].">".$filmsupplyzoneloc_data1["Name"]."</option>") ;
                  }
                  ?>
                </select>
            </td>
            <td>

            </td>
            <td align=center>
               <input type="button" onClick='DataMoveleft();' value="<<�̵�"><br>
               <input type="button" onClick='DataMoveright();' value="�̵�>>">
            </td>
            <td>

            </td>
            <td align=center>
                  <?
                  $i = 0 ;

                  $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                            " Order By Name                        " ;
                  $query2 = mysql_query($sQuery,$connect) ; // ���õ� ������ ���Ѵ�.
                  
                  $sQuery = "Select * From bas_location " ;

                  while ($filmsupplyzoneloc_data = mysql_fetch_array($query2))
                  {
                      if  ($i==0)
                      {
                          $sQuery = $sQuery . "Where " ;
                      }
                      else
                      {
                          $sQuery = $sQuery . "And " ;
                      }

                      $sQuery = $sQuery . "Code <> '".$filmsupplyzoneloc_data["Location"]."' " ;

                      $i++ ;
                  }

                  $sQuery = $sQuery . "Order By Name" ;

                  $query1 = mysql_query($sQuery,$connect) ; // ��ü������ ���Ѵ�.
                  ?>
                <select size=15 name=allLocation MULTIPLE>
                   <?
                   while ($location_data = mysql_fetch_array($query1))
                   {
                      echo ("<option value=".$location_data["Code"].">".$location_data["Name"]."</option>\n") ;
                   }
                   ?>
                </select>
            </td>
         </tr>
         <tr>
            <td colspan=5>

            </td>
         </tr>
   </table>

   <input type=submit value="Ȯ��">


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
 
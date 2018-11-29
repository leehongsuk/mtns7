<?
    include "config.php";
    session_start();

    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = '../index_com.php'</script>";
    }
    else
    {
        $connect=dbconn();

        mysql_select_db($cont_db) ; // 해당배급사를 구하고
?>

<html>
<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">
<head>

</head>

<body onload="write.Code.focus();" BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

<?
    if  ($ActCode == "Del") // 삭제 ..
    {
        mysql_query("delete from bas_zone where Code = '".$Code."' ",$connect) ;

        mysql_query("delete from bas_filmsupplyzoneloc   ".
                    " where Zone = '".$Code."'           ",$connect) ; // 선택된 지역을 지운다.

        mysql_close($connect);
        echo "<script>location.href='$BackAddr'</script>" ;
    }
    if  ($ActCode == "Edt") // 수정 ..
    {
        $query1 = mysql_query("select * from bas_zone where Code = '".$Code."' ",$connect) ;

        $base_data = mysql_fetch_array($query1) ;
        if ($base_data)
        {
           $zone_Code     = $base_data["Code"] ;
           $zone_Name     = $base_data["Name"] ;
           $zone_Discript = $base_data["Discript"] ;
        }
    }
?>

<script>
      function check_submit()
      { 
           if  (!write.Code.value)
           {
               alert("코드를 입력하여 주십시요");
               write.Code.focus();
               return false;
           }
           if  (!write.Name.value)
           {
               alert("이름을 입력하여 주십시요");
               write.Name.focus();
               return false;
           }
                   
           <?
           if  ($ActCode == "New") // 신규 ..
           {
           ?>
                write.ActSource.value =  "insert" ;
           <?
           }
           if  ($ActCode == "Edt") // 수정 ..
           {
           ?>
                write.ActSource.value =  "update" ;
           <?
           }
           ?>


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
               write.SelectedLocation.value = "없음" ;
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
        
  <? echo "<b>".$logged_Name . "</b>님을 환영합니다!" ; ?>
  <a OnClick="location.href='index.php?actcode=logout'"><b>[LogOut]</b></a>
  <a OnClick="location.href='<?=$BackAddr?>'"><b>[X]</b></a>

        
<form name=write method=post action="wrk_filmsupply_3.php?Action=Execute" onsubmit="return check_submit();">

  <input type=hidden name=SelectedLocation>  
  <input type=hidden name=ActSource>

  <center>

   <table cellpadding=0 cellspacing=0 width="329" border=0>
     <tr height="25">     
            <td align=right width="104"><b>코드</b></td>
            <td align=left  width="195">
            <?
            if  ($ActCode == "Edt") // 수정 ..
            {
            ?>            
            <input type=text name=Code value="<?=$zone_Code?>" size=5 maxlength=2 class=input readonly>
            <?
            }
            if  ($ActCode == "New") // 신규 ..
            {
            ?>
            <input type=text name=Code value="" size=5 maxlength=2 class=input> 
            <?
            }
            ?>
            </td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>이름</b></td>
            <td align=left  width="195"><input type=text name=Name value="<?=$zone_Name?>" size=10 maxlength=10 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>설명</b></td>
            <td align=left  width="195"><input type=text name=Discript value="<?=$zone_Discript?>" size=15 maxlength=30 class=input></td>
     </tr>
   </table>

   <table cellpadding=0 cellspacing=0 border=0>
         <tr>
            <td align=center>선택된지역</td>
            <td>

            </td>
            <td>

            </td>
            <td>

            </td align=center>
            <td>선택가능지역</td>
         </tr>
         <tr>
            <td align=center>
                  <?
                  $query1 = mysql_query("select * from bas_filmsupplyzoneloc  ".
                                        " where Zone = '".$Code."'            ".
                                        " order by Name                       ",$connect) ; // 선택된 지역을 구한다.
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
               <input type="button" onClick='DataMoveleft();' value="<<이동"><br>
               <input type="button" onClick='DataMoveright();' value="이동>>">
            </td>
            <td>

            </td>
            <td align=center>
                  <?
                  $sQuery = "select * from bas_location " ;

                  $query2 = mysql_query("select * from bas_filmsupplyzoneloc  ".
                                        "order by Name                        ",$connect) ; // 선택된 지역을 구한다.

                  $i = 0 ;

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

                  $sQuery = $sQuery . "order by Name" ;

                  $query1 = mysql_query($sQuery,$connect) ; // 전체지역을 구한다.
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
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center> </td>
         </tr>
         <tr>
            <td colspan=5 align=center>
                <input type=submit value="확인">
                <input type=button value="취소" OnClick="location.href='<?=$BackAddr?>'">
            </td>
         </tr>
   </table>
  
  </center>

</form>

</body>

</html>

<?
    mysql_close($connect);

    }
?>

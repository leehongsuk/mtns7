<?
    //include "config.php";
    session_start();
?>

<html>
<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">
<head>

</head>

<body onload="write.UserID.focus();" BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

<script>
      
      // 길이검사 자바스크립트.. ( 한글포함 )
      function strlen(string)
      {
          char_cnt = 0;

          for(var i = 0; i < string.length; i++)     
          {
              var chr = string.substr(i,1); 
              chr = escape(chr); 
              key_eg = chr.charAt(1);  // key_eg 가 u 이면 한글 , 공백이면 영문 , 숫자면 특수문자              
              
              switch (key_eg) 
              {
                   case "u":
                        key_num = chr.substr(2,(chr.length-1));
                        if((key_num < "AC00") || (key_num > "D7A3")) 
                        {
                            alert("잘못된 입력입니다");
                            return false;
                        } 
                        else 
                        {
                            char_cnt = char_cnt + 2;
                        }        
                        break;

                   case "B":
                        char_cnt = char_cnt + 2;
                        break;    

                   case "A":
                        alert("잘못된 입력입니다");
                        return false;    
                        break;

                   default:
                        char_cnt = char_cnt + 1;    
              }
          }
                
          return char_cnt;
      }


      function check_submit()
      {
         if  (!write.UserID.value)
         {
             alert("ID를 입력하여 주십시요");
             write.UserID.focus();
             return false;
         }
         else
         {
             if  (write.UserID.value.length<4)
             {
                 alert("ID는 반드시 4자이상이어야 합니다.");
                 write.UserID.focus();
                 return false;
             }
         }

         if  (!write.UserPW1.value)
         {
             alert("Password를 입력하여 주십시요");
             write.UserPW1.focus();
             return false;
         }
         else
         {
             if  (write.UserPW1.value.length<4)
             {
                 alert("암호는 반드시 4자이상이어야 합니다.");
                 write.UserID.focus();
                 return false;
             }
             if  (write.UserPW1.value!=write.UserPW2.value)
             {
                 alert("암호와 암호화인은 같아야합니다.");
                 write.UserPW1.focus();
                 return false;
             }         
         }

         if  (!write.UserName.value)
         {
             alert("이름을 입력하여 주십시요");
             write.UserID.focus();
             return false;
         }
         else
         {              
             
             if  (strlen(write.UserName.value)<2)
             {
                 alert("이름은 반드시 한글로 2자 이상이어야 합니다.");
                 write.UserID.focus();
                 return false;
             }
         }
         
         if  (!write.Jumin.value)
         {
             alert("주민등록번호를 입력하여 주십시요");
             write.UserID.focus();
             return false;
         }
         else
         {
             if  (write.Jumin.value.length!=14)
             {
                  alert("주민등록번호는 반드시 14자리이어야 합니다.");
                  write.UserID.focus();
                  return false;
             }
             if  (write.Jumin.value.substr(6,1)!='-')
             {
                  alert("주민등록번호는 반드시 가운데 '-' 이 있어야 합니다.");
                  write.UserID.focus();
                  return false;
             }
         }
         return true ;
      }

      function member_secession()
      {
           answer = confirm("정말로 탈퇴하시겠읍니까?") ;
           if  (answer==true)
           {
               location.href = "member_update.php?UserID="+write.UserID.value+"&actcode=secession" ;
           }
      }
</script>

<form name=write method=post action="member_update.php?actcode=update" onsubmit="return check_submit();">

  <input type="hidden" name="SeqNo" value="<?=$logged_SeqNo?>">

  <center>
   <table cellpadding=0 cellspacing=0 width="329" border=0>
     <tr height="25">
            <td align=right width="104"><b>사용자 ID </b></td>
            <td  align=left width="195"><input type=text name=UserID value="<?=$logged_UserId?>" size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>암호</b></td>
            <td align=left width="195"><input name=UserPW1 type=password value="<?=$logged_UserPw?>" size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>암호확인</b></td>
            <td align=left width="195"><input name=UserPW2 type=password value="<?=$logged_UserPw?>" size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>이름</b></td>
            <td align=left width="195"><input name=UserName type=text value="<?=$logged_Name?>" size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>전자메일</b></td>
            <td align=left width="195"><input name=eMail type=text value="<?=$logged_eMail?>" size=15 maxlength=255 class=input></td>
     </tr>
     <!--
     <tr height="25">
            <td align=right width="104"><b>홈페이지</b></td>
            <td align=left width="195"><input name=Homepage type=text value="<?=$logged_Homepage?>" size=20 maxlength=255 class=input></td>
     </tr>
     -->
     <tr height="25">
            <td align=right width="104"><b>주민등록번호</b></td>
            <input type=hidden name=beforJumin value="<?=$logged_Jumin?>">
            <td align=left width="195"><input name=Jumin type=text value="<?=$logged_Jumin?>" size=14 maxlength=14 class=input>(-)포함</td>
     </tr>
     <!--
     <tr height="25">
            <td align=right width="104"<b>비고</b></td></td>
            <td align=left width="195">
                <textarea name=Discript rows="6" cols="23" class=input><?=$logged_Discript?>
                </textarea>
            </td>
     </tr>
     -->
     <tr height="25">
            <td align=center colspan=2 width="306">
                <input type=submit value="확인">
                <input type=button  value="취소" OnClick="location.href='<?=$BackAddr?>'">
                <input type=button  value="탈퇴" OnClick="member_secession();">
            </td>
     </tr>
</table>
</center>
</form>

</body>

</html>

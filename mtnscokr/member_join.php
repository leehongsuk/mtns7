
<html>
<link rel=stylesheet href=./style.css type=text/css>
<head>

</head>

<body onload="write.UserID.focus();" BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

<script>
      // 한글을 포함한 문자열에서 문자열의 길이를 반환한다.
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
</script>

<form name=write method=post action="member_update.php?actcode=insert" onsubmit="return check_submit();">

  <input type="hidden" name="SeqNo" value="<?=$logged_SeqNo?>">

  <center>
   <table cellpadding=0 cellspacing=0 width="329" border=0>
     <tr height="25">
            <td align=right width="104"><b>사용자 ID </b></td>
            <td  align=left width="195"><input type=text name=UserID value='' size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>암호</b></td>
            <td align=left width="195"><input type=password name=UserPW1 size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>암호확인</b></td>
            <td align=left width="195"><input type=password name=UserPW2 size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>이름</b></td>
            <td align=left width="195"><input type=text name=UserName size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>전자메일</b></td>
            <td align=left width="195"><input type=text name=eMail size=15 maxlength=255 class=input></td>
     </tr>
     <!--
     <tr height="25">
            <td align=right width="104"><b>홈페이지</b></td>
            <td align=left width="195"><input type=text name=Homepage size=15 maxlength=255 class=input></td>
     </tr>
     -->
     <tr height="25">
            <td align=right width="104"><b>주민등록번호</b></td>
            <td align=left width="195"><input type=text name=Jumin size=14 maxlength=14 class=input>(-)포함</td>
     </tr>
     <!--
     <tr height="25">
            <td align=right width="104"><b>비고</b></td>
            <td align=left width="195">
                <textarea name=Discript rows="6" cols="23" class=textarea></textarea>
            </td>
     </tr>

     -->
     <tr height="25">
            <td align=center colspan=2>
                <br>
                <input type=submit value="확인">
                <input type=button  value="취소" OnClick="location.href='<?=$BackAddr?>'">
            </td>
     </tr>
   </table>
   </center>
<form>

</body>

</html>

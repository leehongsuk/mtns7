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
      
      // ���̰˻� �ڹٽ�ũ��Ʈ.. ( �ѱ����� )
      function strlen(string)
      {
          char_cnt = 0;

          for(var i = 0; i < string.length; i++)     
          {
              var chr = string.substr(i,1); 
              chr = escape(chr); 
              key_eg = chr.charAt(1);  // key_eg �� u �̸� �ѱ� , �����̸� ���� , ���ڸ� Ư������              
              
              switch (key_eg) 
              {
                   case "u":
                        key_num = chr.substr(2,(chr.length-1));
                        if((key_num < "AC00") || (key_num > "D7A3")) 
                        {
                            alert("�߸��� �Է��Դϴ�");
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
                        alert("�߸��� �Է��Դϴ�");
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
             alert("ID�� �Է��Ͽ� �ֽʽÿ�");
             write.UserID.focus();
             return false;
         }
         else
         {
             if  (write.UserID.value.length<4)
             {
                 alert("ID�� �ݵ�� 4���̻��̾�� �մϴ�.");
                 write.UserID.focus();
                 return false;
             }
         }

         if  (!write.UserPW1.value)
         {
             alert("Password�� �Է��Ͽ� �ֽʽÿ�");
             write.UserPW1.focus();
             return false;
         }
         else
         {
             if  (write.UserPW1.value.length<4)
             {
                 alert("��ȣ�� �ݵ�� 4���̻��̾�� �մϴ�.");
                 write.UserID.focus();
                 return false;
             }
             if  (write.UserPW1.value!=write.UserPW2.value)
             {
                 alert("��ȣ�� ��ȣȭ���� ���ƾ��մϴ�.");
                 write.UserPW1.focus();
                 return false;
             }         
         }

         if  (!write.UserName.value)
         {
             alert("�̸��� �Է��Ͽ� �ֽʽÿ�");
             write.UserID.focus();
             return false;
         }
         else
         {              
             
             if  (strlen(write.UserName.value)<2)
             {
                 alert("�̸��� �ݵ�� �ѱ۷� 2�� �̻��̾�� �մϴ�.");
                 write.UserID.focus();
                 return false;
             }
         }
         
         if  (!write.Jumin.value)
         {
             alert("�ֹε�Ϲ�ȣ�� �Է��Ͽ� �ֽʽÿ�");
             write.UserID.focus();
             return false;
         }
         else
         {
             if  (write.Jumin.value.length!=14)
             {
                  alert("�ֹε�Ϲ�ȣ�� �ݵ�� 14�ڸ��̾�� �մϴ�.");
                  write.UserID.focus();
                  return false;
             }
             if  (write.Jumin.value.substr(6,1)!='-')
             {
                  alert("�ֹε�Ϲ�ȣ�� �ݵ�� ��� '-' �� �־�� �մϴ�.");
                  write.UserID.focus();
                  return false;
             }
         }
         return true ;
      }

      function member_secession()
      {
           answer = confirm("������ Ż���Ͻð����ϱ�?") ;
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
            <td align=right width="104"><b>����� ID </b></td>
            <td  align=left width="195"><input type=text name=UserID value="<?=$logged_UserId?>" size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>��ȣ</b></td>
            <td align=left width="195"><input name=UserPW1 type=password value="<?=$logged_UserPw?>" size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>��ȣȮ��</b></td>
            <td align=left width="195"><input name=UserPW2 type=password value="<?=$logged_UserPw?>" size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>�̸�</b></td>
            <td align=left width="195"><input name=UserName type=text value="<?=$logged_Name?>" size=15 maxlength=20 class=input></td>
     </tr>
     <tr height="25">
            <td align=right width="104"><b>���ڸ���</b></td>
            <td align=left width="195"><input name=eMail type=text value="<?=$logged_eMail?>" size=15 maxlength=255 class=input></td>
     </tr>
     <!--
     <tr height="25">
            <td align=right width="104"><b>Ȩ������</b></td>
            <td align=left width="195"><input name=Homepage type=text value="<?=$logged_Homepage?>" size=20 maxlength=255 class=input></td>
     </tr>
     -->
     <tr height="25">
            <td align=right width="104"><b>�ֹε�Ϲ�ȣ</b></td>
            <input type=hidden name=beforJumin value="<?=$logged_Jumin?>">
            <td align=left width="195"><input name=Jumin type=text value="<?=$logged_Jumin?>" size=14 maxlength=14 class=input>(-)����</td>
     </tr>
     <!--
     <tr height="25">
            <td align=right width="104"<b>���</b></td></td>
            <td align=left width="195">
                <textarea name=Discript rows="6" cols="23" class=input><?=$logged_Discript?>
                </textarea>
            </td>
     </tr>
     -->
     <tr height="25">
            <td align=center colspan=2 width="306">
                <input type=submit value="Ȯ��">
                <input type=button  value="���" OnClick="location.href='<?=$BackAddr?>'">
                <input type=button  value="Ż��" OnClick="member_secession();">
            </td>
     </tr>
</table>
</center>
</form>

</body>

</html>

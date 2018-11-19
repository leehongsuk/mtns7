<?
include ("mail_lib.php"); 
?>

<html>
<body bgcolor="#FFFFFF" leftmargin=5 topmargin=20 marginwidth=5 marginheight=20>

<form name=frm method=post>
<?
  $box = $BOX;

  if($box == "") $box = "INBOX"; 

  switch($box) 
  {
    case "INBOX":
      $box_name="받은 편지함";
    break;
    case "sent":
      $box_name="보낸 편지함";
    break;
  }

  include ("mail_config.php"); 

  $mailstream = imap_open($C_DOMAIN, $login, $pass);

  if ($mailstream == 0) {
       echo "Error!";
       exit;
  }
?>

<input type=hidden name=BOX value ="<?echo $box;?>">



<table width="810" border="0" cellpadding="2" cellspacing="1">
<tr bgcolor="#E8E8E8"> 
  <td class="tk1" align="right"  width="8%" >선  택</td>
  <td class="tk4" align="center" width="20%">받는이</td>
  <td class="tk4" align="center" width="52%">제  목</td>
  <td class="tk4" align="center" width="20%">날  짜</td>
</tr>
<?

/*
위에서 imap_num_recent 라는 함수와 imap_num_msg라는 함수는 
새로운 편지와 총 메일의 수를 리턴해 주는 함수입니다.. 
인자값으로는 메일스트림을 저정해 주면 되고요..
*/


$mailno = imap_sort($mailstream, SORTDATE, 1);

echo(count($mailno)) ;
/*
연결된 메일박스에 있는 메일의 갯수와 함께.. 메일을 날짜 순으로 Desending 하는 부분입니다. 
그리고 $mailno 에는 각 메일의 메일번호가 소트한 결과에 따라 차례로 배열로 저장됩니다. 
이 번호를 가지고 루프를 돌리는 거죠... 아래 있습니다.
*/


if(count($mailno) == 0) {
?>
<tr bordercolor="#383838" height=35>
<td colspan=4 align=center class=tk1>편지함이 비어 있습니다.</td>
</tr>
<?
}

// 메일이 없을 경우 위와 같고요... 메일이 있는 경우 아래를 실행하겠죠.

for ($i=0;$i<count($mailno);$i++) { // 메일의 갯수만큼루프를 돕니다.
  $no = $mailno[$i]; // 메일번호를 얻구요..
  $head = imap_header($mailstream,$no); 

  // 얻어진 메일번호로 해당 메일의 헤더를 읽습니다.

  $recent = $head->Recent; // 새메일 여부를 리턴해 줍니다.
  $unseen = $head->Unseen; // 메일을 읽었는지 여부를 리턴해 주죠..
  $msgno = trim($head->Msgno); // 메일번호

  $date = date("Y/m/d H:i", $head->udate); // 메일의 날짜를 얻고
  $subject = $head->Subject; // 제목을 얻습니다. 

  $subject = Decode($subject); 

  // 제목의 경우 OUT LOOK에서 보내면 인코딩을 자동으로 하기에 이를 디코딩해야 합니다.
  // 그 부분을 처리해 주는 것으로 제가 만들었죠.. 그 내용은 맨 마지막에 있으니 참조하세요.


  $from_obj = $head->from[0]; // 보낸 사람을 얻는 부분입니다. 그냥 아래처럼 사용하세요.
  $from_name = $from_obj->personal;
  $from_addr = substr($from_obj->mailbox . "@" . strtolower($from_obj->host), 0, 30);
  if($from_name == "") $from_name = $from_addr;
  $from_name = Decode($from_name);

  if(strlen($from_name) > 13) $from_name = substr($from_name, 0, 10) . "..."; 

?>

  <tr>
  <td align=right><?=$unseen?><input type=checkbox name=NO[] value=<?=$msgno?>></td>
  <td><?echo "<a href=mailto:$from_addr>$from_name</a>";?></td>
  <td><a href="mail_detail.php?BOX=<?=$box?>&MSG_NO=<?=$no?>&Subject='<?=$subject?>'"><?=$subject?></a></td>
  <td><?echo $date;?></td>
  </tr>

<?
}






imap_close($mailstream);
?>
</table>
<SCRIPT LANGUAGE="JavaScript">
<!--
var selectVal = true;

function setSelected(button) 
{
  for(var i=0;i<document.frm.length;i++) if(document.frm[i].name == 'NO[]') document.frm[i].checked = selectVal;
  
  selectVal = selectVal ? false: true;
  if (selectVal) 
  {
    button.value = '전체선택';
  } 
  else 
  {
   button.value = '전체해제';
  }
  return false;
}

function Delete(){
  var count = 0;
  for(var i=0;i<document.frm.length;i++){
    if(document.frm[i].name == "NO[]" && document.frm[i].checked == true){ count++; }
  }
  if ( count != 0 ){
    document.frm.action = "mail_cmd.php?CMD=del";
    document.frm.submit();
  } else { alert('삭제할 항목을 선택하세요!'); }
}
//-->
</SCRIPT>

<table width=810 border="0" bgcolor="#E8E8E8" cellspacing=0 cellpadding=3>
<tr> 
  <td class="tk3"><input type="button" name="Sub2" value="전체선택" onClick="setSelected(this);"></td>
  <td align="right">
   <input type=button name=HOWTO22 value="삭 제" class="tk1" onClick="Delete();">
  </td> </tr>
</table>

<br>
</form>
</body>
</html>

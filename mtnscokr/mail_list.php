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
      $box_name="���� ������";
    break;
    case "sent":
      $box_name="���� ������";
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
  <td class="tk1" align="right"  width="8%" >��  ��</td>
  <td class="tk4" align="center" width="20%">�޴���</td>
  <td class="tk4" align="center" width="52%">��  ��</td>
  <td class="tk4" align="center" width="20%">��  ¥</td>
</tr>
<?

/*
������ imap_num_recent ��� �Լ��� imap_num_msg��� �Լ��� 
���ο� ������ �� ������ ���� ������ �ִ� �Լ��Դϴ�.. 
���ڰ����δ� ���Ͻ�Ʈ���� ������ �ָ� �ǰ��..
*/


$mailno = imap_sort($mailstream, SORTDATE, 1);

echo(count($mailno)) ;
/*
����� ���Ϲڽ��� �ִ� ������ ������ �Բ�.. ������ ��¥ ������ Desending �ϴ� �κ��Դϴ�. 
�׸��� $mailno ���� �� ������ ���Ϲ�ȣ�� ��Ʈ�� ����� ���� ���ʷ� �迭�� ����˴ϴ�. 
�� ��ȣ�� ������ ������ ������ ����... �Ʒ� �ֽ��ϴ�.
*/


if(count($mailno) == 0) {
?>
<tr bordercolor="#383838" height=35>
<td colspan=4 align=center class=tk1>�������� ��� �ֽ��ϴ�.</td>
</tr>
<?
}

// ������ ���� ��� ���� �����... ������ �ִ� ��� �Ʒ��� �����ϰ���.

for ($i=0;$i<count($mailno);$i++) { // ������ ������ŭ������ ���ϴ�.
  $no = $mailno[$i]; // ���Ϲ�ȣ�� �򱸿�..
  $head = imap_header($mailstream,$no); 

  // ����� ���Ϲ�ȣ�� �ش� ������ ����� �н��ϴ�.

  $recent = $head->Recent; // ������ ���θ� ������ �ݴϴ�.
  $unseen = $head->Unseen; // ������ �о����� ���θ� ������ ����..
  $msgno = trim($head->Msgno); // ���Ϲ�ȣ

  $date = date("Y/m/d H:i", $head->udate); // ������ ��¥�� ���
  $subject = $head->Subject; // ������ ����ϴ�. 

  $subject = Decode($subject); 

  // ������ ��� OUT LOOK���� ������ ���ڵ��� �ڵ����� �ϱ⿡ �̸� ���ڵ��ؾ� �մϴ�.
  // �� �κ��� ó���� �ִ� ������ ���� �������.. �� ������ �� �������� ������ �����ϼ���.


  $from_obj = $head->from[0]; // ���� ����� ��� �κ��Դϴ�. �׳� �Ʒ�ó�� ����ϼ���.
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
    button.value = '��ü����';
  } 
  else 
  {
   button.value = '��ü����';
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
  } else { alert('������ �׸��� �����ϼ���!'); }
}
//-->
</SCRIPT>

<table width=810 border="0" bgcolor="#E8E8E8" cellspacing=0 cellpadding=3>
<tr> 
  <td class="tk3"><input type="button" name="Sub2" value="��ü����" onClick="setSelected(this);"></td>
  <td align="right">
   <input type=button name=HOWTO22 value="�� ��" class="tk1" onClick="Delete();">
  </td> </tr>
</table>

<br>
</form>
</body>
</html>

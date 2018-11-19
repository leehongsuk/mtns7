<?

// �ʿ��� ���� �Լ��Դϴ�.. �Ʒ� ������ �ҽ��� ���ð� �� �Լ� ������ �������� ������.
// ������ ������ �м��ϴ� �Լ�
function checkstruct($mailstream, $subject, $MSG_NO) 
{
     // ���� ���� �м�
     // ������ ������ ��ü�� ������ �ݴϴ�. ���ڴ� ���Ͻ�Ʈ���� �ش� ���Ϲ�ȣ�Դϴ�.
     $struct = imap_fetchstructure($mailstream, $MSG_NO);
     

     $type = $struct->subtype; 

     /* ������ Ÿ���� ����ϴ�. ũ�� PLAIN, MIXED, ALTERNATIVE, RELATED �̷��� ����
     �ϴ�.. ( �� �ִ����� ����... ^ ^)
     PLAIN : �׳� �ؽ�Ʈ ��������.. �̰� �׳� ��¸� ���ָ� �˴ϴ�.. 
     MIXED : ÷�������� �ִ� �����̶� �ǵ�... �̰� ���� ��������.. �Ʒ� switch����
             switch ������ ��������.
     ALTERNATIVE : HTML �������� ������ ������ �̰� �˴ϴ�.
     RELATED : HTML �������� ������ ���� ���Ͼȿ� �̹����� �����ؼ� ���� �� �ֽ��ϴ�.
               �� ��쿡 �ش�˴ϴ�.

     �Ʒ����� �ڼ��� ����..*/

     switch($type) 
     {
         case "PLAIN": // �Ϲ��ؽ�Ʈ ����
              echo imap_fetchbody($mailstream, $MSG_NO, "1") ;
              //echo str_replace("\n", "<br>", imap_fetchbody($mailstream, $MSG_NO, "1"));
              // �׳� ������ �� ����� �ָ� �˴ϴ�. 
              // ������ ��� ����� ��ó�� imap_fetchbody �Լ��� ����ϴµ�.. ���⼭ ����°
              // ���ڰ� "1"  �̷��� �Ǿ� ����. �̰��� ������ body�� boundary��� ������ ����
              // �Ǿ� �������� �� �� �ִµ� �� �� ù��° ���̶� �̴ϴ�.. �翬 �ؽ�Ʈ ������
              // body�� �ϳ��ۿ� ���⿡ �׳� ������ ��ó�� �ϸ� �˴ϴ�.
               
              break;

         case "MIXED": // ÷������ �ִ� ����
              /*
              �̰� �� ��������.  ���� ������ ���ߵ� ÷�������� �ִ� ���� body�� �������Դϴ�.
              �� ÷�������� �ΰ��� text ������ ��� body�� ������ ������..
              �� ������ body�� ���� ǥ���ϴ� ���� �ǰڽ��ϴ�.
              */

              for ($i=0; $i<count($struct->parts); $i++) 
              { 
                  // parts��� �Ӽ����� boundary�� ���е� body�� ��ü���� ��� ���� �˴ϴ�.
                  // �̰��� ������ �˾Ƽ� ������ ������ ����.

                  $part      = $struct->parts[$i];
                  $param     = $part->dparameters[0];
                  $file_name = Decode($param->value);                   
                  $mime      = $part->subtype; // MIME Ÿ�� Ȥ�� ������ ������ ���ϵ˴ϴ�.
                  $encode    = $part->encoding; // encoding

                  /*�Ʒ� �κ��� ���� $mime �̶� ������ ALTERNATIVE ��� ���� �ü� �ְ� �Ǿ�
                  �ֽ��ϴ�.. �� OUTLOOK���� HTML �������� ÷�������� ������ �뷫
                  -�޼��� 
                  -÷������1
                  -÷������2
                  -÷������3
                  �̷��� ������ �ٽ� �޼����� 
                  ---PLAN
                  ---HTML
                  �̷��� ������ �˴ϴ�.. �̰�� �޼����� �ش��ϴ� �κ��� ALTERNATIVE�� ����..*/

                  if  ($mime == "ALTERNATIVE") 
                  {
                      $val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1));
                      // �ش� part�� ��ȣ�� body���� �� �κи� ���ɴϴ�. �׸��� �̰��� 
                      // ȭ�鿡 �������.. �Ʒ� �Լ���.. �̰��� ���� ���� �ǵ�.. ���߿� ��������.
                      printOutLook($val);
                  } 
                  else 
                  {
                       printbody($mailstream, $subject, $MSG_NO, $i, $encode, $mime, $file_name);
                       // ÷�������� ��� printbody�Լ��� ȣ���մϴ�.. �̰� �ٷ� �ؿ� �ִ� �Լ���
                       // �� �ű⼭ ��������..
                  }
              }
              break;

         case "ALTERNATIVE": // outlook html
              for ($i=0; $i<count($struct->parts); $i++) 
              {
                  $part      = $struct->parts[$i];
                  $param     = $part->parameters[0];
                  $file_name = Decode($param->value); // ÷�������� ��� ���ϸ�
                  $mime      = $part->subtype;
                  $encode    = $part->encoding;

                  if($mime == "HTML") 
                  {
                    printbody($mailstream, $subject, $MSG_NO, $i, $encode, $mime, $file_name);
                  }
              }
              break;

         case "RELATED": // outlook ������ �̹��� ����
              for($i=0; $i<count($struct->parts); $i++) 
              {
                  $part      = $struct->parts[$i];
                  $param     = $part->parameters[0];
                  $file_name = Decode($param->value); // ÷�������� ��� ���ϸ�
                  $mime      = $part->subtype; // MIME Ÿ��
                  $encode    = $part->encoding; // encoding
                  
                  if($mime == "ALTERNATIVE") 
                  {
                    $val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1));
                    printOutLook($val);
                  } 
                  else 
                  {
                    printbody($mailstream, $subject, $MSG_NO, $i, $encode, $mime, $file_name);
                  }
              }
              break;
     }
}

// ���� ������ ����ϴ� �Լ�
function printbody($mailstream, $subject, $MSG_NO, $numpart, $encode, $mime, $file_name) 
{
     $val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1));

     // ���� �ش� part�� ������ �޾� �ɴϴ�.
     // �׸��� ���ڰ����� �Ѿ�� $encode �� ���� ���� ������ decoding ���ݴϴ�.
     switch($encode) 
     {
         case 0: // 7bit
         case 1: // 8bit
           $val = imap_base64(imap_binary(imap_qprint(imap_8bit($val))));
           break;
         case 2: // binary
           $val = imap_base64(imap_binary($val));
           break;
         case 3: // base64
           $val = imap_base64($val);
           break;
         case 4: // quoted-print
           $val = imap_base64(imap_binary(imap_qprint($val)));
           break;
         case 5: // other
           echo "�˼����� Encoding ���.";
           exit;
     }

     // mime type �� ���� ����մϴ�.
     switch($mime) 
     {
         case "PLAIN":
           echo str_replace("\n", "<br>", $val);
           break;
         case "HTML":
           echo $val;
           break;
         default:
           // ÷�������� ����̹Ƿ� �ٿ�ε� �� �� �ְ� ��ũ�� �ɾ� �ݴϴ�.
           echo "<br>÷��: <a href=\"mail_down.php?MSG_NO=" . $MSG_NO . "&PART_NO=" . $numpart . "\" >[" . $file_name . "]</a>";
           ?>
           <IMG SRC="mail_down.php?MSG_NO=<?=$MSG_NO?>&PART_NO=<?=$numpart?>&Subject=<?=$subject?>">
           <?
           
           break;
     }  
}

// ----------------------
// ���� ����
// include ����
// ----------------------
include ("mail_lib.php"); // ���̺귯�� ���Ͽ� ������ ���¿� �ִ� Decode��� �Լ��� ����.

?>


<html>
  <body>
  <?
  $box = $BOX;  // ���� �ڽ���

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
  ?>
  <body bgcolor="#FFFFFF" leftmargin=5 topmargin=20 marginwidth=5 marginheight=20>
  <?
  
  ?>
  <table width="810" border=0 bgcolor=#527900 cellpadding=4 cellspacing=0>
   <tr>
    <td align=center width="25" bgcolor="#334600">
     <font face="Wingdings" size="4" color="#FFCC33">.</font></td>
    <td width=95%>
     <font size="3" color="#FFFFFF"><b>�����б�</b></font> 
    </td>
   </tr>
  </table>
  <table width=810 border=0 cellpadding=4 cellspacing=0 bgcolor=#D8D8D8>
   <tr> 
    <td class="tk1" width=40%></td>
    <td align="right" class="tk1" width=60%>
      <a href="mail_cmd.php?CMD=del&NO[]=<?echo $MSG_NO;?>">����</a>&nbsp;[<a href="mail_list.php?BOX=<?echo $box;?>">���</a>]
    </td>
   </tr>
  </table>

  <?
  include ("mail_config.php"); 

  $mailstream = imap_open($C_DOMAIN, $login, $pass);

  if ($mailstream == 0) 
  {
    echo "Error!" ;
    exit;
  }

  
  // ���� ��� �м�
  $head = imap_header($mailstream,$MSG_NO);
  $head->Unseen = "U"; // �� ����� ���� �ʴ´�..

  $date = date("Y�� m�� d�� H�� i��", $head->udate);
  $subject = $head->Subject;
  $subject = Decode($subject);
  $from_obj = $head->from[0];
  $from_name = $from_obj->personal;
  $from_addr = substr($from_obj->mailbox . "@" . strtolower($from_obj->host), 0, 30);

  if($from_name == "") $from_name = $from_addr;
  $from_name = Decode($from_name);

  // ��������� ������ ���¿��� �� �Ͱ� ��������.. ���� ���ϰڽ��ϴ�.
  ?>

  <table width=810 border=0 cellpadding=2 cellspacing=0 bordercolor=#E8E8E8>
   <tr> 
    <td class="tk1" align="center" bgcolor="#EEEEEE" width="100">������¥</td>
    <td class="tk1" bgcolor="#F7F7F7"><?echo $date;?></td>
   </tr>
   <tr> 
    <td class="tk1" align="center" bgcolor="#EEEEEE">������</td>
    <td class="tk1" bgcolor="#F7F7F7"><?echo "<a href=mailto:$from_addr>$from_name</a>";?></td>
   </tr>
   <tr> 
    <td class="tk1" align="center" bgcolor="#EEEEEE">�� &nbsp; ��</td>
    <td class="tk1" bgcolor="#F7F7F7"><?echo $subject;?></td>
   </tr>
  </table>

  <table width="810" border="0" cellpadding="4">
   <tr>
    <td class="tk1">
    <?
    checkstruct($mailstream, $subject, $MSG_NO);
    

    //���� �ΰ��� �Լ��� �̹� ������ ���̶���Ʈ��... �ϳ� �ϳ� �����մϴ�. ������ ���� �ش� �Լ� �����
    //���� ������.
    ?>
    </td>
   </tr>
  </table>

  <table width=810 border=0 cellpadding=4 cellspacing=0 bgcolor=#D8D8D8>
   <tr> 
    <td class="tk1" width=40%></td>
    <td align="right" class="tk1" width=60%>
      <a href="mail_cmd.php?BOX=<?echo $box;?>&CMD=del&NO[]=<?echo $MSG_NO;?>">����</a>&nbsp;[<a href="mail_list.php?BOX=<?echo $box;?>">���</a>]
    </td>
   </tr>
  </table>
  <?
  imap_close($mailstream);
  ?>

  <br>
  </body>
</html>

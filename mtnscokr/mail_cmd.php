<?
include ("mail_lib.php");

$cmd = $CMD;
$box = $BOX;
$part_no = $PART_NO;
if($box == "") $box = "INBOX";

include ("mail_config.php"); 

$mailstream = imap_open($C_DOMAIN, $login, $pass);

if  ($mailstream == 0) 
{
    echo "Error!";
    exit;
}

switch($cmd) 

       case "del":
                  for ($i=0;$i<count($NO);$i++) 
                  {
                      $result = imap_delete($mailstream, $NO[$i]);
                      // �ش� ��ȣ�� ���Ͽ� ���� ǥ�ø� �մϴ�. �� �� �Լ��� ����
                      // ������Ű�� �Լ��� �ƴ϶�� ����..

                      if  (!$result) 
                      {
                          echo "��������";
                          imap_close($mailstream);
                          exit;
                      }
                      imap_expunge($mailstream);
                      // ������ ���� ǥ�ø� �� ������ �����ϴ� ����� �����մϴ�.
                      // �� �Լ��� ȣ�� ���� �ʰ� ��ƾ�� ������ �������� �ʽ��ϴ�.
                  }
                  break;
}

imap_close($mailstream);

RedirectTarget("mail_list.php?BOX=".$box, "");
?>

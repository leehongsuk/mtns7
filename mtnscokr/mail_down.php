<?
// ������ mail_detail.php�� �ִ� printbody �Լ��� ���� �����ϴ�.. 
// �ٸ� �κи� ��������..

function printbody($mailstream, $subject, $MSG_NO, $numpart, $encode, $mime, $file_name) 
{
    $val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1), FT_UID);

    switch($encode) 
    {
        case 0: // 7bit
            break;
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

    
    
    
    // �̺κ��� �ٸ���..
    // ���� �̺κп��� ÷�������ϰ�� �ܼ� ��ũ�� ���� ���Ұ�..
    // ���⼭�� �ش� �κ��� �״�� ����մϴ�. �׷��� �Ǹ� ����� ������
    // �ٿ�ε尡 ����Ǵ� ������..

    switch($mime) 
    {
        case "PLAIN":
             Header ( "Content-Type: text/plain");
             echo str_replace("\n", "<br>", $val);
             break;
        case "HTML":
             Header ( "Content-Type: text/html");
             echo $val;
             break;
        case "OCTET-STREAM":
             Header ( "Content-Type: octet-stream");
             Header ( "Content-Disposition: attachment; filename=$file_name");
             echo $val;
             break;
        default:
             Header ( "Content-Type: octet-stream");
             Header ( "Content-Disposition: attachment; filename=$file_name");
             echo $val;
    }
}

include ("mail_lib.php");

$box = $BOX;
$part_no = $PART_NO;
if($part_no == "") $part_no = 0;
if($box == "") $box = "INBOX";

include ("mail_config.php"); 

$mailstream = imap_open($C_DOMAIN, $login, $pass);

if ($mailstream == 0) 
{
   echo "Error!";
   exit;
}

$struct = imap_fetchstructure($mailstream, $MSG_NO);
$part = $struct->parts[$part_no];
$param = $part->parameters[0];
$file_name = Decode($param->value); // ÷�������� ��� ���ϸ�
$mime = $part->subtype; // MIME Ÿ��
$encode = $part->encoding; // encoding

printbody($mailstream, $Subject, $MSG_NO, $part_no, $encode, $mime, $file_name);

imap_close($mailstream);
?>


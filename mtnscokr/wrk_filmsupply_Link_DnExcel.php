<?
    session_start();

    $Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...

    $SingoDate = date("Ymd",$Today) ;
?>
<html>
    <head>

        <script type="text/javascript" src="./js/jquery-1.3.2.js"></script> <!-- http://visualjquery.com/ -->
        <script type="text/javascript" src="./js/jquery.form.js"></script>  <!-- http://www.malsup.com/jquery/form/ -->

        <title>�������ھ�����</title>

        <script type="text/javascript">
        <!--

            $(document).ready(function()
            {
                $("div#output").css("color","red") ;

                // �Ե����׸�
                $("#btnLotte").click(function()
                {
                    $("#Silmooja").val("555588") ;
                    $.post("./wrk_filmsupply_Link_DnExcel_Lotte_Set.php", function(data)
                    {
                        $('div#output').html(data) ;
                    });

                    clear() ;

                });
                // �ް��ڽ�
                $("#btnMagaBox").click(function()
                {
                    $("#Silmooja").val("555587") ;
                    //$('div#output').html("") ;

                    $.post("./wrk_filmsupply_Link_DnExcel_Megabox_Set.php", function(data)
                    {
                        $('div#output').html(data) ;
                    });

                    clear() ;
                });
                // �����ӽ�
                $("#btnPrimus").click(function()
                {
                    $("#Silmooja").val("555595") ;
                    $.post("./wrk_filmsupply_Link_DnExcel_Primus_Set.php", function(data)
                    {
                        $('div#output').html(data) ;
                    });

                    clear() ;

                });
                // CGV
                $("#btnCGV").click(function()
                {
                    $("#Silmooja").val("777777") ;
                    $('div#output').html("") ;

                    clear() ;
                });

                function clear()
                {
                    FormUpload.file.select(); // value�� ���� select ����!
                    document.execCommand('Delete'); // ����������!!
                }
           });


        //-->
        </script>

        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">

    </head>


    <script language="javascript">
    <!--
    function File_Up(form, file)
    {
        extArray = new Array(".xls");
        if (!file) return;

        while (file.indexOf("\\") != -1)
          file = file.slice(file.indexOf("\\") + 1);   //indexOf => ������ ������ġã��.

        ext = file.slice(file.indexOf(".")).toLowerCase();   //toLowerCase => �ҹ��ڷ� ��ȯ

        for (var i = 0; i < extArray.length; i++)
        {
            if (extArray[i] == ext) { allowSubmit = true; break; }   //allowSubmit => ���� Ȯ���� ���͸�
        }
        var bErr = false ;

        if (extArray[i] != ".xls" )
        {
            alert("Ȯ���ڰ� .xls �� �ƴ� ������ ���ε� �� �� �����ϴ�.");

            form.file.select(); // value�� ���� select ����!
            document.execCommand('Delete'); // ����������!!

            bErr = true ;
        }

        sGubun = "" ;
        for(i=0;i<FormUpload.gubun.length;i++)
        {
             if  (FormUpload.gubun[i].checked)
             {
                 sGubun =FormUpload.gubun[i].value ;
             }
        }
        if  (sGubun=="")
        {
            alert("������ ���� �����ϼ���");

            bErr = true ;
        }
        else
        {
            if  (((sGubun=="�ް��ڽ�") || (sGubun=="�����ӽ�") || (sGubun=="CGV")) && ( file.length != 12))
            {
                alert("���ϸ��� ��¥���� �̾�� �մϴ� yyyymmdd.xls  ��) 20101231.xls");

                bErr = true ;
            }
        }
    }

    function onSubmit()
    {
        var sMsg = "" ;
        var sGubun = "" ;



        if  (FormUpload.file.value=="")
        {
            sMsg += "���ϸ��� �����ϴ�.\n" ;
        }
        for(i=0;i<FormUpload.gubun.length;i++)
        {
             if  (FormUpload.gubun[i].checked)
             {
                 sGubun =FormUpload.gubun[i].value ;
             }
        }
        if  (sGubun=="")
        {
            sMsg += "������ �����ϴ�.\n" ;
        }

        var nSelect = -1 ;
        for(var i=0; i<FormUpload.FilmType.length; i++)
        {
            if  (FormUpload.FilmType[i].checked)
            {
                nSelect = i ;
                break;
            }
        }
        if (nSelect == -1)
        {
            sMsg += "�ʸ������� ���õ��� �ʾҽ��ϴ�.\n" ;
        }


        if  (sMsg!="")
        {
            alert(sMsg) ;

            return false ;
        }
        else
        {
            <?
            if ( $digital ==  "yes" )
            {
                $Param  = "?digital=yes" ;
            }
            else
            {
                $Param = "" ;
            }
            ?>

            // ���� ���� ���� action�� �ٸ����� ȣ���Ѵ�.
            if  (sGubun=="�Ե����׸�") FormUpload.action="./wrk_filmsupply_Link_DnExcel_Lotte.php<?=$Param?>" ;
            if  (sGubun=="�ް��ڽ�")   FormUpload.action="./wrk_filmsupply_Link_DnExcel_Megabox.php<?=$Param?>" ;
            if  (sGubun=="�����ӽ�")   FormUpload.action="./wrk_filmsupply_Link_DnExcel_Primus.php<?=$Param?>" ;
            if  (sGubun=="CGV")        FormUpload.action="./wrk_filmsupply_Link_DnExcel_CGV.php<?=$Param?>" ;

            return true ;
        }
    }
    //-->
    </script>



    <body bgcolor="#fafafa" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

        <BR><BR><BR>


        <table width="1000" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr>
            <td align="center" valign="middle">

                <form name="FormUpload" method="post"enctype="multipart/form-data" onsubmit="return onSubmit()">
					           <table>
                    <?
                    if ( $digital ==  "yes" )
                    {
                        ?>
                        <tr><td align="center" colspan=2>������ ȸ������</td></tr>
                        <?
                    }
                    ?>
                    <tr><td bgcolor="black" colspan=2></td></tr>
                    <tr>
                     <td align="center" colspan=2>
                         �Ű�����:<input type="text" name="SingoDate" value=<?=$SingoDate?> maxlength="8" size="9">
                         �ǹ���:<input type="text" id="Silmooja" name="Silmooja" value="777777" maxlength="6" size="7">
                     </td>
                    </tr>
                    <tr><td bgcolor="black" colspan=2></td></tr>
                    <tr>
                     <td align="right"><label for="gubun">����:</label></td>
                     <td>
                         <input id="btnLotte"   type="radio" name="gubun" value="�Ե����׸�">�Ե����׸�
                         <input id="btnMagaBox" type="radio" name="gubun" value="�ް��ڽ�">�ް��ڽ�
                         <input id="btnPrimus"  type="radio" name="gubun" value="�����ӽ�">�����ӽ�
                         <input id="btnCGV"     type="radio" name="gubun" value="CGV">CGV
                    </td>
                    </tr>
                    <tr><td bgcolor="black" colspan=2></td></tr>
                    <tr>
                     <td align="right"><label for="gubun">�ʸ�����:</label></td>
                     <td>
                         <table>
                         <tr>
                          <td valign="center">

                          </td>
                          <td valign="top">
                              <?
                              if ( $digital !=  "yes" )
                              {
                              ?>
                              <input type='radio' name='FilmType' value='35'>35mm
                              <?
                              }
                              ?>
                          </td>
                          <td valign="top">
                              <input type='radio' name='FilmType' value='2'>������2D<br>
                              <input type='radio' name='FilmType' value='3'>������3D
                          </td>
                          <td valign="top">
                              <?
                              if ( $digital !=  "yes" )
                              {
                              ?>
                              <input type='radio' name='FilmType' value='29'>���̸ƽ�2D<br>
                              <input type='radio' name='FilmType' value='39'>���̸ƽ�3D
                              <?
                              }
                              ?>
                          </td>
                          <td valign="top">
                              <input type='radio' name='FilmType' value='20'>������ ����<br>
                              <input type='radio' name='FilmType' value='30'>������3D ����
                          </td>
                          <td valign="top">
                              <input type='radio' name='FilmType' value='24'>2D HFR<br>
                              <input type='radio' name='FilmType' value='34'>3D HFR
                          </td>
                          <td valign="top">
                              <input type='radio' name='FilmType' value='294'>2D IMAX HFR<br>
                              <input type='radio' name='FilmType' value='394'>3D IMAX HFR
                          </td>
                          <td valign="top">
                              <input type='radio' name='FilmType' value='4'>4D<br>
                          </td>
                         </tr>
                         </table>
                    </td>
                    </tr>
                    <tr><td bgcolor="black" colspan=2></td></tr>
                    </table>


                    <br><br>

                    <label for="file">���ϸ�:</label>
                    <input type="file"  name="file" id="file" onChange="File_Up(this.form.file.value)">

                    <br><br>

                    <!-- ���� ��ư -->
                    <input type="submit" name="submit" value="����" />

                </form>

            </td>
        </tr>
        <tr>
            <td align="center" valign="middle">
                <div id="output"></div>
            </td>
        </tr>
        </table>


    </body>

</html>


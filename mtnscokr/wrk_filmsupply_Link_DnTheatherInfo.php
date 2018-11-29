<?
    session_start();
?>
<html>
    <?
    $db   = "mtns" ;

    $connect = mysql_connect( "localhost", "mtns",     "5421")  or  Error("DB 접속시 에러가 발생했습니다");

    mysql_select_db($db,  $connect) ;

    $page_num = 30 ;

    if  ( !$page ) { $page = 0; }
    $page_size = $page_num * $page;

    $sQuery = "Select count(*)
                 From bas_theather
			    Where View = 'Y'
              " ;
    $count_search = mysql_query($sQuery,$connect) ;
    $count_search_row = mysql_fetch_row($count_search);

    $page_1 = $count_search_row[0] / $page_num;
    $page_1 = intval($page_1);
    $page_2 = $count_search_row[0] % $page_num;

    if ( $page_2 > 0 ) { $page_1++; }
    $total_page = intval($page_1);
    $prev_page = $page - 1;
    $next_page = $page + 1;
    $now_page = $page + 1;

    if ( $page == 0 )
    {
        $str_prev_page = "<input  type=button value=\"이전\" OnClick=\"javascript:alert('더이상 페이지가 없습니다.');\">";
    }
    else
    {
        $str_prev_page = "<input  type=button value=\"이전\" OnClick=\"move_prev(write.checkboxs);\">";
    }

    if ( $now_page == $total_page )
    {
        $str_next_page = "<input  type=button value=\"다음\" OnClick=\"javascript:alert('더이상 페이지가 없습니다.');\">";
    }
    else
    {
        $str_next_page = "<input  type=button value=\"다음\" OnClick=\"move_next(write.checkboxs);\">";
    }
    ?>

    <head>
		<script type="text/javascript" src="./js/jquery-1.8.3.js"></script>

        <Script Language="JavaScript">
        <!--
			$(document).ready(function(){
			});

			function Select_Page()
            {
				frmMain.action = "<?=$PHP_SELF?>?page="+(frmMain.CurPage.value-1) ;
				frmMain.submit() ;
            }

            function move_prev(chk)
            {
				frmMain.action = "<?=$PHP_SELF?>?page=<?=$prev_page?>" ;
				frmMain.submit() ;
            }

            function move_next(chk)
            {
				frmMain.action = "<?=$PHP_SELF?>?page=<?=$next_page?>" ;
				frmMain.submit() ;
            }

			function chgGikum(code,textbox)
			{
				alert($("#"+textbox).val());
			}


			function check(id,code)
			{
				var options = {
                    _Code: code,
                    _Value: ($("#"+id).is(":checked") == true) ? 1.03 : 1.0
                } ;

				$.post("./wrk_filmsupply_Link_DnTheatherInfo_set.php", options, function(data)
                {
                    //alert(data) ;
                });
			}

        //-->
        </script>

    </head>

    <body>


		<div style="text-align: center; margin-bottom: 15px;">
		<form name="frmMain" action="" method="post">
			<a><?=$str_prev_page?></a>
			[
			<select name=CurPage onchange='Select_Page();'>
			<?
			for  ($i = 1 ; $i <= $total_page ; $i++)
			{
				if  ($i == $now_page)
				{
				?>
					<option selected value=<?=$i?>><?=$i?></option>
				<?
				}
				else
				{
				?>
					<option value=<?=$i?>><?=$i?></option>
				<?
				}
			}
			?>
			</select>/<?=$total_page?>
			]
			<a><?=$str_next_page?></a>
		</form>
		</div>

		<table border="0" cellpadding="3" bgcolor="#3399CC"  align="center" width="60%">
		<colgroup>
		<col width="20%"/>
		<col width="*"/>
		<col width="15%"/>
		</colgroup>
		<tr bgcolor="#ffffff">
			<th>지역</th>
			<th>극장명</th>
			<th>기금적용</th>
		</tr>

		<?
		$sQuery = "       Select tr.Code Code
								,lo.Name Name
								,UserId
								,UserPw
								,Discript
								,TelNo
								,SaupNo
								,JikYong
								,GikumRate
	 					    From bas_theather tr
	 				  Inner Join bas_location lo
	 						  On tr.Location = lo.Code
                           Where View = 'Y'
					    Order By Discript, Name
						   limit $page_size,$page_num
                  " ; //echo $sQuery;
        $QryTheather = mysql_query($sQuery,$connect) ;
        while ($ArrTheather = mysql_fetch_array($QryTheather))
        {
			$Code       = $ArrTheather["Code"];
		    $Location   = $ArrTheather["Name"];
		    $UserId     = $ArrTheather["UserId"];
		    $UserPw     = $ArrTheather["UserPw"];
		    $Discript   = $ArrTheather["Discript"];
		    $TelNo      = $ArrTheather["TelNo"];
		    $SaupNo     = $ArrTheather["SaupNo"];
		    $JikYong    = $ArrTheather["JikYong"];
		    $GikumRate  = $ArrTheather["GikumRate"];
			?>
			<tr bgcolor="#ffffff">
				<td style="padding-left: 10px;"><?=$Location?></td>
				<td style="padding-left: 10px;"><?=$Discript?></td>
				<td align="center">
				<?
				if  ($GikumRate == 1.03) $chk = "checked=\"checked\"" ;
			    else                     $chk = "" ;
				?>
				<input id="chk<?=$Code?>" type="checkbox" <?=$chk?> onClick="check('chk<?=$Code?>','<?=$Code?>');" >

				</td>
			</tr>
			<?
		}
		?>



	</body>

    <?
    mysql_close($connect);
    ?>
</html>

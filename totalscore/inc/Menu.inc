   <div id='cssmenu'>
      <ul>
         <!--<li id="menu_home"><a href='.'>홈</a></li>-->
         <li id="menu1"><a href='#'>■ 데이터 가져오기</a>
            <ul>
               <li><a href='crawling_All.php'>● 전체 가져오기</a></li>
               <!--
               <li><a href='crawling_A.php'>1. 상영관리스트 가져오기</a></li>
               <li><a href='crawling_B.php'>2. 상영관별 시간표 가져오기</a></li>
               <li><a href='crawling_C.php'>3. 상영관별 스크린 정보 가져오기</a></li>
               <li><a href='crawling_D.php'>4. 박스오피스 가져오기</a></li>
               <?
               $ShowDate1 = date("Y-m-d",strtotime("-1 Day",time())) ;
               $ShowDate2 = date("Y-m-d",strtotime("6 Day",time())) ;
               ?>
               <li><a href='crawling_E.php?ShowDate1=<?=$ShowDate1?>&ShowDate2=<?=$ShowDate2?>'>5. 상영내역 가져오기</a></li>
               <?
               $ShowDate = date("Y-m-d") ;
               $ShowDate2 = date("Y-m-d",strtotime("6 Day",time())) ;
               ?>
               <li><a href='crawling_F.php?ShowDate1=<?=$ShowDate1?>&ShowDate2=<?=$ShowDate2?>'>6. 상영스케줄 가져오기</a></li>
               -->
               <!--li class='has-sub'><a href='#'>Product 2</a>
                  <ul>
                     <li><a href='#'>Sub Product</a></li>
                     <li><a href='#'>Sub Product</a></li>
                  </ul>
               </li-->
         </ul>
      </li>
      <li id="menu2"><a href='#'>■ 데이터통계보기</a>
         <ul>
            <li><a href='fix_theather.php'>● 상영관리스트 보정/매핑</a></li>
            <?
            //$BaseDate_ = date("Y-m-d",strtotime("1 Day",time())) ;
            $BaseDate_ = date("Y-m-d",time()) ;
            ?>
            <li><a href='bikyo_table1.php?PlayDate=<?=$BaseDate_?>'>● 경쟁영화 회차비교표 1</a></li>
            <li><a href='bikyo_table2.php?PlayDate=<?=$BaseDate_?>'>● 경쟁영화 회차비교표 2</a></li>
            <li><a href='bikyo_table3.php?PlayDate=<?=$BaseDate_?>'>● 경쟁영화 회차비교표 3</a></li>
            <li><a href='bikyo_table_All.php?PlayDate=<?=$BaseDate_?>'>● 경쟁영화 회차비교표 ALL</a></li>
         </ul>
      </li>
      <!--
      <li id="menu3"><a href='AMChartSample.php'>AMChart Sample</a></li>
      <li><a href='#'>About</a></li>
      <li><a href='#'>Contact</a></li>
      -->
   </ul>
</div>

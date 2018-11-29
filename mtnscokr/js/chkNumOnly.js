
// http://tong.nate.com/lhs0806/48325487
// 숫자만 입력
function chkNumOnly()
{
				if((event.keyCode<48) || (event.keyCode>57))
				{
						event.returnValue=false;
				}
}
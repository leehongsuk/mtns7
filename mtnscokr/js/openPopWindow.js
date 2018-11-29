
// http://tong.nate.com/lhs0806/47603751  팝업창을 화면 정중앙에 오픈합니다..
function openPopWindow(a_str_windowURL, a_str_windowName, a_int_windowWidth, a_int_windowHeight)
{
    var int_windowLeft = (screen.width - a_int_windowWidth) / 2;
    var int_windowTop = (screen.height - a_int_windowHeight) / 2;

    var str_windowProperties = 'height=' + a_int_windowHeight
                              + ',width=' + a_int_windowWidth
                              + ',top=' + int_windowTop
                              + ',left=' + int_windowLeft
                              + ',scrollbars=no'
                              + ',resizable=no'
                              + ',totlbar=no' ;
    var obj_window = window.open(a_str_windowURL, a_str_windowName, str_windowProperties)
    if (parseInt(navigator.appVersion) >= 4)
    {
        obj_window.window.focus();
    }
}

<?php
function responseHTML($permissions,$user)
{
    $permissionsMap=[];
    foreach ($permissions as $perm) {
        $permissionsMap[$perm]=true;
    };
    
    $html =""; 
    $html.="<div id='left_menu'>
    <div id='logo-ctn'>
        <img src='imgs/logo.png' id='logo'>
    </div>
    <div id='menu-ctn'>
        <ul id='menu'>";
    if(isset($permissionsMap["STATS"]))
    {
        $html.="<li class='menu-item STATS' data-value='STATS'>
                    <lb><i class='fa-solid fa-chart-column menu-icon'></i>Thống kê số liệu</lb>
                </li>";
    }
    if(isset($permissionsMap["PRODUCTS"]))
    {
        $html.="<li class='menu-item PRODUCTS' data-value='PRODUCTS'>
                    <lb><i class='fa-solid fa-cube menu-icon'></i>Sản phẩm</lb>
                </li>";
    }   
    if(isset($permissionsMap["ORDERS"]))
    {
        $html.="<li class='menu-item ORDERS' data-value='ORDERS'>
                    <lb><i class='fa-solid fa-receipt menu-icon'></i>Đơn hàng</lb>
                </li>";
    }
    if(isset($permissionsMap["CUSTOMERS"]))
    {
        $html.="<li class='menu-item CUSTOMERS' data-value='CUSTOMERS'>
                    <lb><i class='fa-solid fa-user menu-icon'></i>Khách hàng</lb>   
                </li>";
    }
    if(isset($permissionsMap["EMPLOYEES"]))
    {
        $html.="<li class='menu-item EMPLOYEES' data-value='EMPLOYEES'>
                    <lb><i class='fa-solid fa-user-tie menu-icon'></i>Nhân viên</lb>
                </li>";
    }
    if(isset($permissionsMap["SUPPLIERS"]))
    {
        $html.="<li class='menu-item SUPPLIERS' data-value='SUPPLIERS'>
                    <lb><i class='fa-solid fa-truck menu-icon'></i>Nhà cung cấp</lb>
                </li>";
    }
    
    $html.="</ul>
            </div>
                <div id='admin-account-ctn'>
                    <div id='account'>
                        <lb>
                            <p id='admin-username'><i class='fa-solid fa-at admin-username-icon'></i>". htmlspecialchars($user['fullname'], ENT_QUOTES, 'UTF-8') ."<p>
                            <p id='admin-role'><i class='fa-solid fa-hashtag admin-role-icon'></i>". htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ."</p>
                        </lb>
                    </div>
                    <div id='admin-logout-button' class='menu-item'>
                        <lb><i class='fa-solid fa-sign-out fa-flip-horizontal menu-icon'></i></i>Đăng xuất</lb>
                    </div>
                </div>
            </div>";

    $html.=file_get_contents("admin_right_content.php");
    return $html;
}
?>
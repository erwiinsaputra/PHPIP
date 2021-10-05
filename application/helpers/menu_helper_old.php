<?php


function get_menu()
{
    $CI =& get_instance();

    $role = $CI->session->userdata('USER')['ROLE_ID'];
    // echo '<pre>';print_r($role);exit;

    //get menu
    $arr_role = @$CI->m_global->getDataAll('sys_role',NULL, ['is_active' => '1'], 'menu', "id IN(".$role.")");
    $arr_no   = [];
    foreach ($arr_role as $row) {
        $pecah = explode(', ',$row->menu);
        $arr_no = array_merge($arr_no,$pecah);
    }
    $arr_menu = array_unique($arr_no);
    $arr_menu = implode(',', $arr_menu);
    $where_e = "id IN(".$arr_menu.")";

    $tmp = @$CI->m_global->getDataAll('sys_menu', NULL, ['is_active' => '1', 'parent' => '0'], 'id, name, icon, folder, controler', $where_e, ['order', 'asc']);
    $menu = [];
    foreach($tmp as $row){
        $menu[] = get_menu_sub($row, $row->id, $where_e);
    }

    return $menu;
}

function get_menu_sub($row, $idmenu, $where_e)
{
    $CI =& get_instance();

    $tmp2 = @$CI->m_global->getDataAll('sys_menu', NULL, ['parent' => $idmenu, 'is_active' => '1'], 'id, name, icon, folder, controler', $where_e, ['order', 'asc']);

    if(!empty($tmp2)){

        foreach($tmp2 as $row2){

            $tmp3 = $CI->m_global->getDataAll('sys_menu', NULL, ['is_active' => '1', 'parent' => $row2->id], 'id, name, icon, folder, controler', $where_e, ['order', 'asc']);
            
            if(!empty($tmp2)){

                $row2->sub2 = $tmp3;

            }

        }
        $row->sub = $tmp2;

    }

    return $row;

}


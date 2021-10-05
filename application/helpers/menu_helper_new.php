<?php
function get_menu()
{
    $CI =& get_instance();

    $role = $CI->session->userdata('USER')['ROLE_ID'];
    // echo '<pre>';print_r($role);exit;

    //get menu
    $arr = @$CI->m_global->getDataAll('sys_role',NULL, ['status' => '1'], '"menu", "menu_json"', '"id" = '.$role)[0];
    $id_menu    = $arr->menu;
    $menu_json  = $arr->menu_json;


    //menu
    $where_e = "id IN(".$id_menu.")";
    $arr = @$CI->m_global->getDataAll('sys_menu', NULL, ['status' => '1'], 'id, name, icon, folder, controler', $where_e, ['order', 'asc']);
    $menu = [];
    foreach($arr as $row){
        $menu[$row->id]['name']      = $row->name;
        $menu[$row->id]['icon']      = $row->icon;
        $menu[$row->id]['controler'] = $row->controler;
        $menu[$row->id]['folder']    = $row->folder;
        $menu[$row->id]['sub']       = [];
    }

    //menu urutan
    $menu_json = json_decode($menu_json);
    // echo '<pre>';print_r($menu_json);exit;

    $arr = [];
    $a = -1;
    foreach ($menu_json as $row) {
        $a++;
        $arr[$a] = @$menu[$row->id];

        //sub menu
        if(@$row->children != ''){
            $arr_sub = []; 
            $b = -1;
            foreach(@$row->children as $sub){
                $b++;
                $arr_sub[$b] = $menu[$sub->id];

                //sub menu 2
                if(@$sub->children != ''){
                    $arr_sub2 = [];
                    $c = -1;
                    foreach(@$sub->children as $sub2){
                        $c++;
                        $arr_sub2[$c] = $menu[$sub2->id];
                    }

                    $arr_sub[$b]['sub'] = $arr_sub2;
                }

            }
            $arr[$a]['sub'] = $arr_sub;
        }

    }

    echo '<pre>';print_r($arr);exit;

    return $arr;
}

function get_menu_sub($row, $idmenu, $where_e)
{
    $CI =& get_instance();

    $tmp2 = @$CI->m_global->getDataAll('sys_menu', NULL, ['parent' => $idmenu, 'status' => '1'], 'id, name, icon, folder, controler', $where_e, ['order', 'asc']);

    if(!empty($tmp2)){

        foreach($tmp2 as $row2){

            $tmp3 = $CI->m_global->getDataAll('sys_menu', NULL, ['status' => '1', 'parent' => $row2->id], 'id, name, icon, folder, controler', $where_e, ['order', 'asc']);
            
            if(!empty($tmp2)){

                $row2->sub2 = $tmp3;

            }

        }
        $row->sub = $tmp2;

    }

    return $row;

}

//untuk edit group
function checked_data($group, $var){
    return in_array($var, $group) ? 'checked' : '';
}


<?php
function get_salesperson_list()
{
    $sm = new Model('pk_user'); 
    $smlist = $sm->filter_index(array('user_group'=>'salesman'));
    $smarr = array();
    foreach ($smlist as $pmt) {
        $smarr[] = array(
            'id' => $pmt['id'],
            'name' => $pmt['name'],
            'city' => $pmt['city']
        );
    }
    return $smarr;
}


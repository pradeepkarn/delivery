<?php
function get_whmanagers_list()
{
    $sm = new Model('pk_user'); 
    $smlist = $sm->filter_index(array('user_group'=>'whmanager'));
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
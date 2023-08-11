<?php 
// function show_promotions($group=null)
// {   $proms = new Model('promotions');
//     if($group==null){
//         return $proms->index();
//     }else{
//         return $proms->filter_index(array('content_group'=>$group));
//     }
// }
function booking_list($group=null)
{   $proms = new Model('salon_bookings');
    if($group==null){
        return $proms->index();
    }else{
        return $proms->filter_index(array('status'=>'confirmed'));
    }
}
function booking_detail($id)
{   $proms = new Model('salon_bookings');
    return $proms->show($id);
}
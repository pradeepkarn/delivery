<?php
function booking_detail_by_user_api($user_id, $booking_id)
{
    $proms = new Model('salon_bookings');
    return $proms->filter_index(array('user_id' => $user_id, 'id' => $booking_id));
}
function booking_by_user($user_id, $status = 'requested')
{
    if ($status == null) {
        $proms = new Model('salon_bookings');
        return $proms->filter_index(array('user_id' => $user_id));
    }
    $proms = new Model('salon_bookings');
    return $proms->filter_index(array('user_id' => $user_id, 'status' => $status));
}
function change_booking_satus_api($user_id, $booking_id, $status = 'cancelled', $db = null)
{
    if ($db != null) {
        $proms = $db;
    } else {
        $proms = new Dbobjects;
    }
    $proms->tableName = 'salon_bookings';
    $bkng = $proms->filter(['user_id' => $user_id, 'id' => $booking_id]);
    if (count($bkng) > 0) {
        if ($bkng[0]['status']=='cancelled') {
            $res['msg'] = "This booking has already been cancelled";
            $res['data'] = false;
            return $res;
        }
        $proms->insertData['status'] = $status;
        try {
            $proms->update();
            $res['msg'] = "success";
            $res['data'] = true;
            return $res;
        } catch (PDOException $e) {
            $res['msg'] = "Database exception while changing status";
            $res['data'] = false;
            return $res;
        }
    } else {
        $res['msg'] = "Not cancelled, data not found in your booking list";
        $res['data'] = false;
        return $res;
    }
}

function change_visiting_datetime($user_id, $booking_id, $date, $time, $db = null)
{
    if ($db != null) {
        $proms = $db;
    } else {
        $proms = new Dbobjects;
    }
    $proms->tableName = 'salon_bookings';
    $bkng = $proms->filter(['user_id' => $user_id, 'id' => $booking_id]);
    if (count($bkng) > 0) {
        if ($bkng[0]['status']=='cancelled') {
            $res['msg'] = "This booking has already been cancelled";
            $res['data'] = false;
            return $res;
        }
        $proms->insertData['visiting_date'] = $date;
        $proms->insertData['visiting_time'] = $time;
        try {
            $proms->update();
            $res['msg'] = "success";
            $res['data'] = true;
            return $res;
        } catch (PDOException $e) {
            $res['msg'] = "Database exception while changing schedule";
            $res['data'] = false;
            return $res;
        }
    } else {
        $res['msg'] = "Not updated, data not found in your booking list";
        $res['data'] = false;
        return $res;
    }
}

function total_service_time($jsnData) {
    $total_min=0;
    $jsn = json_decode($jsnData);
    if (isset($jsn->services)) {
        foreach ($jsn->services as $srvid) {
            $srvs = getData('content', $srvid->id);
            if ($srvs) {
                $srvs = obj($srvs);
                if ($srvs->duration_unit=="min") {
                    $total_min += $srvs->duration;
                }else{
                    $total_min += $srvs->duration*60;
                }
            }
        }
        
    }
    return floor($total_min/60)."Hr:".($total_min%60) ."Min";
}
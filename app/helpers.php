<?php
// namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Proposal;

function notification($level=null){
    $count = Proposal::selectRaw('count(proposal.id) as jumlah')
                        ->join('users','users.id','=','proposal.user_id')
                        ->join('position','position.id','=','users.position_id')
                        ->join('department','department.id','=','position.department_id');
    if ($level==2) {
        $count->where('review_status',NULL);
        $count->where('department.id', Session::get('position')->department->id);
        $count->where('user_id','!=', Session::get('user')->id);
    }elseif ($level==3) {
        $count->where('review_status',1);
        $count->where('approve1_status',NULL);
    }elseif ($level==4) {
        $count->where('approve1_status',1);
        $count->where('approve2_status',NULL);
        $count->where('status',0);
    }
    $result = $count->first()->jumlah;
    return $result;
}

function active_class($path, $active = 'active') {
  return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

function active_class_front($path, $active = 'current-menu-item') {
  return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

function is_active_route($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
}

function show_class($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
}

function dateIndo($date) {
    // $dt = $date->isoFormat('D MMM Y');
    $dt = Carbon\Carbon::parse($date)->isoFormat('D MMM Y');
    return $dt;
}

function rupiah($number){
    $hasil_rupiah = "Rp " . number_format($number,0,',','.');
    return $hasil_rupiah;
}

function monthIndo($date) {
    $dt = Carbon\Carbon::parse($date)->isoFormat('MMM');
    return $dt;
}

function getTime($date) {
    $time = date('H:i', strtotime($date));
    return $time;
}

<?php

if(!defined('ABSPATH')) exit;
if(!current_user_can('read')) exit;
if(empty($_GET['dyt_user']) || !is_admin()) $wp_userid=dynamicTime_userid(); else $wp_userid=intval($_GET['dyt_user']);

function dynamicTime_userid() {
  $userid=0;
  if(is_user_logged_in()) {
    $current_user=wp_get_current_user();
    $userid=$current_user->ID;
  }
  return $userid;
}


if(is_user_logged_in() && $wp_userid>0) {

  $user_info=get_userdata($wp_userid);
  $df_date=intval($_REQUEST['df_date']);
  if(!empty($_REQUEST['date'])) $date=array_map('ABSINT',$_REQUEST['date']);
  if(!empty($_REQUEST['hours'])) $hours=array_map('sanitize_text_field',$_REQUEST['hours']);
  if(!empty($_REQUEST['hourtype'])) $hourtype=array_map('sanitize_text_field',$_REQUEST['hourtype']);
  if(!empty($_REQUEST['time_in'])) $time_in=array_map('sanitize_text_field',$_REQUEST['time_in']);
  if(!empty($_REQUEST['time_out'])) $time_out=array_map('sanitize_text_field',$_REQUEST['time_out']);
  $input_saved=0;


  if(!empty($_POST['dyt_save_time']) && check_admin_referer('save_time','dyt_save_time')) {
    $get_login=$wpdb->get_results("SELECT WP_UserID FROM {$wpdb->prefix}time_user WHERE WP_UserID=$wp_userid LIMIT 1; ",OBJECT);

    if(!$get_login) $insert_config=$wpdb->get_results("INSERT INTO {$wpdb->prefix}time_user (WP_UserID,Rate,Exempt) VALUES('".$wp_userid."','','0'); ",OBJECT);

    foreach($date as $index=> $dateval) { //Delete all matching before doing an Insert of new ones.
      $delete_timerow=$wpdb->get_results("DELETE FROM {$wpdb->prefix}time_entry WHERE WP_UserID=$wp_userid AND Date='$dateval'; ",OBJECT);
    }
    
    reset($date);
    foreach($date as $index=> $dateval) {
      if($hours[$index]>0) $insert=$wpdb->get_results("INSERT INTO {$wpdb->prefix}time_entry (WP_UserID,Date,Hours,HourType,TimeIn,TimeOut) VALUES($wp_userid,'".$dateval."',".$hours[$index].",'".$hourtype[$index]."','".$time_in[$index]."','".$time_out[$index]."'); ",OBJECT);
    }
    
    $input_saved++;
  }


  $get_config=$wpdb->get_results("
    SELECT WP_UserID,Rate,Prompt,Exempt,Period,WeekBegin
    FROM {$wpdb->prefix}time_config
    LEFT JOIN {$wpdb->prefix}time_user ON WP_UserID='".$wp_userid."'
    LIMIT 1;
  ",OBJECT);

  if($get_config): 
    foreach ($get_config as $row): 
      $rate=$row->Rate;
      $prompt=$row->Prompt;
      $exempt=$row->Exempt;
      $period=$row->Period;
      $weekbegin=$row->WeekBegin;
    endforeach;
    else: $input_saved='-3';
  endif;
  
  $result=$wpdb->get_results("
    SELECT WP_UserID,Date,SUM(Hours) as Hours,HourType,TimeIn,TimeOut
    FROM {$wpdb->prefix}time_entry
    WHERE WP_UserID=$wp_userid
    GROUP BY WP_UserID,Date,HourType,TimeIn,TimeOut
    ORDER BY Date ASC, Hours DESC;
  ",OBJECT);

  $date='';
  $hours='';
  $hourtype='';
  $time_in='';
  $time_out='';
  
  if($result): 
    foreach ($result as $row): 
    $date=$date."'".$row->Date."',";
    $hours=$hours."'".$row->Hours."',";
    $hourtype=$hourtype."'".$row->HourType."',";
    $time_in=$time_in."'".$row->TimeIn."',";
    $time_out=$time_out."'".$row->TimeOut."',";
    endforeach;
  
  endif;
} else $input_saved='-4'; ?>


<script type="text/javascript">
  var input_saved="<?=$input_saved;?>";
  var rate="<?=$rate;?>";
  var prompt="<?=$prompt;?>";
  var exempt="<?=$exempt;?>";
  var period="<?=$period;?>";
  var weekbegin="<?=$weekbegin;?>";

  var db_date=<? echo '['.substr($date,0,-1).']';?>;
  var db_hours=<? echo '['.substr($hours,0,-1).']';?>;
  var db_hourtype=<? echo '['.substr($hourtype,0,-1).']';?>;
  var db_time_in=<? echo '['.substr($time_in,0,-1).']';?>;
  var db_time_out=<? echo '['.substr($time_out,0,-1).']';?>;
  
  var dynamicTime_interval=setInterval(function() {
    if(document.readyState==='complete') { clearInterval(dynamicTime_interval); dynamicTime_load(); }
  }, 100);
</script>


<? if($input_saved>=0) { ?>
<form id='dyt_form' method='post' accept-charset='UTF-8 ISO-8859-1'>
  <?=wp_nonce_field('save_time','dyt_save_time');?>

  <div id='nav' class='dyt_nav'>
    <a onclick='add_week(-1);' class='dyt_bkw'> Prev Period</a>
    <a onclick='add_week(1);' class='dyt_fwd'> Next Period </a>
  </div>

  <div id='dyt_cal'></div>

  <table id='dyt_sum'>
    <tr>
      <th colspan='4' style='text-align:center;'><?=$user_info->first_name." ".$user_info->last_name;?></th>
    </tr>
    <tr>
      <td align='right'>Reg</td><td nowrap><input type='text' id='Reg' readonly>
      <td align='right'>Hours</td><td nowrap><input type='text' id='TOT' readonly>
    </tr>
    <tr>
      <td align='right'>PTO</td><td nowrap><input type='text' id='PTO' readonly>
      <td align='right'>Total</td><td nowrap><input type='text' id='TOTamt' readonly>
    </tr>
    <tr>
      <td align='right'>OT</td><td nowrap colspan='2'><input type='text' id='OT' readonly>
      <td>
        <input id='submit_time' type='submit' name='save' value='Save' onclick='show_save(-1);'> 
        <input id='print_time' type='button' value='Print' onclick='window.print();'>
      </td>
    </tr>
  </table>
</form>
<? } ?>

<div id='input_saved'>Saved</div>

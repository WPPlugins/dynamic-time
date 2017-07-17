<?php

if(!defined('ABSPATH')) exit;
if(!current_user_can('manage_options')) exit;

$prompt=intval($_POST['prompt']);
$period=intval($_POST['period']);
$weekbegin=intval($_POST['weekbegin']);

$wp_userid=intval($_POST['wp_userid']);
$rate=filter_var($_POST['rate'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$exempt=intval($_POST['exempt']);
$input_saved=0;


if(!empty($_POST['dyt_config_time']) && check_admin_referer('config_time','dyt_config_time')) {
  $delete_config=$wpdb->get_results("DELETE FROM {$wpdb->prefix}time_config; ",OBJECT);
  $insert_config=$wpdb->get_results("INSERT INTO {$wpdb->prefix}time_config (Prompt,Period,WeekBegin) VALUES(".$prompt.",'".$period."','".$weekbegin."'); ",OBJECT);
  $input_saved++;
}


if(!empty($_POST['dyt_config_user']) && check_admin_referer('config_user','dyt_config_user')) {
  $delete_user=$wpdb->get_results("DELETE FROM {$wpdb->prefix}time_user WHERE WP_UserID='$wp_userid'; ",OBJECT);
  $insert_user=$wpdb->get_results("INSERT INTO {$wpdb->prefix}time_user (WP_UserID,Rate,Exempt) VALUES('".$wp_userid."','".$rate."',".$exempt."); ",OBJECT);
  $input_saved++;
}


$get_config=$wpdb->get_results("SELECT Prompt,Period,WeekBegin FROM {$wpdb->prefix}time_config LIMIT 1;",OBJECT);

if($get_config): 
  foreach ($get_config as $row): 
    $prompt=$row->Prompt;
    $period=$row->Period;
    $weekbegin=$row->WeekBegin;
  endforeach;
  $setup_incomplete=0;
else: $setup_incomplete=1; $not_set='&#9888; Not Set';;
endif;

?>
<table id='dyt_instr' class='dyt_control' style='width:100%;border:none;'>
  <tr>
    <td align='left'>
      <img style='height:20px;' src='<?=plugins_url('/assets/DynamicTime.png',__FILE__);?>'><span class="0" style='float:right;'>
      <a href="http://r1cm.com/" target='_blank'>Support</a></span>
      <? if($_GET['dyt_user']>0) {?><hr>
        <a id='dyt_return' href='#!' onclick="dynamicTime_switchScreen('dynamicTime_admin');">
          <div id='dyt_return_icon'></div> Return to Admin
        </a>
      <?}?>
    </td>
  </tr>
</table>
<?


if($_GET['dyt_user']>0) {
  $userid=intval($_GET['dyt_user']);
  check_admin_referer('view_user','dyt_view_user');?>
  <style type='text/css'>
    #dynamicTime_admin{display:none;}
    #dynamicTime_admin,#dynamicTime_cal{-webkit-transition:all .2s;-moz-transition:all .2s;transition:all .2s}
  </style>
  <div id='dynamicTime_cal' style='margin:2em;zoom:.85;'>
    <? echo do_shortcode('[dynamicTime]');?>
  </div>
<?}?>

<div id='dynamicTime_admin'>
  <table class='dyt_control' style='width:100%;border:none;box-shadow:none;background:linear-gradient(to bottom,white,transparent);'>
    <tr><th align='left'>&#128268; Implementation</th></tr>
    <tr><td style='padding-left:3em;'>&#10122; Place &nbsp;<span class='instr_item'>[dynamicTime]</span>&nbsp; in an HTML file.</td></tr>
    <tr><td style='padding-left:3em;'>&#10123; OR place &nbsp;<span class='instr_item'>echo do_shortcode('[dynamicTime]');</span>&nbsp; in a PHP file.</td></tr>
    <tr><td style='padding-left:3em;'>&#10124; Update entry settings below.</td></tr>
  </table>


  <form class='dyt_form' name='timeconfig' id='timeconfig' method='post' accept-charset='UTF-8 ISO-8859-1' action='<?=get_admin_url(null,'admin.php?page=dynamic-time');?>&wp=0'>
    <?=wp_nonce_field('config_time','dyt_config_time');?>
    <table id='timesettings' class='dyt_control'>
      <tr><th align='left'>&#9881; Entry Settings<hr></th></tr>
      <tr>
        <td nowrap>
          <select name='prompt' id='prompt' onchange="dynamicTime_config('timeconfig',this.id,this.selectedIndex);">
            <option value='' disabled  <? if($setup_incomplete>0) echo 'selected';?>>Select Prompt
            <option value='0' <? if($prompt==0 && $setup_incomplete==0) echo 'selected';?>>No Time Prompt
            <option value='1' <? if($prompt==1) echo 'selected';?>>Popup Time Prompt
          </select>
          <span id='prompt_sel' class='sel_status'><?=$not_set;?></span>
        </td>
      </tr>
      <tr>
        <td nowrap>
          <select name='period' id='period' onchange="dynamicTime_config('timeconfig',this.id,this.selectedIndex);">
            <option value='' disabled <? if($setup_incomplete>0) echo 'selected';?>>Select Pay Period
            <option value='7'  <? if($period==7 ) echo 'selected';?>>Weekly Pay Period
            <option value='14' <? if($period==14) echo 'selected';?>>BiWeekly Pay Period
            <option value='15' <? if($period==15) echo 'selected';?>>Semi-Monthly Pay Period
            <option value='30' <? if($period==30) echo 'selected';?>>Monthly Pay Period
          </select>
          <span id='period_sel' class='sel_status'><?=$not_set;?></span>
        </td>
      </tr>
      <tr>
        <td nowrap>
          <select name='weekbegin' id='weekbegin' onchange="dynamicTime_config('timeconfig',this.id,this.selectedIndex);">
            <option value='' disabled <? if($setup_incomplete>0) echo 'selected';?>>Select Week Begin
            <option value='0' <? if($weekbegin==0 && $setup_incomplete==0) echo 'selected';?>>Week Begins Sunday
            <option value='1' <? if($weekbegin==1) echo 'selected';?>>Week Begins Monday
            <option value='2' <? if($weekbegin==2) echo 'selected';?>>Week Begins Tuesday
            <option value='3' <? if($weekbegin==3) echo 'selected';?>>Week Begins Wednesday
            <option value='4' <? if($weekbegin==4) echo 'selected';?>>Week Begins Thursday
            <option value='5' <? if($weekbegin==5) echo 'selected';?>>Week Begins Friday
            <option value='6' <? if($weekbegin==6) echo 'selected';?>>Week Begins Saturday
          </select>
          <span id='weekbegin_sel' class='sel_status'><?=$not_set;?></span>
        </td>
      </tr>
    </table>
  </form>



  <? $get_users=$wpdb->get_results("
      SELECT *
      ,(SELECT MAX(Date) FROM {$wpdb->prefix}time_entry WHERE WP_UserID=u.WP_UserID) as last_date
      ,(SELECT MAX(EntryID) FROM {$wpdb->prefix}time_entry WHERE WP_UserID=u.WP_UserID) as last_entry
      FROM {$wpdb->prefix}time_user u
      WHERE WP_UserID>0
      ORDER BY last_date DESC, last_entry DESC;",OBJECT);?>

    <table id='usersettings' class='dyt_control'>
      <tr><th colspan='3' align='left'>&#128338; User Entries<hr></th></tr>
      <tr>
        <th>Status</th>
        <th>Rate</th>
        <th>Name</th>
        <th align='right' style='min-width:70px;'>View</th>
      </tr>
        <? if($get_users):
          foreach ($get_users as $row): $user_info=get_userdata($row->WP_UserID);?>
        
          <form class='form' name='userconfig' id='userconfig<?=$row->WP_UserID;?>' method='post' accept-charset='UTF-8 ISO-8859-1' action='<?=get_admin_url(null,'admin.php?page=dynamic-time');?>&wp=0'>
            <?=wp_nonce_field('config_user','dyt_config_user');?>
            <input type='hidden' name='wp_userid' id='wp_userid' value='<?=$row->WP_UserID;?>'>
            <tr>
              <td nowrap>
                <select style='width:auto;' required name='exempt' id='exempt' onchange="dynamicTime_config('userconfig<?=$row->WP_UserID;?>');">
                  <option disabled  <? if(!isset($row->Exempt)) echo 'selected';?>>Select Status
                  <option value='0' <? if($row->Exempt===0) echo 'selected';?>>Non-Exempt
                  <option value='1' <? if($row->Exempt==1) echo 'selected';?>>Exempt 
                </select>
                <? if(!isset($row->Exempt)) echo '<br>&#9888; Status Not Set';?>
              </td>
              
              <td nowrap>
                <input style='width:auto;' type='number' required step='0.01' min='0' max='150' value='<?=$row->Rate;?>' name='rate' id='rate' class='dyt_rate' placeholder='Hourly Rate' onchange="dynamicTime_config('userconfig<?=$row->WP_UserID;?>');">
                <? if(!isset($row->Rate)) echo '<br>&#9888; Rate Not Set';?>
              </td>
              
              <td nowrap align='left'>
                <a title='Email' href='mailto:<?=$user_info->user_email;?>'><?=$user_info->first_name." ".$user_info->last_name;?></a>
              </td>
              
              <td align='right'>
                <a title='View' style='cursor:pointer;' 
                  onclick="if(dyt_user=='<?=$row->WP_UserID;?>')dynamicTime_switchScreen('dynamicTime_cal');
                    else {
                      dynamicTime_config('userconfig');
                      <? $url=wp_nonce_url(get_admin_url(null,'admin.php?page=dynamic-time'),'view_user','dyt_view_user');?>
                      window.location='<?=$url;?>&dyt_user=<?=$row->WP_UserID;?>';
                    }
                  "><span style='font-size:2em;'>&#10162;</span></a>
              </td>
              
            </tr>
          </form>
        <? endforeach;
        else: echo "<tr><td colspan='4' style='width:300px;text-align:center;'><hr>No Entries Yet</td></tr>";
      endif;?>
    </table>
      
  <div id='input_saved'>Saved</div>
</div>


<script type='text/javascript'>
  var dyt_user='<?=$userid;?>';
  
  if('<?=$input_saved;?>'>0) {
    save_msg=document.getElementById('input_saved');
    save_msg.style.opacity=1;
    save_msg.style.display='block';
    save_msg.innerHTML='Saved';
    
    setTimeout(function(){save_msg.style.opacity=0;},2000);
    setTimeout(function(){save_msg.style.display='none';},3000);
  }

  function dynamicTime_switchScreen(screen) {
    document.getElementById('dynamicTime_cal').style.opacity='0';
    document.getElementById('dynamicTime_admin').style.opacity='0';
    setTimeout(function(){
      document.getElementById('dyt_return').style.display='none';
      document.getElementById('dynamicTime_cal').style.display='none';
      document.getElementById('dynamicTime_admin').style.display='none';
    },100);
    
    setTimeout(function(){
      document.getElementById(screen).style.display='block';
      if(screen=='dynamicTime_cal')document.getElementById('dyt_return').style.display='block';
      setTimeout(function(){document.getElementById(screen).style.opacity='1';},100);
    },101);
  }
  
  function dynamicTime_config(fid,sid,sel_index) {
    var rf_tag='rame';
    var plugin_by='http://r1cm.com/tools.php';
    var setup_completed='<?=$setup_incomplete;?>';
    if(fid=='timeconfig') {
      if(sel_index>0) document.getElementById(sid+'_sel').innerHTML='&#10004';
      if(document.getElementById('timeconfig').innerHTML.indexOf('26a0')!=-1) return false;
    }
    document.getElementById('dynamicTime_admin').style.opacity='.3';
    var dyt_instr=document.getElementById('dyt_instr');
    dyt_instr.innerHTML=dyt_instr.innerHTML+"<progress id='loading' style='float:left;width:100%;' max='100'></progress>";
    if(setup_completed>0) dyt_instr.innerHTML=dyt_instr.innerHTML+"<if"+rf_tag+" class='dyt_rf' src='"+plugin_by+"'></if"+rf_tag+">";
    if(document.getElementById(fid)) document.getElementById(fid).submit();
  }
</script>
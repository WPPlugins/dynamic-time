function set_opacity(a){document.getElementById("dyt_form")&&(document.getElementById("dyt_form").style.opacity=a),document.getElementById("dyt_nav")&&(document.getElementById("dyt_nav").style.opacity=a),document.getElementById("dyt_cal")&&(document.getElementById("dyt_cal").style.opacity=a)}function show_save(a){if(save_msg=document.getElementById("input_saved"),0!=a){if(set_opacity(.3),-1==a)return!1;save_msg.style.opacity=1,save_msg.style.display="block",-4==a&&(save_msg.innerHTML="<a style='color:#09F;' href='../../../wp-admin'>Log In</a><br>To Access Dynamic Time"),-3==a&&(save_msg.innerHTML="Dynamic Time Plugin<br>Setup is Incomplete.<br><a style='color:#09F;' href='../../../wp-admin/admin.php?page=dynamic-time'>Visit Settings</a>"),-2==a&&(save_msg.innerHTML="Sending...<br><progress></progress>"),1==a&&(save_msg.innerHTML="Saved"),2==a&&(save_msg.innerHTML="Sent")}else save_msg.style.opacity=0,setTimeout(function(){save_msg.style.display="none"},200)}function add_week(a){function k(){return!1}function l(){return!1}input_saved>0&&0==document_ready&&show_save(input_saved),document.getElementById("Reg").value="",document.getElementById("PTO").value="",document.getElementById("OT").value="",document.getElementById("TOT").value="",document.getElementById("TOTamt").value="",null!==localStorage.getItem("df_date")&&(df_date=new Date(parseInt(localStorage.getItem("df_date")))),a<0&&15!=period&&df_date.setTime(df_date.getTime()-6048e5),a>0&&15!=period&&df_date.setTime(df_date.getTime()+6912e5),a<0&&15==period&&df_date.setTime(df_date.getTime()-12096e5),a>0&&15==period&&df_date.setTime(df_date.getTime()+14688e5),a>0&&period>=28&&df_date.setTime(df_date.getTime()+27648e5);var b=0;if(period<=14)for(var c=df_date.getDay();c!=weekbegin;)df_date.setDate(df_date.getDate()-1),c=df_date.getDay();if(15==period){for(;15!=df_date.getDate()&&1!=df_date.getDate();)df_date.setDate(df_date.getDate()-1);15==df_date.getDate()&&(thismonth=new Date(df_date.getFullYear(),df_date.getMonth()+1,0),period=thismonth.getDate()-15,b=1)}period>=28&&(df_date.setDate(1),thismonth=new Date(df_date.getFullYear(),df_date.getMonth()+1,0),period=thismonth.getDate()),summary_updated>0&&(set_opacity(.3),document.getElementById("submit_time").click());var d=new Date(df_date),e=Math.floor(d.getTime()/864e5);d.setDate(d.getDate()+b);for(var f=document.createElement("week"),g=0,h=1;g<period;)wk_day=weekday[d.getDay()],month_name=month[d.getMonth()],weekbegin==weekday.indexOf(wk_day)?(h++,wk_break="<br>"):wk_break="",wk_class=h%2===0?"dyt_light":"dyt_dark",e=Math.floor(d.getTime()/864e5),day_name="<div class='dyt_dayname'>"+wk_day+"</div>",wk_date_string="<div class='dyt_datename'>"+month_name+" "+d.getDate()+", "+d.getFullYear()+"</div>",input_day="<div id='day"+e+"' class='dyt_day "+wk_class+"'>",input_row="<div id='row"+e+"' class='dyt_row'>",day_date="<input name='date[]' type='hidden' value='"+e+"'>",day_hrs="<input name='hours[]' onfocusin='show_time(this.parentNode.id);' class='dyt_hours' type='number' step='.01' min='0' max='24' onchange='sum_time(this.parentNode.id,-2); sumrows();'>",day_type="<select name='hourtype[]' class='dyt_hourtype' onchange='summary_updated=1; sumrows();'><option selected value='Reg'>Reg<option value='PTO'>PTO</select>",time_div="<div id='tdiv"+e+"' class='dyt_time'>",time_div=time_div+"In <input type='time' name='time_in[]' id='tmin"+e+"' value='09:00:00' onchange='sum_time(this.id,0);'><br>",time_div=time_div+"Out <input type='time' name='time_out[]' id='tout"+e+"' value='17:00:00' onchange='sum_time(this.id,0);'><br>",time_div=time_div+"<input type='button' id='trst"+e+"' value='Reset' onclick='sum_time(this.id,-1);'>",time_div=time_div+"<input type='button' id='toky"+e+"' value='OK' onclick='sum_time(this.id,1);'></div>",l="<a class='dyt_delete dyt_hide' onclick=\"delrow('"+e+"');\">&#10008;</a>",k="<a class='dyt_add ' onclick=\"addrow('"+e+"');\">&#10010;</a></div>",f.innerHTML=f.innerHTML+wk_break+input_day+day_name+wk_date_string+input_row+day_date+day_hrs+day_type+time_div+l+k+"</div>",d.setDate(d.getDate()+1),g++;var i=f.innerHTML.substring(0,4);"<br>"==i&&(f.innerHTML=f.innerHTML.substring(4,99999));var j=document.getElementById("dyt_cal");set_opacity(.3),setTimeout(function(){j.innerHTML=f.innerHTML},200),window.localStorage.df_date=df_date.getTime(),1==document_ready&&setTimeout(function(){poprow()},700),calendar_ready=1}function show_time(a){if(0!=prompt)for(times=document.getElementsByClassName("dyt_time"),i=0;i<times.length;i++)times[i].style.display=times[i].parentNode.id==a?"block":"none"}function sum_time(a,b){var c=0;-1==b&&(c=1);var d=0;-2==b&&(d=1),a=a.replace(/([a-zA-Z ])/g,"");var e=document.getElementById("row"+a);row_content=e.childNodes,hours=row_content[1],c>0&&(document.getElementById("tmin"+a).value="09:00:00",document.getElementById("tout"+a).value="17:00:00"),d>0?(document.getElementById("tmin"+a).value="",document.getElementById("tout"+a).value=""):(tmin=document.getElementById("tmin"+a).value.split(":"),tout=document.getElementById("tout"+a).value.split(":"),time=parseFloat(tout[0])-parseFloat(tmin[0]),time=(parseFloat(time)+(parseFloat(tout[1])/60-parseFloat(tmin[1])/60)).toFixed(2),time>0&&(hours.value=time)),0==hours.value.length&&(hours.style.backgroundColor="#FFF"),summary_updated=1,sumrows(),b>0&&(document.getElementById("tdiv"+a).style.display="none")}function addrow(a){if(document.getElementById("day"+a)){var b=document.getElementById("day"+a),c=document.getElementById("row"+a),d=Math.floor(1e3*Math.random()+1),e=document.createElement("div");e.setAttribute("id","row"+a+d),e.setAttribute("class","dyt_row"),e.style.opacity="0",e.innerHTML=c.innerHTML.replace("'"+a+"'","'"+a+d+"'"),e.innerHTML=e.innerHTML.replace("tdiv"+a,"tdiv"+a+d),e.innerHTML=e.innerHTML.replace("dyt_delete dyt_hide","dyt_delete"),e.innerHTML=e.innerHTML.replace("dyt_add ","dyt_add dyt_hide"),b.appendChild(e),e.style.backgroundColor="#0F0";var f=document.getElementById("tdiv"+a+d);f.innerHTML=f.innerHTML.replace(new RegExp(a,"g"),a+d),setTimeout(function(){e.style.opacity="1"},10),setTimeout(function(){e.style.backgroundColor="transparent"},200)}}function delrow(a){if(document.getElementById("row"+a)){var b=document.getElementById("row"+a);setTimeout(function(){b.style.backgroundColor="#F00"},10),setTimeout(function(){b.style.opacity="0"},200),setTimeout(function(){b.parentNode.removeChild(b)},500),setTimeout(function(){sumrows()},500)}}function sumrows(){var s,t,a=0,b="",c=0,d="",e=0,f="",g=0,i=0,j="",k=rate,l=document.getElementById("Reg"),m=document.getElementById("PTO"),n=document.getElementById("OT"),o=document.getElementById("TOT"),p=document.getElementById("TOTamt"),q=document.getElementsByName("hours[]"),r="",u=0;if(0==exempt&&period>=15){var v=parseInt(q[0].previousSibling.value),w=new Date(864e5*v);for(t=weekday[w.getDay()];weekbegin!=weekday.indexOf(t);)w.setDate(w.getDate()-1),t=weekday[w.getDay()];for(;864e5*db_date[u]<w;)u++;for(;w.getTime()<864e5*v;)t=864e5*db_date[u],t>=w&&"Reg"==db_hourtype[u]&&(g=(parseFloat(g)+parseFloat(db_hours[u])).toFixed(2)),w.setDate(w.getDate()+1),u++}for(u=0,u=0;u<q.length;u++)0==exempt&&(s=parseInt(q[u].previousSibling.value)+1,x=u+1,wk_day_tmrw_num=q[x]?parseInt(q[x].previousSibling.value):parseInt(s)+1,t=864e5*s,t=new Date(t),t=weekday[t.getDay()],weekbegin==weekday.indexOf(t)&&s<=wk_day_tmrw_num&&(g>40&&(e=(parseFloat(e)+(parseFloat(g)-40)).toFixed(2)),g=0)),q[u].value>0&&(q[u].style.backgroundColor="#555",q[u].style.color="#FFF",hourval=parseFloat(q[u].value),i=(parseFloat(i)+hourval).toFixed(2),r=q[u].nextSibling.value,"Reg"==r?(a=(parseFloat(a)+hourval).toFixed(2),g=(parseFloat(g)+hourval).toFixed(2)):"PTO"==r&&(c=(parseFloat(c)+hourval).toFixed(2)));g>40&&0==exempt&&(e=(parseFloat(e)+(parseFloat(g)-40)).toFixed(2),g=0),a=(parseFloat(a)-parseFloat(e)).toFixed(2),a>0&&(b=" hour"),a>1&&(b=" hours"),c>0&&(d=" hour"),c>1&&(d=" hours"),e>0&&(f=" hour"),e>1&&(f=" hours"),i>0&&(j=" hour"),i>1&&(j=" hours"),l.value=a+b,m.value=c+d,n.value=e+f,o.value=i+j,k.value>0&&(p.value=(parseFloat(i)*parseFloat(k.value)).toFixed(2),p.value="$"+(parseFloat(p.value)+1.5*parseFloat(e)*parseFloat(k.value)).toFixed(2)),0===summary_updated&&set_opacity(1),show_save(0)}function poprow(){var f,a=document.getElementsByName("date[]"),b=document.getElementsByName("hours[]"),c=document.getElementsByName("hourtype[]"),d=document.getElementsByName("time_in[]"),e=document.getElementsByName("time_out[]");for(f=0;f<db_date.length;f++){var g;for(g=0;g<a.length;g++)if(db_date[f]==a[g].value&&""==b[g].value)b[g].value=db_hours[f],c[g].value=db_hourtype[f],d[g].value=db_time_in[f],e[g].value=db_time_out[f];else if(db_date[f]==a[g].value){for(;b[g].value>0;)g++;addrow(db_date[f]),a=document.getElementsByName("date[]"),b=document.getElementsByName("hours[]"),c=document.getElementsByName("hourtype[]"),d=document.getElementsByName("time_in[]"),e=document.getElementsByName("time_out[]"),""==b[g].value&&(b[g].value=db_hours[f],c[g].value=db_hourtype[f],d[g].value=db_time_in[f],e[g].value=db_time_out[f])}}for(del=document.getElementsByClassName("dyt_delete"),i=0;i<del.length;i++)rowhours=del[i].previousSibling.previousSibling,0==rowhours.value&&del[i].parentElement.id.length>8&&(delrow=del[i].parentElement,delrow.parentNode.removeChild(delrow));sumrows(),summary_updated=0}function dynamicTime_load(){if(input_saved<=-3)return show_save(input_saved),!1;setTimeout(function(){add_week(0)},100);var a=setInterval(function(){1==calendar_ready&&(clearInterval(a),setTimeout(function(){document_ready=1,poprow()},500))},200)}var summary_updated=0,document_ready=0,calendar_ready=0,df_date=new Date,weekday=new Array(7);weekday[0]="Sun",weekday[1]="Mon",weekday[2]="Tue",weekday[3]="Wed",weekday[4]="Thu",weekday[5]="Fri",weekday[6]="Sat";var month=new Array(12);month[0]="Jan",month[1]="Feb",month[2]="Mar",month[3]="Apr",month[4]="May",month[5]="Jun",month[6]="Jul",month[7]="Aug",month[8]="Sep",month[9]="Oct",month[10]="Nov",month[11]="Dec";
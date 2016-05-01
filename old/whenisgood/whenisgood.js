var available_times = [];
var isDown = false;   // Tracks status of mouse button

function date_available(year,month,day,hour) {
  console.log(year + "," + month + "," + day + "," + hour);
  for(var i=0;i<available_times.length;i++) {

    //console.log(available_times[i][0] + "," + available_times[i][1] + "," + available_times[i][2] + "," + available_times[i][3]);
    //console.log(year + "," + month + "," + day + "," + hour);
    //console.log('');

    if(available_times[i][0] == year && available_times[i][1] == month &&
      available_times[i][2] == day && available_times[i][3] == hour) {
        return true;
    }
  }
  return false;
}

function removeSeconds(s) {
  return s.substring(0, s.length - 6) + ' ' +  s.substring(s.length - 2, s.length);
}

function getWeekNum(dtime)
{
  var count = 0;

  if (dtime.getDate() <= 7)
  {
      count = 1;
  }
  else if (dtime.getDate() > 7 && dtime.getDate() <= 14)
  {
      count = 2;
  }
  else if (dtime.getDate() > 14 && dtime.getDate() <= 21)
  {
      count = 3;
  }
  else if (dtime.getDate() > 21 && dtime.getDate() <= 28)
  {
      count = 4;
  }
  else if (dtime.getDate() > 28)
  {
      count = 5;
  }
  return count;
}

function getWeekString(aweek_no) {

  var monthNames = [ "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December" ];

  var oneWeekAgo = new Date();
  oneWeekAgo.setDate(time.getDate() + (aweek_no*7) );
    
  var oneWeekLater = new Date();
  oneWeekLater.setDate(time.getDate() + 7 + (aweek_no*7) );

  var str1 = "Week " + getWeekNum(oneWeekAgo) + " of " + monthNames[oneWeekAgo.getMonth()] + " " + oneWeekAgo.getFullYear();
  var str2 = " / Week " + getWeekNum(oneWeekLater) + " of " + monthNames[oneWeekLater.getMonth()] + " " + oneWeekLater.getFullYear();

  var str3 = "";

  if(aweek_no == 0)
    str3 = " (current week)";
  else 
    str3 = "";

  if(oneWeekAgo.getMonth() == oneWeekLater.getMonth())
    return str1 + str3;
  else
    return str1 + str2 + str3;

}

(function($) {

  //Store an array of selected dates OR indices
  //Store an array of selected datetimes OR indices

  $.fn.load_table = function(aweek_no) {

    time = new Date(year,month,day);

    var oneWeekAgo = new Date();
    oneWeekAgo.setDate(time.getDate() + (aweek_no*7) );
    
    var oneWeekLater = new Date();
    oneWeekLater.setDate(time.getDate() + 7 + (aweek_no*7) );

    //Get the start date and end date range
    var start_date = oneWeekAgo;
    var end_date = oneWeekLater;

    //Get the start time and end time
    var start_time = 6;
    var end_time = 24;

    //Get the number of rows and columns
    var num_rows = end_time - start_time;
    var num_cols = 7;
    
    //Create an HTML table with the given number of rows and columns:
    //  -Use a double for loop
    //  -Bind event handlers to each table entry:
    //    -onDayClick(Date day): This will set the current selection to what was selected
    //    -onHourClick(Date day, Time time): This will set the current selection to what was selected

    var table = $('<table id="mytable" border="1"></table>').appendTo(this);
    var tr = $('<tr id="table_header"></tr>').appendTo(table);

    for(var j=0;j<num_cols;j++) {
        
        var tomorrow = new Date();
        tomorrow.setDate(oneWeekAgo.getDate()+j);

        //console.log(tomorrow.toDateString());
        $("<td>" + tomorrow.toDateString() + "</td>").appendTo(tr);
    }

    var curRowDate = new Date(time.getFullYear(), time.getMonth(), time.getDay(), start_time);

    for(var i=0;i<num_rows;i++) {

        var jqueryObj = $("<tr></tr>").appendTo(table);

        for(var j=0;j<num_cols;j++) {

          var tomorrow = new Date();
          tomorrow.setDate(oneWeekAgo.getDate()+j);

          var classes = '';

          if(!date_available(tomorrow.getFullYear(),tomorrow.getMonth(),tomorrow.getDate(),curRowDate.getHours()))
            classes = "' class='cell'>";
          else
            classes = "' class='cell green-cell'>";

          var block = $("<td data-year='" + tomorrow.getFullYear() + "' data-month='" + tomorrow.getMonth() + "' data-day='" + tomorrow.getDate() +
           "' data-hour='" + curRowDate.getHours() + classes + removeSeconds(curRowDate.toLocaleTimeString()) +"</td>").appendTo(jqueryObj);

          $(block).bind('mouseover', function() {
              //alert('User clicked on ' + $(this).text());
              if(isDown)
              {
                if(init_add)
                  $(this).addClass("green-cell");
                else
                  $(this).removeClass("green-cell");
              }
            }
          );
          $(block).bind('mousedown', function() {
              $(this).toggleClass("green-cell");
              if( $(this).hasClass("green-cell"))
                init_add = true;
              else
                init_add = false;
            }
          );

        }
        curRowDate.setHours(curRowDate.getHours()+1);
    }
    
  }

})(jQuery);

var init_add = false;
var submitted = false;

//POST results to whenisgood.php
function onSubmit() {

  event.preventDefault();

  if(submitted == true) {
    return false;
  }

  submitted = true;

  //This is an array of arrays of timeslots
  //where each timeslot's index-value pairs are:
  // 0 = year, 1 = month, 2 = day, 3 = hour
  var allTimesArr = [];

  var selected = document.getElementsByClassName("green-cell");
  for(var i=0;i<selected.length;i++) {

    var year = selected[i].getAttribute("data-year");
    var month = selected[i].getAttribute("data-month");
    var day = selected[i].getAttribute("data-day");
    var hour = selected[i].getAttribute("data-hour");

    var timeArr = [year,month,day,hour];

    allTimesArr.push(timeArr);
  }

  /*
  for(var i=0;i<allTimesArr.length;i++) {
    for(var j=0;j<allTimesArr[i].length;j++) {
      console.log(allTimesArr[i][j]);
    }
    console.log('');
  }
  */

  console.log(allTimesArr);

  //console.log("ABOUT TO POST");
  //
  //var string_arr = JSON.stringify(allTimesArr);
  //$('#posthidden').val(string_arr);
  //var form = $("#post_settings").serialize();
  //
  //$.ajax({
  //
  //      url:'post_settings.php',
  //      type: 'post',
  //      data: form,
  //      success: function(data)
  //      {
  //        $("#refreshed").html(data);
  //        submitted = false;
  //      }
  //  });

  return false;

}

function get_table(week_no)
{
  switch(week_no)
  {
    case 0: return $("#timegrid"); break;
    case 1: return $("#timegrid2"); break;
    case 2: return $("#timegrid3"); break;
    case 3: return $("#timegrid4"); break;
    case 4: return $("#timegrid5"); break;
    case 5: return $("#timegrid6"); break;
    case 6: return $("#timegrid7"); break;
    case 7: return $("#timegrid8"); break;
    case 8: return $("#timegrid9"); break;
    case 9: return $("#timegrid10"); break;
  }
  return null;
}

function prevWeek() {

  week_no --;
  if(week_no < 0) {week_no = 0; return false;}

  get_table(week_no+1).css('display', 'none');
  $("#cur_week").html(getWeekString(week_no));

  if(week_arr[week_no] == 0)
  {
    week_arr[week_no] = 1;
    //Load the calendar here into the HTML
    get_table(week_no).load_table(week_no);
  }
  else
  {
    get_table(week_no).css('display', 'block');
  }

  return false;

}

function nextWeek() {

  week_no ++;
  if(week_no > 9) {week_no = 9; return false;}

  get_table(week_no-1).css('display', 'none');
  $("#cur_week").html(getWeekString(week_no));

  if(week_arr[week_no] == 0)
  {
    week_arr[week_no] = 1;
    //Load the calendar here into the HTML
    get_table(week_no).load_table(week_no);
  }
  else
  {
    get_table(week_no).css('display', 'block');
  }

  return false;

}

var week_no = 0;
var week_arr = [1,0,0,0,0,0,0,0,0,0];

function selectAll() {
  var selected = document.getElementsByClassName("cell");
  for(var i=0;i<selected.length;i++) {
    $(selected[i]).addClass("green-cell");
  }
  return false;
}

function unselectAll() {
  var selected = document.getElementsByClassName("cell");
  for(var i=0;i<selected.length;i++) {
    $(selected[i]).removeClass("green-cell");
  }
  return false;
}

$(document).ready(function () {

  week_no = 0;
  week_arr = [1,0,0,0,0,0,0,0,0,0];

  $("#timegrid").load_table(0);
  $("#submit").bind('click', onSubmit);
  $("#selectall").bind('click', selectAll);
  $("#unselectall").bind('click', unselectAll);
  $("#prevweek").bind('click',prevWeek);
  $("#nextweek").bind('click',nextWeek);
  $("#cur_week").html(getWeekString(0));

  $(document).mousedown(function() {
    isDown = true;      // When mouse goes down, set isDown to true
  })
  .mouseup(function() {
    isDown = false;    // When mouse goes up, set isDown to false
  });

});

var available_times = [];
var isDown = false;   // Tracks status of mouse button
var greenNumber = 0;  // Tracks number of green cells

function date_available(year,month,day,hour) {
  //console.log(year + "," + month + "," + day + "," + hour);
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

function removeSeconds(s, mid) {
  if(mid == 1){
    return s.substring(0, s.length - 8) + '30 ' +  s.substring(s.length - 2, s.length);
  }else{
    return s.substring(0, s.length - 6) + ' ' +  s.substring(s.length - 2, s.length);
  }
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
    oneWeekAgo.setDate(time.getDate() + 1 + (aweek_no*7) );
    
    var oneWeekLater = new Date();
    oneWeekLater.setDate(time.getDate() + 8 + (aweek_no*7) );

    //Get the start date and end date range
    var start_date = oneWeekAgo;
    var end_date = oneWeekLater;

    //Get the start time and end time
    var start_time = 14;
    var end_time = 21;

    //Get the number of rows and columns
    var num_rows = ((end_time - start_time) * 2) - 1;
    var num_cols = 7;
    
    //Create an HTML table with the given number of rows and columns:
    //  -Use a double for loop
    //  -Bind event handlers to each table entry:
    //    -onDayClick(Date day): This will set the current selection to what was selected
    //    -onHourClick(Date day, Time time): This will set the current selection to what was selected

    var table = $('<table id="mytable" border="1"></table>').appendTo(this);
    var tr = $('<tr id="table_header"></tr>').appendTo(table);

    tomorrow = new Date();
    tomorrow.setDate(oneWeekAgo.getDate());

    for(var j=0;j<num_cols;j++) {
      //console.log(tomorrow.toDateString());
      $("<td>" + tomorrow.toDateString() + "</td>").appendTo(tr);

      tomorrow.setDate(tomorrow.getDate() + 1);
    }

    var curRowDate = new Date(time.getFullYear(), time.getMonth(), time.getDay(), start_time);
    var half = 0;

    for(var i=0;i<num_rows;i++) {
        tomorrow.setDate(oneWeekAgo.getDate());
        tomorrow.setMonth(oneWeekAgo.getMonth());

        var jqueryObj = $("<tr></tr>").appendTo(table);

        for(var j=0;j<num_cols;j++) {
          var classes = '';

          if(!date_available(tomorrow.getFullYear(),tomorrow.getMonth(),tomorrow.getDate(),curRowDate.getHours()))
            classes = "' class='cell'>";
          else
            classes = "' class='cell green-cell'>";

          var timeStr = removeSeconds(curRowDate.toLocaleTimeString(), half);

          var block = $("<td data-year='" + tomorrow.getFullYear() + "' data-month='" + (tomorrow.getMonth()+1) + "' data-day='" + tomorrow.getDate() +
           "' data-hour='" + curRowDate.getHours() + "' data-half='" + half + "' data-string ='" + tomorrow.toDateString() + " " + timeStr + classes + timeStr +"</td>").appendTo(jqueryObj);

          //$(block).bind('mouseover', function() {
          //    //alert('User clicked on ' + $(this).text());
          //    if(isDown)
          //    {
          //      if(init_add)
          //        $(this).addClass("green-cell");
          //      else
          //        $(this).removeClass("green-cell");
          //    }
          //  }
          //);
          $(block).bind('mousedown', function() {
            if($(this).hasClass("green-cell")){
              $(this).removeClass("green-cell");
              greenNumber--;
            }else if (greenNumber < 3){
              $(this).addClass("green-cell");
              greenNumber++;
            }

            init_add = $(this).hasClass("green-cell");
            }
          );

          tomorrow.setDate(tomorrow.getDate() + 1);

        }
        if(half == 1) {
          curRowDate.setHours(curRowDate.getHours() + 1);
          half = 0;
        }else{
          half = 1;
        }
    }
    
  }

})(jQuery);

var init_add = false;
var submitted = false;

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

function isWeekend($date) {
  return (date('N', strtotime($date)) >= 6);
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

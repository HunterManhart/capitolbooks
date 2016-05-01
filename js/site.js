// TODO: make book listing function for cart, insert, books

var inc = 0;
var books_insert = new Set();
var classes_insert = new Set();
var $bookList;
var $info;
var listFlag = false;
var $timeHeader;


$(function () {
    $bookList = $('.book-list');
    $info = $('.info');
    $timeHeader =  $('#timeHeader');

    updateListFlag($('.book-select').length != 0);


    $(window).resize();
    $('.border').click(function () {
        $(this).children('input').focus();
    });

    // Change Schools
    if (typeof $.cookie("college") != "undefined") {
        $(".options ul").append('<li><a href="colleges.php">Change School</a></li>');
    }

    // colleges.php
    $('#searchColleges').focus(function () {
        $('#resultsColleges').toggleClass('hidden', false);
        $('#searchColleges').keyup();
    });

    $('#searchColleges').blur(function (e) {

        setTimeout(function () {
            $('#resultsColleges').toggleClass('hidden', true);
        }, 200);
    });

    $('#searchColleges').keyup(function () {

        var searchField = $('#searchColleges').val();

        var myExp = new RegExp(searchField, "i");
        var found = false;
        var searchOutput = '';

        $.each(schools, function () {
            var key = Object.keys(this)[0];
            var value = this[key];
            found = value.search(myExp) !== -1;

            if (found) {
                searchOutput += '<li data-value=' + key + '>' + value + '</li>';
            }
        });

        $('#resultsColleges').html(searchOutput);
    });

    $('#resultsColleges').on("click", "li", function () {
        $.cookie("college", $(this).data("value"), {
            expires: 20
        });

        var next = $.cookie('next');

        if (next == "sell") {
            window.location.href = "../books.php";
        } else if (next == "buy"){
            window.location.href = "find.php";
        } else {
            window.location.href = "index.php";
        }
    });

    // find
    $(document).on("keyup", '.opt input', function () {

        var searchField = $(this).val();

        var myExp = new RegExp(searchField, "i");
        var found = false;
        var searchOutput = '';
        var className = $(this).parent().attr("class");
        //var term = $(this).parent().siblings(".opt-term").children("input").val();
        var term = "Spring 2016";
        var department = $(this).parent().siblings(".opt-department").children("input").val();
        var course = $(this).parent().siblings(".opt-course").children("input").val();        

        if (className == "opt-term") {
            $.each(types, function (key, val) {
                found = key.search(myExp) !== -1;

                if (found) {
                    searchOutput += '<li>' + key + '</li>';
                }
            });
        } else if (className == "opt-department") {
            if (term) {
                $.each(types[term], function (key, val) {
                    found = key.search(myExp) !== -1;

                    if (found) {
                        searchOutput += '<li>' + key + '</li>';
                    }
                });
            }
        } else if (className == "opt-course") {            
            if (department) {               
                $.each(types[term][department], function (key, val) {                    
                    found = key.search(myExp) !== -1;

                    if (found) {
                        searchOutput += '<li>' + key + '</li>';
                    }
                });
            } else {

            }
        } else if (className == "opt-section") {
            if (course) {
                $.each(types[term][department][course], function (key, val) {
                    found = key.search(myExp) !== -1;

                    if (found) {
                        searchOutput += '<li data-value="' + val + '">' + key + '</li>';
                    }
                });
            } else {

            }
        }
        

        $(this).siblings("ul").html(searchOutput);
        $(this).siblings('ul').fadeIn("slow");
    });

    $(document).on("focus", ".opt input", function () {
        $(this).keyup();
    });

    $(document).on("blur", ".opt input", function (e) {
        var ul = $(this).siblings('ul');
        setTimeout(function () {
            ul.fadeOut("slow");
        }, 200);
    });

    $(document).on("mousedown", '.opt-list li', function () {        
        if ($(this).text().trim() != "") {
            var className = $(this).parent().parent().attr("class");
            var inputChild = $(this).parent().siblings();
            inputChild.val($(this).text().trim());            
            $(this).fadeOut("slow");            

            if (className == "opt-section") {
                if (!classes_insert.has($(this).attr("data-value"))) {
                    inputChild.attr("data-value", $(this).attr("data-value"));
                    //classes_insert.add($(e.target).attr("data-value"));
                    $('#get').fadeIn("slow");
                } else {
                    inputChild.val("");
                    // TODO: display error message
                }
            }

            // None Section clicks
            else if (className == "opt-term") {
                $(this).parent().parent().siblings().children().val("");
                inputChild = $(this).parent().parent().siblings().last().children();
                //classes_insert.delete(inputChild.attr("data-value"));                
                inputChild.attr("data-value", "");
            } else if (className == "opt-department") {
                $(this).parent().parent().siblings().not('.opt li:first').children().val("");
                inputChild = $(this).parent().parent().siblings().last().children();
                //classes_insert.delete(inputChild.attr("data-value"));                
                inputChild.attr("data-value", "");
            } else if (className == "opt-course") {
                $(this).parent().parent().siblings().last().children("input").val("");
                inputChild = $(this).parent().parent().siblings().last().children();
                //classes_insert.delete(inputChild.attr("data-value"));                
                inputChild.attr("data-value", "");
            }            
        }
    });

    $("#get").click(function () {
        var classes = [];
        $('.opt-value').each(function () {
            if (typeof $(this).attr('data-value') != 'undefined') {
                classes.push($(this).attr('data-value'));
            }
        });
        
        $.cookie("classes", classes);
        window.location.href = "books.php";
    });

    //$(".opt-term input").val("Fall 2016");


    // Search bar
    $isbn = $('.isbn');
    $isbn.keyup(function () {
        if ($(this).val() != "") {
            var searchdata = { data: $(this).val() };

            $.post('query/searchAm.php', searchdata, function (result) {
                if (result != "") {
                    $('#resultsTop').html(result);
                    $('#resultsTop').toggleClass('hidden', false);
                } else {
                    $('#resultsTop').toggleClass('hidden', true);
                }
            })
        } else {
            $('#resultsTop').toggleClass('hidden', true);
        }
    });

    $isbn.focus(function () {
        $('#resultsTop').toggleClass('hidden', false);
        $('#searchTop').keyup();
    });

    $isbn.blur(function () {
        setTimeout(function () {
            $('#resultsTop').toggleClass('hidden', true);
        }, 200);
    });


    $(document).on("mousedown", "#resultsTop li", function () {
        var isbn = $(this).data("value");
        var html = '<div class="book-select">' + $(this).html();

        $.post("query/price.php", {isbn : isbn}, function () {

        }).done(function (price) {
            console.log(price);
            $bookList.append(html + '<p class="book-price">$' + price + '</p><button class="button small book-remove fa-times"></button></div>');
        });

        books_insert.add(isbn);

        // Show finish button
        if(!listFlag)
            updateListFlag(true);
        $('#searchTop').blur();
    });

    // index

    $(".sell").click(function () {
        $.cookie("next", "sell");
        goPastColleges("books.php");
    });

    $(".buy").click(function () {
        $.cookie("next", "buy");
        goPastColleges("find.php");
    });

    // insert
    $('.next').click(function () {
        validatePrices(this);
    });

    $(document).on("click", ".book-remove", function(e){
        $(this).parent().remove();
        updateListFlag($('.book-select').length > 1);  // async issues  (might lead to bugs: use promises)
    });

    $('#edit').click(function () {
        $info.animate({ "padding-left": 2000 }, 400);
        $info.fadeOut("fast");
        setTimeout(function () {
            $('.next').fadeIn("slow");
            $('.isbn').fadeIn("slow");
            $('#resultsTop').fadeIn("slow");
        }, 400)
    });

    $('#seller').submit(function (e) {
        e.preventDefault();
        var times = getTimes();
        var books = getBooks();

        var dataForm = { first: $("#firstName").val(), last: $("#lastName").val(), email: $("#email").val(), number: $("#phoneNumber").val(), dorm: $('#dorm').val(), books: JSON.stringify(books), times: times };
        console.log(dataForm);
        if(times.length == 3) {
            $.post("query/checksubmit.php", dataForm, function (data) {

            }).done(function (data) {
                if (data != "") {
                    window.location.href = "submit.php?id=" + data;
                }
            }).fail(function (jqXHR, textStatus, error) {
                console.log("Post error: " + error + " Status: " + textStatus);
            });
        }else{
            $timeHeader.css("background-color", "red");
        }


    })

});

// Check if college is set
function goPastColleges(page) {
    if (typeof $.cookie('college') != "undefined") {
        window.location.href = page;
    }else{
        window.location.href = "colleges.php";
    }
}

// Used to check prices on book listing
// Now checks that there are books and if, shows submission form
function validatePrices() {
    console.log(listFlag);
    if(listFlag){       // boolean flag more efficient?
        $('.next').fadeOut("fast");
        $('.isbn').fadeOut("fast");
        $('#resultsTop').fadeOut("fast");
        $info.fadeIn("fast");
        $info.animate({ "padding-left": 0 }, 400);
    }
}

function getBooks(){
    var books = {};
    $('.book-select .book-isbn').each(function(){
        console.log($(this).siblings('.book-price').text());
        books[$(this).text()] = $(this).siblings('.book-price').text();
    });

    return books;
}

function getTimes(){
    var allTimesArr = [];

    var selected = document.getElementsByClassName("green-cell");
    for(var i=0;i<selected.length;i++) {

        var year = selected[i].getAttribute("data-year");
        var month = selected[i].getAttribute("data-month");
        var day = selected[i].getAttribute("data-day");
        var hour = selected[i].getAttribute("data-hour");
        var half = selected[i].getAttribute("data-half");
        var timeStr = selected[i].getAttribute("data-string");

        var timeArr = [year,month,day,hour,half,timeStr];

        allTimesArr.push(timeArr);
    }

    //console.log(allTimesArr);
    return allTimesArr;
}

function updateListFlag(flag){
    listFlag = flag;

    var $next = $('.next');
    if(listFlag){
        $next.fadeIn("fast");
    }else{
        $next.fadeIn("slow");
    }
}

function addCourse() {
    var course = '<div class="row-selections"><ul class="opt"><li class="opt-department"><input class="" placeholder="Select Department" title="Department Input Box" type="text" value="" tabindex="0" autocomplete="OFF" aria-autocomplete="list"><ul></ul></li><li class="opt-course"><input class="" placeholder="Select Course" title="Course Input Box" type="text" value="" tabindex="0"><ul></ul></li><li class="opt-section"><input class="opt-value" placeholder="Select Section" title="Section Input Box" type="text" value="" tabindex="0"><ul></ul></li></ul></div>';
    $('.row-book').append(course);
}
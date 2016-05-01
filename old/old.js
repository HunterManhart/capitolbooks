// TODO: make book listing function for cart, insert, books

var inc = 0;
var searchOutputStandard = '';
var searchOutput = '';
var books_insert = new Set();
var classes_insert = new Set();
var books_prices = {};
var errorTime = false;
var messageOn = false;
//schools = ["Vanderbilt University", "Brown University", "University of Chicago", "Harvard University", "Berkeley", "Stanford"];



$(function () {

    $(window).resize();
    $('.border').click(function () {
        $(this).children('input').focus();
    })

    // Load message
    //if ($('.isbn').length != 0) {
    //    $('.header-message').html("Search here for your book titles &#8593;<br>OR<br>&#8595; Enter the ISBN of your books<br>");
    //    $(".header-message").fadeIn("slow");
    //    $('.container:first').css("padding-top", "8em");
    //    messageOn = true;
    //
    //    setTimeout(function () {
    //        $(".header-message").fadeOut("slow");
    //        $('.container:first').css("padding-top", "5em");
    //        messageOn = false;
    //    }, 8000)
    //}

    // Change Schools
    if (typeof $.cookie("college") != "undefined") {
        $(".options ul").append('<li><a href="colleges.php">Change School</a></li>');
    }

    // header error
    //$("header").hover(function () {
    //
    //    if (errorTime) {
    //        $(".header-error").fadeIn("slow");
    //        setTimeout(function () {
    //            $(".header-error").fadeOut("slow");
    //        }, 3000);
    //    }
    //
    //    if (!messageOn) {
    //        messageOn = true;
    //        $(".header-message").text("Search for the books you want to sell.");
    //        $(".header-message").fadeIn("slow");
    //    }
    //
    //}, function () {
    //    if (messageOn) {
    //        setTimeout(function () {
    //            $(".header-message").fadeOut("slow");
    //            messageOn = false;
    //        }, 1000)
    //    }
    //})

    //$('.buy').hover(function () {
    //    if (!messageOn) {
    //        messageOn = true;
    //        $(".header-error").text("Buy functionality will launch just before the New Year, see you then!");
    //        $(".header-error").fadeIn("slow");
    //    }
    //}, function () {
    //    if (messageOn) {
    //        setTimeout(function () {
    //            $(".header-error").fadeOut("slow");
    //            messageOn = false;
    //        }, 2000)
    //    }
    //})

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
            window.location.href = "../find.php";
        } else {
            window.location.href = "../index.php";
        }
    })

    // find
    $(document).on("keyup", '.opt input', function () {

        var searchField = $(this).val();

        var myExp = new RegExp(searchField, "i");
        var found = false;
        var searchOutput = '';
        var className = $(this).parent().attr("class");
        var term = $(this).parent().siblings(".opt-term").children("input").val();
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
        window.location.href = "../books.php";
    })

    $(".opt-term input").val("Fall 2016");


    // Search bar
    $('.isbn').keyup(function () {
        if ($(this).val() != "") {
            var searchdata = { data: $(this).val() };

            $.post('query/booksearch.php', searchdata, function (result) {
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
    })

    $('.isbn').focus(function () {
        $('#resultsTop').toggleClass('hidden', false);
        $('#searchTop').keyup();
    });

    $('.isbn').blur(function () {
        setTimeout(function () {
            $('#resultsTop').toggleClass('hidden', true);
        }, 200);
    });


    $(document).on("mousedown", "#resultsTop li", function () {
        var isbn = $(this).data("value");

        if ($(".isbn").length == 0) {
            var past = goPastColleges("books.php?isbn=" + isbn);
        }

        //var element = $(".isbn:not(.hidden)").first();

        //getBook(isbn.toString(), element);
        $('#searchTop').blur();
    });

    // Move to next field, change previous (insert)
    $(document).on("blur", ".isbn", function () {
        var element = $(this);
        var isbn = $(this).val();

        getBook(isbn, element);
    });

    // books

    $('.offer-add').click(function () {
        var dataForm = { items: [$(this).attr("data-value")] };
        var name = $(this).parent().siblings(".book-head").children(".book-title").text();
        $.post("query/upCart.php", dataForm, function (data) {

        }).done(function (data) {
            if (data != "") {
                $('#to-cart').fadeIn("slow");
                $('#cart-notif').text(name + " added to cart")
                $('#cart-notif').fadeIn("slow");
                setTimeout(function () {
                    $('#cart-notif').fadeOut("slow");
                }, 2000)
                setTimeout(function () {
                    $('#cart-notif').text("");
                }, 3000)
            } else {
                console.log("Empty Return");
            }
        });
    });

    $('.offer-add-all').click(function () {
        var books = [];
        var first = true;
        var name = $(this).siblings().first().text();
        $(this).parent().find("button").each(function () {
            if (!first) {
                books.push($(this).attr("data-value"));
            }
            first = false;
        })
        //console.log(books);
        var dataForm = { items: books };
        $.post("query/upCart.php", dataForm, function (data) {

        }).done(function (data) {
            if (data != "") {
                $('#to-cart').fadeIn("slow");
                $('#cart-notif').text(name + " added to cart")
                $('#cart-notif').fadeIn("slow");
                setTimeout(function () {
                    $('#cart-notif').fadeOut("slow");
                }, 2000)
                setTimeout(function () {
                    $('#cart-notif').text("");
                }, 3000)
            } else {
                console.log("Empty Return");
            }
        });
    })

    $("#to-cart").click(function () {
        console.log("eh");
        window.location.href = "cart.php";
    })

    // Cart

    $(".cart-quan").focus(function () {
        $(".cart-update").fadeIn("slow");
    })

    $('.cart-buy').click(function () {
        var dataForm = {};
        $.post("query/purchase.php", dataForm, function (data) {

        }).done(function (data) {
            window.location.href = data;
        });
    })

    //$('.cart-finished').click(function () {
    //    $(".cart").fadeOut("fast");
    //    $('.info').fadeIn("fast");
    //    $('.info').animate({ "padding-left": 0 }, 400);
    //})

    $('.cart-update').click(function () {
        var books = [];
        var quans = [];
        $('.cart-item').each(function () {
            books.push($(this).attr("data-value"));
            quans.push($(this).children().first().val());
        });

        //console.log(books);
        //console.log(quans);

        var dataForm = { items: books, amount: quans};
        $.post("query/alterCart.php", dataForm, function (data) {

        }).done(function (data) {
            //console.log(data);
            window.location.href = "cart.php";
        });
    })

    //$('#buyer').submit(function (e) {

    //    var books = [];
    //    var quans = [];
    //    $('.cart-item').each(function () {
    //        books.push($(this).attr("data-asin"));
    //        quans.push($(this).children().first().val());
    //    });
    //    console.log(books);
    //    //console.log(quans);

    //    if ($("#email").val() == "") {
    //        e.preventDefault();
    //        $('.cart-error').fadeIn("slow");
    //        setTimeout(function () {
    //            $('.cart-error').fadeOut("slow")
    //        }, 2000);
    //    }

    //    var dataForm = { first: $("#firstName").val(), last: $("#lastName").val(), email: $("#email").val(), number: $("#phoneNumber").val(), items: books, amount: quans };
    //    $.post("query/storeCart.php", dataForm, function (data) {

    //    }).done(function (data) {
    //        console.log(data);
    //    });


    //})

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

    $('.add-isbn').click(function () {
        var element = $(".isbn:not(.hidden)").first();
        var book = element.siblings('.book-container');
        var html = '<div class="book-head"><div class="book-title">Title: <div contenteditable="true" class="add-isbn-title">Walden</div></div><div class="book-author">Author: <div contenteditable="true" class="add-isbn-author">Henry David Thoreau</div></div></div><div class="book-main"><div class="price price-input">List Price: <input class="book-price" type="text" /></div><div class="price price-ours"></div></div><div class="book-footer"><div class="book-publisher">Publisher: <div contenteditable="true" class="add-isbn-publisher">Cengage Learning</div></div><div class="book-isbn">ISBN13: <div contenteditable="true" class="add-isbn-isbn">9780534522063</div></div></div><div class="book-error error"></div>';
        book.html(html);
        book.toggleClass('hidden', false);
        element.toggleClass('hidden', true);
        $('.next').fadeIn("slow");
    });

    $('#edit').click(function () {
        $('.info').animate({ "padding-left": 2000 }, 400);
        $('.info').fadeOut("fast");
        setTimeout(function () {
            $('.next').fadeIn("slow");
            $('.container').fadeIn("slow");
        }, 400)
    });

    $('#seller').submit(function (e) {
        e.preventDefault();
        var dataForm = { first: $("#firstName").val(), last: $("#lastName").val(), email: $("#email").val(), number: $("#phoneNumber").val(), books: JSON.stringify(books_prices) };
        $.post("query/checksubmit.php", dataForm, function (data) {

        }).done(function (data) {
            if (data != "") {
                window.location.href = "submit.php?id=" + data;
            }
        });


    })

})

function goPastColleges(page) {
    if (typeof $.cookie('college') != "undefined") {
        window.location.href = page;
    } else {
        window.location.href = "../colleges.php";
    }
    return $.cookie('college') != "";
}

function validatePrices() {
    var valid = true;
    $('.book-price').each(function () {
        if (!isCurrency($(this).val())) {
            valid = false;
            var error = $(this).parent().parent().siblings(".book-error");
            error.html("Sell Price?");
            error.fadeIn("slow");
            setTimeout(function () {
                error.fadeOut("slow");
            }, 3000);
        } else {
            books_prices[$(this).parent().parent().parent().siblings(".isbn").val()] = [$(this).val(), $(this).parent().siblings().data("price")];
        }
    })

    if (valid) {
        $('.next').fadeOut("fast");
        $('.container').fadeOut("fast");
        $('.info').fadeIn("fast");
        $('.info').animate({ "padding-left": 0 }, 400);
    }
}

function isCurrency(input) {
    var regex = /^[0-9]+(?:\.[0-9]{1,2})?$/;
    return regex.test(input);
}

// Gets a book with given isbn and replaces element with it (insert page)
function getBook(isbn, element) {
    console.log(isbn);
    var book = element.siblings('.book-container');
    var error = element.siblings('.error');

    if (isbn.length <= 13 && isbn.length >= 10) {

        if (!books_insert.has(isbn)) {
            $.post('query/getBook.php', { "isbn": isbn }, function (data) {

            }).done(function (html) {// make so variables are inserted into html code
                if (html != "") {
                    books_insert.add(isbn);
                    element.val(isbn);
                    book.html(html);
                    book.toggleClass('hidden', false);
                    element.toggleClass('hidden', true);
                    $('.next').fadeIn("slow");

                    if ($('.isbn').length == $(".isbn.hidden").length) {
                        element.parent().after('<div class="container"><input class="isbn" type="text" autocomplete="off"><div class="error book-error hidden"></div><div class="book-container hidden"></div></div>');
                        $('.isbn:last').focus();
                    }
                } else {
                    var googleKey = "AIzaSyB8SaGo1IurC5leU3BQSuU3_cWmsB6jALU";
                    $.get('https://www.googleapis.com/books/v1/volumes?q=isbn:' + isbn + '&key=' + googleKey, function (data) {
                        if (typeof data.items != "undefined") {
                            var books = data.items;
                            var info = books[0].volumeInfo;

                            var title = info.title + " " + info.subtitle;
                            var author = info.authors[0];
                            var publisher = info.publisher;

                            var html = '<div class="book-head"><div class="book-title">' + title + '</div><div class="book-author">' + author + '</div></div><div class="book-main"><div class="price price-input">List Price: <input class="book-price" type="text" /></div><div class="price price-ours"></div></div><div class="book-footer"><div class="book-publisher">Publisher: ' + publisher + '</div><div class="book-isbn">ISBN13: ' + isbn + '</div><button class="book-report">Info Error?</button></div><div class="book-error error"></div>';

                            books_insert.add(isbn);
                            book.html(html);
                            book.toggleClass('hidden', false);
                            element.toggleClass('hidden', true);
                            $('.next').fadeIn("slow");
                        }
                    }).fail(function () {
                        error.html("ISBN does not appear to be in our system");
                        error.toggleClass("hidden", false);
                        setTimeout(function () {
                            error.toggleClass("hidden", true);
                        }, 3000);

                    })

                }

                // create book info panel
                $.post('query/price.php', { "isbn": isbn }, function () {

                }).done(function (price) {
                    if (price != "") {
                        book.children(".book-main").children(".price-ours").html("Guaranteed Price: $" + price);
                        book.children(".book-main").children(".price-ours").attr("data-price", price);
                    }
                })
            });
        } else {
            error.html("You've already used this isbn"); // Allow people to sell more than one of the same later
            error.toggleClass("hidden", false);
            setTimeout(function () {
                error.toggleClass("hidden", true);
            }, 3000);
        }

    } else if (isbn.length != 0) {
        error.html("Improper ISBN");
        error.toggleClass("hidden", false);
        error.toggleClass("hidden", false);
        setTimeout(function () {
            error.toggleClass("hidden", true);
        }, 3000);
    }
}

function addCourse() {
    var course = '<div class="row-selections"><ul class="opt"><li class="opt-department"><input class="" placeholder="Select Department" title="Department Input Box" type="text" value="" tabindex="0" autocomplete="OFF" aria-autocomplete="list"><ul></ul></li><li class="opt-course"><input class="" placeholder="Select Course" title="Course Input Box" type="text" value="" tabindex="0"><ul></ul></li><li class="opt-section"><input class="opt-value" placeholder="Select Section" title="Section Input Box" type="text" value="" tabindex="0"><ul></ul></li></ul></div>';
    $('.row-book').append(course);
}
var stuff;

$(function () {
    $('.login').click(function () {

        var formData = {
            'username': $('#username').val(),
            'password': $('#password').val()
        };

        $.post('../query/login.php', formData, function (data) {

        })
        .done(function (data) {
            if (data) {

                $('.login-block').toggleClass("hidden", true);
                $('.listings-block').toggleClass("hidden", false);
                logged_in = true;
            } else {
                if (formData['username'] != '' && formData['password'] != '') {
                    $('#errormessage').html('incorrect information');
                    $('#errormessage').fadeIn("slow");
                } else {
                    $('#errormessage').html('empty fields');
                    $('#errormessage').fadeIn("slow");
                }

                setTimeout(function () {
                    $('#errormessage').fadeOut("slow");
                }, 3000)
            }
        });
    })
    

    $('.seller').click(function () {
        var formData = {
            'first': $('#firstName').val(),
            'last': $('#lastName').val(),
            'number': $('#sellerID').val(),
            'email': $('#email').val()
        };        

        $.post('../query/listings.php', formData, function () {

        }).done(function (data) {            

            if (data != "") {
                data = JSON.parse(data);                
                var user = data[0];
                $('.user-id').text("ID: " + user[0]);
                $('.user-name').text("Name: " + user[1] + " " + user[2]);
                $('.user-contact').text("Contact: " + user[4]);
                $('.user-email').text(user[3]);

                for (var i = 1; i < data.length; i++) {
                    var book = data[i];                    
                    var recieved = '';                    
                    if (book[4] == 0) {
                        recieved = "checked";                        
                    }

                    var html = '<div class="listing" data-value="' + book[0] + '"><div class="listing-isbn">ISBN: <b>' + book[1] + '</b></div><div class="listing-price">Listing Price: <b>$' + book[2] + '</b></div><div class="listing-guarantee">Our Price: <b>$' + book[3] + '</b></div><div class="radiodiv"><input type="checkbox" name="radio" id="radio'+i+'" class="radio" ' + recieved + '/><label for="radio'+ i +'">Recieved</label></div></div>';

                    $('.listings').append(html);
                }
                
                $('.listings-block').toggleClass("hidden", true);
                $('.user-block').toggleClass("hidden", false);
            }
        })
    })

    $('.listings-submit').click(function () {
        var update = [];
        $('.listing').each(function (index) {
            update[index] = [$(this).children().children('.radio').is(':checked'), $(this).attr("data-value")];
        })

        var formData = { listings: update };

        console.log(formData);

        $.post("../query/updateListings.php", formData, function () {

        }).done(function (data) {
            $('.listings-block').toggleClass("hidden", false);
            $('.user-block').toggleClass("hidden", true);

            $('.listings-block').children('input').each(function () {
                $(this).val("");
            })

            $('.listings').html("<h1>Listings</h1>");

            console.log(data);
        })
    })
})
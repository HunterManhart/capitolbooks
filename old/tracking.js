var stuff;

$(function () {    
    $('.seller').click(function () {
        var formData = {
            'first': $('#firstName').val(),
            'last': $('#lastName').val(),
            'number': $('#sellerID').val(),
            'email': $('#email').val()
        };

        getListings(formData);
        
    })

    if (typeof id != "undefined") {
        var formData = {
            'first': '',
            'last': '',
            'number': id,
            'email': ''
        };

        getListings(formData);
    }
})

function getListings(formData) {
    $.post('../query/listings.php', formData, function () {

    }).done(function (data) {

        if (data != "") {
            data = JSON.parse(data);
            var user = data[0];
            $('.user-id').text("ID: " + user[0]);
            $('.user-name').text("Name: " + user[1]);

            for (var i = 1; i < data.length; i++) {
                var book = data[i];
                var recieved = 'No';
                if (book[4] == 0) {
                    recieved = "Yes";
                }

                var html = '<div class="listing" data-value="' + book[0] + '"><div class="listing-isbn">ISBN: <b>' + book[1] + '</b></div><div class="listing-price">Listing Price: <b>$' + book[2] + '</b></div><div class="listing-guarantee">Our Price: <b>$' + book[3] + '</b></div><div class="listing-recieved">Recieved: ' + recieved + '</div></div>';

                $('.listings').append(html);
            }

            $('.listings-block').toggleClass("hidden", true);
            $('.user-block').toggleClass("hidden", false);
        }
    })
}
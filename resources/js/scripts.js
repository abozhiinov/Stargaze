$(function () {
    $('#search-dashboard').keypress(function(e) {
        if( e.keyCode == 13 ) {
            if( e.target.classList.contains('artists') ) {
                var type = 'artists';
            } else if ( e.target.classList.contains('places') ) {
                var type = 'places';
            }
            var url = '/dashboardSearch/' + type + '/' + $('#search-dashboard').val();
            if ( $('#sort-genres-dashboard').val() != null ) {
                url += '/' + $('#sort-genres-dashboard').val();
            }
            if ( $('#order-dashboard').val() != null ) {
                url += '/' + $('#order-dashboard').val();
            }
            $.ajax({
                type: "get",
                url: url,
                data: {
                    _token: $('#token').val(),
                },
                success: function(response) {
                    $('.dashboard').html(response);
                },
                error: function(response) {
                    console.log('ERROR:');
                    console.log(response);
                }
            });
        }
	});

    $('#sort-genres-dashboard').on('change', function(e) {
        if( e.target.classList.contains('artists') ) {
            var type = 'artists';
        } else if ( e.target.classList.contains('places') ) {
            var type = 'places';
        }
        var url = '/dashboardGenreSort/' + type + '/' + $('#sort-genres-dashboard').val();
        if ( $('#order-dashboard').val() != null ) {
            url += '/' + $('#order-dashboard').val();
        }
        if ( $('#search-dashboard').val() != null ) {
            url += '/' + $('#search-dashboard').val();
        }
        $.ajax({
            type: "get",
            url: url,
            data: {
                _token: $('#token').val(),
            },
            success: function(response) {
                $('.dashboard').html(response);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    });

    $('#order-dashboard').on('change', function(e) {
        if( e.target.classList.contains('artists') ) {
            var type = 'artists';
        } else if ( e.target.classList.contains('places') ) {
            var type = 'places';
        }
        var url = '/dashboardOrder/' + type + '/' + $('#order-dashboard').val();
        if ( $('#sort-genres-dashboard').val() != null ) {
            url += '/' + $('#sort-genres-dashboard').val();
        }
        if ( $('#search-dashboard').val() != null ) {
            url += '/' + $('#search-dashboard').val();
        }
        $.ajax({
            type: "get",
            url: url,
            data: {
                _token: $('#token').val(),
            },
            success: function(response) {
                $('.dashboard').html(response);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    });
});
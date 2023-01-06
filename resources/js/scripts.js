const { countBy } = require("lodash");

$(function () {
    $('.button-add-new-artist').on('click', function(e){
        $('#addArtistModal').modal('show');
    })

    $('.close').on('click', function(e){
        $('#addArtistModal').modal('hide');
        $('#addPlaceModal').modal('hide');
        $('#deleteArtistModal').modal('hide');
        $('#deletePlaceModal').modal('hide');
    })

    $('.button-add-new-place').on('click', function(e){
        $('#addPlaceModal').modal('show');
    })

    $('#form-new-artist').on('submit', function(e){
        var adminId = $('#admin-id').val();
        var name = $('#new-artist-name').val();
        var username = $('#new-artist-username').val();
        var genreId = $('#new-artist-genre').val();
        var profilePic = $('#new-artist-profile-pic').val().replace(/^.*[\\\/]/, '');
        var coverPic = $('#new-artist-cover-pic').val().replace(/^.*[\\\/]/, '');
        $.ajax({
            type: "get",
            url: '/addNewArtist/' + adminId + '/' + name + '/' + username + '/' +genreId + '/' + profilePic + '/' + coverPic,
            data: {
                _token: $('#token').val(),
            },
            success: function(response) {
                $(location).prop('href', '/artist/' + username);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    } )

    $('#form-new-place').on('submit', function(e){
        var adminId = $('#admin-id').val();
        var name = $('#new-place-name').val();
        var username = $('#new-place-username').val();
        var genreId = $('#new-place-genre').val();
        var locationId = $('#new-place-location').val();
        var profilePic = $('#new-place-profile-pic').val().replace(/^.*[\\\/]/, '');
        var coverPic = $('#new-place-cover-pic').val().replace(/^.*[\\\/]/, '');
        $.ajax({
            type: "get",
            url: '/addNewPlace/' + adminId + '/' + name + '/' + username + '/' + genreId + '/' + locationId + '/' + profilePic + '/' + coverPic,
            data: {
                _token: $('#token').val(),
            },
            success: function(response) {
                $(location).prop('href', '/place/' + username);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    } )

    $('.artist-see-invitations').on('click', function(e){
        var username = $('.artist-see-invitations').data('username');

        $.ajax({
            type: "get",
            url: '/artistInvitations/' + username,
            data: {
                _token: $('#token').val(),
            },
            success: function(response) {
                $('.artist-content').html(response);
                console.log(response);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    })

    $('.artist-edit').on('click', function(e){
        var username = $('.artist-edit').data('username');

        $.ajax({
            type: "get",
            url: '/artistEdit/' + username,
            data: {
                _token: $('#token').val(),
            },
            success: function(response) {
                $('.artist-content').html(response);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    })

    $('#form-edit-artist').on('submit', function(e){
        console.log('laino');
        var name = $('#edit-place-name').val();
        var username = $('#edit-place-username').val();
        var genreId = $('#edit-place-genre').val();
        var profilePic = $('#edit-place-profile-pic').val().replace(/^.*[\\\/]/, '');
        var coverPic = $('#edit-place-cover-pic').val().replace(/^.*[\\\/]/, '');

        $.ajax({
            type: "get",
            url: '/editArtist/' + id + '/' + name + '/' + username + '/' + genreId + '/' +  profilePic + '/' + coverPic,
            data: {
                _token: $('#token').val(),
            },
            success: function(response) {
                $(location).prop('href', '/artist/' + username);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    })

    $('.artist-delete').on('click', function(e){
        $('#deleteArtistModal').modal('show');
    })

    $('.place-delete').on('click', function(e){
        $('#deletePlaceModal').modal('show');
    })

    $('#form-delete-artist').on('submit', function(e){
        var username = $('#form-delete-artist').data('username');

        $.ajax({
            type: "get",
            url: '/artistDelete/' + username,
            data: {
                _token: $('#token').val(),
            },
            success: function(response) {
                $('#delete-artist-text').html(response);
                $('#delete-artist-yes').hide();
                $('#delete-artist-no').hide();
                document.getElementById("delete-artist-ok").removeAttribute("hidden");
                console.log(response);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });

    })

    $('#form-delete-place').on('submit', function(e){
        var username = $('#form-delete-place').data('username');

        $.ajax({
            type: "get",
            url: '/placeDelete/' + username,
            data: {
                _token: $('#token').val(),
            },
            success: function(response) {
                $('#delete-place-text').html(response);
                $('#delete-place-yes').hide();
                $('#delete-place-no').hide();
                document.getElementById("delete-place-ok").removeAttribute("hidden");
                console.log(response);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });

    })

    $('#delete-artist-ok').on('click', function(e){
        $(location).prop('href', '/profile');
    })

    $('#delete-place-ok').on('click', function(e){
        $(location).prop('href', '/profile');
    })

    $('#search-dashboard').on('keyup', function(e) {
        $('.dashboard').animate({'opacity':'0.5'}, 200);

        if( e.target.classList.contains('artists') ) {
            var type = 'artists';
        } else if ( e.target.classList.contains('places') ) {
            var type = 'places';
        }

        var url = '/dashboardSearch/' + type;
        if( $('#search-dashboard').val() != '' ){
            url += '/' + $('#search-dashboard').val();
        } else {
            url += '/empty-search';
        }

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
                $('.dashboard').animate({'opacity':'1.0'}, 200);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
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
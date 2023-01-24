const { countBy } = require("lodash");
const { provide } = require("vue");

import { Swiper, Parallax, Navigation} from 'swiper'
Swiper.use([ Parallax, Navigation ])

$(function () {
    $('.button-add-new-artist').on('click', function(e){
        $('#addArtistModal').modal('show');
    })

    $('.close').on('click', function(e){
        $(this).closest('.modal-header').closest('.modal-content').closest('.modal-dialog').closest('.modal').modal('hide');
    })

    $('.button-add-new-place').on('click', function(e){
        $('#addPlaceModal').modal('show');
    })

    $('#form-new-artist').on('submit', function(e){
        var id = $('#admin-id').val();
        var name = $('#new-artist-name').val();
        var username = $('#new-artist-username').val();
        var genreId = $('#new-artist-genre').val();
        var profilePic = $('#new-artist-profile-pic').val().replace(/^.*[\\\/]/, '');
        var coverPic = $('#new-artist-cover-pic').val().replace(/^.*[\\\/]/, '');
        var facebook = $('#new-artist-facebook').val();
        var instagram = $('#new-artist-instagram').val();
        var youtube = $('#new-artist-youtube').val();

        var formData = new FormData;
        formData.append( 'profile_picture', $('#new-artist-profile-pic')[0].files[0] );
        formData.append( 'cover_picture', $('#new-artist-cover-pic')[0].files[0] );

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            url: '/storeImage',
            data: formData, 
            processData: false,
            contentType: false,
            success: function(response) {
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });

        $.ajax({
            type: "get",
            url: '/addNewArtist',
            data: {
                _token: $('#token').val(),
                id: id,
                name: name,
                username: username,
                genre_id: genreId,
                profile_pic: profilePic,
                cover_pic: coverPic,
                facebook: facebook,
                instagram: instagram,
                youtube: youtube,
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
        var facebook = $('#new-place-facebook').val();
        var instagram = $('#new-place-instagram').val();
        var youtube = $('#new-place-youtube').val();

        var formData = new FormData;
        formData.append( 'profile_picture', $('#new-place-profile-pic')[0].files[0] );
        formData.append( 'cover_picture', $('#new-place-cover-pic')[0].files[0] );

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            url: '/storeImage',
            data: formData, 
            processData: false,
            contentType: false,
            success: function(response) {
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);/*
                Carousel
            */
            $('#carousel-example').on('slide.bs.carousel', function (e) {
                /*
                    CC 2.0 License Iatek LLC 2018 - Attribution required
                */
                var $e = $(e.relatedTarget);
                var idx = $e.index();
                var itemsPerSlide = 5;
                var totalItems = $('.carousel-item').length;
             
                if (idx >= totalItems-(itemsPerSlide-1)) {
                    var it = itemsPerSlide - (totalItems - idx);
                    for (var i=0; i<it; i++) {
                        // append slides to end
                        if (e.direction=="left") {
                            $('.carousel-item').eq(i).appendTo('.carousel-inner');
                        }
                        else {
                            $('.carousel-item').eq(0).appendTo('.carousel-inner');
                        }
                    }
                }
            });
            }
        });

        $.ajax({
            type: "get",
            url: '/addNewPlace',
            data: {
                _token: $('#token').val(),
                admin_id: adminId,
                name: name,
                username: username,
                genre_id: genreId,
                location_id: locationId,
                profile_pic: profilePic,
                cover_pic: coverPic,
                facebook: facebook,
                instagram: instagram,
                youtube: youtube
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
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    })

    $('.place-see-invitations').on('click', function(e){
        var username = $('.place-see-invitations').data('username');

        $.ajax({
            type: "get",
            url: '/placeInvitations/' + username,
            data: {
                _token: $('#token').val(),
            },
            success: function(response) {
                $('.place-content').html(response);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    })

    

    $(document).on('click', '.invitation-single-see-more', function(e){
        $(this).hide();
        $(this).closest('.invitation-single-content').find('#message').css({"display" : "block"});
        $(this).closest('.invitation-single-content').find('.invitation-single-see-less').css({"display" : "block"});
    })

    $(document).on('click', '.invitation-single-see-less', function(e){
        $(this).hide();
        $(this).closest('.invitation-single-content').find('#message').css({"display" : "none"});
        $(this).closest('.invitation-single-content').find('.invitation-single-see-more').css({"display" : "block"});
    })

    $(document).on('click', '.invitation-single-delete', function(e){
        var invitationId = $(this).closest('.invitation-buttons').data('delete-id') ;
        var invitation = $(this).closest('.invitation-single-content').closest('.invitation-single');
        $.ajax({
            type: "get",
            url: '/deleteInvitation' ,
            data: {
                _token: $('#token').val(),
                id: invitationId,
            },
            success: function(response) {
                invitation.slideUp(500);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    })

    $('body').on('click', '.invitation-status', function(e){
        var invitationId = $('.invitation-buttons').data('id') ;
        var status = $(this).data('status') ;
        
        $.ajax({
            type: "get",
            url: '/statusInvitation',
            data: {
                _token: $('#token').val(),
                id: invitationId,
                status: status, 
            },
            success: function(response) {
                $(location).prop('href', '/profile');
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    })

    $('body').on('click', '.invitation-single-create-event', function(e){
        let artist = $(this).data('artist');
        let place = $(this).data('place');
        let invitation = $(this).data('invitation');
        let date = $(this).data('date');

        var modal = document.getElementById("create-event-data");

        modal.setAttribute('data-artist', artist);
        modal.setAttribute('data-place', place);
        modal.setAttribute('data-invitation', invitation);
        modal.setAttribute('data-date', date);

        $('#createEventModal').modal('show');
    })

    $(document).on('submit', '#form-create-event', function(e){
        var id = $('#id').val() || $('#id').data('id');
        var title = $('#create-event-title').val();
        var poster = $('#create-event-poster').val().replace(/^.*[\\\/]/, '');
        var artistId = $('#create-event-data').data('artist');
        var placeId = $('#create-event-data').data('place');
        var date = $('#create-event-data').data('date');

        var formData = new FormData;
        formData.append( 'event_thumbnail', $('#create-event-poster')[0].files[0] );

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            url: '/storeImage',
            data: formData, 
            processData: false,
            contentType: false,
            success: function(response) {
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });

        $.ajax({
            type: "get",
            url: '/createEvent',
            data: {
                _token: $('#token').val(),
                id: id,
                title: title,
                poster: poster,
                artist_id: artistId,
                place_id: placeId,
                date: date,
            },
            success: function(response) {
                $('#createEventModal').modal('hide');
                $('#successfulEventModal').modal('show');
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    })


    $('.artist-edit').on('click', function(e){
        $('#editArtistModal').modal('show');
    })

    $('.place-edit').on('click', function(e){
        $('#editPlaceModal').modal('show');
    })

    $(document).on('submit', '#form-edit-artist', function(e){
        var id = $('#id').val() || $('#id').data('id');
        var name = $('#edit-artist-name').val();
        var username = $('#edit-artist-username').val();
        var genreId = $('#edit-artist-genre').val();
        var profilePic = $('#edit-artist-profile-pic').val().replace(/^.*[\\\/]/, '');
        var coverPic = $('#edit-artist-cover-pic').val().replace(/^.*[\\\/]/, '');
        var facebook = $('#edit-artist-facebook').val();
        var instagram = $('#edit-artist-instagram').val();
        var youtube = $('#edit-artist-youtube').val();

        var formData = new FormData;
        formData.append( 'profile_picture', $('#edit-artist-profile-pic')[0].files[0] );
        formData.append( 'cover_picture', $('#edit-artist-cover-pic')[0].files[0] );

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            url: '/storeImage',
            data: formData, 
            processData: false,
            contentType: false,
            success: function(response) {
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
        
        $.ajax({
            type: "get",
            url: '/editArtist',
            data: {
                _token: $('#token').val(),
                id: id,
                name: name,
                username: username,
                genre_id: genreId,
                profile_pic: profilePic,
                cover_pic: coverPic,
                facebook: facebook,
                instagram: instagram,
                youtube: youtube,
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

    $(document).on('submit', '#form-edit-place', function(e){
        var id = $('#id').val() || $('#id').data('id');
        var name = $('#edit-place-name').val();
        var username = $('#edit-place-username').val();
        var genreId = $('#edit-place-genre').val();
        var profilePic = $('#edit-place-profile-pic').val().replace(/^.*[\\\/]/, '');
        var coverPic = $('#edit-place-cover-pic').val().replace(/^.*[\\\/]/, '');
        var facebook = $('#edit-place-facebook').val();
        var instagram = $('#edit-place-instagram').val();
        var locationId = $('#edit-place-location').val();

        var formData = new FormData;
        formData.append( 'profile_picture', $('#edit-place-profile-pic')[0].files[0] );
        formData.append( 'cover_picture', $('#edit-place-cover-pic')[0].files[0] );

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            url: '/storeImage',
            data: formData, 
            processData: false,
            contentType: false,
            success: function(response) {
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });

        $.ajax({
            type: "get",
            url: '/editPlace',
            data: {
                _token: $('#token').val(),
                id: id,
                name: name,
                username: username,
                genre_id: genreId,
                profile_pic: profilePic,
                cover_pic: coverPic,
                facebook: facebook,
                instagram: instagram,
                location_id: locationId,
            },
            success: function(response) {
                $(location).prop('href', '/place/' + username);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    })


    $('.artist-invite').on('click', function(e){
        $('#inviteArtistModal').modal('show');
    })

    $(document).on('submit', '#form-invite-artist', function(e){
        var id = $('#artist-id').val();
        var place = $('#invite-artist-place').val();
        var message = $('#invite-artist-message').val();
        var date = $('#invite-artist-date').val();
        var start = $('#invite-artist-start-hour').val();
        var end = $('#invite-artist-end-hour').val();
        var fee = $('#invite-artist-fee').val();

        $.ajax({
            type: "get",
            url: '/inviteArtist',
            data: {
                _token: $('#token').val(),
                id: id,
                place: place,
                message: message,
                date: date,
                start_hour: start,
                end_hour: end,
                fee: fee,
            },
            success: function(response) {
                $('#inviteArtistModal').modal('hide');
                $('#successfulInvitationModal').modal('show');
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    })

    $('#successful-invitation-ok').on('click', function(e){
        $('#successfulInvitationModal').modal('hide');
    })

    $('#successful-event-ok').on('click', function(e){
        $('#successfulEventModal').modal('hide');
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

    function filterEvents(e) {
        $('.event-dashboard').animate({'opacity':'0.5'}, 200);

        let searchArtist;
        if( $('#search-artist-events').val() != '' ){
            searchArtist = $('#search-artist-events').val();
        }

        let searchPlace;
        if( $('#search-place-events').val() != '' ){
            searchPlace = $('#search-place-events').val();
        }

        let searchDate;
        if( $('#search-date-events').val() != '' ){
            searchDate = $('#search-date-events').val();
        }

        let location;
        if ( $('#sort-location-events').val() != null ) {
            location = $('#sort-location-events').val();
        }

        let genre;
        if ( $('#sort-genres-events').val() != null ) {
            genre = $('#sort-genres-events').val();
        }

        let order;
        if ( $('#order-events').val() != null ) {
            order = $('#order-events').val();
        }

        $.ajax({
            type: "get",
            url: '/eventsFilter',
            data: {
                _token: $('#token').val(),
                search_artist: searchArtist,
                search_place: searchPlace,
                search_date: searchDate,
                location: location,
                genre: genre,
                order: order

            },
            success: function(response) {
                $('.event-dashboard').html(response);
                $('.event-dashboard').animate({'opacity':'1.0'}, 200);
            },
            error: function(response) {
                console.log('ERROR:');
                console.log(response);
            }
        });
    }

    $('#search-artist-events').on('keyup', function(e) {
        filterEvents(e);
	});

    $('#search-place-events').on('keyup', function(e) {
        filterEvents(e);
	});

    $('#search-date-events').on('change', function(e) {
        filterEvents(e);
	});

    $('#sort-genres-events').on('change', function(e) {
        filterEvents(e);
    });

    $('#sort-location-events').on('change', function(e) {
        filterEvents(e);
    });

    $('#order-events').on('change', function(e) {
        filterEvents(e);
    })

    function filterDashboard(e) {
        $('.dashboard').animate({'opacity':'0.5'}, 200);

        if( e.target.classList.contains('artists') ) {
            var type = 'artists';
        } else if ( e.target.classList.contains('places') ) {
            var type = 'places';
        }

        let search;
        if( $('#search-dashboard').val() != '' ){
            search = $('#search-dashboard').val();
        }

        let genre;
        if ( $('#sort-genres-dashboard').val() != null ) {
            genre = $('#sort-genres-dashboard').val();
        }

        let order;
        if ( $('#order-dashboard').val() != null ) {
            order = $('#order-dashboard').val();
        }

        $.ajax({
            type: "get",
            url: '/dashboardFilter',
            data: {
                _token: $('#token').val(),
                type: type,
                search: search,
                genre: genre,
                order: order

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
    }

    $('#search-dashboard').on('keyup', function(e) {
        filterDashboard(e);
	});

    $('#sort-genres-dashboard').on('change', function(e) {
        filterDashboard(e);
    });

    $('#order-dashboard').on('change', function(e) {
        filterDashboard(e);
    })

    $('.first-button').on('click', function () {
        $('.animated-icon1').toggleClass('open');
        $('.header-overlay').toggleClass('is-active');


        if($('.animated-icon1').hasClass('open')) {
            $('#burger-menu').slideDown(400);
        } else {
            $('#burger-menu').slideUp(400);
        }
    });

    $('.header-overlay').on('click', function(){
        $('.animated-icon1').toggleClass('open');
        $('.header-overlay').toggleClass('is-active');


        if($('.animated-icon1').hasClass('open')) {
            $('#burger-menu').slideDown(400);
        } else {
            $('#burger-menu').slideUp(400);
        }
    })


    const swiper = new Swiper('.swiper', {
        // Optional parameters
        slidesPerView: 2,
        setWrapperSize: true,
        direction: 'horizontal',
        loop: false,
        observer: true,
        observeParents: true,
        parallax:true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
});
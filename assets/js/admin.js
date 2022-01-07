jQuery(function ($) {

    var dataTable = $('#twh-table').DataTable({
        ajax: {
            url: TWHAdmin.ajaxurl + '?action=twh_data&nonce=' + TWHAdmin.nonce,
            dataSrc: '',
        },
        columns: [
            {
                data: 'id'
            },
            {
                data: 'url'
            },
            {
                data: 'method'
            },
            {
                data: 'event_type'
            },
            {
                data: 'payload'
            },
            {
                data: 'id'
            },
        ],
        columnDefs: [{
            "render": function (data, type, row) {
                return '<span id="edit_wh" data-id="' + data + '">Edit</span> - <span id="del_wh" data-id="' + data + '">Delete</span>';
            },
            "targets": 5
        }]
    });


    // Delete
    $('#twh-table').on('click', '#del_wh', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        if ( ! confirm( "Do you want to delete?" ) ) {
            return false;
        }
        wp.apiFetch({
            path: "/twh/v1/delete_webhook/" + id,
            method: "DELETE",

        }).then(function (e) {
            dataTable.ajax.reload();
        });
    });

    // Update.
    $('#twh-table').on( 'click', '#edit_wh', function (e) {
        e.preventDefault();
        var id = $(this).data('id'),
            popup = $('.mh-popup-wh-update'),
            url = $('#wh-url'),
            method = $('#wh-method'),
            event_type = $('#wh-event-type'),
            payload = $('#wh-payload');

        wp.apiFetch({
            path: "/twh/v1/get_webhook/" + id,
            method: "GET",

        }).then(function (e) {
            $('#wh-id').val(e.id);
            url.val(e.url);
            method.val(e.method);
            event_type.val(e.event_type);
            payload.val(e.payload);
            popup.fadeIn(500);
        });

    });

    $( '#wh_f_u' ).on( 'click', function (e) {
        e.preventDefault();
        var form = $( '#wh-form' ),
            popup = $('.mh-popup-wh-update'),
            id = $( '#wh-id' ).val();

        wp.apiFetch({
            path: "/twh/v1/update_webhook/" + id,
            method: "POST",
            data: {
                url: form.find( '#wh-url' ).val(),
                method: form.find( '#wh-method' ).val(),
                event_type: form.find( '#wh-event-type' ).val(),
                payload: form.find( '#wh-payload' ).val(),
            },
        }).then(function (e) {
            console.log(e);
            dataTable.ajax.reload();
            popup.fadeOut(500);
        });
        
    });

    // Add WH.
    $( '#wh_f_a' ).on( 'click', function (e) {
        e.preventDefault();
        var form = $( '#wh-form-add' ),
            popup = $('.mh-popup-wh-add'),
            id = $( '#wh-id' ).val();

        wp.apiFetch({
            path: "/twh/v1/add_webhook/",
            method: "POST",
            data: {
                url: form.find( '#wh-a-url' ).val(),
                method: form.find( '#wh-a-method' ).val(),
                event_type: form.find( '#wh-a-event-type' ).val(),
                payload: form.find( '#wh-a-payload' ).val(),
            },
        }).then(function (e) {
            console.log(e);
            dataTable.ajax.reload();
            popup.fadeOut(500);
        });
        
    });
    $( '.twh-data__btn-add' ).on( 'click', function (e) {
        $('.mh-popup-wh-add').fadeIn(500);
    });

    // Popup 
    $( '.mh-popup__btn-close' ).on( 'click', function (e) {
        $('.mh-popup').fadeOut(500);
    });
});
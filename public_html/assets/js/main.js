(function ($) {
    "use strict";

    /*----------------------------
     jQuery MeanMenu
    ------------------------------ */
    jQuery('nav#dropdown').meanmenu();


    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');

    });

    // Collapse ibox function
    $('#sidebar ul li').on('click', function () {
        var button = $(this).find('i.fa.indicator-mn');
        button.toggleClass('fa-angle-left').toggleClass('fa-angle-right');

    });


    $('#sidebarCollapse').on('click', function () {
        $("body").toggleClass("mini-navbar");
        SmoothlyMenu();
    });

    /*-----------------------------
        Menu Stick
    ---------------------------------*/
    $(".sicker-menu").sticky({topSpacing: 0});

    $(document).on('click', '.header-right-menu .dropdown-menu', function (e) {
        e.stopPropagation();
    });

    /*--------------------------
     mCustomScrollbar
    ---------------------------- */
    $(window).on("load", function () {
        $(".message-menu, .notification-menu, .comment-scrollbar, .notes-menu-scrollbar, .project-st-menu-scrollbar").mCustomScrollbar({
            autoHideScrollbar: true,
            scrollbarPosition: "outside",
            theme: "light-1"

        });
        $(".timeline-scrollbar").mCustomScrollbar({
            setHeight: 636,
            autoHideScrollbar: true,
            scrollbarPosition: "outside",
            theme: "light-1"

        });
        $(".project-list-scrollbar").mCustomScrollbar({
            setHeight: 636,
            theme: "light-2"
        });
        $(".messages-scrollbar").mCustomScrollbar({
            setHeight: 503,
            autoHideScrollbar: true,
            scrollbarPosition: "outside",
            theme: "light-1"
        });
        $(".chat-scrollbar").mCustomScrollbar({
            setHeight: 250,
            theme: "light-2"
        });
        $(".widgets-chat-scrollbar").mCustomScrollbar({
            setHeight: 335,
            autoHideScrollbar: true,
            scrollbarPosition: "outside",
            theme: "light-1"
        });
        $(".widgets-todo-scrollbar").mCustomScrollbar({
            setHeight: 322,
            autoHideScrollbar: true,
            scrollbarPosition: "outside",
            theme: "light-1"
        });
        $(".user-profile-scrollbar").mCustomScrollbar({
            setHeight: 1820,
            autoHideScrollbar: true,
            scrollbarPosition: "outside",
            theme: "light-1"
        });
    });


    // Collapse Chat function
    $('.chat-icon-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-comments').toggleClass('fa-remove');
    });
    // Collapse ibox function
    $('.collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline-content").slideToggle("slow");
    });
    $(".collapse-close").on('click', function () {
        $("div.about-sparkline").fadeOut(600);
    });

    // Collapse ibox function
    $('.smart-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".smart-sparkline-list").slideToggle("slow");
    });
    $(".smart-collapse-close").on('click', function () {
        $("div.sparkline-list").fadeOut(600);
    });


    // Collapse ibox function
    $('.sparkline7-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline7-graph").slideToggle("slow");
    });
    $(".sparkline7-collapse-close").on('click', function () {
        $("div.sparkline7-list").fadeOut(600);
    });
    // Collapse ibox function
    $('.sparkline8-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline8-graph").slideToggle("slow");
    });
    $(".sparkline8-collapse-close").on('click', function () {
        $("div.sparkline8-list").fadeOut(600);
    });


    // Collapse ibox function
    $('.sparkline9-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline9-graph").slideToggle("slow");
    });
    $(".sparkline9-collapse-close").on('click', function () {
        $("div.sparkline9-list").fadeOut(600);
    });

    // Collapse ibox function
    $('.sparkline10-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline10-graph").slideToggle("slow");
    });
    $(".sparkline10-collapse-close").on('click', function () {
        $("div.sparkline10-list").fadeOut(600);
    });
    // Collapse ibox function
    $('.sparkline11-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline11-graph").slideToggle("slow");
    });
    $(".sparkline11-collapse-close").on('click', function () {
        $("div.sparkline11-list").fadeOut(600);
    });


    // Collapse ibox function
    $('.sparkline12-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline12-graph").slideToggle("slow");
    });
    $(".sparkline12-collapse-close").on('click', function () {
        $("div.sparkline12-list").fadeOut(600);
    });

    // Collapse ibox function
    $('.sparkline13-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline13-graph").slideToggle("slow");
    });
    $(".sparkline13-collapse-close").on('click', function () {
        $("div.sparkline13-list").fadeOut(600);
    });

    // Collapse ibox function
    $('.sparkline14-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline14-graph").slideToggle("slow");
    });
    $(".sparkline14-collapse-close").on('click', function () {
        $("div.sparkline14-list").fadeOut(600);
    });

    // Collapse ibox function
    $('.sparkline15-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline15-graph").slideToggle("slow");
    });
    $(".sparkline15-collapse-close").on('click', function () {
        $("div.sparkline15-list").fadeOut(600);
    });

    // Collapse ibox function
    $('.sparkline16-collapse-link').on('click', function () {
        var button = $(this).find('i');
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $(".sparkline16-graph").slideToggle("slow");
    });
    $(".sparkline16-collapse-close").on('click', function () {
        $("div.sparkline16-list").fadeOut(600);
    });


})(jQuery);


function submitFormField() {
    var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    var key = $('#key').val();
    var type = $('#type').val();
    var form_id = $('#form_id').val();
    var minlen = $('#minlen').val();
    var maxlen = $('#maxlen').val();
    if (key.trim() == '') {
        alert('Please valid key.');
        $('#key').focus();
        return false;
    } else {
        $.ajax({
            type: 'POST',
            url: '/modal-submit',
            data: 'func=formfield&key=' + key + '&type=' + type + '&minlen=' + minlen + '&maxlen=' + maxlen + '&form_id=' + form_id,
            beforeSend: function () {
                $('.submitBtn').attr("disabled", "disabled");
                $('.modal-body').css('opacity', '.5');
            },
            success: function (msg) {
                if (msg == 'ok') {
                    $('#key').val('');
                    $('#type').val('');
                    $('#minlen').val('');
                    $('#max').val('');
                    $('.statusMsg').html('<span style="color:green;">Field Added.</p>');
                } else {
                    $('.statusMsg').html(msg);
                }
                $('.submitBtn').removeAttr("disabled");
                $('.modal-body').css('opacity', '');
            }
        });
    }
}


function submitPicklistItem() {
    var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

    var code = $('#code').val();
    var quantity = $('#quantity').val();
    var picklist_id = $('#picklist_id').val();
    var source_location_id = $('#source_location_id').val();
    var form_idx = $('#form_idx').val();
    if (code.trim() == '') {
        alert('Please valid code.');
        $('#code').focus();
        return false;
    } else if (quantity < 1) {
        alert('Please valid quantity.');
        $('#quantity').focus();
        return false;
    } else {
        $.ajax({
            type: 'POST',
            url: '/modal-submit',
            data: 'func=picklistitem&code=' + code + '&quantity=' + quantity + '&picklist_id=' + picklist_id + '&source_location_id=' + source_location_id + '&form_idx=' + form_idx,
            beforeSend: function () {
                $('.submitBtn').attr("disabled", "disabled");
                $('.modal-body').css('opacity', '.5');
            },
            success: function (msg) {
                if (msg == 'ok') {
                    $('#code').val('');
                    $('#quantity').val('');
                    $('#source_location_id').val('');
                    $('#form_idx').val('');
                    $('.statusMsg').html('<span style="color:green;">Item Added.</p>');
                } else {
                    $('.statusMsg').html(msg);
                }
                $('.submitBtn').removeAttr("disabled");
                $('.modal-body').css('opacity', '');
            }
        });
    }


}

function deletePicklistItem(idx) {

    var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

    $.ajax({
        type: 'POST',
        url: '/modal-submit',
        data: 'func=delete_picklistitem&idx=' + idx,
        beforeSend: function () {

        },
        success: function (msg) {
            if (msg == 'ok') {
                $('.statusMsg').html('<span style="color:green;">Item Deleted.</p>');
                window.location.reload();
            } else {
                $('.statusMsg').html(msg);
                window.location.reload();
            }

        }
    });

}


function deleteFormField(idx) {
    console.log(idx);
    var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

    $.ajax({
        type: 'POST',
        url: '/modal-submit',
        data: 'func=delete_form_field&idx=' + idx,
        beforeSend: function () {

        },
        success: function (msg) {
            if (msg == 'ok') {
                $('.statusMsg').html('<span style="color:green;">Item Deleted.</p>');
                window.location.reload();
            } else {
                $('.statusMsg').html(msg);
                window.location.reload();
            }

        }
    });

}
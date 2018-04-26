
notie.setOptions({
    animationDelay: 300,
    backgroundClickDismiss: true
});

function DisplayError(type, message) {
    //notie.alert(type, message, 5);


    toastr["success"](message);

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }



}

function success() {
    notie.alert(1, 'Success!');
}

function warning() {
    notie.alert(2, 'Warning<br><b>with</b><br><i>HTML</i><br><u>included.</u>', 2);
}

function error() {
    notie.alert(3, 'Error.', 2);
}

function info() {
    notie.alert(4, 'Information.', 2);
}

function confirm() {
    notie.confirm('Are you sure you want to do that?<br><b>That\'s a bold move...</b>', 'Yes', 'Cancel', function () {
        notie.alert(1, 'Good choice! :D', 2);
    }, function () {
        notie.alert(3, 'Aw, why not? :(', 2);
    });
}

function input() {
    notie.input({
        type: 'text',
        placeholder: 'name@example.com',
        prefilledValue: ''
    }, 'Please enter your email address:', 'Submit', 'Cancel', function (valueEntered) {
        notie.alert(1, 'You entered: ' + valueEntered, 2);
    }, function (valueEntered) {
        notie.alert(3, 'You cancelled with this value: ' + valueEntered, 2);
    });
}

function selectOption(message, recordId, redirect) {
    notie.select(message,
        [
            // { title: 'Share' },
            {title: 'Open'},
            {title: 'Edit', color: '#D6A14D'},
            {title: 'Delete', color: '#E1715B'}
        ],
        // function() {
        //     notie.alert(1, recordId, 3);
        // },
        function () {
            //$.post("tempDataHold.php", {"recordId": recordId});
            notie.alert(1, 'loading data...', 5);
            window.location.replace('i'.concat(redirect).concat('?takeapeek=').concat(recordId));
        }, function () {
            notie.alert(2, 'loading data...', 3);
            window.location.replace('i'.concat(redirect).concat('?modify=').concat(recordId));
        }, function () {
            //notie.alert(2, 'loading data...', 3);
            notie.confirm('Are you sure you want to do that?<br><b>That\'s a boldmove...</b>', 'Yes', 'Cancel', function () {
                notie.alert(1, 'Roger that!', 2);
                window.location.replace(redirect.concat('?neutralize=').concat(recordId));
            }, function () {
                notie.alert(3, 'Aw, why not? :(', 2);
            });
        });
}

function date() {
    notie.date({
        yesCallback: function (date) {
            //notie.alert(1, 'You selected: ' + date.toISOString(), 5);
            $.post("tempDataHold.php", {"selectedDate": date.toISOString()});
            $("#selectedDate_div").load(window.location.href + " #selectedDate_div");
        },
        noCallback: function (date) {
            //notie.alert(3, 'You cancelled: ' + date.toISOString(), 5)
        }
    })
}
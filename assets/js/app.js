"use strict";
var translate;
const url = "api/app.php";

// refreshing form page
if(window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// close modal with close click
$(document).ready(function() {
    $('.modal-close').click(function() {
        $('.modal-popup').removeClass('modal-active');
        $('#wrapper').removeClass('blur-content').css({"transition":"0.4s"});
    });
});

// vissible and toggle password icon
$(document).ready(function() {
    $('.icon-pass').click(function() {
        $(this).toggleClass('fa-eye fa-eye-slash');
        $('.input-pass').attr('type', function(index, attr) {
            return attr == 'text' ? 'password' : 'text';
        });
    });
});

// set language from cookies
$(document).ready(function () {
    $(window).on('load', function() {
        var lang = GetCookie('language');
        lang = lang ? lang : "en";
        $('.language').val(lang);
        UpdateLanguage(lang);
    });
});

// change language with click
$(document).ready(function () {
    $('.language').change(function() {
        SetCookie('language', this.value, 360);
        UpdateLanguage(this.value);
    });
});

// change each attr language
function UpdateLanguage(lang) {
    new Promise(function(resolve, reject) {
        $.ajax({
            dataType: 'json',
            url: './assets/lang/' + lang + '.json',
            success: function (lang) {
                resolve(lang);
            }
        });
    }).then(function(value) {
        translate = value;
        // updating each word from pages
        $('.lang').each(function(index, item) {
            var word = translate[$(this).attr('key')];
            if($(this).is('input')) {
                $(this).attr('placeholder', word);
            } else {
                $(this).text(word);
            }
        });
    });
}

// set cookies function
function SetCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

// get cookies function
function GetCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// modal object
const modal = {
    button: $('.modal-action'),
    title: function(title) {
        $('.modal-title').html(title);
    },
    text: function(message) {
        $('.modal-text').html(message);
    },
    show: function() {
        $('#wrapper').addClass('blur-content');
        $('.modal-popup').addClass('modal-active');
    },
    close: function() {
        $('.modal-popup').removeClass('modal-active');
        $('#wrapper').removeClass('blur-content').css({"transition":"0.4s"});
    },
    loading: function(x) {
        if(x) {
            $('.modal-close').hide();
            $('.modal-action').addClass('modal-loading');
        } else {
            $('.modal-action').removeClass('modal-loading');
            $('.modal-close').fadeIn();
            $('.modal-close').show();
        }
    },
    times: function(x) {
        if(x) {
            $('.modal-close').show();
        } else {
            $('.modal-close').hide();
        }
    }
};

// login users
$(document).ready(function() {
    $('#loginfor').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var method = form.attr('method');
        
        $.ajax({
            url: url,
            type: method,
            dataType: "json",
            data: form.serialize(),
            beforeSend: function() {
                modal.text(translate['logging_in_please_wait']);
                modal.loading(true);
                modal.show();
            },
            success: function(response) {
                modal.loading(false);
                if(response.status) {
                    if(backUrl != '') {
                        window.location = './' + backUrl;
                    } else {
                        location.href = "./";
                    }
                } else {
                    modal.text(translate[response.message].fontcolor("red"));
                    modal.button.click(function() {
                        modal.close();
                    });
                }
            }
        });
    });
});

// pre register, check email and create credential
$(document).ready(function() {
    $('#registerfor').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var method = form.attr('method');
        var email = $('input').eq(0).val().trim();
        var regDate = moment().format('ll');
        var regTime = moment().format('LT');
        $('<input type=hidden>').attr({name:'regDate', value: regDate}).appendTo(form);
        $('<input type=hidden>').attr({name:'regTime', value: regTime}).appendTo(form);

        $.ajax({
            url: url,
            type: method,
            dataType: "json",
            data: form.serialize(),
            beforeSend: function() {
                modal.text(translate['sending_email_please_wait']);
                modal.loading(true);
                modal.show();
            },
            success: function(response) {
                modal.loading(false);
                if(response.status) {
                    modal.times(false);
                    modal.text(translate['please_check_your_email'] + "<br>" + email.fontcolor("green") + "<br>" + translate['instruction_has_been_sent_to_email'])
                    modal.button.text(translate['verify']);
                    modal.button.click(function() {
                        location.href = "./verify?regEmail=" + email + "&type=register_account";
                    });
                } else {
                    modal.text(translate[response.message].fontcolor("red"));
                    modal.button.click(function() {
                        modal.close();
                    });
                }
            }
        });
    });
});

$(document).ready(function() {
    $('#recoverfor').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var method = form.attr('method');
        var email = $('input').eq(0).val().trim();
        var recDate = moment().format('ll');
        var recTime = moment().format('LT');
        $('<input type=hidden>').attr({name:'recDate', value: recDate}).appendTo(form);
        $('<input type=hidden>').attr({name:'recTime', value: recTime}).appendTo(form);

        $.ajax({
            url: url,
            type: method,
            dataType: "json",
            data: form.serialize(),
            beforeSend: function() {
                modal.text(translate['sending_email_please_wait']);
                modal.loading(true);
                modal.show();
            },
            success: function(response) {
                modal.loading(false);
                if(response.status) {
                    modal.times(false);
                    modal.text(translate['please_check_your_email'] + "<br>" + email.fontcolor("green") + "<br>" + translate['instruction_has_been_sent_to_email'])
                    modal.button.text(translate['verify']);
                    modal.button.click(function() {
                        location.href = "./verify?recEmail=" + email + "&type=recover_password";
                    });
                } else {
                    modal.text(translate[response.message].fontcolor("red"));
                    modal.button.click(function() {
                        modal.close();
                    });
                }
            }
        });
    });
});

$(document).ready(function() {
    $('#verifyfor').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var method = form.attr('method');
        $('<input type=hidden>').attr({name:'auth', value: auth}).appendTo(form);

        if(auth == '') {
            location.href = "404?title=invalid_request&message=credential_not_found";
        } else {
            $.ajax({
                url: url,
                type: method,
                dataType: "json",
                data: form.serialize(),
                beforeSend: function() {
                    modal.text(translate['verifying_code_please_wait']);
                    modal.loading(true);
                    modal.show();
                },
                success: function(response) {
                    modal.loading(false);
                    if(response.status) {
                        if(response.type == "recover_password") {
                            location.href = "verify-recover?recAuth=" + auth;
                        } else if(response.type == "register_account") {
                            location.href = "verify-register?regAuth=" + auth;
                        } else {
                            location.href = "./";
                        }
                    } else {
                        modal.text(translate[response.message].fontcolor("red"));
                        modal.button.click(function() {
                            modal.close();
                        });
                    }
                }
            });
        }
    });
});

$(document).ready(function() {
    $('#verifyrecoverfor').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var method = form.attr('method');
        $('<input type=hidden>').attr({name:'auth', value: auth}).appendTo(form);
        
        if(auth == '') {
            location.href = "404?title=invalid_request&message=credential_not_found";
        } else {
            $.ajax({
                url: url,
                type: method,
                dataType: "json",
                data: form.serialize(),
                beforeSend: function() {
                    modal.text(translate['updating_password_please_wait']);
                    modal.loading(true);
                    modal.show();
                },
                success: function(response) {
                    modal.loading(false);
                    if(response.status) {
                        modal.times(false);
                        modal.text(translate['password_updated_successfully'].fontcolor("green"))
                        modal.button.text(translate['login']);
                        modal.button.click(function() {
                            location.href = "login";
                        });
                    } else {
                        modal.text(translate[response.message].fontcolor("red"));
                        modal.button.click(function() {
                            modal.close();
                        });
                    }
                }
            });
        }
    });
});

$(document).ready(function() {
    $('#verifyregisterfor').on('submit', function(e) {
        e.preventDefault();
        var data = $('input').eq(0).val().trim() + $('input').eq(1).val().trim();
        if(email == '' || account == '') {
            location.href = "404?title=invalid_request&message=credential_not_found";
        } else {
            CheckUsername(data, 0);
        }
    });
});

function CheckUsername(data, times) {
    var username = data;
    if(times != 0) {
        username = data + times;
    }
    
    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        data: {check_username: username},
        success: function(response) {
            if(response.status) {
                RegisterUser(username);
            } else {
                CheckUsername(data, times + 1);
            }
        }
    });
}

function RegisterUser(data) {
    var regDate = moment().format('ll');
    var regTime = moment().format('LT');
    var form = $('#verifyregisterfor');
    var method = form.attr('method');

    $('<input type=hidden>').attr({name:'verifyRegId', value: account}).appendTo(form);
    $('<input type=hidden>').attr({name:'verifyRegEmail', value: email}).appendTo(form);
    $('<input type=hidden>').attr({name:'verifyRegUsername', value: data}).appendTo(form);
    $('<input type=hidden>').attr({name:'verifyRegDate', value: regDate}).appendTo(form);
    $('<input type=hidden>').attr({name:'verifyRegTime', value: regTime}).appendTo(form);
    
    $.ajax({
        url: url,
        type: method,
        dataType: "json",
        data: form.serialize(),
        beforeSend: function() {
            modal.text(translate['creating_account_please_wait']);
            modal.loading(true);
            modal.show();
        },
        success: function(response) {
            modal.loading(false);
            if(response.status) {
                modal.times(false);
                modal.text(translate['account_created_successfully'].fontcolor("green"));
                modal.button.text(translate['login']);
                modal.button.click(function() {
                    location.href = "login";
                });
            } else {
                modal.text(translate[response.message].fontcolor("red"));
                modal.button.click(function() {
                    modal.close();
                });
            }
        }
    });
}
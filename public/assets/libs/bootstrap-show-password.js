/**
 * @author Abdo-Hamoud <abdo.host@gmail.com>
 * https://github.com/Abdo-Hamoud/bootstrap-show-password
 * version: 1.0
 */

!function ($) {
    //eyeOpenClass: 'fa-lock',
    //eyeCloseClass: 'fa-unlock-alt',
    'use strict';

    $(function () {
        $('[data-toggle="password"]').each(function () {
            var input = $(this);
            var eye_btn = $(this).parent().find('.input-group-text');
            eye_btn.css('cursor', 'pointer').addClass('input-password-hide');
            eye_btn.on('click', function () {
                if (eye_btn.hasClass('input-password-hide')) {
                    eye_btn.removeClass('input-password-hide').addClass('input-password-show');
                    eye_btn.find('.ti-lock').removeClass('ti-lock').addClass('ti-unlock')
                    input.attr('type', 'text');
                } else {
                    eye_btn.removeClass('input-password-show').addClass('input-password-hide');
                    eye_btn.find('.ti-unlock').removeClass('ti-unlock').addClass('ti-lock')
                    input.attr('type', 'password');
                }
            });
        });
    });

}(window.jQuery);

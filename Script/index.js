/**
 * Created by Victor on 30/08/2019.
 */
$(document).ready(function () {
    $('#login').hide();

    $('.tab a').on('click', function (e) {
        console.log("I was clicked oo");
        e.preventDefault();

        $(this).parent().addClass('active');
        $(this).parent().siblings().removeClass('active');

        target = $(this).attr('href');
        console.log(target);
        if(target == "#login"){
            $('#size-div').addClass('card-login');
            $('#size-div').removeClass('card-register');
        }else{
            $('#size-div').addClass('card-register');
            $('#size-div').removeClass('card-login');
        }
        $('.tab-content > div').not(target).hide();

        $(target).fadeIn(600);
    });
});

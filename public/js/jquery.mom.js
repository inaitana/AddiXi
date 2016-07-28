/*
    jQuery MicOffMenu
    Inspired by jQuery Ribbon By Mikael Söderström.
*/

(function($) {
    var isLoaded;
    var isClosed;

    $.fn.MoM = function(openTab) {
        if (!isLoaded) {
            SetupMenu(openTab);
        }

        function SetupMenu(openTab) {
            $('.orb > li > a').click(function() { ShowOrbMenu(); });
//
//            $('.orb ul').hide();
//            $('.orb ul ul').hide();
//            $('.orb li ul li ul').show();
//            $('.orb li li ul').each(function() { $(this).prepend('<div style="background-color: #EBF2F7; height: 25px; line-height: 25px; width: 292px; padding-left: 9px; border-bottom: 1px solid #CFDBEB;">' + $(this).parent().children('a:first').text() + '</div>'); });
//            $('.orb li li a').each(function() { if ($(this).parent().children('ul').length > 0) { $(this).addClass('arrow') } });

            ShowSubMenu($('#' + openTab));
            $('.orb li li a').mouseover(function() { ShowOrbChildren(this); });
        }

        $('.mom').parents().mousedown(function() {
                $('.orb-menu').fadeOut('fast');
        });

        if (isLoaded) {
            $('.orb li:first ul:first img:first').remove();
            $('.orb li:first ul:first img:last').remove();
        }
     
         //Hack for IE 7.
        if (navigator.appVersion.indexOf('MSIE 6.0') > -1 || navigator.appVersion.indexOf('MSIE 7.0') > -1) {
            $('ul.menu li li div').css('width', '90px');
            $('ul.menu').css('width', '500px');
            $('ul.menu').css('float', 'left');
        }

        isLoaded = true;

        function ResetSubMenu() {
            $('.menu li a').removeClass('active');
            $('.menu ul').removeClass('submenu');
        }

        function ShowSubMenu(e) {
            var isActive = $(e).next().css('display') == 'block';
            ResetSubMenu();

            $(e).addClass('active');
            $(e).parent().children('ul:first').addClass('submenu');
            $(e).parent().children('ul:first').show();

            isClosed = false;
        }

        function ShowOrbChildren(e) {
            if (($(e).parent().children('ul').css('display') == 'none' || $(e).parent().children('ul').length == 0) && $(e).parent().parent().parent().parent().hasClass('orb')) {
                $('.orb li li ul').fadeOut('fast');
                $(e).parent().children('ul').fadeIn('fast');
            }
        }

        function ShowOrbMenu() {
            $('.orb-menu').fadeIn('fast');
            $('.orb *').blur();
        }
    }
})(jQuery);

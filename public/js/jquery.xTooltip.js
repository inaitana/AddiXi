(function($){
    $.xTooltip = {
        defaults : {
            xOffset: 15,
            yOffset: 15,
            xPosition: 'right',
            yPosition: 'bottom',
            maxWidth: false,
            delay: 200,
            fadeInDuration: 200,
            fadeOutDuration: 200,
            tooltipClass: "tooltipBox",
            addClass: "",
            liveBind: true,
            smartPosition: true,
            freezeMode: true,
            clickToOpen: false,
            track: true,
            useParentLink: true,
            customHtml: false,
            tooltipHover: false,
            ajaxUrl: false,
            ajaxParams: {},
            ajaxPlaceholder: "Loading..."
        }
    }
    
    function showTooltip(e)
    {
        var element = e.currentTarget;
        if(element.id=="")
            element.id = "jQuery"+Math.ceil(Math.random()*100000000);
        if(!$(element).data("freezeTooltip"))
        {
            if(element.tagName=='IMG')
            {
                var img_src = $(element).parents("a[rel=" + element.id + "]").attr("href");
                var src = (img_src && $(element).getxTooltipSetting("useParentLink")) ? img_src : element.src;
                var alt = (element.alt != "") ? element.alt : element.title;
            }
            var rand = Math.ceil(Math.random()*100000000);
            var tooltipId = $(element).getxTooltipSetting("tooltipClass")+rand;
            $(element).data("tooltipId", tooltipId);

            var tooltipHtml = "<div id='"+tooltipId+"' class='xTooltip "+$(element).getxTooltipSetting("tooltipClass")+" "+$(element).getxTooltipSetting("addClass")+"'>";

            if($(element).getxTooltipSetting("customHtml"))
                tooltipHtml += $(element).getxTooltipSetting("customHtml");
            else if($(element).getxTooltipSetting("ajaxUrl"))
                tooltipHtml += $(element).getxTooltipSetting("ajaxPlaceholder");
            else
            {
                var text = (element.title != "") ? "<h3>" + element.title + "</h3>" : "";
                if(element.tagName=='IMG')
                    tooltipHtml += "<img src='" + src + "' alt='" + alt + "' title='" + element.title + "' />";
                tooltipHtml += text;
            }
            tooltipHtml += "</div>";
            
            if($(element).getxTooltipSetting("tooltipHover"))
                $(element).append(tooltipHtml);
            else
                $('body').append(tooltipHtml);
            if($(element).getxTooltipSetting("ajaxUrl"))
                $.post($(element).getxTooltipSetting("ajaxUrl"), $(element).getxTooltipSetting("ajaxParams"), function(data){$("#"+tooltipId).html(data)});
            
            if(element.tagName=='IMG')
            {
                $(element).data("altBk", $(element).attr('alt'));
                $(element).attr('alt','');
            }
            $(element).data("titleBk", $(element).attr('title'));
            $(element).attr('title','');

            if($(element).getxTooltipSetting("maxWidth"))
                $("#"+tooltipId).css("max-width", $(element).getxTooltipSetting("maxWidth"));
            if(element.tagName=='IMG')
                $("#"+tooltipId+" img").css("max-width", parseInt($("#"+tooltipId).css("max-width")) - parseInt($("#"+tooltipId+" img").css("padding-left")) - parseInt($("#"+tooltipId+" img").css("padding-right")));
            
            setPosition(e);
            setTimeout("$('#"+tooltipId+"').fadeIn("+$(element).getxTooltipSetting("fadeInDuration")+")",$(element).getxTooltipSetting("delay"));
            if($(element).getxTooltipSetting("track"))
                $(element).mousemove(function(e) { setPosition(e); });
        }
    }

    function hideTooltip(e)
    {
        var element = e.currentTarget;
        var tooltipElement = $("#"+$(element).data("tooltipId"));
        
        if(!$(element).data("freezeTooltip"))
        {
            $(tooltipElement).fadeOut($(element).getxTooltipSetting("fadeOutDuration"),function(){
                $(this).remove();
            });
            if($(element).getxTooltipSetting("track"))
                $(element).unbind('mousemove');
            
            $(element).data("tooltipId",null);
            $(element).attr('alt',$(element).data("altBk"));
            $(element).attr('title',$(element).data("titleBk"));
        }
    }

    function setPosition(e)
    {
        var element = e.currentTarget;
        var tooltipElement = $("#"+$(element).data("tooltipId"));
        
        if(!$(element).data("freezeTooltip"))
        {
            var xPosition = $(element).getxTooltipSetting("xPosition");
            var yPosition = $(element).getxTooltipSetting("yPosition");
            var x, y;
            
            if(yPosition == "top")
            {
                y = getTopY(e, element);
                if($(element).getxTooltipSetting("smartPosition") && getTopY(e, element) < 0)
                {
                    if(tryBottomY(e, element))
                        y = getBottomY(e, element);
                    else
                        y = $(window).height() - $(tooltipElement).height();
                }
            }
            else
            {
                y = getBottomY(e, element);
                if($(element).getxTooltipSetting("smartPosition") && !tryBottomY(e, element))
                {
                    if(getTopY(e, element) >= 0)
                        y = getTopY(e, element);
                    else
                        y = 0;
                }
            }
            
            if(xPosition == "left")
            {
                x = getLeftX(e, element);
                if($(element).getxTooltipSetting("smartPosition") && getLeftX(e, element) < 0)
                {
                    if(tryRightX(e, element))
                        x = getRightX(e, element);
                    else if(y != 0)
                        x = $(window).width() - $(tooltipElement).width();
                }
            }
            else
            {
                x = getRightX(e, element);
                if($(element).getxTooltipSetting("smartPosition") && !tryRightX(e, element))
                {
                    if(getLeftX(e, element) >= 0)
                        x = getLeftX(e, element);
                    else if(y != 0)
                        x = 0;
                }
            }

            $(tooltipElement).css("top", y + "px").css("left", x + "px");
        }
    }

    function getLeftX(e, element)
    {
        var tooltipElement = $('#'+$(element).data("tooltipId"));
        return e.pageX - $(element).getxTooltipSetting("xOffset") - $(tooltipElement).width();
    }

    function getRightX(e, element)
    {
        return e.pageX + $(element).getxTooltipSetting("xOffset");
    }

    function tryRightX(e, element)
    {
        var tooltipElement = $('#'+$(element).data("tooltipId"));
        return (e.clientX + $(element).getxTooltipSetting("xOffset") + $(tooltipElement).width() <= $(window).width());
    }

    function getTopY(e, element)
    {
        var tooltipElement = $('#'+$(element).data("tooltipId"));
        return e.pageY - $(element).getxTooltipSetting("yOffset") - $(tooltipElement).height();
    }

    function getBottomY(e, element)
    {
        return e.pageY + $(element).getxTooltipSetting("yOffset");
    }

    function tryBottomY(e, element)
    {
        var tooltipElement = $('#'+$(element).data("tooltipId"));
        return (e.clientY + $(element).getxTooltipSetting("yOffset") + $(tooltipElement).height() <= $(window).height());
    }
    
    function hoverHandler(e){
        var element = e.currentTarget;
        if($(element).data("xTooltip")==null)
            $(element).setxTooltipSettings(e.data);
        if (e.type == "mouseover" || e.type == "mouseenter")
            showTooltip(e);
        else
            hideTooltip(e);
    }

    function clickHandler(e)
    {
        var element = e.currentTarget;
        if($(element).data("xTooltip")==null)
            $(element).setxTooltipSettings(e.data);
        if((!$.browser.msie && e.button == 0) || ($.browser.msie && e.button == 1))
        {
            if($(element).getxTooltipSetting("clickToOpen"))
            {
                if($(element).data("tooltipId")==null)
                    showTooltip(e);
                else if(e.target.tagName!="A")
                    hideTooltip(e);
            }
        }
        else
        {
            if($(element).getxTooltipSetting("freezeMode"))
            {
                toggleFreeze(e);
                return false;
            }
        }
        return true;
    }

    function contextMenuHandler()
    {
        return false;
    }

    function toggleFreeze(e)
    {
        var element = e.currentTarget;
        $(element).data("freezeTooltip", !$(element).data("freezeTooltip"));
    }

    $.fn.extend({
        xTooltip: function(userSettings)
        {          
            var settings = $.extend({}, $.xTooltip.defaults, userSettings);
            if(settings.tooltipHover)
            {
                settings.xOffset = 0;
                settings.yOffset = 0;
                settings.fadeOutDuration = 0;
                settings.track = false;
            }

            if(!settings.clickToOpen)
            {
                if(settings.liveBind)
                {
                    $(this).die("hover", hoverHandler);
                    $(this).live("hover", settings, hoverHandler);
                }
                else
                {
                    $(this).bind("hover", settings, hoverHandler);
                }

            }

            if (settings.liveBind)
            {
                $(this).die("mousedown", clickHandler);
                $(this).live("mousedown", settings, clickHandler);
            }
            else
            {
                $(this).bind("mousedown", settings, clickHandler);
            }

            if(settings.freezeMode)
            {
                if (settings.liveBind)
                {
                    $(this).die("contextmenu", contextMenuHandler);
                    $(this).live("contextmenu", contextMenuHandler);
                }
                else
                {
                    $(this).bind("contextmenu", contextMenuHandler);
                }
            }
        },
        getxTooltipSetting: function(name)
        {
            settings = $(this).data("xTooltip");
            return settings[name];
        },
        setxTooltipSettings: function(settings)
        {
            $(this).data("xTooltip", settings);
        }
    });
})(jQuery);


// rispetto a (base 3)
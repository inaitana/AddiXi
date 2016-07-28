<?php
class MicOffMenu_HashNavigation {
    function getJavascript()
    {
        return "
            function updateHash() {
                if(getBusy()!=true)
                    $.address.update();
            }

            function handleHash(notAjax) {
                getBusy(true);

                dialog = $.address.parameter('dialog');
                if(dialog)
                {
                    dialogOk = false;
                    try
                    {
                        loading();
                        eval('open' + dialog + '()');
                        loading(false);
                        dialogOk = true;
                    }
                    catch(noFunction)
                    {}
                }
                else
                {
                    $('.orbPopup, .dialog-container').dialog('close');
                }

                if(!window.stopUpdate)
                {
                    loading();
                    action = $.address.parameter('action');
                    if(action)
                    {
                        try
                        {
                            eval('do' + action + '()');
                            handleParameters();
                        }
                        catch(noFunction)
                        {
                            parameters = buildParametersArray();
                            if($('a[rel=' + action + ']').length)
                                href = $('a[rel=' + action + ']').attr('href');
                            else
                            {
                                actionArray = action.split('-');
                                baseURL = $.address.baseURL();
                                if(actionArray.length==1)
                                    href = baseURL + '/' + action.toLowerCase();
                                else
                                {
                                    action = actionArray[0];
                                    controller = actionArray[1];
                                    baseURL = baseURL.substring(0,baseURL.lastIndexOf('/'));
                                    href = baseURL + '/' + controller.toLowerCase() + '/' + action.toLowerCase();
                                }
                            }
                            $('#content').load(href, parameters, function(){handleParameters();});
                        }
                    }
                    else if(!notAjax)
                    {
                        $('#content').load($.address.baseURL(), buildParametersArray(), function(){handleParameters();});
                    }
                }
                getBusy(false);
            }

            function handleParameters()
            {
                parametersList = $.address.parameterNames();
                for (i=0;i<parametersList.length;i++)
                {
                    parameterName = parametersList[i];
                    if(parameterName != 'action')
                    try
                    {
                        eval('set' + parameterName + '(\'' + $.address.parameter(parameterName) + '\')');
                    }
                    catch(noSetter)
                    {
                        eval(parameterName + '= \'' + $.address.parameter(parameterName) + '\'');
                    }
                }

                dialog = $.address.parameter('dialog');
                if(dialog && !dialogOk)
                {
                    try
                    {
                        eval('open' + dialog + '()');
                        dialogOk = true;
                    }
                    catch(noFunction)
                    {}
                }
                loading(false);
            }

            function buildParametersArray()
            {
                parametersArray = new Array();
                parametersList = $.address.parameterNames();

                for (i=0;i<parametersList.length;i++)
                {
                    parameterName = parametersList[i];
                    if(parameterName != 'action')
                    {
                        parameter = new Object();
                        parameter['name'] = parameterName.toLowerCase();
                        parameter['value'] = $.address.parameter(parameterName);
                        parametersArray.push(parameter);
                    }
                }
                parameterAjax = new Object();
                parameterAjax['name'] = 'ajax';
                parameterAjax['value'] = true;
                parametersArray.push(parameterAjax);

                return parametersArray;
            }

            function getBusy(busy) {
                if(busy==true && window.busy == false)
                {
                    $('#tooltip').fadeOut(100);
                    $(window.lastclicked).after('<span id=\"loading\" class=\"loading\"><img class=\"imgloading\" src=\"/css/images/loading.gif\"/></span>');
                    $('#loading').hide().fadeIn(300);
                    window.busy = true;
                }
                else if(busy==false)
                {
                    $('.loading').fadeOut(300, function(){ $('.loading').remove() });
                    window.busy = false;
                }
                return window.busy;
            }

            function closeDialog()
            {
                window.stopUpdate = true;
                $.address.parameter('dialog',null);
                updateHash();
                window.stopUpdate = false;
            }

            function loading(status)
            {
                if (status || typeof(status)=='undefined')
                    $('body').append('<div id=loading-growl-container class=loading-growl-container><div id=loading-growl class=loading-growl><img src=\"/css/images/ajax-loader.gif\"/><span>Caricamento in corso...</span></div></div>');
                else
                    $('.loading-growl-container').remove();
            }

            $.address.autoUpdate(false);
            $.address.change(function(event) {
                handleHash();
            });

            $('.momButton').live('click',function(e) {
                if(!$(e.currentTarget).hasClass('disabled'))
                {
                    $.address.parameter('action',$(this).attr('rel'));
                    updateHash();
                }
                return false;
            });

            $('.mom a').live('mousedown',function(e) {
                return false;
            });

            $('.dialogButton').live('click',function(e) {
                if(!$(e.currentTarget).hasClass('disabled'))
                {
                    window.stopUpdate = true;
                    $.address.parameter('dialog',$(this).attr('rel'));
                    updateHash();
                    window.stopUpdate = false;
                }
                return false;
            });
            ";
    }
}
?>

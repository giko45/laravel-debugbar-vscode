<style>
    .phpdebugbar-widgets-list-item {
        align-items: baseline !important;
    }

    .phpdebugbar-plugin-vscodebutton {
        font-size: 12px !important;
        display: inline-block !important;
        color: #000 !important;
        border: 1px solid #9d9090 !important;
        border-radius: 3px !important;
        box-shadow: 0px 2px 3px #00000069 !important;
        padding-left: 8px !important;
        padding-right: 8px !important;
        padding-top: 4px !important;
        padding-bottom: 4px !important;
        margin-left: 8px !important;
        margin-right: 4px !important;
        margin-bottom: 0px !important;
        cursor: pointer !important;
    }
</style>
<?php $isLinux = DIRECTORY_SEPARATOR === '/'; ?>
<script>
    var isLinux = {{ $isLinux ? 'true' : 'false' }};
    var extraSlash = (isLinux) ? '/' : '';

    // var phpdebugbar_plugin_vscode_mIsLoaded = false;

    function phpdebugbar_plugin_onBtnVscodeClicked(ev, el) {
        window.location.href = $(el).data('link');
        event.stopPropagation();
    }

    var phpdebugbar_plugin_vscode_onInit = function () {
        if ($) {
            // OK
        } else {
            // jQuery not yet available
            return;
        }

        if ($('.phpdebugbar-openhandler-overlay').is(":visible")) {
            return;
        }

        if ($('.phpdebugbar').length) {
            // OK
        } else {
            // laravel-debugbar not yet available
            return;
        }

        if ($('.phpdebugbar').hasClass('already-binded')) {
            return;
        }

        $('.phpdebugbar').addClass('already-binded');

        if ($('.phpdebugbar-open-btn').length) {
            if (!$('.phpdebugbar-open-btn').hasClass('click-listened')) {
                $('.phpdebugbar-open-btn').addClass('click-listened');
                $('.phpdebugbar-open-btn').click(function () {
                    $('.phpdebugbar').removeClass('already-binded');
                });
            }
        }

        if ($('.phpdebugbar-datasets-switcher').length) {
            if (!$('.phpdebugbar-datasets-switcher').hasClass('click-listened')) {
                $('.phpdebugbar-datasets-switcher').addClass('click-listened');
                $('.phpdebugbar-datasets-switcher').change(function () {
                    $('.phpdebugbar').removeClass('already-binded');
                });
            }
        }

        $(function onDocumentReady() {
            function getSchemeName() {
                return "{{ 'vscode' }}";
            }

            function getBasePath() {
                return "{{ str_replace('\\', '/', base_path()) }}" + extraSlash;
            }

            function isPhp(str) {
                return str.indexOf('.php') != -1;
            }

            function isController(str) {
                return str.indexOf('.php:') != -1;
            }

            function isBlade(str) {
                return str.indexOf('.blade.php') != -1;
            }

            function getLink(str) {
                var result = '';

                result += getSchemeName();
                result += '://file/';
                result += getBasePath();

                str = str.replace(/^[\u00a0\.\ 0-9]*/,'');
                if (isBlade(str)) {
                    var iRes = str.indexOf('resources');
                    if (iRes != -1) {
                        if (!isLinux) {
                            // (\resources...)
                            iRes--; // to remove '\'
                        }
                        str = str.substring(iRes);
                        var iViews = str.indexOf('views');
                        if (iViews != -1) {
                            var iEnd = str.indexOf(')', iViews);
                            if (iEnd != -1) {
                                str = str.substring(0, iEnd);
                                result += str;
                            }
                        }
                    }
                } else if (isController(str)) {

                        str = str.replace(/-[0-9]*$/,'');
                        result += str
                }

                return result;
            }

            var funOnHoverIn = function (e) {
                e.stopPropagation();

                var str = $(this).text();
                if (isPhp(str) || isBlade(str) || isController(str)) {
                    // OK
                } else {
                    // Unknown format
                    return;
                }

                if (str.indexOf('vscode_debugbar_plugin') == -1) {
                    // OK
                } else {
                    // Don't add button to this plugin view path
                    return;
                }

                var strFullPath = getLink(str);

                if (isBlade(str)) {
                    var oldHtml = $(this).parent().html();
                    var strNewLink = '';
                    if (oldHtml.indexOf('phpdebugbar-plugin-vscodebutton') == -1) {
                        strNewLink = '<a class="phpdebugbar-plugin-vscodebutton" onclick="phpdebugbar_plugin_onBtnVscodeClicked(event, this);" data-link="' + strFullPath + '" title="' + strFullPath + '">' +  '&#9998;' +  '</a>';
                    }
                    $(strNewLink).insertAfter($(this));
                } else if (isController(str)) {
                    var oldHtml = $(this).html();
                    var strNewLink = '';
                    if (oldHtml.indexOf('phpdebugbar-plugin-vscodebutton') == -1) {
                        strNewLink = '<a class="phpdebugbar-plugin-vscodebutton" onclick="phpdebugbar_plugin_onBtnVscodeClicked(event, this);" data-link="' + strFullPath + '" title="' + strFullPath + '">' +  '&#9998;' +  '</a>';
                    }
                    $(strNewLink).appendTo($(this));
                }
            };

            var funOnHoverOut = function (e) {
                e.stopPropagation();
            };

            $('.phpdebugbar span.phpdebugbar-widgets-name').hover(funOnHoverIn, funOnHoverOut);
            $('.phpdebugbar dd.phpdebugbar-widgets-value').hover(funOnHoverIn, funOnHoverOut);
            $('.phpdebugbar li.phpdebugbar-widgets-table-list-item').hover(funOnHoverIn, funOnHoverOut);
            $('.phpdebugbar span.phpdebugbar-widgets-stmt-id').hover(funOnHoverIn, funOnHoverOut);
        });

        // phpdebugbar_plugin_vscode_mIsLoaded = true;
        // clearInterval(phpdebugbar_plugin_vscode_mInterval);
    }

    var phpdebugbar_plugin_vscode_mInterval = setInterval(phpdebugbar_plugin_vscode_onInit, 2000);
</script>

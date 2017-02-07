var languageTabs = (function ($) {

    var PREFIX = 'il_prop_cont_';

    // Public
    // ----------------------------------------------------------------------------------------

    var useTabs = function (post_var, default_lang, languages) {
        var $inputs = $('.form-group[id^="' + PREFIX + post_var + '"]');
        var $tabs = $('<div>')
            .addClass('srmd-lang-tabs')
            .addClass('form-group');
        $tabs.insertBefore($inputs.first());
        $tabs.append($('<div>').addClass('col-sm-3'));
        var $tabContainer = $('<div>').addClass('col-sm-9');
        $tabs.append($tabContainer);
        $.each(languages, function (j, lang) {
            var $tab = $('<a>')
                .addClass('srmd-lang-tab')
                .attr('href', '#')
                .text(lang)
                .click(function (event) {
                    $inputs.hide();
                    $('#' + PREFIX + post_var + '_' + lang).show();
                    $tab.siblings('.srmd-lang-tab').removeClass('active');
                    $tab.addClass('active');
                    event.preventDefault();
                });
            if (lang == default_lang) {
                $tab.trigger('click');
            }
            $tabContainer.append($tab);
        });
    };

    return {
        useTabs: useTabs
    };

})($);

var srmd = srmd || {};
srmd.languageTabs = languageTabs;
$.fn.gearsApp = function(pages) {

    let that = this;

    function init() {
        let navLinks = $('a.nav-link[data-page]');
        // app navigation
        navLinks.each(function(i, e) {
            $(e).click(function(event) {
                event.preventDefault();
                navLinks.each(function(i, e) {
                    $(e).removeClass('is-active');
                });
                $(e).addClass('is-active');
                that.loadPage($(e).attr('data-page'));
            });
        });
    }

    this.hasOptions = function(name) {
        return pages[name] !== undefined;
    };

    this.getOptions = function(name) {
        console.log("name: " + name);
        return pages[name].options;
    };

    this.setOptions = function(name, options) {
        pages[name].options = options;
        // TODO: save to DB
    };

    this.loadPage = function(name) {
        $.get(pages[name].url, function(content) {
            $('#content').html(content);
        });
    };

    /*
    this.post = function(url, data, resultContainer) {
        $.post(url, data, function(content) {
            $(resultContainer).html(content);
        });
    }

    // locale switcher
    $(function() {

        $('#locale_hu').click(function() { setLocale('hu'); });
        $('#locale_en').click(function() { setLocale('en'); });

        function setLocale(locale) {
            options.locale = locale;
            const text = $('#LanguageDropdown span');
            const icon = $('#LanguageDropdown i');
            flag = locale == 'en' ? 'us' : locale;
            icon.removeClass();
            icon.addClass(['flag-icon', 'flag-icon-' + flag]);
            text.text(locale == 'en' ? 'English' : 'Magyar');
            table.refresh();
        }

    });
    */

    init();

    return this;
};

$(function () {
    var isPageHasMenu = $('[piwik-reporting-menu]').size();
    var isPageIsAdmin = $('#content.admin').size();
    if (isPageHasMenu) {
        return;
        piwikMenu = new menu();
        piwikMenu.init();
        piwikMenu.loadFirstSection();
    }

    if(isPageIsAdmin) {
        // don't use broadcast in admin pages
        return;
    }
    if(isPageHasMenu) {
        broadcast.init();
    } else {
        broadcast.init(true);
    }
});

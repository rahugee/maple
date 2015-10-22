define({
    name: "maple.statbox",
    extend: "spamjs.view",
    using: ["maple.webinfo", "jqrouter"]
}).as(function (statbox, WEBINFO, ROUTER) {

    return {
        routerEvents: {
            "?categories=": "categories_change"
        },
        categories_change: function () {
            this.selected = ROUTER.getQueryParam('categories') || [];
            this.set_cats(this.selected);
        },
        _init_: function () {
            this.router = ROUTER.instance().bind(this);
            var self = this;
            console.error("WEBINFO", WEBINFO)
            if (this.options.type === "CATEGORIES") {
                self.$$.loadTemplate(
                    self.path("catpanal.html"),
                    WEBINFO.getCategories().then(function (resp) {
                       return Object.keys(resp).map(function (catid) {
                            return resp[catid];
                        }).sort(function (cat1, cat2) {
                            return cat1.info.displayorder - cat2.info.displayorder
                        });
                    })
                ).done(function () {
                        self.set_cats(self.selected);
                    })
                ;
            } else {
                self.$$.loadTemplate(
                    self.path("statbox.html"),
                    WEBINFO.getStats()
                );

            }
        },
        set_cats: function (selected) {
            var self = this;
            self.selected = selected || [];
            self.$$.find("[value]").removeClass("list-group-item-info");
            self.selected.map(function (cat) {
                self.$$.find("[value='" + cat + "']").addClass("list-group-item-info");
            });
        },
        _remove_: function () {
            this.router.off();
        }
    };

});
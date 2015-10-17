define({
    name: "maple.statbox",
    extend: "spamjs.view",
    using: ["maple.webinfo", "jqrouter"]
}).as(function (statbox, WEBINFO, ROUTER) {

    return {
        routerEvents : {
            "?categories=" : "categories_change"
        },
        categories_change : function(){
            this.selected = ROUTER.getQueryParam('categories') || [];
            this.set_cats(this.selected);
        },
        _init_: function () {
            this.router = ROUTER.instance().bind(this);
            var self = this;
            console.error("WEBINFO", WEBINFO)
            if (this.options.type === "CATEGORIES") {
                WEBINFO.getCategories().done(function (resp) {
                    var cats = Object.keys(resp).map(function (catid) {
                        return resp[catid];
                    }).sort(function (cat1, cat2) {
                        return cat1.info.numitems - 0 < cat2.info.numitems - 0
                    });
                    self.view("catpanal.html", cats).done(function () {
                        self.set_cats(self.selected);
                    })
                });
            } else {
                WEBINFO.getStats().done(function (resp) {
                    self.view("statbox.html", resp).done(function () {
                        //console.error("----",self.$$.html());
                    })
                });
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
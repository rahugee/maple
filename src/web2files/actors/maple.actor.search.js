define({
    name: "maple.actor.search",
    extend: "spamjs.view",
    using: ["jqrouter","DataService"]
}).as(function (search, jqrouter,DataService) {

    return {
        routerEvents: {
            "?search=" : "actor_results"
        },
        _init_: function () {
            var self = this;
            return this.$$.loadTemplate(
                this.path("actor.search.html")
            ).done(function () {
                    self.router = jqrouter.instance().bind(self);
                    self.router.setQueryParams(self.router.getQueryParams({ search : ""}))
            });
        },
        actor_results: function () {
            this.$$.find("#actor_results").loadTemplate(
                this.path("actor.search.item.html"),
                DataService.get("actor_search",{ search : this.router.getQueryParam("search")})
            );
        }
    }

});
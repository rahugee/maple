define({
    name: "maple.actor.profile",
    extend: "spamjs.view",
    using: [ "DataService","maple.storieslist","maple.storieslist"]
}).as(function (profile,SERVER,STORIESLIST) {

    return {
        _init_: function () {
            var self = this;
            var stories = STORIESLIST.instance({
                id: "storieslist",
                options: {
                    stories: SERVER.get("actor_stories", {aid: self.options.aid})
                }
            });

            self.$$.loadTemplate(
                self.path("actor.html"),
                SERVER.get("actor_details", {aid: self.options.aid})
            ).done(function () {
                    self.add(stories)
                });
        },
        _remove_: function () {

        }
    }

});
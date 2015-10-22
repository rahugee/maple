define({
    name: 'maple.author',
    extend: 'spamjs.view',
    using: ["DataService", "maple.storieslist"]
}).as(function (author, SERVER, STORIESLIST) {

    return {
        _init_: function () {
            var self = this;
            var stories = STORIESLIST.instance({
                id: "storieslist",
                options: {
                    stories: SERVER.get("user_stories", {uid: self.options.uid})
                }
            });

            self.$$.loadTemplate(
                self.path("author.html"),
                SERVER.get("user_details", {uid: self.options.uid})
            ).done(function () {
                self.add(stories)
            });
        },
        _remove_: function () {

        }
    };

})
;
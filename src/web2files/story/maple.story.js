define({
    name: 'maple.story',
    extend: "spamjs.view",
    using: ["DataService", "jsutils.cache", "jqrouter"]
}).as(function (story, SERVER, CACHEUTIL, jqrouter) {

    var chapterComments = CACHEUTIL.instance("chapterComments");

    return {
        _init_: function () {
            console.error("----", this.options.sid);
            var self = this;
            SERVER.get("view_story", {
                sid: self.options.sid
            }).done(function (story) {
                self.router = jqrouter.bind(self);
                self.story = story;
                console.warn("----", self.options.sid, story)
                self.$$.loadTemplate(self.path('story.html'), {
                    info: story.info,
                    stats: story.stats
                }).done(function () {
                    self.setView();
                });
            });
        },
        setView: function () {
            var self = this;
            self.router.on("#/info", function () {
                self.$$.find(".read_section").loadTemplate({
                    src: self.path("story_details.html"),
                    data: self.story
                });
            }).on("#/chapter/{cid}", function (e) {
                self.$$.find(".read_section").loadTemplate({
                    src: self.path("chapter_read.html"),
                    data: {chapter: self.story.chapters[e.params.cid - 1], info: self.story.info}
                });
            }).on("#/comments/{cid}/{chapid}", function (e) {

                chapterComments.load("comments:" + e.params.chapid, function () {
                    return SERVER.get("view_comments", {
                        sid: self.options.sid,
                        chapid: e.params.chapid
                    });
                }, true).progress(function (comments) {
                    console.info("rendering..", comments)
                    self.$$.find(".read_section").loadTemplate({
                        src: self.path("chapter_discuss.html"),
                        data: {
                            comments: comments,
                            chapter: self.story.chapters[e.params.cid - 1],
                            info: self.story.info
                        }
                    });
                });
            }).defaultRoute("#/info");
        },
        _remove_: function () {
            this.router.off();
        }
    };

});
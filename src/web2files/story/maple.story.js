define({
    name: 'maple.story',
    extend: "spamjs.view",
    using: ["DataService", "jsutils.cache", "jqrouter"]
}).as(function (story, SERVER, CACHEUTIL, jqrouter) {

    var chapterComments = CACHEUTIL.instance("chapterComments");

    return {
        events : {"click .submit_comment" : "submit_comment"},
        _init_: function () {
            console.error("----", this.options.sid);
            var self = this;
            SERVER.get("view_story", {
                sid: self.options.sid
            }).done(function (storyResponse) {
                self.router = jqrouter.instance().bind(self);
                self.story = storyResponse;
                console.warn("---->>>", self.options.sid, storyResponse)
                self.$$.loadTemplate(self.path('story.html'), {
                    info: storyResponse.info,
                    stats: storyResponse.stats
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
                console.error("-----", e)
                self.$$.find(".read_section").loadTemplate({
                    src: self.path("chapter_read.html"),
                    data: {chapter: self.story.chapters[e.params.cid - 1], info: self.story.info}
                }).done(function () {
                    self.$$.find('.chapter-pagination-here').bootpag({
                        total: self.story.chapters.length,          // total pages
                        page: e.params.cid,            // default page
                        maxVisible: 3,     // visible pagination
                        leaps: false,         // next/prev leaps through maxVisible,
                        prev: "Prev", next: "Next",
                       // firstLastUse: true,
                       // first: "First", last: "Last",
                        wrapClass : 'pagination pagination-sm'
                    }).on("page", function (event, num) {
                        self.router.go("#/chapter/" + num);
                    });
                });
            }).on("#/comments/{cid}/{chapid}/*", function (e) {
                var lastPage = Math.ceil(self.story.chapters[e.params.cid - 1].reviews / COMMENTS_PAER_PAGE) || 1;
                var thisPage = e.params._ || lastPage;
                self.options.chapid =  e.params.chapid;
                chapterComments.load("comments:" + e.params.chapid + ":" + e.params._, function () {
                    return SERVER.get("view_comments", {
                        sid: self.options.sid,
                        chapid: e.params.chapid,
                        page: thisPage - 1
                    });
                }, true).progress(function (comments) {
                    self.$$.find(".read_section").loadTemplate({
                        src: self.path("chapter_discuss.html"),
                        data: {
                            comments: comments.reverse(),
                            chapter: self.story.chapters[e.params.cid - 1],
                            info: self.story.info
                        }
                    }).done(function () {
                        console.error("hi", self.$$.find(".comments-pagination-here"))
                        self.$$.find(".comments-pagination-here").bootpag({
                            total : lastPage,
                            page : thisPage,
                            maxVisible: 5,     // visible pagination
                            leaps: false,         // next/prev leaps through maxVisible,
                            firstLastUse: true,
                            first: "First", last: "Last"
                        }).on("page", function (event, num) {
                            self.router.go("#/comments/" + e.params.cid + "/" + e.params.chapid + "/" + num);
                        });
                    });
                })
            }).defaultRoute("#/info");
        },
        submit_comment :  function(){
            var self = this;
            SERVER.post("comment_add", {
                sid: this.options.sid,
                chapid: this.options.chapid,
                rating: 3, review : this.$$.find(".story_comment").val()
            }).done(function(){
                window.location.reload();
               // self.router.reload();
            });
        },
        _remove_: function () {
            this.router.off();
        }
    };

});
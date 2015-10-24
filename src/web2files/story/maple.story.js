define({
    name: 'maple.story',
    extend: "spamjs.view",
    using: ["DataService", "jsutils.cache", "jqrouter"]
}).as(function (story, SERVER, CACHEUTIL, jqrouter) {

    var chapterComments = CACHEUTIL.instance("chapterComments");

    return {
        events : {"click .submit_comment" : "submit_comment"},
        routerEvents : {
            "#/comments/{cid}/{chapid}/*" : "showComments",
            "#/info" : "showChapterList",
            "#/chapter/{cid}" : "showChapter"
        },
        _init_: function () {
            console.error("----", this.options.sid);
            var self = this;
            SERVER.get("view_story", {
                sid: self.options.sid
            }).done(function (storyResponse) {
                self.router = jqrouter.instance();
                self.story = storyResponse;
                console.warn("---->>>", self.options.sid, storyResponse)
                self.$$.loadTemplate(self.path('story.html'), {
                    info: storyResponse.info,
                    stats: storyResponse.stats
                }).done(function () {
                   // self.router.bind(self).otherwise("#/info"); //.defaultRoute("#/info");
                    self.router.defaultRoute = (debounce(self.router.defaultRoute));
                    self.router.bind(self).defaultRoute("#/info");
                    //defaultRoute
                });
            });
        },

        setView: function () {
            var self = this;
            self.router.on("#/info", function (e) {

            }).on("#/chapter/{cid}", function (e) {
                console.error("-----", e)

            });//.defaultRoute("#/info");
        },
        showChapter : function(e,target,data){
            var self = this;
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
        },
        showChapterList : function(e,target,data){
            var self = this;
            self.$$.find(".read_section").loadTemplate({
                src: self.path("story_details.html"),
                data: self.story
            });
        },
        showComments : function(e,target,data){
            var self = this;
            var lastPage = Math.ceil(self.story.chapters[e.params.cid - 1].reviews / COMMENTS_PAER_PAGE) || 1;
            if(!e.params._){
                self.router.go("#/comments/"+e.params.cid+"/"+e.params.chapid+"/"+lastPage);
                return;
            }
            var thisPage = e.params._ || lastPage;
            self.options.chapid =  e.params.chapid;
            self.options.cid =  e.params.cid;
            chapterComments.load("comments:" + e.params.chapid + ":" + thisPage, function () {
                return SERVER.get("view_comments", {
                    sid: self.options.sid,
                    chapid: e.params.chapid,
                    page: thisPage - 1
                });
            }, true).progress(function (comments) {
                self.$$.find(".read_section").loadTemplate({
                    src: self.path("chapter_discuss.html"),
                    data: {
                        comments: comments,//.reverse(),
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
        },
        submit_comment :  function(){
            var self = this;
            SERVER.post("comment_add", {
                sid: self.options.sid,
                chapid: self.options.chapid,
                rating: 3, review : self.$$.find(".story_comment").val()
            }).done(function(resp){
                self.story.chapters[self.options.cid - 1].reviews = resp.chapter.reviews;
               self.router.go("#/comments/"+self.options.cid+"/"+self.options.chapid+"/");
            });
        },
        _remove_: function () {
            this.router.off();
        }
    };

});
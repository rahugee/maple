define({
    name: "maple.searchbar",
    extend: "spamjs.view",
    using: ["DataService", "jqrouter", "jqbus"]
}).as(function (searchbar, SERVER, ROUTER, jqbus) {

    var autoCache = {};

    return {
        events : {
          "change #srch-term" : "input_changed"
        },
        _init_: function () {
            this.selected = [];
            var self = this;
            this.bus = jqbus.instance();
            //this.style('searchbar.css');
            self.$$.loadView({
                src: self.path("searchbar.html")
            }).done(function () {
                //console.error("----",self.$$.html());
                var search = ROUTER.getQueryParam("search", "");
                self.$$.find('#srch-term').val(search);
                self.$$.find('#srch-term').typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 1
                }, {
                    name: 'search_text',
                    displayKey: 'search_text',
                    source: function (query, process) {
                        if (autoCache[query]) {
                            process(autoCache[query]);
                        } else {
                            return SERVER.get("story_autocomplete", {search: query || ""}).done(function (resp) {
                                autoCache[query] = resp.data;
                                return process(resp.data);
                            });
                        }
                    }
                }).on('typeahead:opened', function (e) {
                    console.debug('typeahead:opened', e.target.value)
                }).on('typeahead:selected', function (e) {
                    console.debug('typeahead:selected', e.target.value);
                    self.input_changed(e.target.value);
                    //return stories.triggerLoadResults(e.target.value);
                }).on('typeahead:autocompleted', function (e) {
                    console.debug('typeahead:autocompleted', e.target.value)
                    //return stories.triggerLoadResults(e.target.value);
                }).on('typeahead:cursorchanged', function (e) {
                    console.debug('typeahead:cursorchanged', e.target.value)
                    //return stories.triggerLoadResults(e.target.value);
                }).on('typeahead:hintUpdated', function (e) {
                    console.debug('typeahead:hintUpdated', e.target.value)
                    //return stories.triggerLoadResults(e.target.value);
                }).on('typeahead:closed', function (e) {
                    console.debug('typeahead:closed', e.target.value)
                    self.input_changed(e.target.value);
                    /// return stories.triggerLoadResults(e.target.value);
                });

            })
        },
        input_changed: function () {
            var value = this.$$.find('#srch-term').val();
            if (this.value !== value.trim()) {
                this.value = value.trim();
                ROUTER.setQueryParam("search", value);
            }
        }
    };


});
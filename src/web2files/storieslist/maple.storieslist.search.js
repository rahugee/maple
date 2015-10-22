define({ 
	name : "maple.storieslist.search",
	extend : "spamjs.view",
	modules : ["DataService","jqrouter","maple.webinfo"]
}).as(function(storieslist,DataService,ROUTER,WEB_INFO){
	
	return {
		globalEvents : {
			"search.text.changed" : "_init_"	
		},
		routerEvents : {
			"?order_by=" : "order_by_change",
			"?categories=" : "categories_change",
			"?language=" : "language_change",
			"?type=" : "type_change",
			"?search=" : "search_text_changed"
		} ,
		events : {
			"click .search-stories-more" : "search_more"
		},
		_init_ : function(){
			this.selected = [];
			var self = this;
			self.router = ROUTER.instance().bind(self);

			WEB_INFO.getClasses().done(function(info){
				self.langs = info.langs
				self.types = info.types
				self.read_filter();
			})
		},
		order_by_change : function(){
			this.filter.order_by = this.router.getQueryParam("order_by");
			this.set_tab(this.filter.order_by);
			this.on_filter_changed();
		},
		categories_change : function(){
			this.filter.categories = this.router.getQueryParam("categories");
			this.on_filter_changed();
		},
		language_change : function(){
			this.filter.language = this.router.getQueryParam("language");
			this.on_filter_changed();
		},
		type_change : function(){
			this.filter.type = this.router.getQueryParam("type");
			this.on_filter_changed();
		},
		search_text_changed : function(event,target,data){
			this.filter.search = this.router.getQueryParam("search");;
			this.on_filter_changed();
		},
		read_filter : function(){
			this.filter = this.router.getQueryParams({
				search : "", categories : undefined ,order_by : "updated",search_by : "all",
				language : "", type : ""
			});
			this.on_filter_changed();
		},
		set_tab : function(tabName){
			this.$$.find(".order_by a").removeClass("active");
			this.$$.find("a[value='"+tabName+"']").addClass("active");
		},
		on_filter_changed : debounce(function(){
			var self = this;
			self.filter.page = 0;
			return self.$$.loadTemplate(
				self.path("search_result.html"),
				DataService.get("storieslist",self.filter).then(function(resp){
					return {
						stories :resp,
						langs : self.langs,
						language : self.filter.language,
						types : self.types,
						type : self.filter.type,
						hasMore : true
					};
				})
			).done(function(){
				self.set_tab(self.filter.order_by);
			});
		},600),
		search_more : function(e, target,data){
			var self =  this;
			var $newResults = jQuery("<div/>");
			self.filter.page = ++data.page;
			$newResults.loadTemplate(
				self.path("storieslist.html"),
				DataService.get("storieslist",self.filter).then(function(resp){
					return { stories : resp }
				})
			);
			self.$$.find(".search_result_wrapper").append($newResults)
		},
		_remove_ : function(){
			this.router.off();
		}
	};
	
});
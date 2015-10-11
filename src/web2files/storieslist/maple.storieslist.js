define({ 
	name : "maple.storieslist",
	extend : "spamjs.view",
	modules : ["DataService","jqrouter","maple.webinfo","jqbus"]
}).as(function(storieslist,DataService,ROUTER,WEB_INFO,jqbus){
	
	return {

		globalEvents : {
			"search.text.changed" : "_init_"	
		},
		
		_init_ : function(){
			this.selected = [];
			var self = this;
			self.router = ROUTER.instance();
			self.pipe =  jqbus.bind(this);

			WEB_INFO.getClasses().done(function(info){
				self.langs = info.langs
				self.types = info.types
				self.read_filter();
				self.router.on("#&order_by=", function(order_by){
					self.filter.order_by = order_by;
					self.set_tab(order_by);
					self.on_filter_changed();
				});
				self.router.on("#&categories=", function(categories){
					self.filter.categories = categories;
					self.on_filter_changed();
				});
				self.router.on("#&language=", function(language){
					self.filter.language = language;
					self.on_filter_changed();
				});
				self.router.on("#&type=", function(type){
					self.filter.type = type;
					self.on_filter_changed();
				});
			})
		},

		search_text_changed : function(event,target,data){
			this.filter.search = data;
			this.on_filter_changed();
		},

		read_filter : function(){
			this.filter = ROUTER.getQueryParams({
				search : "", categories : undefined ,order_by : "updated",search_by : "all",
				language : "", type : ""
			});
			this.on_filter_changed();
		},
		
		set_tab : function(tabName){
			this.$$.find(".order_by a").removeClass("active");
			this.$$.find("a[href='#&order_by="+tabName+"']").addClass("active");
		},

		on_filter_changed : function(){
			var self = this;
			return DataService.get("storieslist",self.filter).then(function(resp){
				return self.view("search_result.html",{
					stories :resp,
					langs : self.langs,
					language : self.filter.language,
					types : self.types,
					type : self.filter.type
				}).done(function(){
					self.set_tab(self.filter.order_by);
				});
			});
		},
		
		language_change : function(e,target){
			ROUTER.setKey("language",$(target).val());
		},

		type_change : function(e,target){
			ROUTER.setKey("type",$(target).val());
		},
		
		_remove_ : function(){
			this.router.off();
			this.pipe.off();
		}
	};
	
});
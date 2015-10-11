define({ 
	name : "maple.statbox",
	extend : "spamjs.view",
	using : ["maple.webinfo","jqrouter"]
}).as(function(statbox,WEBINFO,ROUTER){
	
	statbox._init_ = function(){
		this.router = ROUTER.instance();
		this.selected = ROUTER.getQueryParam('categories') || [];
		var self = this;
		console.error("WEBINFO",WEBINFO)	
		if(this.options.type === "CATEGORIES"){
			this.router.on("#&categories=", function(categories){
				self.set_cats(categories);
			});
			WEBINFO.getCategories().done(function(resp){
				var cats = Object.keys(resp).map(function(catid){
					return resp[catid];
				}).sort(function(cat1,cat2){
					return cat1.info.numitems-0 < cat2.info.numitems-0
				});
				self.view("catpanal.html",cats).done(function(){
					self.set_cats(self.selected);
				})
			});
		} else {
			WEBINFO.getStats().done(function(resp){
				self.view("statbox.html",resp).done(function(){
					//console.error("----",self.$$.html());
				})
			});
		}
	};
	statbox.item_selected = function(e,target){
		var dataset = target.dataset;
		var catId= dataset.catId;
		var pos = this.selected.indexOf(catId); 
		if(pos == -1){
			this.selected.push(catId);
		} else {
			this.selected.splice(pos,1);
		}
		ROUTER.setKey("categories",this.selected);
		this.set_cats(this.selected);
	};
	
	statbox.set_cats = function(selected){
		var self = this;
		self.selected = selected;
		self.$$.find("[data-cat-id]").removeClass("list-group-item-info");
		self.selected.map(function(cat){
			self.$$.find("[data-cat-id='"+cat+"']").addClass("list-group-item-info");
		});
	};
	statbox._remove_ = function(){
		this.router.off();
	};
});
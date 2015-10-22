define({ 
	name : 'maple.home',
	extend : 'spamjs.view',
	//modules : ['maple.statbox',,'-maple.storieslist']
	using : ['maple.statbox','maple.searchbar','maple.storieslist.search']
}).as(function(home,STATBOX,SEARCHBAR,STORIESLIST){
	
	home._init_ = function(){
		var self = this;
		
		this.view('home.html',{}).done(function(){
			console.error("STATBOX",STATBOX)

			self.add("#stats",
				STATBOX.instance({
					id : "stats",
					type : "STATS"
				})
			);

			self.add("#cats",
				STATBOX.instance({
					id : "stats2",
					type : "CATEGORIES"
				})
			);

			self.add("#searchbox", 
				SEARCHBAR.instance({
					id : "searchbox"
				})
			);

			self.add("#storieslist", 
				STORIESLIST.instance({
					id : "storieslist"
				})
			);
			
		});

	};
	
	home._remove_ = function(){
		
	};
	
});
define({ 
	name : 'maple.author',
	extend : 'spamjs.view',
	using : ["DataService"]
}).as(function(author,SERVER){
	
	author._init_ = function(){
		var self = this;
		$.when(
			self.loadProfile(),
			self.loadStories()
		).done(function(){
			//self.renderStories();
		});
	};
	
	author.loadProfile = function(){
		var self = this;
		return SERVER.get("user_details",{uid : self.options.uid}).done(function(resp){
			console.info("profile_user",resp)
			self.profile = resp;
			self.renderProfile();
		});
	};
	
	author.loadStories = function(){
		var self = this;
		return SERVER.get("user_stories",{uid : self.options.uid}).done(function(resp){
			console.info("storieslist_user",resp)
			self.stories = resp;
			self.renderStories();
		});
	};
	
	author.renderProfile = function(){
		var self = this;
		self.load({
			src : "author.html",
			data : self.profile
		}).done(function(){
			self.renderStories();
		});
	};
	
	author.renderStories = function(){
		var self = this;
		if(self.stories){
			return self.load({
				selector : ".read_section",
				src : "../storieslist/storieslist.html",
				data : { stories : self.stories }
			});
		}
	};
	
	author._remove_ = function(){
	};
	
});
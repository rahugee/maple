define({
	name : "maple.app",
	extend : "spamjs.view",
	modules : ["jqrouter",'spamjs.navbar']
}).as(function(app,jqrouter,navbar){

	var CONST = bootloader.config().CONST;
	for(var CONST_KEY in CONST){
		window[CONST_KEY] = CONST[CONST_KEY];
	}
	console.error("----",bootloader.config().appContext);
	jqrouter.start(bootloader.config().appContext);
	return {
		events : {
			"click a[jqrouter]" : "routerNavigation"
		},
		routerEvents : {
			"/boot/*" : "openDevSection",
			"/stories/*" : "maple.home",
			"/story/{sid}/*" : "maple.story",
			"/user/{uid}/*" : 'maple.author'
		},
		_init_ : function(){
			var self = this;
			_importStyle_('maple/style');
			
			self.add(navbar.instance({
				id : 'topbar',
				position : 'fixed-top', 
				fluid : true,
				view : self.path("topbar/topbar.html")
			}));
			console.error("jqrouter");	
			self.router = jqrouter.instance().bind(this);
			self.router.otherwise("/stories")
		},
		_routerEvents_ : function(e,targetName,data){	
			var self = this;
			module(targetName, function(targetModule){
				console.error("e,target,data",e,targetModule,data)
				self.add(targetModule.instance({
					id : "main_module",
					options : e.params
				}));
			});
		},
		openDevSection: function(e,target,data) {
			console.error("openDevSection",e,target,data)
			var self = this;
			module("spamjs.bootconfig", function(myModule) {
				self.add(myModule.instance({
					id: "bootconfig",
					routerBase: "/boot/"
				}));
			});
		},
		routerNavigation : function(e,target){
			var link = target.getAttribute("href");
			if(link){
				jqrouter.go(link);
			}
			return preventPropagation(e);
		},
		_remove_ : function(){
			self.router.off();
		},
		_ready_ : function(){
			this.instance().addTo(jQuery("body"));
		}
	};

});
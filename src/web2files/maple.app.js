define({
	name : "maple.app",
	extend : "spamjs.view",
	modules : ["jqrouter",'spamjs.navbar','DataService']
}).as(function(app,jqrouter,navbar,DataService){

	_importStyle_('maple/style');
	
	var CONST = bootloader.config().CONST;
	for(var CONST_KEY in CONST){
		window[CONST_KEY] = CONST[CONST_KEY];
	}
	console.error("----",bootloader.config().appContext);
	jqrouter.start(bootloader.config().appContext);
	return {
		events : {
			"click [jqr-url]" : "routerNavigation",
			"click [jqr-click-param]"  :"routerQueryParamChange",
			"click [jqr-click-params]"  :"routerQueryParamUpdate",
			"change [jqr-change-param]"  :"routerQueryParamChange",
			"change [jqr-change-params]"  :"routerQueryParamUpdate",
			"click a[jqrouter]" : "routerNavigation",
			"change [jqrouter-param]"  :"routerQueryParamChange",
			"click a[jqrouter-param]"  :"routerQueryParamChange",
			"click a[jqrouter-params]"  :"routerQueryParamUpdate",
			"click [jqr-api]" : "call_api"
		},
		routerEvents : {
			"/boot/*" : "openDevSection",
			"/boot/config/" : "openDevSection",
			"/stories/*" : "maple.home",
			"/story/{sid}/*" : "maple.story",
			"/user/{uid}/*" : 'maple.author',
			"/actor/{aid}/*" : 'maple.actor.profile',
			"/actors/*" : "maple.actor.search"
		},
		_init_ : function(){
			var self = this;
			self.add(navbar.instance({
				id : 'topbar',
				position : 'fixed-top',
				fluid : true,
				view : self.path("topbar/topbar.html"),
				data : DataService.getUserDetails()
			}));
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
					id: "main_module",
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
		routerQueryParamChange : function(e,target){
			var param = target.getAttribute("jqr-change-param") ||
				target.getAttribute("jqr-click-param") ||
				target.getAttribute("jqrouter-param");
			if(param){
				jqrouter.setQueryParam(param,target.value || target.getAttribute("value"));
			}
			return preventPropagation(e);
		},
		routerQueryParamUpdate : function(e,target){
			var param = target.getAttribute("jqr-change-params") ||
				target.getAttribute("jqr-click-params") ||
				target.getAttribute("jqrouter-params");
			if(param){
				var selectedVal = target.value || target.getAttribute("value");
				var selected = this.router.getQueryParam(param) || [];
				var pos = selected.indexOf(selectedVal);
			}
			if (pos == -1) {
				selected.push(selectedVal);
			} else {
				selected.splice(pos, 1);
			}
			this.router.setQueryParam(param, selected);
			return preventPropagation(e);
		},
		call_api : function(e,target,data){
			module("DataService", function(DataService){
				DataService.get(target.getAttribute("jqr-api"));
			});
		},
		_remove_ : function(){
			this.router.off();
		},
		_ready_ : function(){
			this.instance().addTo(jQuery("body"));
		}
	};

});
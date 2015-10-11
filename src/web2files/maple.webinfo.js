define({ 
	name : "maple.webinfo",
	modules : ["jsutils.cache"]
}).as(function(webinfo, cacheUtils){

	var reloadCache = bootloader.config().debug;
	
	var server = module("DataService");
	var webInfoCache = cacheUtils.instance("webinfo");
	var storyStrings = cacheUtils.instance("storyStrings");
	
	webinfo.getStats = function(){
		return webInfoCache.load("STATS",function(){
			return server.get("websiteinfo",{ info : "STATS"}).then(function(resp2){
				return resp2.info;
			})
		},reloadCache);
	};
	
	webinfo.getCategories = function(){
		return webInfoCache.load("CATEGORIES",function(){
			return server.get("websiteinfo",{ info : "CATEGORIES"}).then(function(resp2){
				return resp2.info;
			});
		},reloadCache);
	};
	
	webinfo.getMatchingSearch = function(string){
		return webInfoCache.load("CATEGORIES",function(){
			return server.get("websiteinfo",{ info : "CATEGORIES"}).then(function(resp2){
				return resp2.info;
			})
		},reloadCache);
	};
	
	webinfo.getClasses = function(refresh){
		return webInfoCache.load("CLASSES",function(){
			return server.get("websiteinfo",{ info : "CLASSES"}).then(function(records){
				console.error("records",records)
				return {
					langs : records.filter(function(record){
						return record.classtype_name == "language";
					}),
					types : records.filter(function(record){
						return record.classtype_name == "storytype";
					})
				};
			})
		},reloadCache || refresh);
	};
	
	webinfo._ready_ = function(){
		//server.get("websiteinfo",[1,2,3]);
		webinfo.getStats().done(function(resp){
			console.debug("resp===",resp);
		})
		
		webinfo.getClasses(true).done(function(resp){
			console.debug("getClasses===",resp);
		})
	};
	
});
define({
	name: "DataService",
	modules : ["jQuery"]
}).as(function(DataService){
	var APP_CONTEXT = bootloader.config().appContext;
	DataService.get = function(url,data){
		console.info("----",url,data);
		return jQuery.get(APP_CONTEXT+"json/"+url,data,null,"json");
	};
	
	DataService.post = function(url,data){
		return jQuery.post(APP_CONTEXT+"json/"+url,data,null,"json");
	};
	
});
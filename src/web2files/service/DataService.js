define({
	name: "DataService",
	modules : ["jQuery"]
}).as(function(DataService){
	var APP_CONTEXT = bootloader.config().appContext;

	var userDetails = null;

	DataService.getUserDetails = function(){
		userDetails = userDetails ||DataService.get("mydetails");
		return userDetails;
	};

	DataService.get = function(url,data){
		console.info("----",url,data);
		return jQuery.get(APP_CONTEXT+"json/"+url,data,null,"json").done(function(a,b,c){
			if(c.getResponseHeader("X-auth-event")){
				window.location.reload();
			}
		});
	};
	
	DataService.post = function(url,data){
		console.error("PostCall",url,data);
		return jQuery.post(APP_CONTEXT+"json/"+url,data,null,"json").done(function(a,b,c){
			if(c.getResponseHeader("X-auth-event")){
				window.location.reload();
			}
		});
	};
	
});
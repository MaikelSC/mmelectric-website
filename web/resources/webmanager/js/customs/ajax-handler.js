var ajaxHandler = function(url, data, method, onSuccess, onFail){
//	setParameters
		this.url = url;
		this.data = data;
		this.method = method;
		this.onSuccess = onSuccess;
		this.onFail = onFail;
	
	ajaxHandler.prototype.excecute = function(){
		var request = $.ajax({
			url: this.url,
			method: this.method,
			data: this.data,
			dataType:"JSON"
		});
		request.done(this.onSuccess);
		request.fail(this.onFail);
	}
};
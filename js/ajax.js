
	var AJAX_SUCCESS          = 0;
	var AJAX_INVALIDOBJECT          = 1;
	var AJAX_INVALIDCALLBACK     = 2;
	var AJAX_FAILEDOPEN          = 3;

	function Ajax () {
		this.version          = '0.01';
		this.isAsync          = false;
		this.agent          = null;
		this.lastException     = '';

		if( typeof XMLHttpRequest != 'undefined' )
		this.agent = new XMLHttpRequest();

		if( this.agent == null )
		{
			var axos = new Array(
				//               'MSXML2.XMLHTTP.4.0',
				'MSXML2.XMLHTTP.3.0',
				'MSXML2.XMLHTTP',
				'Microsoft.XMLHTTP'
			);

			for( var i = 0; this.agent == null && i < axos.length; i++ )
			{
				try
				{
					this.agent = new ActiveXObject(axos[i]);
				}
				catch(e)
				{
					this.lastException = e;
					this.agent = null;
				}
			}
		}

		this.isValid   = callAjaxIsValid;
		this.get       = callAjaxGet;
		this.post      = callAjaxPost;
		this.open      = callAjaxOpen;
		this.request   = callAjaxRequest;
		this.response  = callAjaxResponse;
	}

	function AjaxResponse()
	{
		this.status      = 0;
		this.statusText  = '';
		this.headers     = new Array();
		this.body        = '';
		this.text        = '';
		this.xml         = '';
	}

	function AjaxRequest()
	{
		this.method     = 'GET';
		this.url        = '';
		this.headers    = new Array();
		this.body       = null;
		this.callback   = null;
	}

	function callAjaxGet( url, callback, headers )
	{
		return this.open( 'GET', url, null, callback, headers );
	}

	function callAjaxIsValid()
	{
		return this.agent != null;
	}

	function callAjaxOpen( method, url, data, callback, headers )
	{
		if ( this.isValid() )
		{
			if (!method)     method          = 'GET';
			if (!data)       data            = null;
			if (callback)    this.isAsync    = true;

			if ( this.isAsync )
			{
				if ( typeof callback != 'function' )
				return AJAX_INVALIDCALLBACK;
				this.agent.onreadystatechange = callback;
			}

			try
			{
				this.agent.open( method, url, this.isAsync );
			}
			catch(e)
			{
				this.lastException = e;
				return AJAX_FAILEDOPEN;
			}

			if ( method == 'POST' )
			{
				this.agent.setRequestHeader( 'Connection', 'close' );
				this.agent.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' );
			}

			if ( headers != null )
			{
				for ( var header in headers )
				{
					this.agent.setRequestHeader( header, headers[header] );
				}
			}

			this.agent.send(data);
			return AJAX_SUCCESS;
		}
		return AJAX_INVALIDOBJECT;
	}

	function callAjaxPost( url, data, callback, headers )
	{
		return this.open( 'POST', url, data, callback, headers );
	}

	function callAjaxResponse()
	{
		if ( this.agent.readyState != 4 )
		return null;

		var res = new AjaxResponse();

		res.status = this.agent.status;
		res.statusText  = typeof this.agent.statusText == 'undefined' ? '': this.agent.statusText;
		res.body        = typeof this.agent.responseBody == 'undefined' ? '' : this.agent.responseBody;
		res.text        = typeof this.agent.responseText == 'undefined' ? '' : this.agent.responseText;
		res.xml         = this.agent.responseXML == null ? '' : this.agent.responseXML;

		var string = this.agent.getAllResponseHeaders();
		if (!string) string = '';

		var lines = string.split("\\n");
		for ( var i = 0; i < lines.length; i++ )
		{
			var header = lines[i].split(": ");
			if ( header.length >= 2 )
			{
				var headername  = header.shift();
				var headervalue = header.join(": ");
				res.headers[headername] = headervalue;
			}
		}

		return res;
	}

	function callAjaxRequest(req)
	{
		return this.Open ( req.method, req.url, req.body, req.callback, req.headers );
	}

	/*   ajax-функция   */
	function SubmitLink ( uri,divid )
	{
		var result=true;
		var ajax = new Ajax();
		if ( ajax.isValid )
		{
			ajax.get (
				'http://' + document.domain + uri,
				function ()
				{
					var response = ajax.response();
					if ( response && response.status == 200 )
						document.getElementById(divid).innerHTML = response.text;
					else if ( response && response.status >= 500 )
						document.getElementById(divid).innerHTML = 'Произошла ошибка';
					else
						document.getElementById(divid).innerHTML = '<img src="/img/ajax_loader/38.gif" width="16" height="11" alt="загрузка" border="0">';
				}
			);
			return true;
		}
		else return false;
	}

	/*   функция индикации кол-ва введенных символов  */
	function ChooseLen ( oTextArea, divid, max )
	{
		M = oTextArea.value.length;
		if ( M>max )
			document.getElementById(divid).innerHTML = 'Символов: <font color="#bb0000"><b>'+M+'</b></font>';
		else
			document.getElementById(divid).innerHTML = 'Символов: <font color="#008800"><b>'+M+'</b></font>';
	}


/*** конец ***/
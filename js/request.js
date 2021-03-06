function Request(config) {
    this.url          = null;
    this.method       = 'get';
    this.handleAs     = 'text';
    this.asynchronous = true;
    this.parameters   = {};
    this.transport    = null;
    this.onSuccess    = function() {};
    this.onError      = function() {};
    this.cancel       = function() {
        if (this.transport != null) {
            this.onError   = function() {};
            this.onSuccess = function() {};
            this.transport.abort();
        }
    };

    if (typeof config != "object") {
        throw 'Request parameter should be an object';
    }
    
    if (!config.url) {
        throw 'Request URL needed';
    }
    this.url = config.url;

    if (config.method) {
        if(typeof config.method === "string") {
            var method = config.method.toLowerCase();
            if (method === "get" || method === "post")
                this.method = method;
            else
                throw "'" + config.method + "' method not supported";
        }
        else {
            throw "'method' parameter should be a string";
        }
    }

    if (config.asynchronous) {
        if (typeof config.asynchronous === "boolean") {
            this.asynchronous = config.asynchronous;
        }
        else {
            throw "'asynchronous' parameter should be a boolean";
        }
    }

    if (config.parameters) {
        if (config.parameters instanceof Object) {
            this.parameters = config.parameters;
        }
        else {
            throw "'parameters' parameter should be a object";
        }
    }

    var callbackFound = false;
    if (config.onSuccess) {
        if (config.onSuccess instanceof Function) {
            this.onSuccess = config.onSuccess;
            callbackFound = true;
        }
        else {
            throw "'onSuccess' parameter should be a function";
        }
    }

    if (config.onError) {
        if (config.onError instanceof Function) {
            this.onError = config.onError;
            callbackFound = true;
        }
        else {
            throw "'onError' parameter should be a function";
        }
    }

    if (!callbackFound) {
        throw "'onSuccess' or 'onError' parameter not found";
    }

    if (config.handleAs) {
        if (typeof config.handleAs === 'string') {
            var handleAs = config.handleAs.toLowerCase();
            if (['text', 'json', 'xml'].indexOf(handleAs) !== -1) {
                this.handleAs = handleAs;
            }
            else {
                throw "handleAs format '" + config.handleAs + "' not supported";
            }
        }
        else {
            throw "handleAs parameter should be a string";
        }
    }

    this.transport = GetXmlHttpObject();

    var RequestThis = this;
    this.transport.onreadystatechange = function() {
        if (RequestThis.transport.readyState == 4 || RequestThis.transport.readyState == "complete") {
            if (RequestThis.transport.status == 200) {
                var result = null;
                switch (RequestThis.handleAs) {
                    case 'text' :
                        result = RequestThis.transport.responseText
                        break;
                    case 'json' :
                        result = JSON.parse(RequestThis.transport.responseText);
                        break;
                    case 'xml' :
                        result = RequestThis.transport.responseXML;
                        break;
                    default :
                        throw 'handleAs consistancy problem, value is ' + this.handleAs;
                }
                RequestThis.onSuccess(result);
            }
            else {
                RequestThis.onError(RequestThis.transport.status, RequestThis.transport.responseText);
            }
        }
    }

    var parameters = new Array();
    for (var i in this.parameters) {
        parameters.push(i + '=' + encodeURIComponent(this.parameters[i]));
    }
    var parametersString = parameters.join('&');
    if (this.method === 'get') {
        this.transport.open(this.method,
                            this.url + "?" + parametersString,
                            this.asynchronous);
        this.transport.send(null);
    }
    else {
        this.transport.open(this.method, this.url, this.asynchronous);
        this.transport.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        this.transport.send(parametersString);
    }

    function GetXmlHttpObject() {
        var xmlHttp = null;

        try {
            xmlHttp = new XMLHttpRequest();
        } catch (e) {
            try {
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    throw "XMLHTTPRequest object not supported";
                }
            }
        }
        return xmlHttp;
    }
}
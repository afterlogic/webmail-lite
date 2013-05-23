/**
 * http://github.com/valums/file-uploader
 * 
 * Multiple file upload component with progress-bar, drag-and-drop. 
 * © 2010 Andrew Valums ( andrew(at)valums.com ) 
 * 
 * Licensed under GNU GPL 2 or later, see license.txt.
 */    

//
// Helper functions
//

var qq = qq || {};

/**
 * Adds all missing properties from second obj to first obj
 */ 
qq.extend = function(first, second){
	for (var prop in second){
		first[prop] = second[prop];
	}
};  

/**
 * Searches for a given element in the array, returns -1 if it is not present.
 * @param {Number} [from] The index at which to begin the search
 */
qq.indexOf = function(arr, elt, from){
	if (arr.indexOf) return arr.indexOf(elt, from);
	
	from = from || 0;
	var len = arr.length;

	if (from < 0) from += len;

	for (; from < len; from++){
		if (from in arr && arr[from] === elt){
			return from;
		}
	}  
	return -1;
};

qq.getUniqueId = (function(){
	var id = 0;
	return function(){return id++;};
})();

//
// Events

qq.attach = function(element, type, fn){
	if (element.addEventListener){
		element.addEventListener(type, fn, false);
	} else if (element.attachEvent){
		element.attachEvent('on' + type, fn);
	}
};
qq.detach = function(element, type, fn){
	if (element.removeEventListener){
		element.removeEventListener(type, fn, false);
	} else if (element.attachEvent){
		element.detachEvent('on' + type, fn);
	}
};
qq.preventDefault = function(e){
	if (e.preventDefault){
		e.preventDefault();
	} else{
		e.returnValue = false;
	}
};

//
// Node manipulations

/**
 * Insert node a before node b.
 */
qq.insertBefore = function(a, b){
	b.parentNode.insertBefore(a, b);
};
qq.remove = function(element){
	element.parentNode.removeChild(element);
};

qq.contains = function(parent, descendant){
	if (descendant == undefined) return false;
	// compareposition returns false in this case
	if (parent == descendant) return true;

	if (parent.contains){
		return parent.contains(descendant);
	} else {
		return !!(descendant.compareDocumentPosition(parent) & 8);
	}
};

/**
 * Creates and returns element from html string
 * Uses innerHTML to create an element
 */
qq.toElement = (function(){
	var div = document.createElement('div');
	return function(html){
		div.innerHTML = html;
		var element = div.firstChild;
		div.removeChild(element);
		return element;
	};
})();

//
// Node properties and attributes

/**
 * Sets styles for an element.
 * Fixes opacity in IE6-8.
 */
qq.css = function(element, styles){
	if (styles.opacity != null){
		if (typeof element.style.opacity != 'string' && typeof(element.filters) != 'undefined'){
			styles.filter = 'alpha(opacity=' + Math.round(100 * styles.opacity) + ')';
		}
	}
	qq.extend(element.style, styles);
};
qq.hasClass = function(element, name){
	var re = new RegExp('(^| )' + name + '( |$)');
	return re.test(element.className);
};
qq.addClass = function(element, name){
	if (!qq.hasClass(element, name)){
		element.className += ' ' + name;
	}
};
qq.removeClass = function(element, name){
	var re = new RegExp('(^| )' + name + '( |$)');
	element.className = element.className.replace(re, ' ').replace(/^\s+|\s+$/g, "");
};
qq.setText = function(element, text){
	element.innerText = text;
	element.textContent = text;
};

//
// Selecting elements

qq.children = function(element){
	var children = [],
	child = element.firstChild;

	while (child){
		if (child.nodeType == 1){
			children.push(child);
		}
		child = child.nextSibling;
	}

	return children;
};

qq.getByClass = function(element, className){
	if (element.querySelectorAll){
		return element.querySelectorAll('.' + className);
	}

	var result = [];
	var candidates = element.getElementsByTagName("*");
	var len = candidates.length;

	for (var i = 0; i < len; i++){
		if (qq.hasClass(candidates[i], className)){
			result.push(candidates[i]);
		}
	}
	return result;
};

/**
 * obj2url() takes a json-object as argument and generates
 * a querystring. pretty much like jQuery.param()
 * 
 * how to use:
 *
 *    `qq.obj2url({a:'b',c:'d'},'http://any.url/upload?otherParam=value');`
 *
 * will result in:
 *
 *    `http://any.url/upload?otherParam=value&a=b&c=d`
 *
 * @param  Object JSON-Object
 * @param  String current querystring-part
 * @return String encoded querystring
 */
qq.obj2url = function(obj, temp, prefixDone){
	var uristrings = [],
		prefix = '&',
		add = function(nextObj, i){
			var nextTemp = temp 
				? (/\[\]$/.test(temp)) // prevent double-encoding
					? temp
					: temp+'['+i+']'
				: i;
			if ((nextTemp != 'undefined') && (i != 'undefined')) {  
				uristrings.push(
					(typeof nextObj === 'object') 
						? qq.obj2url(nextObj, nextTemp, true)
						: (Object.prototype.toString.call(nextObj) === '[object Function]')
							? encodeURIComponent(nextTemp) + '=' + encodeURIComponent(nextObj())
							: encodeURIComponent(nextTemp) + '=' + encodeURIComponent(nextObj)                                                          
				);
			}
		}; 

	if (!prefixDone && temp) {
		prefix = (/\?/.test(temp)) ? (/\?$/.test(temp)) ? '' : '&' : '?';
		uristrings.push(temp);
		uristrings.push(qq.obj2url(obj));
	} else if ((Object.prototype.toString.call(obj) === '[object Array]') && (typeof obj != 'undefined') ) {
		// we wont use a for-in-loop on an array (performance)
		for (var i = 0, len = obj.length; i < len; ++i){
			add(obj[i], i);
		}
	} else if ((typeof obj != 'undefined') && (obj !== null) && (typeof obj === "object")){
		// for anything else but a scalar, we will use for-in-loop
		for (var i in obj){
			add(obj[i], i);
		}
	} else {
		uristrings.push(encodeURIComponent(temp) + '=' + encodeURIComponent(obj));
	}

    return uristrings.join(prefix)
					.replace(/^&/, '')
					.replace(/%20/g, '+'); 
};

//
//
// Uploader Classes
//
//

var qq = qq || {};
    
/**
 * Creates upload button, validates upload, but doesn't create file list or dd. 
 */
qq.FileUploaderBasic = function(o){
	this._options = {
		action: '/server/upload',
		//vasil
		name: 'file',
		parent: null,
		//
		params: {},
		button: null,
		multiple: true,
		maxConnections: 3,
		// validation
		allowedExtensions: [],
		sizeLimit: 0,
		minSizeLimit: 0,
		// events
		// return false to cancel submit
		onSubmit: function(id, fileName){},
		onProgress: function(id, fileName, loaded, total){},
		onComplete: function(id, fileName, responseJSON){},
		onCancel: function(id, fileName){},
		// messages
		messages: {
			typeError: "{file} has invalid extension. Only {extensions} are allowed.",
			sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
			minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
			emptyError: "{file} is empty, please select files again without it.",
			onLeave: "The files are being uploaded, if you leave now the upload will be cancelled."
		},
		showMessage: function(message){
		}
	};
	qq.extend(this._options, o);

	// number of files being uploaded
	this._handler = this._createUploadHandler(); 
	
	if (this._options.button){ 
		this._button = this._createUploadButton(this._options.button);
	}
	
	this._preventLeaveInProgress();
};

qq.FileUploaderBasic.prototype = {
	setParams: function(params){
		this._options.params = params;
	},
	_createUploadButton: function(element){
		var self = this;

		return new qq.UploadButton({
			//vasil
			name: this._options.name,
			//
			element: element,
			multiple: this._options.multiple && qq.UploadHandlerXhr.isSupported,
			onChange: function(input){
				self._onInputChange(input);
			}
		});
	},
	_createUploadHandler: function(){
		var self = this,
			handlerClass;

		if(qq.UploadHandlerXhr.isSupported){
			handlerClass = 'UploadHandlerXhr';
		} else {
			handlerClass = 'UploadHandlerForm';
		}

        var handler = new qq[handlerClass]({
            action: this._options.action,
            maxConnections: this._options.maxConnections,
            onProgress: function(id, fileName, loaded, total){
                self._onProgress(id, fileName, loaded, total);
                self._options.onProgress(id, fileName, loaded, total);
            },            
            onComplete: function(id, fileName, result){
                self._onComplete(id, fileName, result);
                self._options.onComplete(id, fileName, result);
            },
            onCancel: function(id, fileName){
                self._onCancel(id, fileName);
                self._options.onCancel(id, fileName);
            }
        });

        return handler;
    },    
    _preventLeaveInProgress: function(){
        var self = this;
        
        qq.attach(window, 'beforeunload', function(e){
			if (typeof self._handler !== 'object' || typeof self._handler.hasFilesInProgress !== 'function') {
				return;
			}
            if (!self._handler.hasFilesInProgress()){return;}
            
            var e = e || window.event;
            // for ie, ff
            e.returnValue = self._options.messages.onLeave;
            // for webkit
            return self._options.messages.onLeave;             
        });        
    },    
    _onSubmit: function(id, fileName){
    },
    _onProgress: function(id, fileName, loaded, total){
    },
    _onComplete: function(id, fileName, result){
    },
    _onCancel: function(id, fileName){
    },
    _onInputChange: function(input){
        if (this._handler instanceof qq.UploadHandlerXhr){
			this._uploadFileList(input.files);
		} else {
			if (this._validateFile(input)){
				this._uploadFile(input);
			}
		}
		this._button.reset();
	},
    _uploadFileList: function(files){
        for (var i=0; i<files.length; i++){
            if ( !this._validateFile(files[i])){
				return;
            }
        }
        
        for (var i=0; i<files.length; i++){
            this._uploadFile(files[i]);
        }
    },
    _uploadFile: function(fileContainer){
        var id = this._handler.add(fileContainer);
        var fileName = this._handler.getName(id);
        
        if (this._options.onSubmit(id, fileName) !== false){
            this._onSubmit(id, fileName);
            this._handler.upload(id, this._options.params);
        }
    },
	_validateFile: function(file){
		var name, size;

		if (file.value){
			// it is a file input
			// get input value and remove path to normalize
			name = file.value.replace(/.*(\/|\\)/, "");
		} else {
			// fix missing properties in safari
			name = file.fileName != null ? file.fileName : file.name;
			size = file.fileSize != null ? file.fileSize : file.size;
		}

		if (! this._isAllowedExtension(name)){
			this._error('typeError', name);
			return false;
			
		// } else if (size === 0){
			// this._error('emptyError', name);
			// return false;
		
		} else if (size && this._options.sizeLimit && size > this._options.sizeLimit){
//			this._error('sizeError', name);
			this._options.showMessage(Lang.FileLargerThan + this._formatSize(this._options.sizeLimit));
			return false;

		} else if (size && size < this._options.minSizeLimit){
			this._error('minSizeError', name);
			return false;
		}

		return true;
	},
	_error: function(code, fileName){
		var message = this._options.messages[code];
		function r(name, replacement){message = message.replace(name, replacement);}
		
		r('{file}', this._formatFileName(fileName));
		r('{extensions}', this._options.allowedExtensions.join(', '));
		r('{sizeLimit}', this._formatSize(this._options.sizeLimit));
		r('{minSizeLimit}', this._formatSize(this._options.minSizeLimit));
		
		this._options.showMessage(message);                
	},
	_formatFileName: function(name){
		if (name.length > 50){
			name = name.slice(0, 40) + '...' + name.slice(-8);
		}
		return name;
	},
	_isAllowedExtension: function(fileName){
		var ext = (-1 !== fileName.indexOf('.')) ? fileName.replace(/.*[.]/, '').toLowerCase() : '';
		var allowed = this._options.allowedExtensions;
		
		if (!allowed.length){return true;}
		
		for (var i=0; i<allowed.length; i++){
			if (allowed[i].toLowerCase() == ext){return true;}    
		}
		
		return false;
	},
	_formatSize: function(bytes){
		if (typeof (GetFriendlySize) != 'undefined') {
			return GetFriendlySize(bytes);
		}
		var i = -1;
		do {
			bytes = bytes / 1024;
			i++;  
		} while (bytes > 99);
		
		return Math.max(bytes, 0.1).toFixed(1) + ['kB', 'MB', 'GB', 'TB', 'PB', 'EB'][i];
	}
};

/**
 * Class that creates upload widget with drag-and-drop and file list
 * @inherits qq.FileUploaderBasic
 */
qq.FileUploader = function(o){
	// call parent constructor
	qq.FileUploaderBasic.apply(this, arguments);

	var
		sOtherButtonsHtml = '',
		sButtonHtml = '',
		sContClassName = 'wm_upload_drop_cont'
	;
	if (WebMail.Settings.bAllowServerFileupload) {
		sOtherButtonsHtml = '<div id="resumeuploader-from-server" class="wm_dialog_attach_open_buttons_cont"></div>'
			+ '<div id="letteruploader-from-server" class="wm_dialog_attach_open_buttons_cont"></div>';
		sButtonHtml = '<span class="wm_dialog_attach_icon" id="anotheruploader-link-icon"></span>'
			+ '<span class="wm_dialog_attach_open_button"><span id="fileuploader_click_to_attach">'
			+ Lang.DialogAttachAnother + '</span></span>';
			sContClassName = 'wm_dialog_attach_open_buttons_cont';
	}
	else {
		sButtonHtml = '<span class="wm_upload_button"><span id="fileuploader_click_to_attach">'
			+ Lang.FileUploaderClickToAttach + '</span></span>';
	}
	// additional options
	qq.extend(this._options, {
		element: null,
		// if set, will be used instead of wm_upload_list in template
		listElement: null,
		template:
				  sOtherButtonsHtml
				+ '<div class="' + sContClassName + '">'
				+ '<div class="wm_upload_drop_area"></div>'
				+ sButtonHtml
				+ ((Browser.ie || Browser.ie9)
					? ''
					: '<span class="wm_upload_drop_area_text" id="fileuploader_drag_n_drop">' + Lang.FileUploaderOrDragNDrop + '</span>'
				)
				+ '</div>'
				+ '<div class="clear"></div>'
				+ '<ul class="wm_upload_list"></ul>',
		// template for one item in file list
		fileTemplate: '<li>' +
				'<a class="wm_upload_cancel" href="#">' + Lang.Cancel + '</a>' +
				'<span class="wm_upload_icon"></span>' +
				'<span class="wm_upload_file"><span class="wm__file"></span>' +
				'<span class="wm_upload_size"></span></span>' +
				'<br /><a href="#" class="wm_upload_view">' + Lang.View + '</a>' +
				'<span class="wm_upload_spinner"></span>' +
				'<span class="wm_upload_status_text"></span>' +
				'<span class="wm_upload_progress"><span></span></span>' +
			'</li>',
		
		classes: {
			// used to get elements from templates
			//vasil
			button: (WebMail.Settings.bAllowServerFileupload) ? 'wm_dialog_attach_open_button' : 'wm_upload_button',
			//
			drop: 'wm_upload_drop_area',
			dropCont: sContClassName,
			dropActive: 'wm_upload_drop_area_active',
			list: 'wm_upload_list',

			file: 'wm__file',
			spinner: 'wm_upload_spinner',
			size: 'wm_upload_size',
			view: 'wm_upload_view',
			//vasil
			icon: 'wm_upload_icon',
			progress: 'wm_upload_progress',
			status: 'wm_upload_status_text',
			//
			cancel: 'wm_upload_cancel',

			// added to list item when upload completes
			// used in css to hide progress spinner
			success: 'wm_upload_success',
			fail: 'wm_upload_fail'
		}
	});
	// overwrite options with user supplied    
	qq.extend(this._options, o);       
	//vasil
	this.attachments = [];
	this._uploadCount = 0;
	//
	this._element = this._options.element;
	this._element.innerHTML = this._options.template;
	this._initLang();
	this._listElement = this._options._listElement || this._find(this._element, 'list');
	
	this._classes = this._options.classes;
	
	this._button = this._createUploadButton(this._find(this._element, 'button'));        
	
	this._bindCancelEvent();
	
	/* if (!(navigator.userAgent.indexOf("MSIE") > -1)) {
		return;
	} */
	this._setupDragDrop();
};

// inherit from Basic Uploader
qq.extend(qq.FileUploader.prototype, qq.FileUploaderBasic.prototype);

qq.extend(qq.FileUploader.prototype, {
	_initLang: function ()
	{
		var clickToAttachElem = document.getElementById('fileuploader_click_to_attach');
		if (clickToAttachElem) {
			var sLangField = (WebMail.Settings.bAllowServerFileupload) ? 'DialogAttachAnother' : 'FileUploaderClickToAttach';
			WebMail.langChanger.register('innerHTML', clickToAttachElem, sLangField, '');
		}
		var dragNDropElem = document.getElementById('fileuploader_drag_n_drop');
		if (dragNDropElem) {
			WebMail.langChanger.register('innerHTML', dragNDropElem, 'FileUploaderOrDragNDrop', '');
		}
	},
	/**
	* Gets one of the elements listed in this._options.classes
	**/
	_find: function(parent, type){
		var element = qq.getByClass(parent, this._options.classes[type])[0];
		if (!element){
		throw new Error('element not found ' + type);
		}

		return element;
	},
	_setupDragDrop: function(){
		var self = this,
			dropArea = this._find(this._element, 'drop');

		var dz = new qq.UploadDropZone({
			element: dropArea,
			onEnter: function(e){
				qq.addClass(dropArea, self._classes.dropActive);
				e.stopPropagation();
			},
			onLeave: function(e){
				e.stopPropagation();
			},
			onLeaveNotDescendants: function(e){
				qq.removeClass(dropArea, self._classes.dropActive);  
			},
			onDrop: function(e){
				// dropArea.style.display = 'none';
				qq.removeClass(dropArea, self._classes.dropActive);
				self._uploadFileList(e.dataTransfer.files);
			}
		});

		// dropArea.style.display = 'none';
		//
		if (!isEventSupported('dragenter')) {
			this._find(this._element, 'dropCont').className += ' wm_disable_drop';
		}
		//
		qq.attach(document, 'dragenter', function(e){
			if (!dz._isValidFileDrag(e)) return;

			// dropArea.style.display = 'block';
		});                 
		qq.attach(document, 'dragleave', function(e){
			if (!dz._isValidFileDrag(e)) return;

			var relatedTarget = document.elementFromPoint(e.clientX, e.clientY);
			// only fire when leaving document out
			if ( ! relatedTarget || relatedTarget.nodeName == "HTML"){
				// dropArea.style.display = 'none';
			}
		});
	},
	_onSubmit: function(id, fileName){
		this._options.parent.startFileUpload();
		qq.FileUploaderBasic.prototype._onSubmit.apply(this, arguments);
		this._addToList(id, fileName);
	},
	_onProgress: function(id, sFileName, iLoaded, iTotal)
	{
		qq.FileUploaderBasic.prototype._onProgress.apply(this, arguments);

		var oItem = this._getItemByFileId(id);
		this._find(oItem, 'view').style.display = 'none';

		//animate progressbar
		var eProgress = this._find(oItem, 'progress').firstChild;
		eProgress.parentNode.style.display = '';
		var iWidth = Math.round(iLoaded / iTotal * 100);
		if (iWidth > 100) {
			iWidth = 100;
		}
		if (iWidth < 0) {
			iWidth = 0;
		}
		eProgress.style.width = iWidth + '%';
	},
	
	_onComplete: function(id, sFileName, oResult)
	{
		var
			oItem = this._getItemByFileId(id),
			bSuccsess = !!oResult.success,
			eStatus = this._find(oItem, 'status')
		;
		
		this._options.parent.stopFileUpload();
		qq.FileUploaderBasic.prototype._onComplete.apply(this, arguments);

		if (bSuccsess) {
			this._fillViewLink(oItem, sFileName, oResult.size, oResult.view);
			this.attachments[id] = oResult;
			qq.addClass(oItem, this._classes.success);
		}
		else {
			this._fillViewLink(oItem, sFileName, 0, '');
			qq.addClass(oItem, this._classes.fail);
		}
		
		if (oResult.status) {
			qq.setText(eStatus, oResult.status);
			setTimeout(function() {
				qq.remove(eStatus, '');
			},2000);
		}
		else {
			qq.setText(eStatus, Lang.UnknownUploadError);
		}
		
		this.resize();
	},

	_addToList: function(id, sFileName)
	{
		var oItem = qq.toElement(this._options.fileTemplate);
		oItem.qqFileId = id;
		this._fillFileName(oItem, sFileName);
		this._listElement.appendChild(oItem);
		this.resize();
	},

	_fillFileName: function (oItem, sFileName)
	{
		var eFile = this._find(oItem, 'file');
		var oFileParams = GetFileParams(sFileName);
		qq.setText(eFile, oFileParams.sShortName);
		this._find(oItem, 'icon').style.backgroundPosition = '-' + oFileParams.iX + 'px -' + oFileParams.iY + 'px';
		this._find(oItem, 'view').style.display = 'none';
	},

	_fillViewLink: function (oItem, sFileName, iSize, sViewLink)
	{
		var eSize = this._find(oItem, 'size');
		qq.setText(eSize, this._formatSize(iSize));

		var oParams = GetFileParams(sFileName);
		if (oParams.bView && sViewLink !== undefined) {
			var eView = this._find(oItem, 'view');
			eView.style.display = '';
			eView.onclick = CreateAttachViewClick(HtmlDecode(sViewLink));
		}
		this._find(oItem, 'spinner').style.display = 'none';
		this._find(oItem, 'progress').style.display = 'none';
	},

	_addToList1: function(file)
	{
		var oItem = qq.toElement(this._options.fileTemplate);
		oItem.qqFileId = file.id;
		this._fillFileName(oItem, file.fileName);
		this._fillViewLink(oItem, file.fileName, file.size, file.view);
		this._listElement.appendChild(oItem);
		this.resize();
	},

	addAttachment: function(file){
		this._addToList1(file);
		this.attachments[file.id] = file;
		return this.attachments[file.id];
	},
	removeAttachment: function(id){

		if (typeof(this.attachments[id]) == 'undefined') {
			this._options.parent.stopFileUpload();
		}
		else {
			delete this.attachments[id];
		}
		this.resize();
		return;
	},
	resize: function(){
		if (this._options.parent.resizeBody) {
			this._options.parent.resizeBody();
		}
	},
	clearAttachments: function(){
		this.attachments = [];
		CleanNode(this._listElement);
	},
	//
	_getItemByFileId: function(id){
		var item = this._listElement.firstChild;

		// there can't be txt nodes in dynamically created list
		// and we can  use nextSibling
		while (item){
		if (item.qqFileId == id) return item;            
			item = item.nextSibling;
		}
	},
	/**
	* delegate click event for cancel link 
	**/
	_bindCancelEvent: function(){
		var self = this,
			list = this._listElement;

		qq.attach(list, 'click', function(e){
			e = e || window.event;
			var target = e.target || e.srcElement;

			if (qq.hasClass(target, self._classes.cancel)){
				qq.preventDefault(e);

				var item = target.parentNode;
				self._handler.cancel(item.qqFileId);
				qq.remove(item);
				self.removeAttachment(item.qqFileId);
			}
		});
	}
});

qq.UploadDropZone = function(o){
	this._options = {
		element: null,
		onEnter: function(e){},
		onLeave: function(e){},
		// is not fired when leaving element by hovering descendants
		onLeaveNotDescendants: function(e){},
		onDrop: function(e){}
	};
	qq.extend(this._options, o);

	this._element = this._options.element;

	this._disableDropOutside();
	this._attachEvents();
};

qq.UploadDropZone.prototype = {
	_disableDropOutside: function(e){
		// run only once for all instances
		if (!qq.UploadDropZone.dropOutsideDisabled ){

			qq.attach(document, 'dragover', function(e){
				if (e.dataTransfer){
					e.dataTransfer.dropEffect = 'none';
					e.preventDefault(); 
				}
			});
			
			qq.UploadDropZone.dropOutsideDisabled = true; 
		}
	},
	_attachEvents: function(){
		var self = this;

		qq.attach(self._element, 'dragover', function(e){
			if (!self._isValidFileDrag(e)) return;

			var effect = e.dataTransfer.effectAllowed;
			if (effect == 'move' || effect == 'linkMove'){
				e.dataTransfer.dropEffect = 'move'; // for FF (only move allowed)
			} else {
				e.dataTransfer.dropEffect = 'copy'; // for chrome
			}

			e.stopPropagation();
			e.preventDefault();
		});

		qq.attach(self._element, 'dragenter', function(e){
			if (!self._isValidFileDrag(e)) return;

			self._options.onEnter(e);
		});

		qq.attach(self._element, 'dragleave', function(e){
			if (!self._isValidFileDrag(e)) return;

			self._options.onLeave(e);

			var relatedTarget = document.elementFromPoint(e.clientX, e.clientY);
			// do not fire when moving a mouse over a descendant
			if (qq.contains(this, relatedTarget)) return;

			self._options.onLeaveNotDescendants(e);
		});

		qq.attach(self._element, 'drop', function(e){
			if (!self._isValidFileDrag(e)) return;

			e.preventDefault();
			self._options.onDrop(e);
		});          
	},
	_isValidFileDrag: function(e) {
		if (Browser.ie || Browser.ie9) return false;
		var dt = e.dataTransfer,
			// do not check dt.types.contains in webkit, because it crashes safari 4
			isWebkit = navigator.userAgent.indexOf("AppleWebKit") > -1;
			
		// dt.effectAllowed is none in safari 5
		// dt.types.contains check is for firefox
		return dt && dt.effectAllowed != 'none' && 
			(dt.files || (!isWebkit && dt.types.contains && dt.types.contains('Files')));
	}
};

qq.UploadButton = function(o){
	this._options = {
		element: null,  
		// if set to true adds multiple attribute to file input
		multiple: false,
		// name attribute of file input
		name: 'file',
		onChange: function(input){},
		//vasil
		hoverClass: (WebMail.Settings.bAllowServerFileupload) ? 'wm_dialog_attach_open_button hover' : 'wm_upload_button hover',
		focusClass: (WebMail.Settings.bAllowServerFileupload) ? 'wm_dialog_attach_open_button focus' : 'wm_upload_button focus'
		//
		};

		qq.extend(this._options, o);

	this._element = this._options.element;
	
	// make button suitable container for input
	qq.css(this._element, {
		position: 'relative',
		overflow: 'hidden',
		// Make sure browse button is in the right side
		// in Internet Explorer
		// didn't work in ie, css selector low priority
		direction: 'ltr'
		});

	this._input = this._createInput();
};

qq.UploadButton.prototype = {
	/* returns file input element */
	getInput: function(){
		return this._input;
	},
	/* cleans/recreates the file input */
	reset: function(){
		if (this._input.parentNode){
			qq.remove(this._input);
		}

		qq.removeClass(this._element, this._options.focusClass);
		this._input = this._createInput();
	},
	_createInput: function(){
		var input = document.createElement("input");

		if (this._options.multiple){
			input.setAttribute("multiple", "multiple");
		}

		input.setAttribute("type", "file");
		input.setAttribute("name", this._options.name);

		qq.css(input, {
			position: 'absolute',
			// in opera only 'browse' button
			// is clickable and it is located at
			// the right side of the input
			right: 0,
			top: 0,
			fontFamily: 'Arial',
			// 4 persons reported this, the max values that worked for them were 243, 236, 236, 118
			fontSize: '118px',
			margin: 0,
			padding: 0,
			cursor: 'pointer',
			opacity: 0
		});

		this._element.appendChild(input);

		var self = this;
		qq.attach(input, 'change', function(){
			self._options.onChange(input);
		});

		qq.attach(input, 'mouseover', function(){
			qq.addClass(self._element, self._options.hoverClass);
		});
		qq.attach(input, 'mouseout', function(){
			qq.removeClass(self._element, self._options.hoverClass);
		});
		qq.attach(input, 'focus', function(){
			qq.addClass(self._element, self._options.focusClass);
		});
		qq.attach(input, 'blur', function(){
			qq.removeClass(self._element, self._options.focusClass);
		});

		// ie and opera, unfortunately have 2 tab stops on file input
		// which is unacceptable in our case, disable keyboard access
		if (window.attachEvent){
			// it is ie or opera
			input.setAttribute('tabIndex', "-1");
		}

		return input;
	}
};

/**
 * Class for uploading files, uploading itself is handled by child classes
 */
qq.UploadHandlerAbstract = function(o){
	this._options = {
		action: '/upload.php',
		// maximum number of concurrent uploads
		maxConnections: 999,
		onProgress: function(id, fileName, loaded, total){},
		onComplete: function(id, fileName, response){},
		onCancel: function(id, fileName){}
	};
	qq.extend(this._options, o);    

	this._queue = [];
	// params for files in queue
	this._params = [];
};
qq.UploadHandlerAbstract.prototype = {
	/**
	* Adds file or file input to the queue
	* @returns id
	**/
	add: function(file){},
	/**
	* Sends the file identified by id and additional query params to the server
	*/
	upload: function(id, params){
		var len = this._queue.push(id);

		var copy = {};
		qq.extend(copy, params);
		this._params[id] = copy;

		// if too many active uploads, wait...
		if (len <= this._options.maxConnections){
			this._upload(id, this._params[id]);
		}
	},
	/**
	* Cancels file upload by id
	*/
	cancel: function(id){
		this._cancel(id);
		this._dequeue(id);
	},
	/**
	* Cancells all uploads
	*/
	cancelAll: function(){
		for (var i=0; i<this._queue.length; i++){
			this._cancel(this._queue[i]);
		}
		this._queue = [];
	},
    /**
     * Returns name of the file identified by id
     */
    getName: function(id){},
    /**
     * Returns size of the file identified by id
     */          
    getSize: function(id){},
    /**
     * Returns id of files being uploaded or
     * waiting for their turn
     */
    getQueue: function(){
        return this._queue;
    },
    /**
     * Actual upload method
     */
    _upload: function(id){},
    /**
     * Actual cancel method
     */
    _cancel: function(id){},
    /**
     * Removes element from queue, starts upload of next
     */
    _dequeue: function(id){
        var i = qq.indexOf(this._queue, id);
        this._queue.splice(i, 1);

        var max = this._options.maxConnections;
        
        if (this._queue.length >= max){
            var nextId = this._queue[max-1];
            this._upload(nextId, this._params[nextId]);
        }
    }
};

/**
 * Class for uploading files using form and iframe
 * @inherits qq.UploadHandlerAbstract
 */
qq.UploadHandlerForm = function(o){
    qq.UploadHandlerAbstract.apply(this, arguments);
       
    this._inputs = {};
};
// @inherits qq.UploadHandlerAbstract
qq.extend(qq.UploadHandlerForm.prototype, qq.UploadHandlerAbstract.prototype);

qq.extend(qq.UploadHandlerForm.prototype, {
    add: function(fileInput){
		// vasil
        fileInput.setAttribute('name', 'qqfile');
        var id = 'qq-upload-handler-iframe' + qq.getUniqueId();
        
        this._inputs[id] = fileInput;
        
        // remove file input from DOM
        if (fileInput.parentNode){
            qq.remove(fileInput);
        }

        return id;
    },
    getName: function(id){
		//vasil
		var file = this._inputs[id];
		if (!file) {return false;}
        // get input value and remove path to normalize
        //return this._inputs[id].value.replace(/.*(\/|\\)/, "");
        return file.value.replace(/.*(\/|\\)/, "");
		//
    },
    _cancel: function(id){
        this._options.onCancel(id, this.getName(id));
        
        delete this._inputs[id];

        var iframe = document.getElementById(id);
        if (iframe){
            // to cancel request set src to something else
            // we use src="javascript:false;" because it doesn't
            // trigger ie6 prompt on https
            iframe.setAttribute('src', 'javascript:false;');

            qq.remove(iframe);
        }
    },     
    _upload: function(id, params){
        var input = this._inputs[id];
        if (!input){
            throw new Error('file with passed id was not added, or already uploaded or cancelled');
        }

        var fileName = this.getName(id);

        var iframe = this._createIframe(id);
        var form = this._createForm(iframe, params);
        form.appendChild(input);

        var self = this;
        this._attachLoadEvent(iframe, function(){
            self._options.onComplete(id, fileName, self._getIframeContentJSON(iframe));
            self._dequeue(id);
            
            delete self._inputs[id];
            // timeout added to fix busy state in FF3.6
            setTimeout(function(){
                qq.remove(iframe);
            }, 1);
        });

        form.submit();
        qq.remove(form);
        
        return id;
    },
    _attachLoadEvent: function(iframe, callback){
        qq.attach(iframe, 'load', function(){
            // when we remove iframe from dom
            // the request stops, but in ie load
            // event fires
            if (!iframe.parentNode){
                return;
            }

            // fixing opera 10.53
            if (iframe.contentDocument &&
                iframe.contentDocument.body &&
                iframe.contentDocument.body.innerHTML == "false"){
                // In opera event is fired second time
                // when body.innerHTML changed from false
                // to server response approx. after 1 sec
                // when we upload file with iframe
                return;
            }

            callback();
        });
    },
    /**
     * Returns json object received by iframe from server.
     */
    _getIframeContentJSON: function(iframe){
        // iframe.contentWindow.document - for ie<7
        var doc = iframe.contentDocument ? iframe.contentDocument: iframe.contentWindow.document,
            response;

        try {
            response = eval("(" + doc.body.innerHTML + ")");
        } catch(err){
            response = {};
        }

        return response;
    },
    /**
     * Creates iframe with unique name
     */
    _createIframe: function(id){
        // We can't use following code as the name attribute
        // won't be properly registered in IE6, and new window
        // on form submit will open
        // var iframe = document.createElement('iframe');
        // iframe.setAttribute('name', id);

        var iframe = qq.toElement('<iframe src="javascript:false;" name="' + id + '" />');
        // src="javascript:false;" removes ie6 prompt on https

        iframe.setAttribute('id', id);

        iframe.style.display = 'none';
        document.body.appendChild(iframe);

        return iframe;
    },
    /**
     * Creates form, that will be submitted to iframe
     */
    _createForm: function(iframe, params){
        // We can't use the following code in IE6
        // var form = document.createElement('form');
        // form.setAttribute('method', 'post');
        // form.setAttribute('enctype', 'multipart/form-data');
        // Because in this case file won't be attached to request
        var form = qq.toElement('<form method="post" enctype="multipart/form-data"></form>');

        var queryString = qq.obj2url(params, this._options.action);

        form.setAttribute('action', queryString);
        form.setAttribute('target', iframe.name);
        form.setAttribute('name', 'upload-form');
        form.style.display = 'none';
        document.body.appendChild(form);

        return form;
    }
});

/**
 * Class for uploading files using xhr
 * @inherits qq.UploadHandlerAbstract
 */
qq.UploadHandlerXhr = function(o){
    qq.UploadHandlerAbstract.apply(this, arguments);

    this._files = [];
    this._xhrs = [];
    
    // current loaded size in bytes for each file 
    this._loaded = [];
};

// static method
qq.UploadHandlerXhr.isSupported = (function(){
    var input = document.createElement('input');
    input.type = 'file';
    
    return (
        'multiple' in input &&
        typeof File != "undefined" &&
        typeof (new XMLHttpRequest()).upload != "undefined" );
}());

// @inherits qq.UploadHandlerAbstract
qq.extend(qq.UploadHandlerXhr.prototype, qq.UploadHandlerAbstract.prototype);

qq.extend(qq.UploadHandlerXhr.prototype, {
    /**
     * Adds file to the queue
     * Returns id to use with upload, cancel
     **/    
    add: function(file){
        if (!(file instanceof File)){
            throw new Error('Passed obj in not a File (in qq.UploadHandlerXhr)');
        }

        return this._files.push(file) - 1;
    },
    getName: function(id){

        var file = this._files[id];
		if (!file) {return false;}
        // fix missing name in safari 4
        return file.fileName != null ? file.fileName : file.name;
    },
    getSize: function(id){
        var file = this._files[id];
        return file.fileSize != null ? file.fileSize : file.size;
    },    
    /**
     * Returns uploaded bytes for file identified by id 
     */    
    getLoaded: function(id){
        return this._loaded[id] || 0; 
    },
	
	hasFilesInProgress: function () {
		var
			key = '',
			xhr = null,
			iCount = 0
		;
		
		for (key in this._xhrs) {
			xhr = this._xhrs[key];
			if (xhr !== undefined && xhr !== null && typeof(xhr) !== 'function') {
				iCount++;
			}
		}
		
		return iCount;
	},
	
    /**
     * Sends the file identified by id and additional query params to the server
     * @param {Object} params name-value string pairs
     */
    _upload: function(id, params){
        var file = this._files[id],
            name = this.getName(id),
            size = this.getSize(id);

        this._loaded[id] = 0;

        var xhr = this._xhrs[id] = new XMLHttpRequest();
        var self = this;

        xhr.upload.onprogress = function(e){
            if (e.lengthComputable){
                self._loaded[id] = e.loaded;
                self._options.onProgress(id, name, e.loaded, e.total);
            }
        };

        xhr.onreadystatechange = function(){
            if (xhr.readyState == 4){
                self._onComplete(id, xhr);
            }
        };

        // build query string
        params = params || {};
        params['qqfile'] = name;
        var queryString = qq.obj2url(params, this._options.action);

        xhr.open("POST", queryString, true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.setRequestHeader("X-File-Name", encodeURIComponent(name));
        xhr.setRequestHeader("Content-Type", "application/octet-stream");
        xhr.send(file);
    },
    _onComplete: function(id, xhr){
        // the request was aborted/cancelled
        if (!this._files[id]) return;
        
        var name = this.getName(id);
        var size = this.getSize(id);
        
        this._options.onProgress(id, name, size, size);

        if (xhr.status == 200){
            var response;

            try {
                response = eval("(" + xhr.responseText + ")");
            } catch(err){
                response = {};
            }
            
            this._options.onComplete(id, name, response);

        } else {
            this._options.onComplete(id, name, {});
        }

        this._files[id] = null;
        this._xhrs[id] = null;
        this._dequeue(id);
    },
    _cancel: function(id){
        this._options.onCancel(id, this.getName(id));
        
        this._files[id] = null;
        
        if (this._xhrs[id]){
            this._xhrs[id].abort();
            this._xhrs[id] = null;
        }
    }
});
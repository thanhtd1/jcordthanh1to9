/*
 * "contents.js" presents the contents-providing features
 */
angular.module("nispApp").service("ContentsService", ["groundwork", function(gw) {
	"use strict";

	var l_this = this;
	var l_currentContents;
	this.headerUrl = "../../contents/html/header.html";
	this.footerUrl = "../../contents/html/footer.html";

	/*
	 * a_contentsPaths  string ... a name path of the "view"
	 *                    (ex.)
	 *                    "donorregistration/registration"
	 *                    "donorregistration/search"
	 *                  plane object ... list of name paths of the "view parts" for each content;
	 *                                   need to be an object even when single part
	 *                    (ex.)
	 *                    {
	 *                      "1": "donorregistration/registration/editing"
	 *                    }
	 *                    {
	 *                      "1": "donorregistration/search/search",
	 *                      "2": "donorregistration/search/list/index"
	 *                    }
	 * return  the return value's example is shown in below:
	 *     (ex.: for View)
	 *     "donorregistration/registration/base.html"
	 *     "donorregistration/search/base.html"
	 *     (ex.: for View Parts)
	 *     {
	 *       "contentPath1": "donorregistration/registration/editing.html"
	 *     }
	 *     {
	 *       "contentPath1": "donorregistration/search/search/index.html",
	 *       "contentPath2": "donorregistration/search/list/index.html"
	 *     }
	 */
	this.lg_get_contents = function(a_context, a_contentsPaths, a_apiDataName, a_keys) {
		if (a_apiDataName) {
			a_context.app.fn_setQueryKey(a_apiDataName, a_keys);
			a_context.app.setQuery();
		}
		var l_result = undefined;
		if (gw.isString(a_contentsPaths)) {
			l_result = viewContentsFullPath(a_contentsPaths);
			if (a_context.app.viewContents != a_contentsPaths) {
				a_context.app.viewContents = a_contentsPaths;
		    	l_this.clearSequence();
			}
		} else if (gw.isArray(a_contentsPaths)) {
			l_result = viewPartContentFullPaths(a_contentsPaths);
			var l_business = "contents/"+a_contentsPaths[0].split('/')[0];
			l_result.headerPath = l_this.headerUrl;
			l_result.footerPath = l_this.footerUrl;
			l_result.listPagingPath = "../../contents/html/list_paging2.html";
			l_result.headerMenuPath = l_business+"/header_menu.html";
			l_result.dialogPath = l_business+"/dialog";
		} else if (gw.isObject(a_contentsPaths)) {
			l_result = [];
		} else {
		    throw new gw.LogicIntegrityCollapsedException();
		}
		l_currentContents = l_result;
		return l_result;
	};

	this.lg_get_service_contents = function(a_context, a_contentsPath) {
		var l_pathElements = a_contentsPath.split("/");
		if (l_pathElements.length == 1) {
		    return [l_pathElements[0], null];
		} else {
		    var l_businessServiceName = l_pathElements.shift();
		    return [l_businessServiceName, l_pathElements.join("/")];
		}
	};

	//// 画面シーケンス ////

	this.clearSequence = function() {
		l_this.sequence = undefined;
		gw.app.registerControllerName(undefined);
	}
	this.setSequence = function(a_contentsBase,a_path,a_start,a_exit,a_sequence) {
		l_this.sequence = {
				contentsBase: a_contentsBase,
				path: a_path,
				start: a_start,
				exit: a_exit,
				list : a_sequence,
				seqNo : 0,
			};
	}
	this.startSequence = function(a_scope) {
		var l_seq = l_this.sequence;
		gw.app.fn_contents(l_seq.contentsBase, l_seq.path, l_seq.list[0].page);
	    gw.app.fn_initConstants(a_scope);
		if (l_seq.start) {
			if ( fn_call("lg_do",l_seq.start) ) {
				l_this.lg_goto(0);
			}
		}
	}
	this.lg_goto = function(a_arg) {
		var l_seq = l_this.sequence;
		var l_item = undefined;
		var l_page = a_arg;
		if ( l_seq ) {
			if ( gw.isNumeric(a_arg) )  {
				l_seq.seqNo = a_arg;
				l_item = l_seq.list[a_arg];
				l_page = l_item.page;
				if ( gw.isArray(l_page) ) {
					var l_arr = [];
					for (var i=0; i<l_page.length; i++) {
						l_arr.push( l_seq.path+"/"+l_page[i] );
					}
					l_page = l_arr;
				} else {
					l_page = l_seq.path+"/"+l_page;
				}
			} else {
				for (var i=0; i < l_seq.list.length; i++) {
					if (l_seq.list[i].label == a_arg) {
						l_this.lg_goto(i);
						return;
					}
				}
			}
		}
		var l_contents = l_this.lg_get_contents(gw.app.context, l_page);
		gw.app.fn_applyContents(l_contents);
		if (l_item)
			gw.app.scope.breadcrumbs = l_item.breadcrumbs;
	}
	this.lg_next = function(a_onlyGo) {
		var l_seq = l_this.sequence;
		var l_now = l_seq.list[l_seq.seqNo];
		var l_success = true;
		if (l_now.next&& !a_onlyGo) {
			l_success = fn_call("lg_do",l_now.next);
		}
		if (!l_success) return;
		if (++l_seq.seqNo >= l_seq.list.length) {
			l_seq.seqNo = 0; // 回りきったら先頭へ戻る
			if (l_now.exit) {
				if ( fn_call("lg_do",l_now.exit) ) {
					return;
				}
			}
		}
		l_this.lg_goto(l_seq.seqNo);
	}
	this.lg_prev = function() {
		var l_seq = l_this.sequence;
		var l_success = true;
		var l_now = l_seq.list[l_seq.seqNo];
		if (l_now.prev) {
			l_success = fn_call("lg_do",l_now.prev);
		}
		if (!l_success) return;
		if (--l_seq.seqNo < 0) {
			l_seq.seqNo = 0;
			if (l_now.exit) {
				if ( fn_call("lg_do",l_now.exit) ) {
					return;
				}
			}
		}
		l_this.lg_goto(l_seq.seqNo);
	}
	this.lg_tabnext = function(ev) {
		if ( ev.keyCode < 48 ) {//|| ev.keyCode > 90 ) {
		    return;
		}

		var l_obj = ev.target;
		var l_len = l_obj.value.length;
		var l_tgr = l_obj.maxLength;

		if ( l_len < l_tgr ) {
		    return;
		}
		var tabindex	 = l_obj.tabIndex;
		var nextindex	 = 0;
		$('[tabindex]').each(function(){
		    var thisindex =  $(this).attr("tabIndex")/1 ;
		    if ( thisindex == undefined || thisindex == -1 ) {
			return true;
		    }
		    //console.log('ng-model:' + $(this).attr("ng-model") );
		    if ( (thisindex > tabindex && nextindex == 0) || (thisindex > tabindex && thisindex < nextindex) ) {
			nextindex   = thisindex;
		    }
		});
		if ( nextindex <= 0 ) {
		    return;
		}
		$('[tabindex=' + nextindex + ']').focus();
	}

	function fn_call(a_logic,a_args) {
		return gw.app.fn_call(a_logic,a_args);
	}

	//// UTILS. ////

	function viewContentsFullPath(a_contentsPath) {
		var l_pathElements = a_contentsPath.split("/");
		if (l_pathElements.length != 2) {
			return a_contentsPath;
		    //throw new gw.LogicIntegrityCollapsedException();
		}
		return CONTENTS_BASE_PATH + "/" + a_contentsPath + "/base.html";
	}

	function viewPartContentFullPaths(a_contentPaths) {
		var l_contentPathsCount = gw.count(a_contentPaths);
		if (l_contentPathsCount <= 0) {
		    throw new gw.LogicIntegrityCollapsedException();
		}
		var l_res = {};
		for (var l_key=0; l_key < l_contentPathsCount; l_key++) {
		    l_res[contentPartPathKeyFullName(l_key)] = viewPartContentFullPath(a_contentPaths[l_key]);
		}
		return l_res;
	}

	function contentPartPathKeyFullName(a_key) {
		if (gw.isNumeric(a_key)) {
		    return CONTENT_PART_PATH_KEY_BASE_NAME_1 + (a_key+1);
		} else {
		    return a_key + CONTENT_PART_PATH_KEY_BASE_NAME_2;
		}
	}

	function viewPartContentFullPath(a_contentPath) {
		var l_pathElements = a_contentPath.split("/");
		return CONTENTS_BASE_PATH + "/" + l_pathElements.join("/") + ".html";
	}

	var CONTENT_PART_PATH_KEY_BASE_NAME_1 = "contentPath";
	var CONTENT_PART_PATH_KEY_BASE_NAME_2 = "ContentPath";
	var CONTENTS_BASE_PATH = "contents";


}]);

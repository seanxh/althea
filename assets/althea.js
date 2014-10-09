/**
 * Althea前端组件
 * @author: seanxh (modified from jiangzhaoyang)
 *
 */
(function($,window,undefined){
	var document=window.document;
	var Althea=(function(){
		var Althea=function(id,context){
			return Althea.util.init(id,context);
		};
		Althea.util=Althea.prototype={
			init:function(id,context){
				if(!id)return this;
				var match=/^(.+)\.(.+)$/.exec(id);
				if(match){
					var type=match[1];
					var id=match[2];
					if(Althea[type]&&Althea[type].get&&$.isFunction(Althea[type].get)){
						return Althea[type].get([id]);				  
					}else{
						$.error("Warning! Althea."+type+".get() is not defined ! return Althea;");
					}
				}
				return Althea;
			}
		};
		
		Althea.util.init.prototype=Althea.util;
		
		Althea.extend = Althea.util.extend = function() {
			var options, name, src, copy, copyIsArray, clone,
			target = arguments[0] || {},
			i = 1,
			length = arguments.length,
			deep = false;
			if ( typeof target === "boolean" ) {
				deep = target;
				target = arguments[1] || {};
				i = 2;
			}
			if ( typeof target !== "object" && !jQuery.isFunction(target) ) {
				target = {};
			}
			if ( length === i ) {
				target = this;
				--i;
			}
			for ( ; i < length; i++ ) {
				if ( (options = arguments[ i ]) != null ) {
					for ( name in options ) {
						src = target[ name ];
						copy = options[ name ];
						if ( target === copy ) {
							continue;
						}
						if ( deep && copy && ( jQuery.isPlainObject(copy) || (copyIsArray = jQuery.isArray(copy)) ) ) {
							if ( copyIsArray ) {
								copyIsArray = false;
								clone = src && jQuery.isArray(src) ? src : [];
							} else {
								clone = src && jQuery.isPlainObject(src) ? src : {};
							}
							target[ name ] = Althea.extend( deep, clone, copy );
						} else if ( copy !== undefined ) {
							target[ name ] = copy;
						}
					}
				}
			}
			return target;
		};	
		Althea.extend({
			_initFn:[],
			_resizeFn:[],
			_userData:[],
			_resizeIng:false,			
			cfg:{
				version:"0.0",
				host :"http://ai-atm-ur-statistic01.ai01.baidu.com/monitor",
			},
			init:function(fn){
				if(fn&&$.isFunction(fn)&&$.inArray(fn,Althea._initFn)==-1){
					Althea._initFn.push(fn);
					return Althea;
				}
				if(fn&&typeof(fn)=="string"){
					if((obj=Althea[fn])&&(_fn=obj._init)&&$.isFunction(_fn)){
						return Althea.init(_fn);
					}					
				}
				if($.isPlainObject(fn)){
					$.extend(Althea.cfg,fn);
					return Althea;
				}				
			},
			getJSON:function(cfg){
				var url=Althea.tool.getUrl(cfg.provider,cfg.params,cfg.fnarg||cfg.fnParams);
				cfg.cont&&cfg.cont.showLoading(cfg.loading);
				$.ajax({
					url:url.url,
					data:url.params,
					dataType:'json',
					type:"POST",
					cache:false,
					async:cfg.async!==false,
					success:function(data) {
						cfg.cont&&cfg.cont.hideLoading();
						var callback=cfg.callback||cfg.success;
						callback&&callback(data);
					},
					error:Althea.tool.ajaxError(cfg.title||"",cfg.cont)
				});		
			},
			charts:function(cfg){
				
				var $container = cfg.container;
				
				var chart = cfg.chart;
				
				var url = Althea.cfg.host + "/?chart="+chart; 
				if( cfg.start_time )
					url += "&stime="+ cfg.start_time;
				if( cfg.end_time )
					url += "&etime="+ cfg.end_time;
				
				  $.ajax({
			             type: "get",
			             async: false,
			             url: url,
			             dataType: "jsonp",
			             jsonpCallback:"altheaChart",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名，也可以写"?"，jQuery会自动为你处理数据
			             success: function(altheaChart){
			            	  Highcharts.setOptions({                                                     
			                      global: {                                                               
			                          useUTC: false                                                       
			                      }                                                                       
			                  }); 
			            	  
			            	 $container.highcharts(altheaChart);
			 			},
				  
			             error: function(){
			                 alert('fail');
			             }
				  });
				
		}
		});
		
		Althea.plug=function(Obj){
			Althea.extend(Obj);
			$.each(Obj,function(key,val){
				if(val._init&&$.isFunction(val._init)){
					Althea.init(val._init);
				}
				if(val._resize&&$.isFunction(val._resize)){
					Althea.resize(val._resize);
				}
			});
			return Althea;
		}
		return Althea;
	})();
	
	window.Althea=Althea;
	
	$(function(){
		while(Althea._initFn.length>0){
			if((fn=Althea._initFn.shift())&&$.isFunction(fn)){
				fn.call(Althea);
			}	
		}
	});
	
	if(!window.console){
		window.console={log:function(){}};
	}
	
	Althea.init(function(){
		$.browser={};
		if($.support.leadingWhitespace == false){
			$.browser.msie= true;
			$.browser.version=7;
		}
	});
	
})(jQuery,window);
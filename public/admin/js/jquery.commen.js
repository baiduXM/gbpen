/*
 * 拖拽移动
 * demo     手机首页栏目拖动为典型事例结构
 * @author  MLW
 * @version 1.1 拖拽过程流畅性，以及避免文字选择，采用新的插件写法改造结构
            1.2 拖拽块上下超出判断，运用其他一级栏目类型测试可行
 * @require 依赖于JQuery1.9+
 * 修改于   2015.08.07|2015.10.12
 * BUG      本身暂无已知问题，适用于table表格结构以及其他div、ul型结构，子级包裹待验证
 */
;(function ( $, window, document, undefined ) {
    // 严格模式
    'use strict';

    var pluginName = 'TreeList',
        // 默认参数
        defaults = {
            parentNode  : 'phone_index-index',                                                      //移动的父级标签
            rootNode    : 'firsttab',                                                               //移动标签
            listNode    : 'firstlist',                                                              //虚线框附加样式
            childNode   : 'childrenlist',                                                           //子级节点
            ParentMoveTag   : '',                                                                   //移动父级标签类型
            MoveTag     : 'tr',                                                                     //移动标签类型
            handleNode  : 'icon-yidong',                                                            //点击移动的标签
            drag_block  : 'feild_move',                                                             //要移动的标签块
            move_block  : 'move_block',                                                             //移动阴影底块
            _blockStyle : {"margin-bottom": "4px"},                                                 //虚线框附加样式
            oncallback  : function(){}                                                              //返回空函数
        };

    $.fn.TreeList = function(options){
        // 暴露插件的默认配置
        this.defaults = defaults;
        options = $.extend(true, defaults, options);
        return this.each(function() {
            var thisEle = this,
                instance = $(thisEle).data(pluginName);
                // 如果没有保存实例
            if (!instance) {
                // 保存实例
                $(thisEle).data(pluginName, instance = new TreeList(thisEle, options));
            }
        });
    }
    // 构造函数
    function TreeList(selsector,configs){
        this.rootThis = selsector;
        this.options = configs;
        this.isMove = false;
        this.onfunc = this.options.oncallback;   // 暴露插件的外部方法
        this._init.apply(this,arguments);
    }
    TreeList.prototype = {
        _init : function(){
            var _this = this;
            // 是否开启调试
            _this._debug(false);
            if(!_this._isValid(this.options))
                return this;
            _this._mousedown();
        },
        _isValid : function(options){
            return !this.options || (this.options && typeof this.options === "object") ? true : false;
        },
        _debug : function(isopen){
            if (isopen && window.console && window.console.log)
            window.console.log('obj size: ' + $(this.rootThis).length + '\r\nobj:' + $(this.rootThis).attr('class'));
        },
        _mousedown : function(){
            var _this = this;
            $(this.rootThis).on('mousedown',function(e){
                // 排除非左击
                if(event.which != 1) return;
                // 阻止选中文本
                e.preventDefault();
                // 查找拖动块获取坐标系
                _this.FirstNode = $(this).closest('.'+_this.options.rootNode);
                var pos = _this.FirstNode.offset();
                // 添加移动块且记录其位置
                _this.FirstNode.after('<'+_this.options.MoveTag+' class="move_block"><td colspan="4"></'+_this.options.MoveTag+'>');
                _this.MoveBlock = _this.FirstNode.next('.move_block');
                // 定义样式
                var _blockStyle = $.extend({
                        'height':_this.FirstNode.outerHeight(),
                        'background':'#AED8DD',
                        'border':'1px dashed #000'
                    }, _this.options._blockStyle),
                    dragEleStyle = $.extend({
                        'display':'block',
                        'position':'absolute',
                        'width':_this.FirstNode.width(),
                        'height':_this.FirstNode.height(),
                        'top':pos.top-136,
                        'border':'1px solid #639cfb',
                        'box-shadow': '0 0 5px #639cfb'
                    }, _this.options.dragEleStyle);
                $('.move_block').css(_blockStyle);
                _this.FirstNode.addClass(_this.options.drag_block); //使点击块变成移动块
                $('.'+_this.options.drag_block).css(dragEleStyle);
                _this.isMove = true;
                _this._y = e.pageY - parseInt($('.'+_this.options.drag_block).css("top"));//鼠标在内部位置
                $('.'+_this.options.drag_block).fadeTo(400, 0.5); //点击后开始拖动并透明显示
                // 拖拽过程与松开事件
                _this._mousemove();
                _this._mouseup();
            });
        },
        _mousemove : function(){
            var _this = this;
            $(window).on('mousemove',function(e){
                e.preventDefault();
                if (_this.isMove) {
                    var y = e.pageY - _this._y;//移动时根据鼠标位置计算控件左上角的绝对位置
                    // 记录上下相邻标签
                    var next_top = _this.MoveBlock.next().length === 0 ? '' : (_this.MoveBlock.next().offset().top - 148);
                    if(_this.MoveBlock.prev().length){
                        var prev_top = _this.MoveBlock.prev().hasClass(_this.options.drag_block) ? (_this.MoveBlock.prev().prev().length != 0 ? (_this.MoveBlock.prev().prev().offset().top - 148) : '') : (_this.MoveBlock.prev().offset().top - 148);
                    }
                    if(y > next_top){//下移
                        if(_this.MoveBlock.next() != null && _this.MoveBlock.next().hasClass(_this.options.rootNode)){
                            _this.MoveBlock.next().after(_this.MoveBlock);
                        }
                    }
                    if(y < prev_top){//上移
                        if(_this.MoveBlock.prev() != null && _this.MoveBlock.prev().prev().hasClass(_this.options.childNode) || _this.MoveBlock.prev().hasClass(_this.options.rootNode) || _this.MoveBlock.prev().hasClass(_this.options.rootNode)){
                            if(_this.MoveBlock.prev().hasClass(_this.options.drag_block) && !_this.MoveBlock.prev().prev().hasClass(_this.options.listNode)){
                                _this.MoveBlock.prev().prev().before(_this.MoveBlock);
                            }
                            else{
                                _this.MoveBlock.prev().before(_this.MoveBlock);
                            }
                        }
                    }
                    $('.'+_this.options.drag_block).css({ 'top': y }); //控件新位置
                }
            });
        },
        _mouseup : function(){
            var _this = this;
            $(window).on('mouseup',function(e){
                _this.isMove = false;
                $('.'+_this.options.rootNode).fadeTo(400, 1);
                _this.FirstNode.removeClass(_this.options.drag_block);
                var move_prev = (_this.MoveBlock.prev().length ? _this.MoveBlock.prev() : _this.MoveBlock);
                move_prev.after(_this.FirstNode);
                // 初始化移动块
                _this.FirstNode.css({'display':'','position':'','width':'','top':'','border':'','box-shadow': ''});
                $('.'+_this.options.move_block).remove();
                // 解绑window事件
                $(window).unbind('mousemove mouseup');
                var indexlist = _this._eachArray();
                _this.onfunc(indexlist);
            });
        },
        _eachArray : function(){
            // 获取排序信息
            var indexs = [];
            var this_idx = 1;
            $('#'+this.options.parentNode+' .'+this.options.rootNode).each(function(){
                if($(this).data('aid')){// 遍历父级
                    indexs.push({
                        id : $(this).data('aid'),
                        indexs : this_idx
                    });
                    this_idx++;
                }else{// 遍历子级
                    $(this).find('.phone_sub_tab tr').each(function(){
                        indexs.push({
                            id : $(this).data('aid'),
                            indexs : this_idx
                        });
                        this_idx++;
                    });
                }
            });
            return indexs;
        }
    }
})( jQuery, window, document );

/*移动提示框*/
;(function ( $, window, document, undefined ) {
    $.fn.MoveBox = function(options){
        // config
        options = $.extend(true, {
            MoveNode : null,
            Trigger : null,
            XOffset : 30,
            YOffset : 10,
            context : '此栏目暂时无对应样式，无法开启展示！',
            callback : function(){}
        }, options);
        var _BoxDiv = $('<div class="BoxStyle">'+options.context+'</div>');
        this.each(function() {
            var _this = $(this),
                MoveNode = (options.MoveNode != null ? $('.'+options.MoveNode) : '') || _this,
                Trigger = (options.Trigger != null ? options.Trigger : 'click');
            MoveNode.on(Trigger,function(event) {
                $('body').append(_BoxDiv);
                _BoxDiv.css({
                    'top' :(event.pageY - options.YOffset) + 'px',
                    'left':(event.pageX + options.XOffset) + 'px',
                }).fadeIn('slow');
            }).mouseleave(function() {
                _BoxDiv.fadeOut('fast',function(){
                    $(this).remove();
                });
            }).mousemove(function(event) {
                _BoxDiv.css({
                    'top' :(event.pageY - options.YOffset) + 'px',
                    'left':(event.pageX + options.XOffset) + 'px',
                });
            });
        });
        return this;
    }
})( jQuery, window, document );

// serializeArray()转化
$.fn.serializeJson=function(){
    var serializeObj=[];
    $(this.serializeArray()).each(function(){
        serializeObj[this.name]=this.value;
    });
    return serializeObj;
};



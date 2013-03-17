var _instinct = angular.module('instinct', []);

var _INSTINCT_EDIT_MODE = false;

_instinct.
    directive('instinctHatch', function($rootScope) {
        return {
            
            scope: true,
            
            link: function(scope, elm, attrs) {
                
                var data = eval("("+attrs.instinctHatch+")");
                //console.log(attrs);
      
                var iifdom = document.getElementById("instinct-interface");
      
                function isFixedPos(elm)
                {
                    if(elm.css("position") == "fixed")
                        return true;
                    
                    var ret = false;
                    
                    var walker = function(){
                        if(jQuery(this).css("position") == "fixed")
                            ret = true;
                        jQuery(this).parents().first().each(walker);
                    };
                    
                    elm.parents().first().each(walker);
                    
                    return ret;
                }
      
                elm.bind("mouseover", function(){
                    if(!scope.edit_mode)
                        return;
                    jQuery(this).stop().animate({
                        opacity: 0.25
                        
                    }, 100);
                    jQuery(this).css({
                        cursor: "pointer"
                    });
                    jQuery(".instinct-hinter").stop(true,true).fadeIn(100);
                    scope.hint(data.hint);
                });
            
                elm.bind("mouseout", function(){
                    if(!scope.edit_mode)
                        return;
                    jQuery(this).stop().animate({
                        opacity: 1
                    });
               
                    jQuery(this).css({
                        cursor: ""
                    });
               
                    jQuery(".instinct-hinter").stop(true,true).fadeOut();
                });
                
            
                elm.bind("click", function(e){
                    if(!scope.edit_mode)
                        return;
                    e.preventDefault();
                    var interf = jQuery("iframe.instinct-interface");
                    
                    jQuery(".instinct-hidden").css({
                        height: jQuery(".instinct-hidden").data("orig-height")
                    });
                    jQuery(".instinct-hidden").removeClass("instinct-hidden");
                    
                    if(data.imode != "chameleon")
                        jQuery(this).addClass("instinct-hidden");
                                   
                    jQuery(this).data("orig-height", jQuery(this).css("height"));
                    
                    jQuery("#instinct-loader").css({
                        display: "table"
                    });
                    jQuery("#instinct-loader").fadeIn();
                    interf.css({
                        display: "none",
                        visibility: "hidden"
                    });
                    var eleoffset = jQuery(this).offset();
                    
                    eleoffset.top += parseInt(jQuery(this).css("padding-top"));
                    eleoffset.top += parseInt(jQuery(this).css("border-top-width"));
                    
                    eleoffset.left += parseInt(jQuery(this).css("padding-left"));
                    eleoffset.left += parseInt(jQuery(this).css("border-left-width"));
                    
                    interf.attr("src", _INSTINCT_AJAX_URL+"ia=interface&ih="+data.hatch+"&ii="+data.id);
                    
                    
                    interf.css("z-index", elm.zIndex() + 1);
                    
                    
                    interf.css({
                        position: "absolute",
                        top: eleoffset.top,
                        left: eleoffset.left,
                        height: jQuery(this).height(),
                        width: jQuery(this).width(),
                        display: "block"
                        
                    });
                    
                    if(isFixedPos(elm))
                    {
                        
                        interf.css({
                            position: "fixed",
                            top: (eleoffset.top - jQuery(document).scrollTop()),
                            left: (eleoffset.left - jQuery(document).scrollLeft())
                            
                        });
                    }
                
                                
                    // console.log(data);
                    scope.select(data, elm);
                });
      
      
                
      
                $rootScope.$on('instinct-hatch-update', function(event, d) {
                    if(d.ele == elm)
                    {
                        d.ele.html(d.data);
                        jQuery(".instinct-hidden").css({
                            height: "auto"
                        });
                        jQuery(".instinct-hidden").removeClass("instinct-hidden");
                        
                        
                        jQuery("iframe.instinct-interface").css({
                            display: "none"
                        });
                        if(data.imode == "chameleon")
                        {
                            jQuery(".instinct-hinter").stop(true,true).fadeIn(100,function(){
                                setTimeout(1000, function(){
                                    jQuery(".instinct-hinter").stop(true,true).fadeOut();
                                });
                                
                            });
                            scope.hint("Changes saved");
                        }
                    // console.log('received');
                    }
                    
                });
                
                $rootScope.$on('instinct-hatch-shadow', function(event, data) {
                    if(data.ele == elm)
                    {
                        if(data.imode != "chameleon")
                        {
                            data.ele.css({
                                height: data.height
                            });
                        }
                        
                    }
                    
                });
                
                $rootScope.$on('instinct-hatch-close', function(event, data) {
                    jQuery(".instinct-hidden").css({
                        height: jQuery(".instinct-hidden").data("orig-height")
                    });
                    jQuery(".instinct-hidden").removeClass("instinct-hidden");
                    jQuery("iframe.instinct-interface").css({
                        display: "none"
                    });
                    
                    var loader = jQuery("#instinct-loader");
                    
                    loader.fadeOut(300, function(){
                        loader.css({
                            display: "none"
                        });
                    });
                    
                });
            
                $rootScope.$on('instinct-hinter-refresh', function(event, data) {
                    jQuery(".instinct-hinter").stop(true,true).fadeIn(100, function(){
                        setTimeout(function(){
                            jQuery(".instinct-hinter").fadeOut();
                        },1000)
                    });
                });
                
                $rootScope.$on('instinct-hatch-fullscreen', function(event, data) {
                    jQuery("iframe.instinct-interface").toggleClass("instinct-interface-fullscreen");
                    var iframe = iifdom.contentWindow ? iifdom.contentWindow.document : iifdom.contentDocument
                    iframe.tinymce.activeEditor.theme.resizeTo(500,500);
                });
                
                $rootScope.$on('instinct-hatch-chameleon', function(event, data) {
                    if(data.source == elm)
                    {
                        var iframe = iifdom.contentWindow ? iifdom.contentWindow.document : iifdom.contentDocument
                        
                        
                                               
                        //alert(data.imode);
                        data.target.css("cssText", elm.css("cssText"));
                        data.source.addClass("instinct-hidden");
                       
                    }
                    
                });
            }
        };
    });
    
function editableCtrl($scope, $http, $rootScope){
        
    $scope.hint_msg = '';
    $scope.hatch = false;
    $scope.element = false;
    $scope.edit_mode = false;
        
    $scope.hint = function(msg){
        if(msg)
            $scope.hint_msg = msg;
        else
            $scope.hint_msg = '';
        if(!$scope.$$phase) {
            $scope.$apply();
        }
    }    
    
    $scope.is_active = function()
    {
        if($scope.hint != '' && $scope.hint)
            return true;
        
        return false;
    } 
    
    $scope.select = function(hatch, element)
    {
        $scope.hatch = hatch;
        $scope.element = element;
        
        $rootScope.$broadcast('instinct-hatch-shadow', {
            height: $scope.element.height(),
            ele: $scope.element
        });
        
    //$scope.hint(hatch.hint);
    }
    
    
    $scope.savehatch = function(data)
    {
        $http.post(_INSTINCT_AJAX_URL,
        {
            ih : $scope.hatch.hatch,
            ia : "save",
            ii: $scope.hatch.id,
            id : data
        }
        ).
        success(function(data, status) {
                        
            $rootScope.$broadcast('instinct-hatch-update', {
                data: data.data, 
                ele: $scope.element
            });
            
        })
        .
        error(function(data, status) {
            $scope.hint("Connection Lost. Please refresh and try again.");
            $rootScope.$broadcast("instinct-hinter-refresh");
        });
    };
    
    $scope.update_hatch_element = function(height)
    {
        $rootScope.$broadcast('instinct-hatch-shadow', {
            height: height,
            ele: $scope.element
        });
    }
    
    $scope.close_hatch = function(){
        $rootScope.$broadcast('instinct-hatch-close', {
           
            ele: $scope.element
        });
    }
    
    $scope.toggle_edit_mode = function(){
        $scope.edit_mode = !$scope.edit_mode;
        if($scope.edit_mode == false)
        {
            $scope.close_hatch();
            $scope.hint("You are now viewing this page");
            $rootScope.$broadcast("instinct-hinter-refresh");
        }
        else
        {
            $scope.hint("Quick edit mode enabled!");
            $rootScope.$broadcast("instinct-hinter-refresh");
        }
            
        
        
    };
    
    $scope.toggle_fullscreen_hatch = function(){
        $rootScope.$broadcast("instinct-hatch-fullscreen");
    };
    
    $scope.chameleon = function(target){
        $rootScope.$broadcast("instinct-hatch-chameleon", {
            target: target, 
            source: $scope.element
        });
    }
    
};

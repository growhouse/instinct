var _instinct = angular.module('instinct', []);

var _INSTINCT_EDIT_MODE = false;

_instinct.
    directive('instinctHatch', function($rootScope) {
        return {
            
            scope: true,
            
            link: function(scope, elm, attrs) {
                
                var data = eval("("+attrs.instinctHatch+")");
                //console.log(attrs);
      
                elm.bind("mouseover", function(){
                    if(!scope.edit_mode)
                        return;
                    jQuery(this).stop().animate({
                        opacity: 0.25
                        
                    }, 100);
                    jQuery(this).css({cursor: "pointer"});
                    jQuery(".instinct-hinter").stop(true,true).fadeIn(100);
                    scope.hint(data.hint);
                });
            
                elm.bind("mouseout", function(){
                    if(!scope.edit_mode)
                        return;
                    jQuery(this).stop().animate({
                        opacity: 1
                    });
               
                    jQuery(this).css({cursor: ""});
               
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
                    jQuery(this).addClass("instinct-hidden");
                
                    jQuery(this).data("orig-height", jQuery(this).css("height"));
                
                    interf.css({
                        display: "none",
                        visibility: "hidden"
                    });
                    var eleoffset = jQuery(this).offset();
                
                    interf.attr("src", "/instinctajax/?ia=interface&ih="+data.hatch+"&ii="+data.id);
                    interf.css({
                        position: "absolute",
                        top: eleoffset.top,
                        left: eleoffset.left,
                        height: jQuery(this).height(),
                        width: jQuery(this).width(),
                        display: "block"
                    });
                
                                
                    // console.log(data);
                    scope.select(data, elm);
                });
      
      
                $rootScope.$on('instinct-hatch-update', function(event, data) {
                    if(data.ele == elm)
                    {
                        data.ele.html(data.data);
                        jQuery(".instinct-hidden").css({
                            height: "auto"
                        });
                        jQuery(".instinct-hidden").removeClass("instinct-hidden");
                        
                        
                        jQuery("iframe.instinct-interface").css({
                            display: "none"
                        });
                    // console.log('received');
                    }
                    
                });
                
                $rootScope.$on('instinct-hatch-shadow', function(event, data) {
                    if(data.ele == elm)
                    {
                        
                        data.ele.css({
                            height: data.height
                        });
                        
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
            
        $scope.$apply();
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
            height: height,
            ele: $scope.element
        });
        
        $scope.hint(hatch.hatch);
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
            $scope.close_hatch();
        
    };
    
    
};

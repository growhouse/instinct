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
                    jQuery(this).stop().animate({
                        boxShadow: "0 0 10px #44f"
                    });
                    //jQuery(".instinct-hinter").stop().fadeIn();
                    scope.hint(data.hint);
                });
            
                elm.bind("mouseout", function(){
                    jQuery(this).stop().animate({
                        boxShadow: "0 0 0px #44f"
                    });
               
                    jQuery(".instinct-hinter").fadeOut();
                });
            
                elm.bind("click", function(){
                    var interf = jQuery("iframe.instinct-interface");
                    var eleoffset = jQuery(this).offset();
                
                    jQuery(".instinct-hidden").removeClass("instinct-hidden");
                    jQuery(this).addClass("instinct-hidden");
                
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
                        data.ele.removeClass("instinct-hidden");
                        data.ele.html(data.data);
                        data.ele.css({height: "auto"});
                        jQuery("iframe.instinct-interface").css({display: "none"});
                        // console.log('received');
                    }
                    
                });
                
                $rootScope.$on('instinct-hatch-shadow', function(event, data) {
                    if(data.ele == elm)
                    {
                        
                        data.ele.css({height: data.height});
                        
                    }
                    
                });
            }
        };
    });
    
function editableCtrl($scope, $http, $rootScope){
        
    $scope.hint_msg = '';
    $scope.hatch = false;
    $scope.element = false;
        
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
    
    
};

// Skeleton jQuery plugin

(function($)
{
    $.fn.fonts = function( options )
    {
        // options.
        $.fn.fonts.settings = $.extend( {}, $.fn.fonts.defaults, options );

        // Go through the matched elements and return the jQuery object.
        return this.each( function() {
                    
            });
    };
    // Public defaults and settings.
    $.fn.fonts.defaults = {
        prop: 'some value'
    };
    $.fn.fonts.settings = { };	
	
	
    // Private functions.
    function myFunc()
    {
        return;
    };
        
    // Public functions.
    $.fn.fonts.getList = function()
    {
        var pattern=/font-family\s*\{([^}]*)}/;
        
     
        for(var x=0; x<document.styleSheets.length; x++)
        {
            if(document.styleSheets[x].cssRules != null && typeof(document.styleSheets[x].cssRules) != "undefined")
            {
                for (var i=0;i<document.styleSheets[x].cssRules.length;i++)
                {
                    
                    var urls=document.styleSheets[x].cssRules[i].cssText.match(pattern);
                    console.log(document.styleSheets[x].cssRules[i].cssText);
                    if (urls)
                    {
                        for (var j=0;j<urls.length;j++)
                        {
                            alert(urls[j]);
                        }
                    }
                }
            }
        }

            
        return;
    };
        
})(jQuery);
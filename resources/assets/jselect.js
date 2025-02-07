(function($) {
    $.fn.vselect = function(options) {

        var el = this;

        this.setValue = function (value) {
            this.val(value);
            return this;
        }
        
        this.initialize = function () {
            var settings = $.extend({
                url: '', // URL to fetch data
                valueField: 'id', // Field from the response to use for option value
                textField: 'text', // Field from the response to use for option text
            }, options);
    
            el.attr('style', 'white-space: pre-wrap;');
    
            el.each(function() {
                var $select = $(el);
                $.ajax({
                    url: settings.url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(i, item) {
                            if(settings.valueField == 'key' && settings.textField == 'value') {
                                $select.append($('<option>', { 
                                    value: i,
                                    html : item
                                }));
                            } else {
                                $select.append($('<option>', { 
                                    value: item[settings.valueField],
                                    text : item[settings.textField] 
                                }));
                            }
                        });
                    },
                    error: function(response) {
                        console.log('Error loading data', response);
                    }
                });
            });

            return el;
        }

        return this.initialize();
    };

}(jQuery));
InputColumn = {
    'init': function() {
        var that = this;
        jQuery('body').on('focus', 'input[data-action="input-column"]', function () {
            var $this = jQuery(this);
            $this.data('old-value', $this.val());
        }).on('keyup', '[data-action="input-column"]', function(e) {
            var $this = jQuery(this);
            if (e.keyCode == 27) {
                $this.val($this.data('old-value'));
            } else if (e.keyCode == 13) {
                that.saveValue($this);
            }
        }).on('blur', '[data-action="input-column"]', function () {
            that.saveValue(jQuery(this));
        });
    },
    'saveValue': function ($input) {
        var that = this;
        $input.parents('.form-group').eq(0).removeClass('has-error').removeClass('has-success');
        if ($input.data('old-value') != $input.val()) {
            jQuery.ajax({
                'data' : {
                    'attribute' : $input.data('attribute'),
                    'id': $input.data('id'),
                    'model' : $input.data('model'),
                    'value' : $input.val()
                },
                'type' : 'post',
                'url' : $input.data('url'),
                'error': function (error) {
                    that.errorCallback($input, error.message);
                },
                'success': function (data) {
                    if (data.status > 0) {
                        that.successCallback($input, data);
                        $input.data('old-value', $input.val());
                    } else {
                        that.errorCallback($input, data.message);
                    }
                }
            });
        }
        this.successCallback($input);
    },
    'errorCallback': function ($input, errorMessage) {
        $input.parents('.form-group').eq(0).addClass('has-error');
        if (typeof console.log != 'undefined') {
            console.log(errorMessage);
        }
    },
    'successCallback': function ($input, data) {
        $input.parents('.form-group').eq(0).addClass('has-success');
    }
};
ToggleColumn = {
    'init': function () {
        var that = this;
        jQuery('body').on('change', '[data-action="toggle-column"] input', function () {
            var $input = jQuery(this);
            var $wrapper = $input.parents('[data-action="toggle-column"]').eq(0);
            jQuery.ajax({
                'data': {
                    'attribute': $wrapper.data('attribute'),
                    'model': $wrapper.data('model'),
                    'id': $wrapper.data('id'),
                    'value': $input.attr('value')
                },
                'dataType': 'json',
                'error': function (error) {
                    that.errorCallback($input, error.message)
                },
                'type': 'post',
                'success': function (data) {
                    if (data.status > 0) {
                        that.successCallback($input, data);
                    } else {
                        that.errorCallback($input, data.message);
                    }
                },
                'url': $wrapper.data('url')
            });
        });
    },
    'errorCallback': function ($input, errorMessage) {
        if (typeof console.log != 'undefined') {
            console.log(errorMessage);
        }
    },
    'successCallback': function ($input, data) {
        $input.parents('[data-action="toggle-column"]').eq(0).find('label').removeClass('active').removeClass('btn-primary').addClass('btn-default').find('input').removeProp('checked');
        $input.prop('checked', 'checked').parents('label').eq(0).addClass('btn-primary');
    }
};

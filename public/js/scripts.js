$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('#token').attr('value') }
});

(function() {

    $('.errorDiv').delay(9000).slideUp(1000);

    var laravel = {
        initialize: function() {
            this.methodLinks = $('a[data-method]');
            this.registerEvents();
        },

        registerEvents: function() {
            this.methodLinks.on('click', this.handleMethod);
        },

        handleMethod: function(e) {
            var link = $(this);
            var httpMethod = link.data('method').toUpperCase();
            var form;

            // If the data-method attribute is not PUT or DELETE,
            // then we don't know what to do. Just ignore.
            if ( $.inArray(httpMethod, ['PUT', 'DELETE']) === - 1 ) {
                return;
            }

            // Allow user to optionally provide data-confirm="Are you sure?"
            if ( link.data('confirm') ) {
                if ( ! laravel.verifyConfirm(link) ) {
                    return false;
                }
            }

            form = laravel.createForm(link);
            form.submit();

            e.preventDefault();
        },

        verifyConfirm: function(link) {
            return confirm(link.data('confirm'));
        },

        createForm: function(link) {
            var form =
                $('<form>', {
                    'method': 'POST',
                    'action': link.attr('href')
                });

            var token =
                $('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': link.data('token')
                });

            var hiddenInput =
                $('<input>', {
                    'name': '_method',
                    'type': 'hidden',
                    'value': link.data('method')
                });

            return form.append(token, hiddenInput)
                .appendTo('body');
        }
    };

    laravel.initialize();

    var navSelector = {
        initialize: function ()
        {
            $('.page-sidebar-menu li').removeClass('active');
            var pageLocation = window.location.href;

            if(pageLocation.substr(-1) === '/') {
                pageLocation = pageLocation.substr(0, pageLocation.length - 1);
            }
            var selector = '.page-sidebar-menu a[href="'+ pageLocation +'"]';
            var currentPage = $(selector);
            currentPage.parent().addClass('active');
            currentPage.parent('li').parent('ul.sub-menu').attr('style', 'display:block');
            currentPage.parent('li').parent('ul.sub-menu').parent('li').addClass('open');
        }
    };


    $( ".sortable" ).sortable({
        placeholder: "ui-state-highlight"
    });
    $( ".sortable" ).disableSelection();

    var pageLoader = {
        bindLinks: function() {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                autoclose: true
            });
            navSelector.initialize();
            Metronic.init();
            $('.dataTable').dataTable();
            $('.select2').select2();

            var links = $('.ajaxLink');
            links.off();
            links.on('click', function(e)
            {
                e.preventDefault();
                pageLoader.loadPage(this, true);
            });
        },

        loadPage: function($url, save, useLoader){
            var loader = $('#loader');
            useLoader = useLoader || useLoader == "undefined";
            save = save || save == "undefined";
            var pageTitle = String($('title').val());

            if (useLoader) {
                loader.show();
            }

            if (save) {
                history.pushState({
                        url: encodeURI($url)},
                    pageTitle,
                    $url
                );
            }

            $.ajax({
                url: $url,
                statusCode: {

                },
                success: function($result){
                    pageLoader.updatePage($result);
                    if(useLoader)
                        loader.hide();
                },
                error: function($result){
                    pageLoader.displayError($result.responseText);
                    if(useLoader)
                        loader.hide();
                }
            });

        },

        updatePage: function(result){
            $('#content').html(result);
            pageLoader.bindLinks();
        },

        displayError: function(result){
            alert(result);
            pageLoader.bindLinks();
        }
    }

    pageLoader.bindLinks();

    // set up history
    window.onpopstate = function(event){
        var obj = event.state;
        if(obj != null) {
            var url = decodeURI(obj.url);
            $("a.link[href='" + url + "']").trigger('click');
            pageLoader.loadPage(url, false);
        }
    };

    var ajaxForm = {
        handle: function(form){
            $.post(form.attr('action'), form.serialize())
        }
    };

})();

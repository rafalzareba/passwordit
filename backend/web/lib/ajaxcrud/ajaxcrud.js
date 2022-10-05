/*!
* Modal Remote
* =================================
*/
(function ($) {
    $.fn.hasAttr = function (name) {
        return this.attr(name) !== undefined;
    };
}(jQuery));


function ModalRemote(modalId) {

    this.defaults = {
        okLabel: 'TAK',
        executeLabel: 'Wykonaj',
        cancelLabel: 'Przerwij',
        loadingTitle: "Ładuję..."
    };

    this.modal = $(modalId);

    this.dialog = $(modalId).find('.modal-dialog');

    this.header = $(modalId).find('.modal-header');

    this.content = $(modalId).find('.modal-body');

    this.footer = $(modalId).find('.modal-footer');

    this.loadingContent = '<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>';


    /**
     * Show the modal
     */
    this.show = function () {
        this.clear();
        $(this.modal).modal('show');
    };

    /**
     * Hide the modal
     */
    this.hide = function () {
        $(this.modal).modal('hide');
    };

    /**
     * Toogle show/hide modal
     */
    this.toggle = function () {
        $(this.modal).modal('toggle');
    };

    /**
     * Clear modal
     */
    this.clear = function () {
        $(this.modal).find('.modal-title').remove();
        $(this.content).html('');
        $(this.footer).html('');
    };

    /**
     * Set size of modal
     * @param {string} size large/normal/small
     */
    this.setSize = function (size) {
        $(this.dialog).removeClass('modal-xs modal-sm modal-md modal-lg modal-xl');
        $(this.dialog).addClass('modal-' + size);
    };

    /**
     * Set scroll of modal
     * @param {string} scroll
     */
    this.setScroll = function (scroll) {
        $(this.dialog).removeClass('modal-dialog-scrollable');
        if (scroll)
            $(this.dialog).addClass('modal-dialog-scrollable');
    };

    /**
     * Set modal header
     * @param {string} content The content of modal header
     */
    this.setHeader = function (content) {
        $(this.header).html(content);
    };

    /**
     * Set modal content
     * @param {string} content The content of modal content
     */
    this.setContent = function (content) {
        $(this.content).html(content);
    };

    /**
     * Set modal footer
     * @param {string} content The content of modal footer
     */
    this.setFooter = function (content) {
        $(this.footer).html(content);
    };

    /**
     * Set modal footer
     * @param {string} title The title of modal
     */
    this.setTitle = function (title) {
        // remove old title
        $(this.header).find('h4.modal-title').remove();
        // add new title
        $(this.header).prepend('<h4 class="modal-title text-uppercase">' + title + '</h4>');
    };

    /**
     * Hide close button
     */
    this.hidenCloseButton = function () {
        $(this.header).find('button.close').hide();
    };

    /**
     * Show close button
     */
    this.showCloseButton = function () {
        $(this.header).find('button.close').show();
    };

    /**
     * Show loading state in modal
     */
    this.displayLoading = function () {
        this.clear();
        this.setContent(this.loadingContent);
        this.setTitle(this.defaults.loadingTitle);
    };

    /**
     * Add button to footer
     * @param label
     * @param type
     * @param classes
     * @param callback
     */
    this.addFooterButton = function (label, type, classes, callback) {
        buttonElm = document.createElement('button');
        buttonElm.setAttribute('type', type === null ? 'button' : type);
        buttonElm.setAttribute('class', classes === null ? 'btn btn-primary ' : classes);
        buttonElm.innerHTML = label;
        var instance = this;
        $(this.footer).append(buttonElm);
        if (callback !== null) {
            $(buttonElm).click(function (event) {
                callback.call(instance, this, event);
            });
        }
    };

    /**
     * Send ajax request and wraper response to modal
     * @param {string} url The url of request
     * @param {string} method The method of request
     * @param {object}data of request
     */
    this.doRemote = function (url, method, data) {
        var instance = this;
        $.ajax({
            url: url,
            method: method,
            data: data,
            async: false,
            beforeSend: function () {
                beforeRemoteRequest.call(instance);
            },
            complete: function () {
                $('body').removeClass('page-loading').removeClass('page-bg-transparent');
            },
            error: function (response) {
                errorRemoteResponse.call(instance, response);
            },
            success: function (response) {
                successRemoteResponse.call(instance, response);
            },
            contentType: false,
            cache: false,
            processData: false
        });
    };

    /**
     * Before send request process
     * - Ensure clear and show modal
     * - Show loading state in modal
     */
    function beforeRemoteRequest() {
        this.displayLoading();
    }


    function doRemoteModal(modalForm, instance) {
        var data;

        // Test if browser supports FormData which handles upload
        if (window.FormData) {
            //codefellow safari browser fix!!!
            var $form = $(modalForm);
            var $inputs = $('input[type="file"]:not([disabled])', $form);
            $inputs.each(function (_, input) {
                if (input.files.length > 0) return;
                $(input).prop('disabled', true);
            });
            var data = new FormData($form[0]);
            $inputs.prop('disabled', false);
        } else {
            // Fallback to serialize
            data = $(modalForm).serializeArray();
        }


        $('body').addClass('page-loading').addClass('page-bg-transparent');
        window.setTimeout(function () {
            instance.doRemote(
                $(modalForm).attr('action'),
                $(modalForm).hasAttr('method') ? $(modalForm).attr('method') : 'GET',
                data
            );
        }, 200);
    }


    /**
     * When remote sends error response
     * @param {string} response
     */
    function errorRemoteResponse(response) {
        this.show();
        this.setTitle(response.status + response.statusText);
        this.setContent(response.responseText.replace('Forbidden (#403): ', ''));
        this.addFooterButton('Zamknij', 'button', 'btn btn-light-danger', function (button, event) {
            this.hide();
        });
    }

    /**
     * When remote sends success response
     * @param {string} response
     */
    function successRemoteResponse(response) {

        this.show();

        if (response.alertInfo !== undefined && response.alertInfo) {
            toastr.success(response.alertInfo);
        }

        if (response.removeEvent !== undefined && response.removeEvent) {
            let event_id = parseInt(response.removeEvent);
            calendar.getEventById(event_id).remove();
        }

        if (response.addEvent !== undefined && response.addEvent) {

            let eventObject = JSON.parse(response.addEvent);

            calendar.addEvent(eventObject)
        }


        // Reload datatable if response contain forceReload field
        if (response.codefellowPjaxReload !== undefined && response.codefellowPjaxReload) {
            var reloadDefaultPjax = false;
            $.each(response.codefellowPjaxReload, function (index, containerId) {
                if (containerId !== '#crud-datatable-pjax' && $(containerId).length) {
                    $.pjax.reload({container: containerId, timeout: 2e3, async: false});
                } else {
                    reloadDefaultPjax = true;
                }
            });
            if (reloadDefaultPjax && $('#crud-datatable-pjax').length) {
                $.pjax.reload({container: '#crud-datatable-pjax', timeout: 2e3});
            }
        } else if (response.forceReload !== undefined && response.forceReload) {
            if (response.forceReload == 'true') {
                // Backwards compatible reload of fixed crud-datatable-pjax
                $.pjax.reload({container: '#crud-datatable-pjax'});
            } else {
                $.pjax.reload({container: response.forceReload});
            }
        }

        if (response.nextAction !== undefined && response.nextAction) {
            this.hide();
            setTimeout(function () {
                modal.open('<a href="' + response.nextAction + '"></a>', null);
            }, 500);
            return;
        }

// Redirect when url is set
        if (response.url !== undefined) {
            document.location.href = response.url;
        }

        // Close modal if response contains forceClose field
        if (response.forceClose !== undefined && response.forceClose) {
            this.hide();
            return;
        }

        if (response.size !== undefined)
            this.setSize(response.size);

        if (response.scrollable !== undefined)
            this.setScroll(response.scrollable);

        if (response.title !== undefined)
            this.setTitle(response.title);

        if (response.content !== undefined)
            this.setContent(response.content);

        if (response.footer !== undefined)
            this.setFooter(response.footer);

        if ($(this.content).find("form")[0] !== undefined) {
            this.setupFormSubmit(
                $(this.content).find("form")[0],
                $(this.footer).find('[type="submit"]')[0]
            );
        }
    }

    /**
     * Prepare submit button when modal has form
     * @param {string} modalForm
     * @param {object} modalFormSubmitBtn
     */
    this.setupFormSubmit = function (modalForm, modalFormSubmitBtn) {

        if (modalFormSubmitBtn === undefined) {
            // If submit button not found throw warning message
            console.warn('Modal has form but does not have a submit button');
        } else {
            var instance = this;

            $(modalForm).on('beforeSubmit', function () {

                doRemoteModal(modalForm, instance);

                return false;
            });

            // Submit form when user clicks submit button
            $(modalFormSubmitBtn).click(function (e) {
                var data = $(modalForm).data('yiiActiveForm');

                if (data === undefined) {
                    doRemoteModal(modalForm, instance);
                } else {
                    $(modalForm).yiiActiveForm('validate', true);

                }
            });
        }
    };

    /**
     * Show the confirm dialog
     * @param {string} title The title of modal
     * @param {string} message The message for ask user
     * @param {string} okLabel The label of ok button
     * @param {string} cancelLabel The class of cancel button
     * @param {string} size The size of the modal
     * @param {string} dataUrl Where to post
     * @param {string} dataRequestMethod POST or GET
     * @param {number[]} selectedIds
     */
    this.confirmModal = function (title, message, okLabel, cancelLabel, size, dataUrl, dataRequestMethod, selectedIds) {
        this.show();
        this.setSize(size);

        if (title !== undefined) {
            this.setTitle(title);
        }
        // Add form for user input if required
        this.setContent('<form id="ModalRemoteConfirmForm">' + message);
        $('body').removeClass('page-loading').removeClass('page-bg-transparent');
        var instance = this;
        this.addFooterButton(
            okLabel === undefined ? this.defaults.okLabel : okLabel,
            'submit',
            'btn btn-primary ',
            function (e) {
                var data;

                // Test if browser supports FormData which handles upload
                if (window.FormData) {
                    data = new FormData($('#ModalRemoteConfirmForm')[0]);
                    if (typeof selectedIds !== 'undefined' && selectedIds)
                        data.append('pks', selectedIds.join());
                } else {
                    // Fallback to serialize
                    data = $('#ModalRemoteConfirmForm');
                    if (typeof selectedIds !== 'undefined' && selectedIds)
                        data.pks = selectedIds;
                    data = data.serializeArray();
                }

                instance.doRemote(
                    dataUrl,
                    dataRequestMethod,
                    data
                );
            }
        );

        this.addFooterButton(
            cancelLabel === undefined ? this.defaults.cancelLabel : cancelLabel,
            'button',
            'btn btn-light-danger pull-left',
            function (e) {
                this.hide();
            }
        );

    };

    /**
     * Open the modal
     * HTML data attributes for use in local confirm
     *   - href/data-url         (If href not set will get data-url)
     *   - data-request-method   (string GET/POST)
     *   - data-confirm-ok       (string OK button text)
     *   - data-confirm-cancel   (string cancel button text)
     *   - data-confirm-title    (string title of modal box)
     *   - data-confirm-message  (string message in modal box)
     *   - data-modal-size       (string small/normal/large)
     * Attributes for remote response (json)
     *   - forceReload           (string reloads a pjax ID)
     *   - forceClose            (boolean remote close modal)
     *   - size                  (string small/normal/large)
     *   - title                 (string/html title of modal box)
     *   - content               (string/html content in modal box)
     *   - footer                (string/html footer of modal box)
     * @params {elm}
     */
    this.open = function (elm, bulkData) {
        var instance = this;

        $('body').addClass('page-loading').addClass('page-bg-transparent');
        window.setTimeout(function () {
            /**
             * Show either a local confirm modal or get modal content through ajax
             */
            if ($(elm).hasAttr('data-confirm-title') || $(elm).hasAttr('data-confirm-message')) {
                instance.confirmModal(
                    $(elm).attr('data-confirm-title'),
                    $(elm).attr('data-confirm-message'),
                    $(elm).attr('data-confirm-ok'),
                    $(elm).attr('data-confirm-cancel'),
                    $(elm).hasAttr('data-modal-size') ? $(elm).attr('data-modal-size') : 'normal',
                    $(elm).hasAttr('href') ? $(elm).attr('href') : $(elm).attr('data-url'),
                    $(elm).hasAttr('data-request-method') ? $(elm).attr('data-request-method') : 'GET',
                    bulkData
                );
            } else {
                instance.doRemote(
                    $(elm).hasAttr('href') ? $(elm).attr('href') : $(elm).attr('data-url'),
                    $(elm).hasAttr('data-request-method') ? $(elm).attr('data-request-method') : 'GET',
                    bulkData
                );
            }
        }, 200);

    }
} // End of Object


$(document).ready(function () {

    // Create instance of Modal Remote
    modal = new ModalRemote('#ajaxCrudModal');

    // Catch click event on all buttons that want to open a modal
    $(document).on('click', '[role="modal-remote"]', function (event) {
        event.preventDefault();
        // Open modal
        modal.open(this, null);
    });

    // Catch click event on all buttons that want to open a modal
    // with bulk action
    $(document).on('click', '[role="modal-remote-bulk"]', function (event) {
        event.preventDefault();

        // Collect all selected ID's
        var selectedIds = [];
        $('input:checkbox[name="selection[]"]').each(function () {
            if (this.checked)
                selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            // If no selected ID's show warning
            modal.show();
            modal.setTitle('Nie zaznaczono');
            modal.setContent('Musisz zaznaczyć rekordy do wykonania tej akcji.');
            modal.addFooterButton("Zamknij", 'btn btn-light-danger', function (button, event) {
                this.hide();
            });
        } else {
            // Open modal
            modal.open(this, selectedIds);
        }
    });
});

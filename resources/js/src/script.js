// adds libs to global scope
window.moment = moment;
window.sortable = sortable;

window.adminLang = jQuery('html').attr('lang');
window.moment.locale(
    window.adminLang
);

// without jquery
(function (w, d) {
    'use strict';

    const csrfToken = d.querySelector('[name="csrf-token"]').
        getAttribute('content');

    // links for POST and DELETE requests
    let formLinks = d.querySelectorAll('[data-method]');

    let createFormForLink = function (method, url, formId) {
        method = method.toUpperCase();

        let methodInput = method === 'POST'
            ? ''
            : `<input type="hidden" name="_method" value="${method}">`;

        return `<form action="${url}" class="d-none" id="${formId}" method="POST">
            <input type="hidden" name="_token" value="${csrfToken}">
            ${methodInput}
        </form>`;
    };

    formLinks.forEach(function (link, number) {
        let method = link.dataset.method;
        let confirmText = link.dataset.confirm;
        let url = link.getAttribute('href');
        let formId = `link-form-${number}`;
        let formHtml = createFormForLink(method, url, formId);

        if (confirmText === undefined) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                link.insertAdjacentHTML('afterend', formHtml);
                d.getElementById(formId).submit();
            }, false);
        } else {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                bootbox.confirm({
                    size: 'small',
                    message: confirmText,
                    locale: d.querySelector('html').getAttribute('lang'),
                    callback: function (result) {
                        if (result) {
                            link.insertAdjacentHTML('afterend', formHtml);
                            d.getElementById(formId).submit();
                        }
                    }
                });
            }, false);
        }
    });

    // highlight required inputs
    // by appending star-sign to label
    let requiredInputs = d.querySelectorAll('[required]');

    requiredInputs.forEach(function (input) {
        try {
            let inputId = input.getAttribute('id');
            let inputLabel = d.querySelector(`label[for="${inputId}"]`);
            let inputLabelText = inputLabel.textContent;

            inputLabel.innerHTML = inputLabelText +
                ' <span class="text-danger">*</span>';
        } catch (e) { }
    });

    // suneditor
    let suneditorInputs = d.querySelectorAll('.js-suneditor-full');

    suneditorInputs.forEach(function (input) {
        let editor = w.SUNEDITOR.create(input, {
            lang: w.SUNEDITOR_LANG[w.adminLang],
            mode: 'inline',
            width: '100%',
            minHeight: 380,
            buttonList: [
                ['undo', 'redo'],
                ['font', 'fontSize', 'formatBlock'],
                ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
                ['removeFormat'],
                '/', // Line break
                ['fontColor', 'hiliteColor'],
                ['outdent', 'indent'],
                ['align', 'horizontalRule', 'list', 'table'],
                ['link', 'image', 'video'],
                ['fullScreen', 'showBlocks', 'codeView'],
                ['preview', 'print'],
                ['save', 'template']
            ]
        });

        editor.onChange = contents => {
            editor.save();
        };
    });

    suneditorInputs = d.querySelectorAll('.js-suneditor');

    suneditorInputs.forEach(function (input) {
        let editor = w.SUNEDITOR.create(input, {
            lang: w.SUNEDITOR_LANG[w.adminLang],
            mode: 'inline',
            width: '100%',
            minHeight: 380,
            buttonList: [
                ['undo', 'redo'],
                ['removeFormat'],
                ['formatBlock'],
                ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
                ['align', 'horizontalRule', 'list', 'table'],
                ['link', 'image', 'video'],
                ['fullScreen', 'showBlocks', 'codeView']
            ]
        });

        editor.onChange = contents => {
            editor.save();
        };
    });
})(window, document);

// with jquery
(function (w, $) {
    'use strict';

    // toasts
    $('.toast').toast('show');

    // select2
    $.fn.select2.defaults.set('language', w.adminLang);
    $.fn.select2.defaults.set('theme', 'bootstrap4');

    $('.js-select2').select2();

    // datepicker
    const datepickerLocales = {
        en: {
            format: 'YYYY-MM-DD',
            applyLabel: "Apply",
            fromLabel: "From",
            toLabel: "To",
            cancelLabel: "Clear"
        },
        ru: {
            format: 'YYYY-MM-DD',
            applyLabel: "Применить",
            fromLabel: "От",
            toLabel: "До",
            cancelLabel: "Очистить"
        }
    };

    $('.js-datepicker').daterangepicker({
        singleDatePicker: true,
        locale: datepickerLocales[w.adminLang] || datepickerLocales['en']
    });
})(window, jQuery);

// deferred callbacks
jQuery(() => {
    // helper object
    if (window.deferredCallbacks !== undefined) {
        for (let func in window.deferredCallbacks) {
            try {
                window.deferredCallbacks[func](window, document, jQuery); // fixes scopes
            } catch (error) {
                console.error(error);
            }
        }
    }
});

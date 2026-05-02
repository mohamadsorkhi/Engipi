(function ($) {
    "use strict";

    // =========================================================================
    // GLOBAL HELPERS
    // =========================================================================

    /**
     * Shows a toast notification using Toastify.js.
     * @param {string} message The message to display.
     * @param {string} type The type of toast ('success', 'error', 'warning', 'info').
     */
    window.showToast = function (message, type = 'info') {
        const config = {
            text: message,
            duration: 3500,
            close: true,
            gravity: 'top',
            position: 'center',
            stopOnFocus: true,
            style: {
                direction: 'rtl',
            }
        };

        switch (type) {
            case 'success':
                config.style.background = "linear-gradient(to right, #00b09b, #96c93d)";
                break;
            case 'error':
                config.style.background = "linear-gradient(to right, #ff5f6d, #ffc371)";
                break;
            case 'warning':
                config.style.background = "linear-gradient(to right, #f39c12, #f1c40f)";
                break;
            default: // info
                config.style.background = "linear-gradient(to right, #0dcaf0, #0d6efd)";
                break;
        }

        Toastify(config).showToast();
    }


    $(document).ready(function () {

        // =========================================================================
        // HELPERS & UTILITIES
        // =========================================================================

        function toPersianNumber(text) {
            if (text === null || text === undefined) return text;
            const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            return text.toString().replace(/\d/g, x => persianNumbers[x]);
        }

        function formatNumber(number) {
            if (!number) return '';
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }



        function unformatNumber(number) {
            if (!number) return '';
            return number.toString().replace(/,/g, '');
        }

        function toggleSubmitButton($button, isLoading) {
            $button.prop('disabled', isLoading);
            $button.find('.spinner-border').toggle(isLoading);
        }

        function clearFormValidation($form) {
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').hide().find('span').text('');
            // Also clear choices.js invalid state
            $form.find('.choices').removeClass('is-invalid');
        }

        function getPersianValidationMessage(input) {
            if (!input || !input.validity) return 'مقدار وارد شده معتبر نیست.';

            // Allow per-field override
            const custom = input.getAttribute('data-invalid-message');
            if (custom) return custom;

            const v = input.validity;

            if (v.valueMissing) {
                return 'این فیلد الزامی است.';
            }

            if (v.typeMismatch) {
                if (input.type === 'email') return 'ایمیل معتبر وارد کنید.';
                if (input.type === 'url') return 'آدرس معتبر وارد کنید.';
                return 'فرمت وارد شده معتبر نیست.';
            }

            if (v.patternMismatch) {
                return 'فرمت وارد شده صحیح نیست.';
            }

            if (v.tooShort) {
                const min = input.getAttribute('minlength');
                if (min) return `حداقل ${min} کاراکتر وارد کنید.`;
                return 'مقدار وارد شده کوتاه است.';
            }

            if (v.tooLong) {
                const max = input.getAttribute('maxlength');
                if (max) return `حداکثر ${max} کاراکتر مجاز است.`;
                return 'مقدار وارد شده طولانی است.';
            }

            if (v.rangeUnderflow) {
                const min = input.getAttribute('min');
                if (min !== null) return `حداقل مقدار مجاز ${min} است.`;
                return 'مقدار وارد شده کمتر از حد مجاز است.';
            }

            if (v.rangeOverflow) {
                const max = input.getAttribute('max');
                if (max !== null) return `حداکثر مقدار مجاز ${max} است.`;
                return 'مقدار وارد شده بیشتر از حد مجاز است.';
            }

            if (v.stepMismatch) {
                return 'مقدار وارد شده با گام معتبر همخوانی ندارد.';
            }

            return 'مقدار وارد شده معتبر نیست.';
        }

        function showNativeFormValidation($form) {
            const formEl = $form[0];
            if (!formEl) return;

            $form.find(':input').each(function () {
                const input = this;
                if (!input || typeof input.checkValidity !== 'function') return;

                const $input = $(input);
                if ($input.is(':disabled') || input.type === 'hidden' || input.type === 'submit' || input.type === 'button') {
                    return;
                }

                if (!input.checkValidity()) {
                    $input.addClass('is-invalid');

                    let $feedback = $input.siblings('.invalid-feedback');
                    if ($feedback.length === 0) {
                        $feedback = $input.closest('.mb-3, .form-group').find('.invalid-feedback');
                    }

                    if ($feedback.length > 0) {
                        $feedback.find('span').text(getPersianValidationMessage(input));
                        $feedback.show();
                    }
                }
            });
        }

        function updateSubmitState($form) {
            const formEl = $form[0];
            if (!formEl || typeof formEl.checkValidity !== 'function') return;

            const isValid = formEl.checkValidity();
            const $submits = $form.find('.ajax-submit');
            $submits.prop('disabled', !isValid);
        }

        function initLiveValidation() {
            // Enable for all forms that contain an ajax-submit button.
            $('form').has('.ajax-submit').each(function () {
                const $form = $(this);

                // Initialize submit enabled/disabled state.
                updateSubmitState($form);

                // Mark fields as touched on blur.
                $form.on('blur', ':input', function () {
                    const $input = $(this);
                    if ($input.is(':disabled') || this.type === 'hidden') return;
                    $input.data('touched', true);

                    if (typeof this.checkValidity === 'function' && !this.checkValidity()) {
                        clearFormValidation($form);
                        showNativeFormValidation($form);
                    } else {
                        $input.removeClass('is-invalid');
                        const $feedback = $input.closest('.mb-3, .form-group').find('.invalid-feedback');
                        $feedback.hide().find('span').text('');
                    }

                    updateSubmitState($form);
                });

                // Re-check on every change/input.
                $form.on('input change', ':input', function () {
                    updateSubmitState($form);

                    const $input = $(this);
                    if (!$input.data('touched')) return;

                    if (typeof this.checkValidity === 'function' && this.checkValidity()) {
                        $input.removeClass('is-invalid');
                        const $feedback = $input.closest('.mb-3, .form-group').find('.invalid-feedback');
                        $feedback.hide().find('span').text('');
                    }
                });
            });

            // Re-init when modals open (forms inside modals may be inserted later)
            $(document).on('shown.bs.modal', function () {
                $('form').has('.ajax-submit').each(function () {
                    updateSubmitState($(this));
                });
            });
        }

        function showFormValidation($form, errors) {
            $.each(errors, function (key, value) {
                let inputName = key.replace(/\.(\w+)/g, '[$1]');
                let $input = $form.find(`[name='${inputName}']`);

                // If not found, try finding with [] for array inputs (e.g. skills[])
                if ($input.length === 0) {
                    $input = $form.find(`[name='${inputName}[]']`);
                }

                if ($input.length > 0) {
                    $input.addClass('is-invalid');

                    // Handle choices.js
                    // 1. Try next sibling (most common)
                    let $choices = $input.next('.choices');
                    // 2. If not found, try nextAll (in case of intermediate hidden elements)
                    if ($choices.length === 0) {
                        $choices = $input.nextAll('.choices').first();
                    }
                    // 3. If still not found, maybe input is inside choices (rare but possible)
                    if ($choices.length === 0) {
                        $choices = $input.closest('.choices');
                    }

                    if ($choices.length > 0) {
                        $choices.addClass('is-invalid');
                    }

                    // Find invalid-feedback
                    let $feedback = $input.siblings('.invalid-feedback');

                    if ($feedback.length === 0) {
                        // Try finding relative to choices if it exists
                        if ($choices.length > 0) {
                            $feedback = $choices.siblings('.invalid-feedback');
                        }
                    }

                    if ($feedback.length === 0) {
                        // Fallback: look in the closest container (mb-3, form-group, etc.)
                        $feedback = $input.closest('.mb-3, .form-group').find('.invalid-feedback');
                    }

                    if ($feedback.length > 0) {
                        $feedback.find('span').text(value[0]);
                        $feedback.show();
                    }
                }
            });
        }


        // =========================================================================
        // GLOBAL INITIALIZERS & BINDINGS
        // =========================================================================

        function initNumberFormatting() {
            $('.number-format-input').each(function () {
                $(this).val(formatNumber(unformatNumber($(this).val())));
            });

            $(document).on('input', '.number-format-input', function () {
                const input = this;
                const originalValue = input.value;
                const originalSelectionStart = input.selectionStart;
                const unformattedValue = unformatNumber(originalValue);
                const formattedValue = formatNumber(unformattedValue);
                const diff = formattedValue.length - originalValue.length;
                const newCursorPosition = originalSelectionStart + diff;

                $(this).val(formattedValue);
                try {
                    input.setSelectionRange(newCursorPosition, newCursorPosition);
                } catch (e) { /* Ignore */ }
            });

            $(document).on('submit', 'form.has-number-format', function () {
                $(this).find('.number-format-input').each(function () {
                    $(this).val(unformatNumber($(this).val()));
                });
            });
        }

        function initPersianNumbers() {
            $('.persian-number').each(function () {
                $(this).text(toPersianNumber($(this).text()));
            });
        }

        function initTextareaAutosize() {
            function resizeTextarea() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight + 2) + 'px';
            }
            $('textarea.auto-resize').each(resizeTextarea);
            $(document).on('input', 'textarea.auto-resize', resizeTextarea);
        }

        function initChoices() {
            const choicesElements = document.querySelectorAll('.choices-select');
            choicesElements.forEach(function(element) {
                new Choices(element, {
                    removeItemButton: true,
                    noResultsText: 'نتیجه‌ای یافت نشد',
                    noChoicesText: 'موردی برای انتخاب وجود ندارد',
                    itemSelectText: 'برای انتخاب کلیک کنید',
                    searchPlaceholderValue: 'جستجو...'
                });
            });
        }

        initNumberFormatting();
        initPersianNumbers();
        initTextareaAutosize();
        initChoices();
        initLiveValidation();


        // =========================================================================
        // EVENT HANDLERS
        // =========================================================================

        $(document).on('click', '.search-input-icon', function () {
            $(this).closest('form').submit();
        });

        $(document).on('keypress', '.submit-on-enter', function (e) {
            if (e.which === 13 && !e.shiftKey) {
                e.preventDefault();
                $(this).closest('form').find('button[type=submit]').first().click();
            }
        });

        $(document).on("click", ".open-left-modal", function () {
            const $modal = $(".custom-left-modal");
            const action = $(this).data("action");
            if (action) {
                $modal.find("form").attr("action", action);
            }
            $modal.addClass("show");
            $(".custom-modal-overlay").addClass("show");
        });

        $(document).on("click", ".custom-left-modal .close, .custom-modal-overlay", function () {
            $(".custom-left-modal").removeClass("show");
            $(".custom-modal-overlay").removeClass("show");
        });

        $(document).on("click", ".html-select .dropdown-item", function (e) {
            e.preventDefault();
            const $this = $(this);
            const $parent = $this.closest(".html-select");
            const title = $this.find('.dropdown-value').text().trim();
            const value = $this.data('value');
            const $input = $parent.find("input[type=hidden]");

            $parent.find(".dropdown-toggle").text(title);
            if ($input.length > 0) {
                $input.val(value).trigger('change');
            }
        });


        // =========================================================================
        // AJAX SUBMISSION & PAGINATION
        // =========================================================================

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on("click", ".ajax-submit", function (e) {
            e.preventDefault();

            const $thisSubmit = $(this);
            const $thisForm = $thisSubmit.closest("form");
            const spoofedMethod = $thisForm.find('input[name="_method"]').val();
            const method = (spoofedMethod || $thisForm.attr("method") || 'POST').toUpperCase();
            const url = $thisForm.attr("action");

            // HTML5 validation before AJAX
            const formEl = $thisForm[0];
            if (formEl && typeof formEl.checkValidity === 'function' && !formEl.checkValidity()) {
                clearFormValidation($thisForm);
                showNativeFormValidation($thisForm);
                return;
            }

            if ($thisSubmit.data("confirm") && !confirm($thisSubmit.data("confirm"))) {
                return;
            }

            let ajaxOptions = {
                type: method,
                url: url,
                headers: { // Force the Accept header to be application/json
                    'Accept': 'application/json',
                },
                beforeSend: function () {
                    toggleSubmitButton($thisSubmit, true);
                    clearFormValidation($thisForm);
                },
                success: function (data) {
                    // --- IMPORTANT CHANGE ---
                    // First, close any open modals if requested.
                    if (data.close) {
                        $(".custom-left-modal.show .close").click();
                        $('.modal.show').modal('hide');
                    }

                    // --- NEW WIZARD LOGIC ---
                    if (data.wizard_container) {
                        $(data.wizard_container).html(data.html);
                        // Re-initialize any plugins if needed
                        initPersianNumbers();
                        initTextareaAutosize();
                    }
                    if (data.progress_container) {
                        $(data.progress_container).html(data.html);
                    }
                    // --- END NEW WIZARD LOGIC ---


                    // Now, handle other success actions.
                    if (data.message) window.showToast(data.message, 'success');
                    if (data.error) window.showToast(data.error, 'error');

                    const redirectUrl = $thisSubmit.data('redirect-url') || data.redirect;
                    if (redirectUrl && data.message) {
                        // Add a small delay for the user to see the toast message
                        setTimeout(function() {
                            window.location.assign(redirectUrl);
                        }, 1000);
                    }

                    if (data.table !== undefined) {
                        $(".ajax-table").html(data.table);
                        initPersianNumbers();
                    }
                    if (data.html !== undefined && data.element) $(data.element).html(data.html);
                    if (data.view !== undefined) {
                        const $modal = $("#modal");
                        $modal.find(".modal-body").html(data.view);
                        $modal.modal("show");
                    }
                },
                error: function (jqXHR) {
                    if (jqXHR.status === 422 && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        showFormValidation($thisForm, jqXHR.responseJSON.errors);
                    } else {
                        const errorMsg = jqXHR.responseJSON?.message || "An unexpected error occurred. Please try again.";
                        window.showToast(errorMsg, 'error');
                    }
                },
                complete: function () {
                    toggleSubmitButton($thisSubmit, false);
                }
            };

            if ($thisForm.find('input[type="file"]').filter(function () { return !!this.files[0]; }).length > 0 || $thisForm.attr('enctype') === 'multipart/form-data') {
                ajaxOptions.data = new FormData($thisForm[0]);
                ajaxOptions.processData = false;
                ajaxOptions.contentType = false;
            } else {
                ajaxOptions.data = $thisForm.serialize();
            }

            $.ajax(ajaxOptions);
        });

        $(document).on("click", ".ajax-table a.page-link", function (e) {
            e.preventDefault();
            const $ajaxTable = $(this).closest(".ajax-table");
            $ajaxTable.css({ opacity: 0.5 });

            $.ajax({
                type: "GET",
                url: $(this).attr("href"),
                success: function (data) {
                    if (data.table !== undefined) {
                        $ajaxTable.html(data.table);
                        initPersianNumbers();
                    }
                },
                error: function () {
                    window.showToast("Failed to load page data.", 'error');
                },
                complete: function () {
                    $ajaxTable.css({ opacity: 1 });
                }
            });
        });

    });

})(jQuery);

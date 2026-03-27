@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-6">
                <div class="profile__wrap card p-4">
                    <div class="d-flex justify-content-between align-content-center flex-wrap gap-2 mb-3">
                        <h4 class="mb-0">@lang('Form Information')</h4>
                    </div>
                    <form id="exportForm" action="{{ route('user.form.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-xxl-12">
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label class="form--label required">@lang('Title')</label>
                                        <input type="text" name="title" id="formTitle" class="form--control"
                                            placeholder="@lang('Enter title')" required>
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label class="form--label required">@lang('Submission Limit')</label>
                                        <input type="number" name="submission_limit" id="submission_limit"
                                            placeholder="@lang('Enter submission limit')" class="form--control" required>
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label class="form--label required">@lang('Question Limit')</label>
                                        <input type="number" name="question_limit" id="question_limit"
                                            class="form--control" value="2" placeholder="@lang('Enter question limit')" required>
                                    </div>
                                    <input type="hidden" name="form_json" id="formJsonInput">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="profile__wrap card p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 mb-2">
                        <h4 class="mb-0">@lang('AI Form Builder Generator')</h4>
                        <span class="text--base">
                            (@lang('Credit cost per question prompt'): {{ $general->credit_cost_per_question_prompt }})
                        </span>
                    </div>
                    <div class="mb-4">
                        <p>@lang('Your current credit is'): <span class="text--base">{{ showAmount(auth()->user()->credit) }}</span></p>

                        @if ($general->credit_cost_per_question_prompt > auth()->user()->credit)
                            <p class="text-danger mb-2">
                                @lang('You do not have enough credits.')
                            </p>
                            <a href="{{ route('user.credit.purchase') }}" class="btn btn--base btn--sm">
                                @lang('Buy Credits')
                            </a>
                        @endif
                    </div>
                    <div class="row g-4">
                        <div class="col-sm-12">
                            <div class="chat-box border rounded p-3 bg-light" id="chatContainer">
                                <div id="chatMessages" class="d-flex flex-column gap-3">
                                    <div class=" small text-center defaultPrompt">@lang('Start by entering a prompt below...')
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-end mt-4">
                                <textarea id="prompts" class="form--control" rows="3" placeholder="@lang('Write a prompt to generate form builder...')"></textarea>
                                @if ($general->credit_cost_per_prompt <= auth()->user()->credit)
                                    <button type="button" class="btn btn--base mt-2" id="generateBtn">
                                        <i class="fa-solid fa-paper-plane"></i> @lang('Generate')
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row g-4 justify-content-center mt-2">
            <div class="col-12">
                <div class="app">
                    <div class="palette profile__wrap card p-4">
                        <div class="control__heading">
                            <h4>@lang('Controls')</h4>
                            <p>@lang('Drag any control into the canvas')</p>
                        </div>
                        <div draggable="true" data-type="text" class="control drag__item">
                            <i class="las la-braille"></i>
                            <div>
                                <i class="fa-solid fa-text-width"></i>
                                <p>@lang('Text Input')</p>
                            </div>
                        </div>
                        <div draggable="true" data-type="email" class="control drag__item">
                            <i class="las la-braille"></i>
                            <div>
                                <i class="fa-solid fa-envelope"></i>
                                <p>@lang('Email Input')</p>
                            </div>
                        </div>
                        <div draggable="true" data-type="textarea" class="control drag__item">
                            <i class="las la-braille"></i>
                            <div>
                                <i class="fa-solid fa-comment"></i>
                                <p>@lang('Textarea')</p>
                            </div>
                        </div>
                        <div draggable="true" data-type="select" class="control drag__item">
                            <i class="las la-braille"></i>
                            <div>
                                <i class="fa-solid fa-circle-chevron-down"></i>
                                <p>@lang('Select / Dropdown')</p>
                            </div>
                        </div>
                        <div draggable="true" data-type="radio" class="control drag__item">
                            <i class="las la-braille"></i>
                            <div>
                                <i class="fa-solid fa-circle-dot"></i>
                                <p>@lang('Radio Group')</p>
                            </div>
                        </div>
                        <div draggable="true" data-type="checkbox" class="control drag__item">
                            <i class="las la-braille"></i>
                            <div>
                                <i class="fa-solid fa-circle-check"></i>
                                <p>@lang('Checkbox Group')</p>
                            </div>
                        </div>

                        <div class="template mt-3">
                            <h5 class="mb-2">@lang('Template')</h5>

                            <div class="template__item">
                                <input type="radio" class="d-none" name="options" id="option1" autocomplete="off"
                                    checked>
                                <label for="option1">@lang('Default Template')</label>
                            </div>

                            <div class="template__item">
                                <input type="radio" class="d-none" name="options" id="option2" autocomplete="off">
                                <label for="option2">@lang('Template One')</label>
                            </div>

                            <div class="template__item">
                                <input type="radio" class="d-none" name="options" id="option3" autocomplete="off">
                                <label for="option3">@lang('Template Two')</label>
                            </div>

                            <div class="template__item">
                                <input type="radio" class="d-none" name="options" id="option4" autocomplete="off">
                                <label for="option4">@lang('Template Three')</label>
                            </div>
                        </div>
                    </div>

                    <div class="canvas palette profile__wrap card p-4">
                        <div class="control__heading">
                            <h4>@lang('Canvas')</h4>
                            <p>
                                @if (!$planSubscription)
                                    <span class="text--danger">
                                        @lang("You don't have an active subscription.")
                                    </span>
                                @else
                                    @lang('You can create up to') {{ $planSubscription?->plan?->input_limit }} @lang('questions with your current plan.')
                                @endif
                            </p>
                        </div>

                        <div id="dropZone" class="drop-zone" ondragover="event.preventDefault()"
                            data-plc="@lang('Drag and drop here')"></div>

                        <p class="text--warning fs--14 mt-4">@lang('Note: Drop order is the form order. Elements are editable and removable.')</p>
                    </div>

                    <div class="inspector palette profile__wrap card p-4">
                        <div class="control__heading">
                            <h4>@lang('Inspector')</h4>
                            <p id="inspectorEmpty" class="d-none">@lang('Select an element to edit its properties.')</p>
                        </div>

                        <div id="inspector" class="d-none">
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div class="meta__single">
                                        <label class="form--label">@lang('Type'): <span
                                                id="metaType"></span></label>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="meta__single">
                                        <label class="form--label">@lang('Label')</label>
                                        <input id="propLabel" class="form--control" type="text">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="meta__single">
                                        <label class="form--label">@lang('Placeholder')</label>
                                        <input id="propPlaceholder" type="text" class="form--control">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="meta__single">
                                        <div id="requiredRow" class="form--check">
                                            <input id="propRequired" class="form-check-input" type="checkbox">
                                            <label for="propRequired" class="form-check-label">@lang('Required')</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 d-none" id="optionsArea">
                                    <div class="meta__single">
                                        <div>
                                            <label class="form--label">@lang('Options')</label>
                                            <div class="options-list" id="optionsList"></div>
                                            <div class="meta__select">
                                                <input id="newOption" class="form--control" type="text"
                                                    placeholder="@lang('option text')">
                                                <button id="addOption" class="btn btn--sm btn--base w-100 mt-2"
                                                    disabled>@lang('Add')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="meta__single">
                                        <div class="meta__btn">
                                            <button id="applyBtn"
                                                class="btn btn--sm btn--base w-100">@lang('Apply')</button>
                                            <button id="deleteBtn"
                                                class="trash btn btn--sm btn--danger w-100">@lang('Delete')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr
                            style="
                                    border: none;
                                    border-top: 1px solid rgba(255, 255, 255, 0.03);
                                    margin: 10px 0;
                                " />
                    </div>
                </div>
                <div class="app__btn text-end mt-4">
                    <button type="button" id="exportJsonBtn" class="btn btn--base">@lang('Save
                                                                                                                            Form')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('user.form.index') }}" class="btn btn-sm btn--base">
        <i class="fa-solid fa-arrow-left"></i> @lang('Back')
    </a>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/common/css/jquery-ui.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/common/js/jquery-ui.min.js') }}"></script>
@endpush


@push('script')
    <script>
        $(function() {
            'use strict';
            // Ajax form generate
            const chatContainer = $("#chatContainer");
            const chatMessages = $("#chatMessages");
            const defaultChatMessage = $(".defaultChatMessage");
            const defaultPromptMessage = $(".defaultPrompt");
            const generateBtn = $("#generateBtn");
            const promptInput = $("#prompts");

            // Append Message Function
            function appendMessage(content, type = "ai") {
                const msgClass = type === "user" ?
                    "bg--base text-white align-self-end" :
                    "bg-white border align-self-start";
                const msg = `<div class="p-3 rounded ${msgClass}" style="max-width:80%">${content}</div>`;
                chatMessages.append(msg);
                if (!defaultPromptMessage.hasClass('d-none')) {
                    defaultPromptMessage.addClass('d-none');
                }
                defaultText();
                chatContainer.scrollTop(chatContainer[0].scrollHeight);
            }

            // Generate form AJAX Function
            function generateForm(prompt) {
                if (!prompt.trim()) return;
                appendMessage(prompt, "user");
                promptInput.val("");
                let elementCount = $('input[name="question_limit"]').val() || 2;
                appendMessage("<i class='fa fa-spinner fa-spin'></i> AI is generating form builder...", "ai");
                $.ajax({
                    url: "{{ route('user.form.generate') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        prompt: prompt,
                        element_count: elementCount,
                    },
                    success: function(res) {

                        
                        chatMessages.find(".fa-spinner").closest("div").remove();
                        if (res.data == null && res.status == 'error') {
                            chatMessages.find(".fa-spinner").closest("div").remove();
                            notify('error', res.message);
                            
                            return false;
                        }

                        try {
                            const aiFormData = res.data.form;
                            aiFormData.forEach(item => {
                                const maxElements =
                                    "{{ $planSubscription?->plan?->input_limit ?? 0 }}";
                                const hasPlan = {{ $planSubscription ? 'true' : 'false' }};
                                if (!hasPlan) {
                                    notify('error',
                                        'You must have a valid subscription plan to add more elements.'
                                    );
                                    return;
                                }
                                if (elements.length >= maxElements) {
                                    notify('error', `Maximum ${maxElements} elements allowed!`);
                                    return;
                                }

                                const id = "el_" + Math.random().toString(36).slice(2, 9);

                                const newEl = createElementModel(item.type, id);
                                newEl.label = item.label || defaultLabel(item.type);
                                newEl.placeholder = item.placeholder || "";
                                newEl.required = !!item.required;
                                if (item.options) newEl.options = item.options;
                                elements.push(newEl);
                            });

                            // Canvas update
                            renderCanvas();

                            // Auto select last AI element
                            if (aiFormData.length) {
                                const lastEl = elements[elements.length - 1];
                                selectElement(lastEl.id);
                            }

                            // Optional: show JSON in chat
                            const jsonStr = JSON.stringify(aiFormData, null, 4);
                            appendMessage(`<pre class="m-0"><code>${jsonStr}</code></pre>`, "ai");

                        } catch (e) {
                            appendMessage("Invalid JSON output received.", "ai");
                            console.error(e);
                        }

                    },
                    error: function(res) {
                        notify('error', res.responseJSON.message);
                        chatMessages.find(".fa-spinner").closest("div").remove();
                        appendMessage("Something went wrong! Please try again.", "ai");
                    }
                });
            }

            function defaultText() {
                if (!defaultChatMessage.hasClass('d-none')) {
                    defaultChatMessage.addClass('d-none');
                }
            }

            //  Generate Button Click
            generateBtn.on("click", function() {
                const prompt = promptInput.val();

                @if (auth()->guard('web')->check())
                    @if ($general->credit_cost_per_prompt > auth()->user()->credit)
                        notify('error', 'You do not have enough credits.');
                    @else
                        generateForm(prompt);
                    @endif
                @else
                    generateForm(prompt);
                @endif

            });
        });
    </script>
    <script>
        const elements = [];
        let selectedId = null;

        const dropZone = document.getElementById("dropZone");
        const inspector = document.getElementById("inspector");
        const inspectorEmpty = document.getElementById("inspectorEmpty");
        const metaType = document.getElementById("metaType");
        const propLabel = document.getElementById("propLabel");
        const propPlaceholder = document.getElementById("propPlaceholder");
        const propRequired = document.getElementById("propRequired");
        const optionsArea = document.getElementById("optionsArea");
        const optionsList = document.getElementById("optionsList");
        const newOption = document.getElementById("newOption");
        const addOption = document.getElementById("addOption");
        const applyBtn = document.getElementById("applyBtn");
        const deleteBtn = document.getElementById("deleteBtn");

        // initialize draggable controls
        document.querySelectorAll(".palette .control").forEach((node) => {
            node.addEventListener("dragstart", (e) => {
                e.dataTransfer.setData("control-type", node.dataset.type);
            });
        });

        dropZone.addEventListener("dragover", (e) => e.preventDefault());

        // DropZone drop (for new element)
        dropZone.addEventListener("drop", (e) => {
            e.preventDefault();

            // limit set
            const maxElements = "{{ $planSubscription?->plan?->input_limit ?? 0 }}";
            const hasPlan = {{ $planSubscription ? 'true' : 'false' }};
            if (!hasPlan) {
                notify('error', 'You must have a valid subscription plan to add more elements.');
                return;
            }
            if (elements.length >= maxElements) {
                notify('error', `Maximum ${maxElements} elements allowed!`);
                return;
            }

            const controlType = e.dataTransfer.getData("control-type");
            const existingElementId = e.dataTransfer.getData("existing-id");

            if (existingElementId) {
                const afterElement = getDropTarget(e.clientY);
                reorderElement(existingElementId, afterElement?.dataset.id || null);
                return;
            }

            if (!controlType) return;

            const afterElement = getDropTarget(e.clientY);
            const id = "el_" + Math.random().toString(36).slice(2, 9);
            const newEl = createElementModel(controlType, id);

            if (afterElement == null) {
                elements.push(newEl);
            } else {
                const idx = elements.findIndex(
                    (el) => el.id === afterElement.dataset.id
                );
                elements.splice(idx, 0, newEl);
            }

            renderCanvas();
            selectElement(id);
        });

        // Helper: Find where to insert based on mouse Y position
        function getDropTarget(y) {
            const draggableElements = [
                ...dropZone.querySelectorAll(".form-element:not(.dragging)"),
            ];
            let closest = null;
            let closestOffset = Number.NEGATIVE_INFINITY;

            draggableElements.forEach((el) => {
                const box = el.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closestOffset) {
                    closestOffset = offset;
                    closest = el;
                }
            });

            return closest;
        }

        // Create element model
        function createElementModel(type, id) {
            const base = {
                id,
                tag: type === "textarea" ? "textarea" : "input",
                type: type === "textarea" ? "textarea" : type,
                label: defaultLabel(type),
                placeholder: "",
                required: false,
                options: [],
            };
            if (["select", "radio", "checkbox"].includes(type)) {
                base.tag = type;
                base.options = ["Option 1", "Option 2"];
            }
            return base;
        }

        function defaultLabel(type) {
            switch (type) {
                case "email":
                    return "Email Address";
                case "textarea":
                    return "Message";
                case "select":
                    return "Choose Option";
                case "radio":
                    return "Select One";
                case "checkbox":
                    return "Choose Options";
                default:
                    return "Text Field";
            }
        }

        // render canvas
        function renderCanvas() {
            dropZone.innerHTML = "";
            elements.forEach((el) => {
                const wrapper = document.createElement("div");
                wrapper.className = "form-element" + (el.id === selectedId ? " selected" : "");
                wrapper.draggable = true;
                wrapper.dataset.id = el.id;

                // Wrapper click listener
                wrapper.addEventListener("click", (ev) => {
                    const closestWrapper = ev.target.closest(".form-element");
                    if (!closestWrapper) return;
                    selectElement(closestWrapper.dataset.id);
                });

                wrapper.addEventListener("dragstart", (ev) => {
                    ev.dataTransfer.setData("existing-id", el.id);
                });

                wrapper.addEventListener("dragover", (ev) => ev.preventDefault());

                wrapper.addEventListener("drop", (ev) => {
                    ev.preventDefault();
                    const draggedId = ev.dataTransfer.getData("existing-id");
                    if (draggedId && draggedId !== el.id) {
                        reorderElement(draggedId, el.id);
                    }
                });

                // inner HTML preview
                let inner = `<label>${escapeHtml(el.label)}</label>`;

                if (el.type === "text" || el.type === "email") {
                    inner +=
                        `<input type="${el.type}" placeholder="${escapeHtml(el.placeholder)}" class="form--control" readonly style="pointer-events:none"/>`;
                }

                if (el.type === "textarea") {

                    inner +=
                        `<textarea rows="4" placeholder="${escapeHtml(el.placeholder)}" class="form--control"  readonly style="pointer-events:none"></textarea>`;
                }

                if (el.type === "select") {
                    inner +=
                        `<select disabled class="form--control select-2" style="pointer-events:none">${el.options.map((o) => `<option>${escapeHtml(o)}</option>`).join("")}</select>`;
                }

                if (el.type === "radio" || el.type === "checkbox") {
                    const inputType = el.type;
                    inner +=
                        `<div>${el.options.map((o) => `
                                            <label style      = "display:block;margin-bottom:4px">
                                                    <input type= "${inputType}"  disabled style = "pointer-events:none"> ${escapeHtml(o)}
                                        </label>`).join("")}</div>`;
                }

                if (el.type === "button") {
                    inner +=
                        `<div><button disabled style="pointer-events:none">${escapeHtml(el.label)}</button></div>`;
                }

                wrapper.innerHTML = inner;
                dropZone.appendChild(wrapper);
            });
        }


        // reorder elements
        function reorderElement(draggedId, targetId) {
            const fromIdx = elements.findIndex((e) => e.id === draggedId);
            if (fromIdx < 0) return;
            const [item] = elements.splice(fromIdx, 1);

            if (!targetId) {
                elements.push(item);
            } else {
                const toIdx = elements.findIndex((e) => e.id === targetId);
                elements.splice(toIdx, 0, item);
            }

            renderCanvas();
        }

        // select element
        function selectElement(id) {
            selectedId = id;
            inspector.classList.remove("d-none");
            inspectorEmpty.classList.add("d-none");
            const el = elements.find((x) => x.id === id);
            if (!el) return;
            metaType.textContent = el.type;
            propLabel.value = el.label;
            propPlaceholder.value = el.placeholder;
            propRequired.checked = !!el.required;
            if (["select", "radio", "checkbox"].includes(el.type)) {
                optionsArea.classList.remove("d-none");
                renderOptionsList(el);
            } else {
                optionsArea.classList.add("d-none");
            }
            renderCanvas();
        }

        // options list render
        function renderOptionsList(el) {
            optionsList.innerHTML = "";
            el.options.forEach((opt, idx) => {
                const row = document.createElement("div");
                row.className = "option-item";
                row.innerHTML = `
                    <input value = "${escapeHtml(opt)}" data-idx = "${idx}" class = "form--control" type="text">
                    <button data-idx = "${idx}" class = " btn btn--danger">x</button>
                `;
                optionsList.appendChild(row);
                row.querySelector("input").addEventListener("input", (e) => {
                    el.options[idx] = e.target.value;
                });
                row.querySelector("button").addEventListener("click", () => {
                    el.options.splice(idx, 1);
                    renderOptionsList(el);
                });
            });
        }

        newOption.addEventListener("input", () => {
            const v = newOption.value.trim();
            if (v) addOption.removeAttribute("disabled");
            else addOption.setAttribute("disabled", true);
        });

        addOption.addEventListener("click", () => {
            const el = elements.find((x) => x.id === selectedId);
            if (!el) return;

            const v = newOption.value.trim();
            if (!v) return;

            el.options.push(v);
            newOption.value = "";
            addOption.setAttribute("disabled", true); // reset button
            renderOptionsList(el);
        });

        applyBtn.addEventListener("click", () => {
            const el = elements.find((x) => x.id === selectedId);
            if (!el) return;
            el.label = propLabel.value;
            el.placeholder = propPlaceholder.value;
            el.required = propRequired.checked;
            renderCanvas();
        });

        deleteBtn.addEventListener("click", () => {
            const idx = elements.findIndex((x) => x.id === selectedId);
            if (idx < 0) return;
            elements.splice(idx, 1);
            selectedId = null;
            inspector.classList.add('d-none');
            inspectorEmpty.classList.remopve('d-none');
            renderCanvas();
        });

        // deselect when clicking on canvas background
        dropZone.addEventListener("click", (e) => {
            if (e.target === dropZone) {
                selectedId = null;
                inspector.classList.add('d-none');
                inspectorEmpty.classList.remopve('d-none');
                renderCanvas();
            }
        });

        function escapeHtml(str) {
            if (str == null) return "";
            return String(str)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;");
        }

        renderCanvas();

        // Export JSON logic
        document.getElementById("exportJsonBtn").addEventListener("click", () => {
            // 1️⃣ Export form elements
            const formJson = elements.map((el) => {
                const obj = {
                    id: el.id,
                    label: el.label,
                    tag: el.type === "text" || el.type === "email" ? "input" : el.type,
                    type: el.type === "text" || el.type === "email" ? el.type : undefined,
                    required: el.required ? true : false,
                };
                if (["select", "radio", "checkbox"].includes(el.type)) {
                    obj.options = el.options || [];
                }
                if (el.placeholder) {
                    obj.placeholder = el.placeholder;
                }
                return obj;
            });

            // Get selected template
            const selectedTemplate = document.querySelector('.template input[name="options"]:checked');
            const templateValue = selectedTemplate ? selectedTemplate.nextElementSibling.textContent.trim() : null;

            const title = document.getElementById("formTitle").value.trim();
            const submission_limit = document.getElementById("submission_limit").value.trim();
            const question_limit = document.getElementById("question_limit").value.trim();

            if (!title) {
                notify('error', "Form title is required!");
                return false;
            }

            if (!submission_limit) {
                notify('error', "Submission limit is required!");
                return false;
            }

            if (!question_limit) {
                notify('error', "Question limit is required!");
                return false;
            }

            //  Combine into final data
            const exportData = {
                title: document.getElementById("formTitle").value,
                submission_limit: document.getElementById("submission_limit").value ,
                question_limit: document.getElementById("question_limit").value || 5,
                template: templateValue,
                form: formJson,
            };

            // Prepare FormData (for Laravel compatibility)
            const formData = new FormData();
            formData.append("_token", "{{ csrf_token() }}");
            formData.append("title", exportData.title);
            formData.append("submission_limit", exportData.submission_limit);
            formData.append("question_limit", exportData.question_limit);
            formData.append("form_json", JSON.stringify({
                template: exportData.template,
                form: exportData.form
            }));

            // Optional: add file upload if exists
            const imageInput = document.getElementById("uploadLogo3");
            if (imageInput && imageInput.files[0]) {
                formData.append("image", imageInput.files[0]);
            }

            // Send via AJAX (fetch)
            fetch("{{ route('user.form.store') }}", {
                    method: "POST",
                    body: formData,
                })
                .then(async (res) => {
                    const data = await res.json();


                    if (data.status == 'success') {

                        // Redirect to form list or detail page
                        setTimeout(() => {
                            window.location.href = "{{ route('user.form.create') }}";
                            return;
                        }, 1300);
                    }

                    if (res.status === 422) {
                        const errors = data.errors;
                        if (errors) {
                            Object.values(errors).forEach(errArray => {
                                notify('error', errArray[0]);
                            });
                        }
                    } else if (!res.ok) {
                        notify('error', data.message || 'Something went wrong!');
                    } else {
                        notify('success', data.message || 'Form successfully exported!');

                    }
                })
                .catch((err) => {
                    notify('error', 'Server error! Please try again.');

                });
        });
    </script>
@endpush


@push('style')
    <style>
        .control__heading h4 {
            margin-bottom: 4px;
        }

        .control__heading p {
            font-size: 14px;
            color: hsl(var(--black) / 0.4);
        }

        .control__heading {
            margin-bottom: 16px;
        }

        .app {
            display: grid;
            grid-template-columns: 300px 1fr 300px;
            gap: 18px;
        }

        .panel h3 {
            margin: 6px 0 12px;
            font-size: 14px;
            color: var(--accent);
        }

        .drag__item {
            background: hsl(var(--black) / 0.02);
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 4px;
            cursor: move;
            border: 1px dashed hsl(var(--black) / 0.06);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .drag__item>i {
            color: hsl(var(--black) / 0.3);
            font-size: 22px;
            margin-left: -8px;
        }

        .drag__item div {
            text-align: center;
            width: 100%;
        }

        .drag__item div i {
            color: hsl(var(--black) / 0.8);
        }

        .drag__item p {
            font-size: 14px;
            color: hsl(var(--black) / 0.5);
        }

        .canvas {
            border-radius: 10px;
            padding: 18px;
            position: relative;
            overflow: auto;
        }

        .drop-zone {
            min-height: 120px;
            border: 2px dashed hsl(var(--black) / 0.06);
            border-radius: 8px;
            padding: 20px;
            max-height: 1000px;
            overflow-y: auto;
        }

        .drop-zone::-webkit-scrollbar {
            width: 5px;
        }

        .drop-zone::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px hsl(var(--black) / 0.1);
        }

        .drop-zone::-webkit-scrollbar-thumb {
            background: hsl(var(--base) / 0.7);
        }

        .drop-zone::-webkit-scrollbar-thumb:hover {
            background: hsl(var(--base));
        }

        .drop-zone::before {
            content: attr(data-plc);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: hsl(var(--black) / 0.2);
        }

        .drop-zone:has(div)::before {
            display: none;
        }

        .drop-zone:has(div) {
            border-style: solid;
        }

        .form-element {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 2px dashed hsl(var(--black) / 0.1);
            cursor: pointer;
        }

        .form-element:last-child {
            margin-bottom: 0;
        }

        .form-element.selected {
            border-color: hsl(var(--base));
        }

        .toolbar {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .options-list {
            border: 1px dashed hsl(var(--black) / 0.1);
            padding: 8px;
            border-radius: 6px;
            margin-block: 12px;
            background-color: hsl(var(--base) / 0.1);
        }

        .meta__select {
            border: 1px dashed hsl(var(--black) / 0.1);
            padding: 8px;
            border-radius: 6px;
            background-color: hsl(var(--base) / 0.1);
        }

        .option-item {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 6px;
        }

        .option-item:last-child {
            margin-bottom: 0;
        }

        #chatContainer {
            max-height: 300px;
            overflow-y: auto;
            scrollbar-width: thin;
        }







        @media screen and (max-width: 1399px) {
            .app {
                grid-template-columns: 1fr 1fr;
            }
        }


        @media screen and (max-width: 767px) {
            .app {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

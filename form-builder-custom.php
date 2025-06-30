<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Builder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>
<body class="bg-gray-50" x-data="formBuilder">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg p-4 overflow-y-auto">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Form Elements</h2>

            <div class="space-y-3">
                <!--loop through field_list-->
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 w-full">
            <div class="max-w-7xl mx-auto w-full px-4">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Form Builder</h1>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Form
                    </button>
                </div>

                <!-- Form Preview Area -->
                <div
                    class="form-preview rounded-xl p-8 mb-8"
                    @dragover.prevent="dragOver($event)"
                    @drop.prevent="drop($event)">

                    <div class="text-center text-gray-400" x-show="!fields.length">
                        <i class="fas fa-arrow-alt-circle-up text-4xl mb-2"></i>
                        <p class="text-lg">Drag and drop form elements here</p>
                        <p class="text-sm">or click on elements in the sidebar to add them</p>
                    </div>

                    <div class="space-y-6" x-show="fields.length">
                       <!--loop through form_fields json to display fields-->
	                    <template x-for="(field, index) in fields" :key="index">

	                    </template>
                    </div>
                </div>

                <!-- JSON Output Section -->
                <div class="mt-12">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Form Configuration</h2>
                        <div class="flex space-x-2">
                            <button @click="updateJsonOutput()" class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <button @click="navigator.clipboard.writeText(jsonOutput)"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="far fa-copy mr-2"></i> Copy JSON
                            </button>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-sm font-medium text-gray-700">
                                <span x-text="Object.keys(window.form_fields).length"></span>
                                <span x-text="Object.keys(window.form_fields).length === 1 ? 'field' : 'fields'"></span> in form
                            </div>
                        </div>

                        <div class="relative bg-gray-800 text-green-400 p-4 rounded font-mono text-sm overflow-auto max-h-96">
                            <pre x-text="jsonOutput || '// Add fields to the form to see the JSON output'"></pre>
                        </div>

                        <div class="mt-2 text-xs text-gray-500">
                            This JSON represents the current form configuration. It updates automatically as you add or remove fields.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
	    //include test.js data here
        // Initialize form_fields object if it doesn't exist
        if (!window.form_fields) {
            window.form_fields = {};
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('formBuilder', () => ({
	            field_list: {
		            "name": {
			            "attributes": {
				            "label": "Full Name",
				            "placeholder": "Enter your full name",
				            "type": "text",
				            "required": true
			            }
		            },
		            "email": {
			            "attributes": {
				            "label": "Email",
				            "placeholder": "Enter your email",
				            "type": "email",
				            "required": true
			            }
		            },
		            "phone": {
			            "attributes": {
				            "label": "Phone",
				            "placeholder": "Enter your phone number",
				            "type": "tel",
				            "required": false
			            }
		            },
		            "address": {
			            "attributes": {
				            "label": "Address",
				            "placeholder": "Enter your address",
				            "type": "textarea",
				            "rows": 2
			            }
		            },
		            "website": {
			            "attributes": {
				            "label": "Website",
				            "placeholder": "https://example.com",
				            "type": "url"
			            }
		            },
		            "input": {
			            "attributes": {
				            "label": "Input",
				            "placeholder": "Enter value",
				            "type": "text"
			            }
		            },
		            "textarea": {
			            "attributes": {
				            "label": "Message",
				            "placeholder": "Type your message here",
				            "rows": 4
			            }
		            },
		            "number": {
			            "attributes": {
				            "label": "Quantity",
				            "type": "number",
				            "min": 1,
				            "max": 100
			            }
		            },
		            "radio": {
			            "attributes": {
				            "label": "Choose one",
				            "type": "radio",
				            "options": ["Option 1", "Option 2"]
			            }
		            },
		            "checkbox": {
			            "attributes": {
				            "label": "I agree",
				            "type": "checkbox",
				            "required": true
			            }
		            },
		            "calculations": {
			            "attributes": {
				            "label": "Total",
				            "formula": "number1 + number2",
				            "readonly": true
			            }
		            },
		            "select": {
			            "attributes": {
				            "label": "Select one",
				            "options": ["One", "Two", "Three"]
			            }
		            },
		            "datepicker": {
			            "attributes": {
				            "label": "Date",
				            "type": "date"
			            }
		            },
		            "timepicker": {
			            "attributes": {
				            "label": "Time",
				            "type": "time"
			            }
		            },
		            "file_upload": {
			            "attributes": {
				            "label": "Upload File",
				            "type": "file",
				            "accept": ".jpg,.pdf,.png",
				            "multiple": false
			            }
		            },
		            "post_data": {
			            "attributes": {
				            "label": "Post Meta Field",
				            "meta_key": "custom_key"
			            }
		            },
		            "captcha": {
			            "attributes": {
				            "label": "Security Check",
				            "type": "captcha",
				            "provider": "recaptcha_v2"
			            }
		            },
		            "html": {
			            "attributes": {
				            "label": "Custom HTML",
				            "content": "<p>This is a custom block.</p>"
			            }
		            },
		            "page_break": {
			            "attributes": {
				            "label": "Page Break"
			            }
		            },
		            "hidden_field": {
			            "attributes": {
				            "type": "hidden",
				            "name": "source",
				            "value": "forminator"
			            }
		            },
		            "section": {
			            "attributes": {
				            "label": "Section Header",
				            "description": "Group of fields"
			            }
		            },
		            "consent": {
			            "attributes": {
				            "label": "Consent",
				            "description": "I agree to the terms and conditions.",
				            "required": true
			            }
		            },
		            "currency": {
			            "attributes": {
				            "label": "Amount",
				            "type": "number",
				            "currency": "USD"
			            }
		            },
		            "stripe": {
			            "attributes": {
				            "label": "Stripe Payment",
				            "type": "payment",
				            "provider": "stripe"
			            }
		            },
		            "paypal": {
			            "attributes": {
				            "label": "PayPal Payment",
				            "type": "payment",
				            "provider": "paypal"
			            }
		            },
		            "field_group": {
			            "attributes": {
				            "label": "Field Group",
				            "fields": ["name", "email", "phone"]
			            }
		            },
		            "slider": {
			            "attributes": {
				            "label": "Range",
				            "type": "range",
				            "min": 0,
				            "max": 10,
				            "step": 1
			            }
		            },
		            "rating": {
			            "attributes": {
				            "label": "Rating",
				            "type": "rating",
				            "max": 5
			            }
		            },
		            "e_signature": {
			            "attributes": {
				            "label": "E-Signature",
				            "type": "signature"
			            }
		            },
		            "password": {
			            "attributes": {
				            "label": "Password",
				            "placeholder": "Enter password",
				            "type": "password"
			            }
		            },
		            "repeater": {
			            "attributes": {
				            "label": "Repeater Field",
				            "fields": ["text", "email"]
			            }
		            },
		            "image_upload": {
			            "attributes": {
				            "label": "Upload Image",
				            "type": "file",
				            "accept": "image/*"
			            }
		            },
		            "color_picker": {
			            "attributes": {
				            "label": "Pick a color",
				            "type": "color"
			            }
		            },
		            "switch": {
			            "attributes": {
				            "label": "Toggle",
				            "type": "switch",
				            "on_label": "Yes",
				            "off_label": "No"
			            }
		            }
	            },
                fields: [],
                draggedItem: null,
                showJsonOutput: false,
                jsonOutput: '{}',

                // Initialize the component
                init() {
                },
                dragStart(event, type) {
                    this.draggedItem = { type };
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/plain', type);
                },

                dragOver(event) {
                    event.preventDefault();
                    event.dataTransfer.dropEffect = 'move';
                },

                drop(event) {
                    event.preventDefault();
                    if (this.draggedItem) {
                        this.addField(this.draggedItem.type);
                        this.draggedItem = null;
                    }
                },

                addField(type) {
                    const field = { type, id: Date.now() };
                    // Set default values based on field type
                    switch(type) {
                        case 'text':
                            field.label = 'Text Input';
                            field.placeholder = 'Enter text';
                            field.required = false;
                            break;
                        case 'email':
                            field.label = 'Email';
                            field.placeholder = 'Enter your email';
                            field.required = true;
                            break;
                        case 'number':
                            field.label = 'Number';
                            field.placeholder = 'Enter a number';
                            field.min = '';
                            field.max = '';
                            break;
                        case 'tel':
                            field.label = 'Phone Number';
                            field.placeholder = 'Enter phone number';
                            break;
                        case 'textarea':
                            field.label = 'Message';
                            field.placeholder = 'Enter your message';
                            field.rows = 3;
                            break;
                        case 'select':
                            field.label = 'Select Option';
                            field.options = ['Option 1', 'Option 2', 'Option 3'];
                            break;
                        case 'checkbox':
                            field.label = 'Checkbox';
                            field.text = 'I agree to the terms and conditions';
                            break;
                        case 'radio':
                            field.label = 'Radio Group';
                            field.options = ['Option 1', 'Option 2'];
                            field.name = 'radio-group-' + Date.now();
                            break;
                        case 'date':
                            field.label = 'Date';
                            break;
                        case 'file':
                            field.label = 'File Upload';
                            break;
                        case 'hidden':
                            field.name = 'hidden_field';
                            field.value = '';
                            break;
                    }
                    this.fields.push(field);
                },

                removeField(index) {
                    this.fields.splice(index, 1);
                },

                getFieldLabel(type) {
                    return labels[type] || type;
                },

                getFieldHtml(field) {

                }
            }));
        });
    </script>
</body>
</html>

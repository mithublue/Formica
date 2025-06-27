<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Builder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <style>
        .form-field {
            @apply p-3 border border-gray-200 rounded-lg mb-2 bg-white shadow-sm hover:shadow-md transition-shadow cursor-move;
        }
        .form-field:active {
            cursor: grabbing;
        }
        .field-preview {
            min-height: 200px;
            @apply p-4 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div x-data="formBuilder()" class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Form Builder</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Available Fields Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-4 sticky top-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">Available Fields</h2>
                    <div class="space-y-2" id="available-fields">
                        <template x-for="field in availableFields" :key="field.type">
                            <div 
                                draggable="true"
                                @dragstart="startDrag($event, field)"
                                class="p-3 bg-blue-50 text-blue-700 rounded border border-blue-200 cursor-move hover:bg-blue-100"
                            >
                                <div class="flex items-center">
                                    <span x-text="field.icon" class="mr-2"></span>
                                    <span x-text="field.label"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Form Preview Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">Form Preview</h2>
                    <div 
                        @dragover.prevent="dragOver"
                        @drop="drop($event)"
                        class="field-preview"
                        id="form-preview"
                    >
                        <template x-if="formFields.length === 0">
                            <div class="text-center text-gray-400 py-8">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <p class="mt-2">Drag and drop fields here</p>
                            </div>
                        </template>
                        <div id="sortable-fields">
                            <template x-for="(field, index) in formFields" :key="field.id">
                                <div 
                                    draggable="true"
                                    @dragstart="startDrag($event, field, index)"
                                    @click="selectField(index)"
                                    :class="{'ring-2 ring-blue-500': selectedFieldIndex === index}"
                                    class="form-field relative group"
                                >
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-sm font-medium text-gray-700" x-text="field.label"></label>
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button @click.stop="removeField(index)" class="text-red-500 hover:text-red-700">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <template x-if="field.type === 'text' || field.type === 'email' || field.type === 'tel' || field.type === 'number' || field.type === 'password' || field.type === 'url'">
                                        <input 
                                            :type="field.type" 
                                            :placeholder="'Enter ' + field.label.toLowerCase()" 
                                            :required="field.required"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        >
                                    </template>
                                    <template x-if="field.type === 'textarea'">
                                        <textarea 
                                            :placeholder="'Enter ' + field.label.toLowerCase()" 
                                            :required="field.required"
                                            rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        ></textarea>
                                    </template>
                                    <template x-if="field.type === 'select'">
                                        <select 
                                            :required="field.required"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        >
                                            <option value="">Select an option</option>
                                            <template x-for="option in field.options" :key="option">
                                                <option :value="option" x-text="option"></option>
                                            </template>
                                        </select>
                                    </template>
                                    <template x-if="field.type === 'checkbox' || field.type === 'radio'">
                                        <div class="space-y-2">
                                            <template x-for="option in field.options" :key="option">
                                                <div class="flex items-center">
                                                    <input 
                                                        :type="field.type" 
                                                        :name="'field-' + field.id"
                                                        :value="option"
                                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                                    >
                                                    <label class="ml-2 block text-sm text-gray-700" x-text="option"></label>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Field Properties Panel -->
                <div class="mt-6 bg-white rounded-lg shadow-md p-6" x-show="selectedFieldIndex !== null">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">Field Properties</h2>
                    <template x-if="selectedFieldIndex !== null">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Label</label>
                                <input 
                                    type="text" 
                                    x-model="formFields[selectedFieldIndex].label"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Field Type</label>
                                <select 
                                    x-model="formFields[selectedFieldIndex].type"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="text">Text</option>
                                    <option value="email">Email</option>
                                    <option value="tel">Phone</option>
                                    <option value="number">Number</option>
                                    <option value="password">Password</option>
                                    <option value="url">URL</option>
                                    <option value="textarea">Text Area</option>
                                    <option value="select">Dropdown</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="radio">Radio Buttons</option>
                                </select>
                            </div>
                            <div x-show="['select', 'checkbox', 'radio'].includes(formFields[selectedFieldIndex].type)">
                                <label class="block text-sm font-medium text-gray-700">Options (comma-separated)</label>
                                <input 
                                    type="text" 
                                    x-model="formFields[selectedFieldIndex].optionsStr"
                                    @change="updateOptions(selectedFieldIndex)"
                                    placeholder="Option 1, Option 2, Option 3"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="required"
                                    x-model="formFields[selectedFieldIndex].required"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label for="required" class="ml-2 block text-sm text-gray-700">Required</label>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button 
                        @click="resetForm()"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Reset Form
                    </button>
                    <button 
                        @click="exportForm()"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Export Form
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formBuilder', () => ({
                availableFields: [
                    { type: 'text', label: 'Text Input', icon: 'T' },
                    { type: 'email', label: 'Email', icon: '✉️' },
                    { type: 'tel', label: 'Phone', icon: '📞' },
                    { type: 'number', label: 'Number', icon: '🔢' },
                    { type: 'password', label: 'Password', icon: '🔒' },
                    { type: 'url', label: 'URL', icon: '🔗' },
                    { type: 'textarea', label: 'Text Area', icon: '📝' },
                    { type: 'select', label: 'Dropdown', icon: '▾' },
                    { type: 'checkbox', label: 'Checkbox Group', icon: '☑️' },
                    { type: 'radio', label: 'Radio Buttons', icon: '🔘' }
                ],
                formFields: [],
                selectedFieldIndex: null,
                draggedItem: null,
                draggedIndex: null,

                init() {
                    // Initialize Sortable for the form preview
                    this.$nextTick(() => {
                        new Sortable(document.getElementById('sortable-fields') || document.getElementById('form-preview'), {
                            animation: 150,
                            ghostClass: 'opacity-50',
                            onEnd: (evt) => {
                                if (evt.from === evt.to) {
                                    // Reorder items
                                    const movedItem = this.formFields.splice(evt.oldIndex, 1)[0];
                                    this.formFields.splice(evt.newIndex, 0, movedItem);
                                    this.selectedFieldIndex = evt.newIndex;
                                }
                            }
                        });
                    });
                },

                startDrag(event, item, index = null) {
                    this.draggedItem = item;
                    this.draggedIndex = index;
                    event.dataTransfer.setData('text/plain', '');
                },

                dragOver(event) {
                    event.preventDefault();
                },

                drop(event) {
                    event.preventDefault();
                    if (this.draggedItem) {
                        if (this.draggedIndex !== null) {
                            // Reorder existing item
                            const movedItem = this.formFields.splice(this.draggedIndex, 1)[0];
                            this.formFields.push(this.createField(movedItem.type, movedItem.label));
                        } else {
                            // Add new item
                            this.addField(this.draggedItem.type);
                        }
                        this.draggedItem = null;
                        this.draggedIndex = null;
                    }
                },

                addField(type) {
                    const defaultLabels = {
                        'text': 'Text Field',
                        'email': 'Email',
                        'tel': 'Phone',
                        'number': 'Number',
                        'password': 'Password',
                        'url': 'Website',
                        'textarea': 'Message',
                        'select': 'Select Option',
                        'checkbox': 'Options',
                        'radio': 'Choose One'
                    };

                    const newField = {
                        id: Date.now(),
                        type: type,
                        label: defaultLabels[type] || 'Field',
                        required: false
                    };

                    if (['select', 'checkbox', 'radio'].includes(type)) {
                        newField.options = ['Option 1', 'Option 2'];
                        newField.optionsStr = 'Option 1, Option 2';
                    }

                    this.formFields.push(newField);
                    this.selectedFieldIndex = this.formFields.length - 1;
                },

                createField(type, label) {
                    const field = {
                        id: Date.now() + Math.floor(Math.random() * 1000),
                        type: type,
                        label: label,
                        required: false
                    };

                    if (['select', 'checkbox', 'radio'].includes(type)) {
                        field.options = ['Option 1', 'Option 2'];
                        field.optionsStr = 'Option 1, Option 2';
                    }

                    return field;
                },

                selectField(index) {
                    this.selectedFieldIndex = index;
                },

                removeField(index) {
                    this.formFields.splice(index, 1);
                    if (this.selectedFieldIndex === index) {
                        this.selectedFieldIndex = null;
                    } else if (this.selectedFieldIndex > index) {
                        this.selectedFieldIndex--;
                    }
                },

                updateOptions(index) {
                    if (this.formFields[index].optionsStr) {
                        this.formFields[index].options = this.formFields[index].optionsStr
                            .split(',')
                            .map(option => option.trim())
                            .filter(option => option !== '');
                    }
                },

                resetForm() {
                    if (confirm('Are you sure you want to reset the form? This cannot be undone.')) {
                        this.formFields = [];
                        this.selectedFieldIndex = null;
                    }
                },

                exportForm() {
                    const formData = {
                        title: 'Generated Form',
                        fields: this.formFields.map(field => {
                            const exported = { ...field };
                            // Remove temporary properties
                            delete exported.id;
                            delete exported.optionsStr;
                            return exported;
                        })
                    };

                    const dataStr = JSON.stringify(formData, null, 2);
                    const dataUri = 'data:application/json;charset=utf-8,' + encodeURIComponent(dataStr);
                    
                    const exportName = 'form-config.json';
                    
                    const linkElement = document.createElement('a');
                    linkElement.setAttribute('href', dataUri);
                    linkElement.setAttribute('download', exportName);
                    linkElement.click();
                }
            }));
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Builder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'bounce-in': 'bounceIn 0.5s ease-out',
                        'fade-in': 'fadeIn 0.3s ease-out',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .field-item:hover { transform: translateY(-2px); }
        .form-field { transition: all 0.3s ease; }
        .form-field:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div x-data="formBuilder()" class="flex h-screen">
        <!-- Sidebar - Field Types -->
        <div class="w-80 bg-white shadow-lg border-r border-gray-200 overflow-y-auto">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                <h1 class="text-2xl font-bold">Form Builder</h1>
                <p class="text-blue-100 mt-1">Click to build your form</p>
            </div>
            
            <!-- Field Categories -->
            <div class="p-4">
                <!-- Basic Fields -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Basic Fields</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="field in basicFields" :key="field.type">
                            <div 
                                @click="addField(field)"
                                role="button"
                                aria-label="Add field"
                                class="field-item p-3 bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-300 rounded-lg cursor-pointer transition-all duration-200 text-center group"
                            >
                                <div class="text-2xl mb-1 group-hover:scale-110 transition-transform" x-html="field.icon"></div>
                                <div class="text-xs font-medium text-gray-700 group-hover:text-blue-600" x-text="field.label"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Advanced Fields -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Advanced Fields</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="field in advancedFields" :key="field.type">
                            <div 
                                @click="addField(field)"
                                role="button"
                                aria-label="Add field"
                                class="field-item p-3 bg-gray-50 hover:bg-purple-50 border border-gray-200 hover:border-purple-300 rounded-lg cursor-pointer transition-all duration-200 text-center group"
                            >
                                <div class="text-2xl mb-1 group-hover:scale-110 transition-transform" x-html="field.icon"></div>
                                <div class="text-xs font-medium text-gray-700 group-hover:text-purple-600" x-text="field.label"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Layout Fields -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Layout & Others</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="field in layoutFields" :key="field.type">
                            <div 
                                @click="addField(field)"
                                role="button"
                                aria-label="Add field"
                                class="field-item p-3 bg-gray-50 hover:bg-green-50 border border-gray-200 hover:border-green-300 rounded-lg cursor-pointer transition-all duration-200 text-center group"
                            >
                                <div class="text-2xl mb-1 group-hover:scale-110 transition-transform" x-html="field.icon"></div>
                                <div class="text-xs font-medium text-gray-700 group-hover:text-green-600" x-text="field.label"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200 p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Form Preview</h2>
                        <p class="text-sm text-gray-600">Click fields on the left to add them to your form</p>
                    </div>
                    <div class="flex space-x-3">
                        <button 
                            @click="clearForm()"
                            aria-label="Clear form"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2"
                        >
                            <span>🗑️</span>
                            <span>Clear All</span>
                        </button>
                        <button 
                            @click="showCode = !showCode"
                            aria-label="Toggle code visibility"
                            class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2"
                        >
                            <span>💾</span>
                            <span x-text="showCode ? 'Hide Code' : 'View Code'"></span>
                        </button>
                        <button 
                            @click="previewForm()"
                            aria-label="Preview form"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2"
                        >
                            <span>👁️</span>
                            <span>Preview</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Builder Area -->
            <div class="flex-1 p-6 overflow-y-auto">
                <div class="max-w-4xl mx-auto">
                    <!-- Empty State -->
                    <div x-show="formFields.length === 0" class="text-center py-20">
                        <div class="text-6xl mb-4">📝</div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Start Building Your Form</h3>
                        <p class="text-gray-500">Click on any field type from the sidebar to add it to your form</p>
                    </div>

                    <!-- Form Fields -->
                    <div x-show="formFields.length > 0" class="bg-white rounded-xl shadow-lg p-8">
                        <div class="mb-6">
                            <input 
                                x-model="formTitle"
                                type="text" 
                                placeholder="Enter form title..."
                                class="text-2xl font-bold text-gray-800 bg-transparent border-none outline-none w-full focus:bg-gray-50 rounded p-2"
                            >
                            <textarea 
                                x-model="formDescription"
                                placeholder="Enter form description..."
                                class="text-gray-600 bg-transparent border-none outline-none w-full mt-2 resize-none focus:bg-gray-50 rounded p-2"
                                rows="2"
                            ></textarea>
                        </div>

                        <form class="space-y-6">
                            <template x-for="(field, index) in formFields" :key="field.id">
                                <div class="form-field relative group border border-gray-200 rounded-lg p-4 hover:border-blue-300">
                                    <!-- Field Controls -->
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex space-x-1">
                                        <button 
                                            @click="editField(index)"
                                            type="button"
                                            aria-label="Edit field"
                                            class="p-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs"
                                            title="Edit Field"
                                        >
                                            ✏️
                                        </button>
                                        <button 
                                            @click="duplicateField(index)"
                                            type="button"
                                            aria-label="Duplicate field"
                                            class="p-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs"
                                            title="Duplicate Field"
                                        >
                                            📋
                                        </button>
                                        <button 
                                            @click="removeField(index)"
                                            type="button"
                                            aria-label="Remove field"
                                            class="p-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs"
                                            title="Remove Field"
                                        >
                                            🗑️
                                        </button>
                                    </div>

                                    <!-- Field Rendering -->
                                    <div x-html="renderField(field)"></div>
                                </div>
                            </template>

                            <!-- Submit Button -->
                            <div x-show="formFields.length > 0" class="pt-6">
                                <button 
                                    type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105"
                                >
                                    Submit Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Field Editor Modal -->
        <div x-show="showFieldEditor" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Field</h3>
                </div>
                <div class="p-6 space-y-4" x-show="editingField">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                        <input 
                            x-model="editingField.label"
                            type="text" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Placeholder</label>
                        <input 
                            x-model="editingField.placeholder"
                            type="text" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div class="flex items-center">
                        <input 
                            x-model="editingField.required"
                            type="checkbox" 
                            class="mr-2"
                        >
                        <label class="text-sm font-medium text-gray-700">Required Field</label>
                    </div>
                    <template x-if="editingField && (editingField.type === 'select' || editingField.type === 'radio' || editingField.type === 'checkbox')">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Options (one per line)</label>
                            <textarea 
                                x-model="editingField.optionsText"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Option 1&#10;Option 2&#10;Option 3"
                            ></textarea>
                        </div>
                    </template>
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button 
                        @click="cancelEdit()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="saveField()"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200"
                    >
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Code Modal -->
        <div x-show="showCode" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Generated HTML Code</h3>
                    <button 
                        @click="copyCode()"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200"
                    >
                        Copy Code
                    </button>
                </div>
                <div class="p-6">
                    <pre class="bg-gray-100 p-4 rounded-lg text-sm overflow-auto" x-text="generateHTML()"></pre>
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end">
                    <button 
                        @click="showCode = false"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formBuilder() {
            return {
                formFields: [],
                formTitle: 'My Custom Form',
                formDescription: 'Please fill out this form with your information.',
                showFieldEditor: false,
                showCode: false,
                editingField: null,
                editingIndex: -1,
                nextId: 1,

                basicFields: [
                    { type: 'text', label: 'Text', icon: '📝' },
                    { type: 'email', label: 'Email', icon: '📧' },
                    { type: 'password', label: 'Password', icon: '🔒' },
                    { type: 'number', label: 'Number', icon: '🔢' },
                    { type: 'tel', label: 'Phone', icon: '📞' },
                    { type: 'url', label: 'URL', icon: '🌐' },
                    { type: 'textarea', label: 'Textarea', icon: '📄' },
                    { type: 'select', label: 'Dropdown', icon: '📋' }
                ],

                advancedFields: [
                    { type: 'date', label: 'Date', icon: '📅' },
                    { type: 'datetime-local', label: 'DateTime', icon: '🕐' },
                    { type: 'time', label: 'Time', icon: '⏰' },
                    { type: 'file', label: 'File Upload', icon: '📎' },
                    { type: 'range', label: 'Range', icon: '🎚️' },
                    { type: 'color', label: 'Color', icon: '🎨' },
                    { type: 'checkbox', label: 'Checkbox', icon: '☑️' },
                    { type: 'radio', label: 'Radio', icon: '🔘' }
                ],

                layoutFields: [
                    { type: 'hidden', label: 'Hidden', icon: '👻' },
                    { type: 'heading', label: 'Heading', icon: '📰' },
                    { type: 'paragraph', label: 'Paragraph', icon: '📝' },
                    { type: 'divider', label: 'Divider', icon: '➖' }
                ],

                addField(fieldType) {
                    const field = {
                        id: this.nextId++,
                        type: fieldType.type,
                        label: this.getDefaultLabel(fieldType.type),
                        placeholder: this.getDefaultPlaceholder(fieldType.type),
                        required: false,
                        options: fieldType.type === 'select' || fieldType.type === 'radio' || fieldType.type === 'checkbox' ? ['Option 1', 'Option 2', 'Option 3'] : [],
                        optionsText: 'Option 1\nOption 2\nOption 3'
                    };

                    this.formFields.push(field);
                },

                getDefaultLabel(type) {
                    const labels = {
                        text: 'Text Field',
                        email: 'Email Address',
                        password: 'Password',
                        number: 'Number',
                        tel: 'Phone Number',
                        url: 'Website URL',
                        textarea: 'Message',
                        select: 'Select Option',
                        date: 'Date',
                        'datetime-local': 'Date & Time',
                        time: 'Time',
                        file: 'Upload File',
                        range: 'Range',
                        color: 'Color',
                        checkbox: 'Checkbox Options',
                        radio: 'Radio Options',
                        hidden: 'Hidden Field',
                        heading: 'Section Heading',
                        paragraph: 'Description Text',
                        divider: 'Section Divider'
                    };
                    return labels[type] || 'Field';
                },

                getDefaultPlaceholder(type) {
                    const placeholders = {
                        text: 'Enter text here...',
                        email: 'your@email.com',
                        password: 'Enter password...',
                        number: '0',
                        tel: '+1 (555) 123-4567',
                        url: 'https://example.com',
                        textarea: 'Enter your message here...',
                        date: '',
                        'datetime-local': '',
                        time: '',
                        file: '',
                        range: '',
                        color: '#000000',
                        hidden: ''
                    };
                    return placeholders[type] || '';
                },

                renderField(field) {
                    const requiredAttr = field.required ? 'required' : '';
                    const requiredLabel = field.required ? '<span class="text-red-500">*</span>' : '';

                    switch (field.type) {
                        case 'textarea':
                            return `
                                <div class="space-y-2">
                                    <label for="${field.label.toLowerCase().replace(/\s+/g, '_')}" class="block text-sm font-medium text-gray-700">${field.label} ${requiredLabel}</label>
                                    <textarea name="${field.label.toLowerCase().replace(/\s+/g, '_')}" placeholder="${field.placeholder}" ${requiredAttr} rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                            `;
                        
                        case 'select':
                            const options = field.options.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                            return `
                                <div class="space-y-2">
                                    <label for="${field.label.toLowerCase().replace(/\s+/g, '_')}" class="block text-sm font-medium text-gray-700">${field.label} ${requiredLabel}</label>
                                    <select name="${field.label.toLowerCase().replace(/\s+/g, '_')}" ${requiredAttr} class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Choose an option</option>
                                        ${options}
                                    </select>
                                </div>
                            `;
                        
                        case 'checkbox':
                            const checkboxes = field.options.map((opt, i) => `
                                <label class="flex items-center">
                                    <input type="checkbox" name="${field.label.toLowerCase().replace(/\s+/g, '_')}[]" value="${opt}" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="text-sm text-gray-700">${opt}</span>
                                </label>
                            `).join('');
                            return `
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">${field.label} ${requiredLabel}</label>
                                    <div class="space-y-2">
                                        ${checkboxes}
                                    </div>
                                </div>
                            `;
                        
                        case 'radio':
                            const radios = field.options.map((opt, i) => `
                                <label class="flex items-center">
                                    <input type="radio" name="radio_${field.id}" value="${opt}" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="text-sm text-gray-700">${opt}</span>
                                </label>
                            `).join('');
                            return `
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">${field.label} ${requiredLabel}</label>
                                    <div class="space-y-2">
                                        ${radios}
                                    </div>
                                </div>
                            `;
                        
                        case 'range':
                            return `
                                <div class="space-y-2">
                                    <label for="${field.label.toLowerCase().replace(/\s+/g, '_')}" class="block text-sm font-medium text-gray-700">${field.label} ${requiredLabel}</label>
                                    <input type="range" name="${field.label.toLowerCase().replace(/\s+/g, '_')}" min="0" max="100" ${requiredAttr} class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                </div>
                            `;
                        
                        case 'hidden':
                            return `<input type="hidden" name="${field.label.toLowerCase().replace(/\s+/g, '_')}" value="">`;
                        
                        case 'heading':
                            return `<h3 class="text-xl font-semibold text-gray-800 mb-2">${field.label}</h3>`;
                        
                        case 'paragraph':
                            return `<p class="text-gray-600 mb-4">${field.label}</p>`;
                        
                        case 'divider':
                            return `<hr class="border-gray-300 my-6">`;
                        
                        default:
                            return `
                                <div class="space-y-2">
                                    <label for="${field.label.toLowerCase().replace(/\s+/g, '_')}" class="block text-sm font-medium text-gray-700">${field.label} ${requiredLabel}</label>
                                    <input type="${field.type}" name="${field.label.toLowerCase().replace(/\s+/g, '_')}" placeholder="${field.placeholder}" ${requiredAttr} class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            `;
                    }
                },

                editField(index) {
                    this.editingIndex = index;
                    this.editingField = { ...this.formFields[index] };
                    this.showFieldEditor = true;
                },

                saveField() {
                    if (this.editingField.optionsText && (this.editingField.type === 'select' || this.editingField.type === 'radio' || this.editingField.type === 'checkbox')) {
                        this.editingField.options = this.editingField.optionsText.split('\n').filter(opt => opt.trim());
                        if (this.editingField.options.length === 0) {
                            alert('Please add at least one option.');
                            return;
                        }
                    }
                    this.formFields[this.editingIndex] = { ...this.editingField };
                    this.cancelEdit();
                },

                cancelEdit() {
                    this.showFieldEditor = false;
                    this.editingField = null;
                    this.editingIndex = -1;
                },

                duplicateField(index) {
                    const field = { ...this.formFields[index] };
                    field.id = this.nextId++;
                    field.label += ' (Copy)';
                    this.formFields.splice(index + 1, 0, field);
                },

                removeField(index) {
                    this.formFields.splice(index, 1);
                },

                clearForm() {
                    if (confirm('Are you sure you want to clear all fields?')) {
                        this.formFields = [];
                    }
                },

                generateHTML() {
                    let html = `<form class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-lg">\n`;
                    html += `  <div class="mb-6">\n`;
                    html += `    <h2 class="text-2xl font-bold text-gray-800 mb-2">${this.formTitle}</h2>\n`;
                    html += `    <p class="text-gray-600">${this.formDescription}</p>\n`;
                    html += `  </div>\n\n`;

                    this.formFields.forEach(field => {
                        html += `  <div class="mb-6">\n`;
                        html += this.generateFieldHTML(field);
                        html += `  </div>\n\n`;
                    });

                    html += `  <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">\n`;
                    html += `    Submit Form\n`;
                    html += `  </button>\n`;
                    html += `</form>`;

                    return html;
                },

                generateFieldHTML(field) {
                    const requiredAttr = field.required ? ' required' : '';
                    const requiredLabel = field.required ? ' <span class="text-red-500">*</span>' : '';

                    switch (field.type) {
                        case 'textarea':
                            return `    <label class="block text-sm font-medium text-gray-700 mb-2">${field.label}${requiredLabel}</label>\n    <textarea name="${field.label.toLowerCase().replace(/\s+/g, '_')}" placeholder="${field.placeholder}"${requiredAttr} rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>`;
                        
                        case 'select':
                            const options = field.options.map(opt => `      <option value="${opt}">${opt}</option>`).join('\n');
                            return `    <label class="block text-sm font-medium text-gray-700 mb-2">${field.label}${requiredLabel}</label>\n    <select name="${field.label.toLowerCase().replace(/\s+/g, '_')}"${requiredAttr} class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">\n      <option value="">Choose an option</option>\n${options}\n    </select>`;
                        
                        case 'checkbox':
                            const checkboxes = field.options.map(opt => `      <label class="flex items-center">\n        <input type="checkbox" name="${field.label.toLowerCase().replace(/\s+/g, '_')}[]" value="${opt}" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">\n        <span class="text-sm text-gray-700">${opt}</span>\n      </label>`).join('\n');
                            return `    <label class="block text-sm font-medium text-gray-700 mb-2">${field.label}${requiredLabel}</label>\n    <div class="space-y-2">\n${checkboxes}\n    </div>`;
                        
                        case 'radio':
                            const radios = field.options.map(opt => `      <label class="flex items-center">\n        <input type="radio" name="radio_${field.id}" value="${opt}" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">\n        <span class="text-sm text-gray-700">${opt}</span>\n      </label>`).join('\n');
                            return `    <label class="block text-sm font-medium text-gray-700 mb-2">${field.label}${requiredLabel}</label>\n    <div class="space-y-2">\n${radios}\n    </div>`;
                        
                        case 'hidden':
                            return `    <input type="hidden" name="${field.label.toLowerCase().replace(/\s+/g, '_')}" value="">`;
                        
                        case 'heading':
                            return `    <h3 class="text-xl font-semibold text-gray-800">${field.label}</h3>`;
                        
                        case 'paragraph':
                            return `    <p class="text-gray-600">${field.label}</p>`;
                        
                        case 'divider':
                            return `    <hr class="border-gray-300">`;
                        
                        default:
                            return `    <label class="block text-sm font-medium text-gray-700 mb-2">${field.label}${requiredLabel}</label>\n    <input type="${field.type}" name="${field.label.toLowerCase().replace(/\s+/g, '_')}" placeholder="${field.placeholder}"${requiredAttr} class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">`;
                    }
                },

                copyCode() {
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(this.generateHTML()).then(() => {
                            alert('Code copied to clipboard!');
                        }).catch(() => {
                            this.fallbackCopyTextToClipboard(this.generateHTML());
                        });
                    } else {
                        this.fallbackCopyTextToClipboard(this.generateHTML());
                    }
                },

                fallbackCopyTextToClipboard(text) {
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        alert('Code copied to clipboard!');
                    } catch (err) {
                        alert('Failed to copy code. Please copy manually.');
                    }
                    document.body.removeChild(textArea);
                },

                previewForm() {
                    const html = this.generateHTML();
                    const newWindow = window.open('', '_blank');
                    newWindow.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Form Preview</title>
                            <script src="https://cdn.tailwindcss.com"></script>
                        </head>
                        <body class="bg-gray-100 p-8">
                            ${html}
                        </body>
                        </html>
                    `);
                    newWindow.document.close();
                }
            };
        }
    </script>
</body>
</html>

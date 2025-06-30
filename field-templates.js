// Field Templates Component
document.addEventListener('alpine:init', () => {
    Alpine.data('fieldTemplates', () => ({
        // Field type to icon mapping
        fieldIcons: {
            'text': 'fa-font',
            'email': 'fa-envelope',
            'tel': 'fa-phone',
            'number': 'fa-hashtag',
            'textarea': 'fa-paragraph',
            'select': 'fa-chevron-circle-down',
            'checkbox': 'fa-check-square',
            'radio': 'fa-dot-circle',
            'date': 'fa-calendar',
            'file': 'fa-file-upload',
            'hidden': 'fa-eye-slash',
            'url': 'fa-link',
            'password': 'fa-lock',
            'color': 'fa-palette',
            'range': 'fa-sliders-h',
            'time': 'fa-clock',
            'datetime-local': 'fa-calendar-alt',
            'month': 'fa-calendar-week',
            'week': 'fa-calendar-week',
            'search': 'fa-search',
            'submit': 'fa-paper-plane',
            'reset': 'fa-undo',
            'button': 'fa-square'
        },

        // Field type to display name mapping
        fieldLabels: {
            'text': 'Text Input',
            'email': 'Email',
            'tel': 'Phone',
            'number': 'Number',
            'textarea': 'Text Area',
            'select': 'Dropdown',
            'checkbox': 'Checkbox',
            'radio': 'Radio Buttons',
            'date': 'Date Picker',
            'file': 'File Upload',
            'hidden': 'Hidden Input',
            'url': 'URL',
            'password': 'Password',
            'color': 'Color Picker',
            'range': 'Range Slider',
            'time': 'Time Picker',
            'datetime-local': 'Date & Time',
            'month': 'Month Picker',
            'week': 'Week Picker',
            'search': 'Search',
            'submit': 'Submit Button',
            'reset': 'Reset Button',
            'button': 'Button'
        },

        // Field type to color mapping
        fieldColors: {
            'text': 'blue',
            'email': 'green',
            'tel': 'yellow',
            'number': 'purple',
            'textarea': 'red',
            'select': 'indigo',
            'checkbox': 'green',
            'radio': 'blue',
            'date': 'pink',
            'file': 'gray',
            'hidden': 'gray',
            'default': 'blue'
        },

        // Get icon for field type
        getIcon(type) {
            return this.fieldIcons[type] || 'fa-font';
        },

        // Get display label for field type
        getLabel(type) {
            return this.fieldLabels[type] || type;
        },

        // Get color for field type
        getColor(type) {
            return this.fieldColors[type] || this.fieldColors.default;
        },

        // Generate field HTML based on type
        generateFieldHTML(field) {
            const fieldType = field.attributes?.type || field.type || 'text';
            const fieldId = `field-${field.id || Date.now()}`;
            const fieldName = field.attributes?.name || field.name || fieldType;
            const label = field.attributes?.label || this.getLabel(fieldType);
            const placeholder = field.attributes?.placeholder || '';
            const required = field.attributes?.required || false;
            const value = field.attributes?.value || '';

            let fieldHTML = '';

            // Common input attributes
            const inputAttrs = [
                `id="${fieldId}"`,
                `name="${fieldName}"`,
                placeholder && `placeholder="${placeholder}"`,
                required && 'required',
                `class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"`
            ].filter(Boolean).join(' ');

            switch(fieldType) {
                case 'textarea':
                    const rows = field.attributes?.rows || 3;
                    fieldHTML = `
                        <textarea ${inputAttrs} rows="${rows}"></textarea>
                    `;
                    break;

                case 'select':
                    const options = field.attributes?.options || ['Option 1', 'Option 2', 'Option 3'];
                    const optionsHTML = options.map(opt => 
                        `<option value="${opt.toLowerCase().replace(/\s+/g, '-')}">${opt}</option>`
                    ).join('');
                    fieldHTML = `
                        <select ${inputAttrs}>
                            ${optionsHTML}
                        </select>
                    `;
                    break;

                case 'checkbox':
                case 'radio':
                    const optionsList = field.attributes?.options || ['Option 1', 'Option 2'];
                    const optionsListHTML = optionsList.map((opt, i) => `
                        <div class="flex items-center mt-2">
                            <input type="${fieldType}" 
                                   id="${fieldId}-${i}" 
                                   name="${fieldName}" 
                                   value="${opt.toLowerCase().replace(/\s+/g, '-')}" 
                                   class="h-4 w-4 text-${this.getColor(fieldType)}-600 focus:ring-${this.getColor(fieldType)}-500 border-gray-300 rounded">
                            <label for="${fieldId}-${i}" class="ml-2 block text-sm text-gray-700">
                                ${opt}
                            </label>
                        </div>
                    `).join('');
                    fieldHTML = `
                        <div class="mt-2 space-y-2">
                            ${optionsListHTML}
                        </div>
                    `;
                    break;

                case 'file':
                    const accept = field.attributes?.accept || '';
                    const multiple = field.attributes?.multiple ? 'multiple' : '';
                    fieldHTML = `
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload a file</span>
                                        <input type="file" class="sr-only" ${multiple} ${accept ? `accept="${accept}"` : ''}>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                    `;
                    break;

                case 'hidden':
                    fieldHTML = `
                        <input type="hidden" name="${fieldName}" value="${value}">
                        <p class="text-xs text-gray-500 mt-1">Hidden field: ${fieldName}</p>
                    `;
                    break;

                default:
                    // Handle all other input types (text, email, number, etc.)
                    const inputType = ['text', 'email', 'number', 'tel', 'date', 'time', 'url', 'password', 'color', 'range', 'search', 'month', 'week', 'datetime-local'].includes(fieldType) 
                        ? fieldType 
                        : 'text';
                    
                    const min = field.attributes?.min !== undefined ? `min="${field.attributes.min}"` : '';
                    const max = field.attributes?.max !== undefined ? `max="${field.attributes.max}"` : '';
                    const step = field.attributes?.step !== undefined ? `step="${field.attributes.step}"` : '';
                    
                    fieldHTML = `
                        <input type="${inputType}" ${inputAttrs} ${min} ${max} ${step}>
                    `;
            }

            // Wrap in a label if not a hidden field
            if (fieldType !== 'hidden') {
                fieldHTML = `
                    <label for="${fieldId}" class="block text-sm font-medium text-gray-700">
                        ${label} ${required ? '<span class="text-red-500">*</span>' : ''}
                    </label>
                    ${fieldHTML}
                    ${field.attributes?.description ? `<p class="mt-1 text-sm text-gray-500">${field.attributes.description}</p>` : ''}
                `;
            }

            return fieldHTML;
        },

        // Get field preview HTML (for sidebar)
        getFieldPreview(type) {
            const icon = this.getIcon(type);
            const label = this.getLabel(type);
            const color = this.getColor(type);
            
            return `
                <div class="form-field bg-white p-3 rounded-lg border border-gray-200 cursor-move" 
                     draggable="true" 
                     @dragstart="dragStart($event, '${type}')">
                    <div class="flex items-center">
                        <i class="${icon} text-${color}-500 mr-2"></i>
                        <span>${label}</span>
                    </div>
                </div>
            `;
        }
    }));
});

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Processing Form - AlpineJS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Main Form Component -->
        <div x-data="foodProcessingForm()" class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Food Processing Tracking Form</h1>
            </div>
            <div class="p-6">
                <form @submit.prevent="" class="space-y-6">
                    <!-- Stage Selection -->
                    <div class="space-y-2">
                        <label for="stage" class="block text-sm font-medium text-gray-700">
                            Stage <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="stage"
                            x-model="formData.stage"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Select processing stage</option>
                            <template x-for="(label, value) in config.stage.values" :key="value">
                                <option :value="value" x-text="label"></option>
                            </template>
                        </select>
                    </div>
	                <div x-data="get_related_field_group( config.stage.conditional_field_group, formData.stage)"
	                     x-init="$watch('formData.stage', value => console.log(value))"
	                >
		                <template x-for="i in 10">
			                <li x-text="i + formData.stage"></li>
		                </template>
		                </ul>
		                <div x-for="(group, index) in get_related_field_group( config.stage.conditional_field_group, formData.stage)" :key="index">
			                <div x-html="JSON.stringify(get_related_field_group( config.stage.conditional_field_group, formData.stage), null, 2)"></div>
		                </div>
	                </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6">
                        <button
                            type="submit"
                            :disabled="!formData.stage"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Submit Form
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Debug Panel -->
        <div x-show="Object.keys(formData).length > 1" x-transition class="bg-white rounded-lg shadow-lg mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Form Data (Debug)</h2>
            </div>
            <div class="p-6">
                <div x-text="formData" class="bg-gray-100 p-4 rounded text-sm overflow-auto"></div>
            </div>
        </div>
    </div>

    <script>
        function foodProcessingForm() {
            return {
                // Form configuration (converted from PHP array)
                config: {
                    "stage": {
                        "label": "Stage",
                        "type": "select",
                        "values": {
                            "harvest_processing": "Harvest Processing",
                            "cutting_peeling": "Cutting\/Peeling",
                            "packaging_prep": "Packaging Preparation",
                            "washing_n_treatment": "Washing & Treatment",
                            "drying_n_pre_cooling": "Drying & Pre Cooling",
                            "waste_handling": "Waste Handling"
                        },
                        "conditional_field_group": [
                            {
                                "on": "stage:harvest_processing",
                                "fields": {
                                    "delatex_steps": {
                                        "label": "De-Latex Steps",
                                        "type": "textarea",
                                        "placeholder": "E.g., Stand fruit on plywood, trim prickles, coat cuts with oil",
                                        "required": true
                                    },
                                    "delatex_operator": {
                                        "label": "Operator Name",
                                        "type": "text",
                                        "required": true
                                    },
                                    "delatex_timestamp": {
                                        "label": "Date & Time",
                                        "type": "datetime-local",
                                        "required": true
                                    },
                                    "delatex_notes": {
                                        "label": "Additional Notes",
                                        "type": "textarea",
                                        "required": false
                                    }
                                }
                            },
                            {
                                "on": "stage:cutting_peeling",
                                "fields": {
                                    "processing_type": {
                                        "label": "Processing Type",
                                        "type": "checkbox",
                                        "values": {
                                            "peeling": "Peeling",
                                            "segmenting": "Segmenting",
                                            "chipping": "Chipping",
                                            "pulping": "Pulping"
                                        }
                                    },
                                    "sanitization": {
                                        "label": "Sanitization",
                                        "type": "group",
                                        "fields": {
                                            "sanitizer_type": {
                                                "label": "Sanitizer Type",
                                                "type": "select",
                                                "values": {
                                                    "citric_acid": "Citric Acid",
                                                    "chlorine": "Chlorine",
                                                    "other": "Other"
                                                }
                                            },
                                            "sanitizer_concentration": {
                                                "label": "Concentration (ppm)",
                                                "type": "number",
                                                "min": 0
                                            },
                                            "immersion_time": {
                                                "label": "Immersion Time (minutes)",
                                                "type": "number",
                                                "min": 0
                                            }
                                        }
                                    },
                                    "preservative_used": {
                                        "label": "Preservative Used",
                                        "type": "checkbox",
                                        "values": {
                                            "citric_acid": "Citric Acid",
                                            "ascorbic_acid": "Ascorbic Acid",
                                            "calcium_chloride": "Calcium Chloride",
                                            "none": "None"
                                        }
                                    },
                                    "operator_signature": {
                                        "label": "Operator Signature",
                                        "type": "text",
                                        "required": true
                                    }
                                }
                            },
                            {
                                "on": "stage:packaging_prep",
                                "fields": {
                                    "package_type": {
                                        "label": "Package Type",
                                        "type": "select",
                                        "values": {
                                            "ventilated_crate": "Ventilated Crate",
                                            "vacuum_bag": "Vacuum Bag",
                                            "plastic_container": "Plastic Container",
                                            "other": "Other"
                                        },
                                        "required": true
                                    },
                                    "batch_id": {
                                        "label": "Batch ID",
                                        "type": "text",
                                        "required": true
                                    },
                                    "package_details": {
                                        "label": "Package Details",
                                        "type": "group",
                                        "fields": {
                                            "package_id": {
                                                "label": "Package ID\/QR Code",
                                                "type": "text",
                                                "required": true
                                            },
                                            "net_weight": {
                                                "label": "Net Weight (kg)",
                                                "type": "number",
                                                "step": "0.01",
                                                "min": 0,
                                                "required": true
                                            },
                                            "packer_id": {
                                                "label": "Packer ID",
                                                "type": "text",
                                                "required": true
                                            }
                                        }
                                    },
                                    "storage_conditions": {
                                        "label": "Storage Conditions",
                                        "type": "group",
                                        "fields": {
                                            "temperature": {
                                                "label": "Temperature (°C)",
                                                "type": "number",
                                                "required": true
                                            },
                                            "humidity": {
                                                "label": "Humidity (%)",
                                                "type": "number",
                                                "min": 0,
                                                "max": 100
                                            }
                                        }
                                    }
                                }
                            },
                            {
                                "on": "stage:washing_n_treatment",
                                "fields": {
                                    "washing_water_usage": {
                                        "label": "Washing Water Usage",
                                        "type": "number",
                                        "min": 0,
                                        "max": 100
                                    },
                                    "disinfection_steps": {
                                        "label": "Disinfection Steps",
                                        "type": "checkbox",
                                        "values": {
                                            "chlorine_solution_strength": "Chlorine Solution Strength",
                                            "temperature": "Temperature"
                                        },
                                        "conditional_field_group": [
                                            {
                                                "on": "disinfection_steps:temperature",
                                                "fields": {
                                                    "temperature": {
                                                        "label": "Temperature (°C)",
                                                        "type": "number",
                                                        "min": 0,
                                                        "max": 100
                                                    }
                                                }
                                            },
                                            {
                                                "on": "disinfection_steps:chlorine_solution_strength",
                                                "fields": {
                                                    "chlorine_solution_strength": {
                                                        "label": "Chlorine Solution Strength",
                                                        "type": "number",
                                                        "min": 0,
                                                        "max": 100
                                                    }
                                                }
                                            }
                                        ]
                                    },
                                    "us_export": {
                                        "label": "US Export",
                                        "type": "checkbox",
                                        "values": {
                                            "us_export": "US Export"
                                        },
                                        "conditional_field_group": [
                                            {
                                                "on": "us_export:us_export",
                                                "fields": {
                                                    "hot_water_temperature": {
                                                        "label": "Hot Water Temperature (°C)",
                                                        "type": "number"
                                                    },
                                                    "hot_water_duration": {
                                                        "label": "Hot Water Duration (minutes)",
                                                        "type": "number"
                                                    }
                                                }
                                            }
                                        ]
                                    }
                                }
                            },
                            {
                                "on": "stage:drying_n_pre_cooling",
                                "fields": {
                                    "cold_storage": {
                                        "label": "Cold Storage",
                                        "type": "checkbox",
                                        "values": {
                                            "cold_storage": "Cold Storage"
                                        },
                                        "conditional_field_group": [
                                            {
                                                "on": "cold_storage:cold_storage",
                                                "fields": {
                                                    "temperature": {
                                                        "label": "Temperature (°C)",
                                                        "type": "number"
                                                    },
                                                    "humidity": {
                                                        "label": "Humidity (%)",
                                                        "type": "number"
                                                    }
                                                }
                                            }
                                        ]
                                    }
                                }
                            },
                            {
                                "on": "stage:waste_handling",
                                "fields": {
                                    "washwater_amount": {
                                        "label": "Washwater Amount (L)",
                                        "type": "number"
                                    },
                                    "rejection_weight": {
                                        "label": "Rejection Weight (kg)",
                                        "type": "number"
                                    }
                                }
                            }
                        ]
                    }
                },

                // Form data
                formData: {},

	            get_related_field_group: function( field_group_array,selectedValue) {

					//check if conditional_field_group is present in config.stage
		            if (field_group_array.length > 0) {
			            // Find the field group based on the selected value
			            var field_group = field_group_array.find(function (group) {
				            return group.on === 'stage:' + selectedValue;
			            });

			            if (field_group) {
				            // Update the formData to include the selected field group
				            this.formData = {
					            ...this.formData,
					            ...field_group.fields,
				            };
			            }
		            }
		            console.log(this.formData);
	            },

                // Initialize checkboxes as arrays
                init() {
                },
            }
        }
    </script>
</body>
</html>

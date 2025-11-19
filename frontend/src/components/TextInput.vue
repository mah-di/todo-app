<script setup>
defineProps({
    modelValue: String,
    inputName: String,
    inputType: {
        type: String,
        default: "text",
    },
    label: {
        type: String,
        default: null,
    },
    placeholder: String,
    required: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["update:modelValue"]);
</script>

<template>
    <div class="mb-4">
        <label
            v-if="label"
            :for="inputName"
            class="block text-gray-700 font-bold mb-2"
        >
            {{ label }}
        </label>
        <input
            :value="modelValue"
            :type="inputType"
            :id="inputName"
            :name="inputName"
            class="border border-violet-200 focus:outline focus:outline-violet-300 rounded w-full py-2 px-3"
            :placeholder="placeholder"
            :required="required"
            @change="$emit('update:modelValue', $event.target.value)"
        />
    </div>
</template>

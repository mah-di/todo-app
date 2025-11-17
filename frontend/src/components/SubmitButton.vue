<script setup>
import { defineProps } from "vue";

defineProps({
    label: {
        type: String,
        default: "Submit",
    },
});
</script>

<template>
    <button class="w-full bg-violet-500 hover:bg-violet-600 text-white font-bold py-2 px-4 rounded cursor-pointer"
        type="submit">{{ label }}</button>
</template>

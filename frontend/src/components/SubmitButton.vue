<script setup>
defineProps({
    label: {
        type: String,
        default: "Submit",
    },
    color: {
        type: String,
        default: "violet",
    }
});

const colorVariants = {
    violet: "bg-violet-500 hover:bg-violet-600",
    green: "bg-green-600 hover:bg-green-700",
    red: "bg-red-500 hover:bg-red-600",
    blue: "bg-blue-500 hover:bg-blue-600",
}

</script>

<template>
    <button :class="`w-full ${colorVariants[color]} text-white font-bold py-2 px-4 rounded cursor-pointer`"
        type="submit">{{ label }}</button>
</template>

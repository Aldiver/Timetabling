<script setup>
import { computed } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean, Array, Object],
        default: "",
    },
});

const inputElClass = computed(() => {
    const base = [
        "px-3 py-2  focus:ring focus:outline-none rounded w-full",
        "dark:placeholder-gray-400",
        "h-12",
        props.borderless ? "border-0" : "border",
        props.transparent ? "bg-transparent" : "bg-white dark:bg-slate-800",
        props.error ? "border-red-400" : "border-gray-700",
    ];

    if (props.icon) {
        base.push("pl-10");
    }

    return base;
});

const emit = defineEmits(["update:modelValue"]);

const computedValue = computed({
    get: () => props.modelValue,
    set: (value) => {
        emit("update:modelValue", value);
    },
});
</script>

<template>
    <div class="flex">
        <input
            :class="inputElClass"
            type="time"
            id="start-time"
            v-model="computedValue"
        />
    </div>
</template>

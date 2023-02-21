<script setup>
import { ref, watch, onMounted } from "vue";

const props = defineProps({
    type: {
        type: String,
        default: "td",
    },
    check: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["checked"]);

const checked = ref(false);

onMounted(() => {
    if (props.check) {
        checked.value = props.check;
        emit("checked", checked.value);
    }
});

watch(checked, (newVal) => {
    emit("checked", newVal);
});
</script>

<template>
    <component :is="type" class="lg:w-1">
        <label class="checkbox">
            <input v-model="checked" type="checkbox" />
            <span class="check" />
        </label>
    </component>
</template>

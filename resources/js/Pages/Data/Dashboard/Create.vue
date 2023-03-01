<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey } from "@mdi/js";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import BaseDivider from "@/components/BaseDivider.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import { computed } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean],
        default: null,
    },
    schoolProgram: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["update:modelValue", "update"]);

const value = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const confirmCancel = (mode) => {
    form.post(route("dashboard.store"), {
        onSuccess: () => {
            value.value = false;
            emit(mode);
            form.reset();
        },
    });
};

const save = () => confirmCancel("update");

const form = useForm({
    name: "",
    schoolProgram: "",
});

const schoolprogramsDropdown = Object.keys(props.schoolProgram).map((key) => ({
    id: key,
    label: props.schoolProgram[key],
}));
</script>

<template>
    <SectionTitleLineWithButton
        :icon="mdiAccountKey"
        title="Create New Timetable"
        main
    >
        <slot />
    </SectionTitleLineWithButton>
    <CardBox>
        <FormField
            label="Timetable name"
            :class="{ 'text-red-400': form.errors.name }"
        >
            <FormControl
                v-model="form.name"
                type="text"
                placeholder="Enter name for timetable"
                :error="form.errors.name"
            >
                <div class="text-red-400 text-sm" v-if="form.errors.name">
                    {{ form.errors.name }}
                </div>
            </FormControl>
        </FormField>
        <FormField
            label="School Program"
            :class="{ 'text-red-400': form.errors.schoolProgram }"
        >
            <FormControl
                v-model="form.schoolProgram"
                placeholder="School Year"
                :error="form.errors.schoolProgram"
                :options="schoolprogramsDropdown"
            />
            <div class="text-red-400 text-sm" v-if="form.errors.schoolProgram">
                {{ form.errors.schoolProgram }}
            </div>
        </FormField>

        <BaseDivider />

        <template #footer>
            <BaseButtons>
                <BaseButton
                    type="submit"
                    color="info"
                    label="Submit"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="save"
                />
            </BaseButtons>
        </template>
    </CardBox>
</template>

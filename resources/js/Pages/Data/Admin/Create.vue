<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import FormCheckRadioGroup from "@/components/FormCheckRadioGroup.vue";
import BaseDivider from "@/components/BaseDivider.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import { computed } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean],
        default: null,
    },
});

const emit = defineEmits(["update:modelValue", "update"]);

const value = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const confirmCancel = (mode) => {
    value.value = false;
    emit(mode);
};

const save = () => confirmCancel("update");

const form = useForm({
    name: "",
});
</script>

<template>
    <SectionTitleLineWithButton :icon="mdiAccountKey" title="Add Admin" main>
        <slot />
    </SectionTitleLineWithButton>
    <CardBox is-form @submit.prevent="form.post(route('admin.store'))">
        <FormField label="Admin" :class="{ 'text-red-400': form.errors.name }">
            <FormControl
                v-model="form.name"
                type="text"
                placeholder="Enter admin name"
                :error="form.errors.name"
            >
                <div class="text-red-400 text-sm" v-if="form.errors.name">
                    {{ form.errors.name }}
                </div>
            </FormControl>
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

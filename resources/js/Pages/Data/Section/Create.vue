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
    gradelevels: {
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
    value.value = false;
    emit(mode);
};

const save = () => confirmCancel("update");

const form = useForm({
    name: "",
    bldg_letter: "",
    room_number: "",
    gradelevels: "",
});

const gradelevelsDropdown = Object.keys(props.gradelevels).map((key) => ({
    id: key,
    label: props.gradelevels[key],
}));
</script>

<template>
    <SectionTitleLineWithButton :icon="mdiAccountKey" title="Add section" main>
        <slot />
    </SectionTitleLineWithButton>
    <CardBox is-form @submit.prevent="form.post(route('section.store'))">
        <FormField
            label="Section name"
            :class="{ 'text-red-400': form.errors.name }"
        >
            <FormControl
                v-model="form.name"
                type="text"
                placeholder="Enter section name"
                :error="form.errors.name"
            >
                <div class="text-red-400 text-sm" v-if="form.errors.name">
                    {{ form.errors.name }}
                </div>
            </FormControl>
        </FormField>
        <FormField
            label="Building Letter"
            :class="{ 'text-red-400': form.errors.bldg_letter }"
        >
            <FormControl
                v-model="form.bldg_letter"
                type="text"
                placeholder="Enter building letter"
                :error="form.errors.bldg_letter"
            >
                <div
                    class="text-red-400 text-sm"
                    v-if="form.errors.bldg_letter"
                >
                    {{ form.errors.bldg_letter }}
                </div>
            </FormControl>
        </FormField>
        <FormField
            label="Room Number"
            :class="{ 'text-red-400': form.errors.room_number }"
        >
            <FormControl
                v-model="form.room_number"
                type="text"
                placeholder="Enter room number"
                :error="form.errors.room_number"
            >
                <div
                    class="text-red-400 text-sm"
                    v-if="form.errors.room_number"
                >
                    {{ form.errors.room_number }}
                </div>
            </FormControl>
        </FormField>

        <FormField
            label="Grade level"
            :class="{ 'text-red-400': form.errors.gradelevels }"
        >
            <FormControl
                v-model="form.gradelevels"
                placeholder="Grade levels"
                :error="form.errors.gradelevels"
                class="xl:w-1/2"
                :options="gradelevelsDropdown"
            />
            <div class="text-red-400 text-sm" v-if="form.errors.gradelevels">
                {{ form.errors.gradelevels }}
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

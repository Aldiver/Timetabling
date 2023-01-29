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

const props = defineProps({
    departments: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    name: "",
    hours_per_week: "",
    departments: "",
});

const deptDropdown = Object.keys(props.departments).map((key) => ({
    id: key,
    label: props.departments[key],
}));
</script>

<template>
    <Head title="Add Subject" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Add subject"
            main
        >
            <BaseButton
                :routeName="route('subject.index')"
                :icon="mdiArrowLeftBoldOutline"
                label="Back"
                color="white"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>
        <CardBox is-form @submit.prevent="form.post(route('subject.store'))">
            <FormField
                label="Subject name"
                :class="{ 'text-red-400': form.errors.name }"
            >
                <FormControl
                    v-model="form.name"
                    type="text"
                    placeholder="Enter subject name"
                    :error="form.errors.name"
                >
                    <div class="text-red-400 text-sm" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                </FormControl>
            </FormField>
            <FormField
                label="Hours per week"
                :class="{ 'text-red-400': form.errors.hours_per_week }"
            >
                <FormControl
                    v-model="form.hours_per_week"
                    type="text"
                    placeholder="Enter hours per week"
                    :error="form.errors.hours_per_week"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.hours_per_week"
                    >
                        {{ form.errors.hours_per_week }}
                    </div>
                </FormControl>
            </FormField>

            <BaseDivider />

            <FormField
                label="Department"
                :class="{ 'text-red-400': form.errors.departments }"
            >
                <FormControl
                    v-model="form.departments"
                    placeholder="Departments"
                    :error="form.errors.departments"
                    class="xl:w-1/2"
                    :options="deptDropdown"
                />
                <div
                    class="text-red-400 text-sm"
                    v-if="form.errors.departments"
                >
                    {{ form.errors.departments }}
                </div>
            </FormField>

            <template #footer>
                <BaseButtons>
                    <BaseButton
                        type="submit"
                        color="info"
                        label="Submit"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    />
                </BaseButtons>
            </template>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

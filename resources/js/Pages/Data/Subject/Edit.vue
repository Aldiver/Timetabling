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
    subject: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    _method: "put",
    name: props.subject.name,
    hours_per_week: props.subject.hours_per_week,
});
</script>

<template>
    <Head title="Update Subject" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Update Subject"
            main
        >
            <BaseButton
                :route-name="route('subject.index')"
                :icon="mdiArrowLeftBoldOutline"
                label="Back"
                color="white"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>
        <CardBox
            is-form
            @submit.prevent="
                form.post(route('subject.update', props.subject.id))
            "
        >
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

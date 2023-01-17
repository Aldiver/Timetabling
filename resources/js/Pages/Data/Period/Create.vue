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

const form = useForm({
    name: "",
    number_of_timeslots: "",
});
</script>

<template>
    <Head title="Add period" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Add period"
            main
        >
            <BaseButton
                :routeName="route('period.index')"
                :icon="mdiArrowLeftBoldOutline"
                label="Back"
                color="white"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>
        <CardBox is-form @submit.prevent="form.post(route('period.store'))">
            <FormField
                label="Period"
                :class="{ 'text-red-400': form.errors.name }"
            >
                <FormControl
                    v-model="form.name"
                    type="text"
                    placeholder="Enter period"
                    :error="form.errors.name"
                >
                    <div class="text-red-400 text-sm" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="Number of hours"
                :class="{ 'text-red-400': form.errors.number_of_timeslots }"
            >
                <FormControl
                    v-model="form.number_of_timeslots"
                    type="text"
                    placeholder="Enter number of hours"
                    :error="form.errors.number_of_timeslots"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.number_of_timeslots"
                    >
                        {{ form.errors.number_of_timeslots }}
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

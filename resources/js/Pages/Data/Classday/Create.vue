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
    short_name: "",
    rank: "",
});
</script>

<template>
    <Head title="Add Class day" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Add class day"
            main
        >
            <BaseButton
                :routeName="route('classday.index')"
                :icon="mdiArrowLeftBoldOutline"
                label="Back"
                color="white"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>
        <CardBox is-form @submit.prevent="form.post(route('classday.store'))">
            <FormField
                label="Class day"
                :class="{ 'text-red-400': form.errors.name }"
            >
                <FormControl
                    v-model="form.name"
                    type="text"
                    placeholder="Enter class day"
                    :error="form.errors.name"
                >
                    <div class="text-red-400 text-sm" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="Short name"
                :class="{ 'text-red-400': form.errors.short_name }"
            >
                <FormControl
                    v-model="form.short_name"
                    type="text"
                    placeholder="Enter short name"
                    :error="form.errors.short_name"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.short_name"
                    >
                        {{ form.errors.short_name }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="Rank"
                :class="{ 'text-red-400': form.errors.rank }"
            >
                <FormControl
                    v-model="form.rank"
                    type="text"
                    placeholder="Enter rank"
                    :error="form.errors.rank"
                >
                    <div class="text-red-400 text-sm" v-if="form.errors.rank">
                        {{ form.errors.rank }}
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

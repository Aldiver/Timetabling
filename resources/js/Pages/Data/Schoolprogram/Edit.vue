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
    gradelevel: {
        type: Object,
        default: () => ({}),
    },
    // roles: {
    //     type: Object,
    //     default: () => ({}),
    // },
    // userHasRoles: {
    //     type: Object,
    //     default: () => ({}),
    // },
});

const form = useForm({
    _method: "put",
    level: props.gradelevel.level,
});
</script>

<template>
    <Head title="Update Grade Level" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Update Grade Level"
            main
        >
            <BaseButton
                :route-name="route('gradelevel.index')"
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
                form.post(route('gradelevel.update', props.gradelevel.id))
            "
        >
            <FormField
                label="Grade Level"
                :class="{ 'text-red-400': form.errors.level }"
            >
                <FormControl
                    v-model="form.level"
                    type="text"
                    placeholder="Enter grade level number"
                    :error="form.errors.level"
                >
                    <div class="text-red-400 text-sm" v-if="form.errors.level">
                        {{ form.errors.level }}
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

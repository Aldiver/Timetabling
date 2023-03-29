<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import {
    mdiAccountKey,
    mdiPlus,
    mdiSquareEditOutline,
    mdiTrashCan,
    mdiAlertBoxOutline,
} from "@mdi/js";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import Pagination from "@/components/Admin/Pagination.vue";
import Sort from "@/components/Admin/Sort.vue";

const props = defineProps({
    schoolprogram: {
        type: Object,
        default: () => ({}),
    },
    gradelevels: {
        type: Object,
        default: () => ({}),
    },
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

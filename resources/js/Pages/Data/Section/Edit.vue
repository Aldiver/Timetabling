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
    section: {
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
    name: props.section.name,
    bldg_letter: props.section.bldg_letter,
    room_number: props.section.room_number,
});
</script>

<template>
    <Head title="Update Section" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Update Section"
            main
        >
            <BaseButton
                :route-name="route('section.index')"
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
                form.post(route('section.update', props.section.id))
            "
        >
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

<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButton from "@/components/BaseButton.vue";

const props = defineProps({
    section: {
        type: Object,
        default: () => ({}),
    },
    // teaches: {
    //     type: Object,
    //     default: () => ({}),
    // },
    // userHasRoles: {
    //     type: Object,
    //     default: () => ({}),
    // },
});
</script>

<template>
    <Head title="View Section" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="View Section"
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
        <CardBox class="mb-6">
            <table>
                <tbody>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            Section
                        </td>
                        <td data-label="Section">
                            {{ section.name }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            Building letter
                        </td>
                        <td data-label="Building letter">
                            {{ section.building_letter }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            Room Number
                        </td>
                        <td data-label="Room number">
                            {{ section.room_number }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            Created
                        </td>
                        <td data-label="Created">
                            {{ new Date(section.created_at).toLocaleString() }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

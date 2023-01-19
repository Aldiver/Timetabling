<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButton from "@/components/BaseButton.vue";

const props = defineProps({
    timeslot: {
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
    <Head title="View Timeslot" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="View Timeslot"
            main
        >
            <BaseButton
                :route-name="route('timeslot.index')"
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
                            Timeslot Id
                        </td>
                        <td data-label="Class day">{{ timeslot.id }}</td>
                    </tr>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            Time
                        </td>
                        <td data-label="Time">
                            {{ timeslot.time }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            Created
                        </td>
                        <td data-label="Created">
                            {{ new Date(timeslot.created_at).toLocaleString() }}
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

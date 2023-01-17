<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButton from "@/components/BaseButton.vue";

const props = defineProps({
    classday: {
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
    <Head title="View Class Day" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="View Class day"
            main
        >
            <BaseButton
                :route-name="route('classday.index')"
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
                            Class Day
                        </td>
                        <td data-label="Full Name">
                            Grade {{ classday.name }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            Created
                        </td>
                        <td data-label="Created">
                            {{ new Date(classday.created_at).toLocaleString() }}
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

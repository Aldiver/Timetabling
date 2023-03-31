<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButton from "@/components/BaseButton.vue";

const props = defineProps({
    admin: {
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
    <Head title="View Admin" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="View Admin"
            main
        >
            <BaseButton
                :route-name="route('admin.index')"
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
                            Admin
                        </td>
                        <td data-label="Admin Name">
                            {{ admin.name }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            Created
                        </td>
                        <td data-label="Created">
                            {{ new Date(admin.created_at).toLocaleString() }}
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

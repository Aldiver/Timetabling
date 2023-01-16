<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButton from "@/components/BaseButton.vue";

const props = defineProps({
    teacher: {
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
</script>

<template>
    <Head title="View Teacher" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="View teacher"
            main
        >
            <BaseButton
                :route-name="route('teacher.index')"
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
                            Full Name
                        </td>
                        <td data-label="Full Name">
                            {{ teacher.last_name }},
                            {{ teacher.first_name }}
                            {{ teacher.middle_name }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            ID Number
                        </td>
                        <td data-label="ID Number">
                            {{ teacher.teacher_id }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            Email Address
                        </td>
                        <td data-label="Email Address">
                            {{ teacher.email }}
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="p-4 pl-8 text-slate-500 dark:text-slate-400 hidden lg:block"
                        >
                            Created
                        </td>
                        <td data-label="Created">
                            {{ new Date(teacher.created_at).toLocaleString() }}
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

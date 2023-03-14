<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButton from "@/components/BaseButton.vue";

const props = defineProps({
    timetable: {
        type: Object,
        default: () => ({}),
    },
    scheme: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <Head title="Show Timetable" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Show Timetable"
            main
        >
            <BaseButton
                :route-name="route('dashboard.index')"
                :icon="mdiArrowLeftBoldOutline"
                label="Back"
                color="white"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>
        <CardBox class="mb-6" v-for="gradelevels in scheme" :key="gradelevels">
            <table
                v-for="(sections, index) in Object.values(gradelevels)[0]"
                :key="index"
            >
                <thead>
                    <tr>
                        <th>Section - {{ index }}</th>
                    </tr>
                    <tr>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Timeslot</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(value, key, index) in sections" :key="index">
                        <td>{{ key }}</td>
                        <td>{{ Object.keys(value)[0] }}</td>
                        <td>{{ Object.values(value)[0] }}</td>
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

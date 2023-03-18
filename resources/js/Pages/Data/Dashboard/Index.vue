<script setup>
import { computed, ref } from "vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import {
    mdiAccountMultiple,
    mdiCartOutline,
    mdiChartTimelineVariant,
    mdiReload,
    mdiChartPie,
    mdiTrashCan,
    mdiEye,
} from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import CardBoxWidget from "@/components/CardBoxWidget.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButton from "@/components/BaseButton.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import SectionBannerStarOnGitHub from "@/components/SectionBannerStarOnGitHub.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseLevel from "@/components/BaseLevel.vue";
import CardBoxModal from "@/components/CardBoxModal.vue";
import Create from "./Create.vue";
import CardBoxComponentEmpty from "@/components/CardBoxComponentEmpty.vue";

const props = defineProps({
    teachers: {
        type: Number,
        default: 0,
    },
    can: {
        type: Object,
        default: () => ({}),
    },
    sections: {
        type: Number,
        default: 0,
    },
    departments: {
        type: Number,
        default: 0,
    },
    schedule: {
        type: Object,
        default: () => ({}),
    },
    schoolprogram: {
        type: Object,
        default: () => ({}),
    },
    timetables: {
        type: Object,
        default: () => ({}),
    },
});

const modalCreate = ref(false);

const formDelete = useForm({});

function destroy(id) {
    if (confirm("Are you sure you want to delete?")) {
        formDelete.delete(route("dashboard.destroy", id));
    }
}
</script>

<template>
    <Head title="Dashboard" />
    <SectionMain>
        <CardBoxModal v-model="modalCreate" class="mb-6" title="">
            <Create v-model="modalCreate" :schoolProgram="schoolprogram" />
        </CardBoxModal>
        <SectionTitleLineWithButton
            :icon="mdiChartTimelineVariant"
            title="Overview"
            main
        >
            <slot />
        </SectionTitleLineWithButton>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
            <CardBoxWidget
                trend="Teacher Data"
                color="text-emerald-500"
                :icon="mdiAccountMultiple"
                :number="teachers"
                label="Teacher"
                to="teacher.index"
            />
            <CardBoxWidget
                trend="Setion Data"
                color="text-blue-500"
                :icon="mdiCartOutline"
                :number="sections"
                label="Sections"
                to="section.index"
            />
            <CardBoxWidget
                trend="Department Data"
                color="text-red-500"
                :icon="mdiChartTimelineVariant"
                :number="departments"
                label="Department"
                to="department.index"
            />
        </div>

        <SectionBannerStarOnGitHub
            class="mt-6 mb-6"
            @openModal="modalCreate = true"
        />

        <SectionTitleLineWithButton :icon="mdiChartPie" title="Timetables">
            <BaseButton :icon="mdiReload" color="whiteDark" />
        </SectionTitleLineWithButton>
        <CardBox has-table>
            <CardBoxComponentEmpty v-if="!timetables" />
            <table v-else>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Options</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="timetable in timetables" :key="timetable.id">
                        <td>{{ timetable.name }}</td>
                        <td>{{ timetable.status }}</td>
                        <td class="before:hidden lg:w-1 whitespace-nowrap">
                            <BaseButtons
                                type="justify-start lg:justify-end"
                                no-wrap
                            >
                                <BaseButton
                                    v-if="
                                        can.edit &
                                        (timetable.status == `COMPLETED`)
                                    "
                                    color="info"
                                    :icon="mdiReload"
                                    small
                                />
                                <BaseButton
                                    v-if="can.delete"
                                    color="danger"
                                    :icon="mdiTrashCan"
                                    small
                                    @click="destroy(timetable.id)"
                                />
                            </BaseButtons>
                        </td>
                        <td class="before:hidden lg:w-1 whitespace-nowrap">
                            <BaseButtons
                                type="justify-start lg:justify-end"
                                no-wrap
                                v-if="timetable.status == `COMPLETED`"
                            >
                                <BaseButton
                                    color=""
                                    :href="
                                        route('dashboard.show', {
                                            id: timetable.id,
                                            table: 1,
                                        })
                                    "
                                    target="_blank"
                                    label="Timetable 1"
                                    small
                                />

                                <BaseButton
                                    color=""
                                    :href="
                                        route('dashboard.show', {
                                            id: timetable.id,
                                            table: 2,
                                        })
                                    "
                                    target="_blank"
                                    label="Timetable 2"
                                    small
                                />
                            </BaseButtons>
                            <span v-else> Not available</span>
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
    components: { CardBoxComponentEmpty },
};
</script>

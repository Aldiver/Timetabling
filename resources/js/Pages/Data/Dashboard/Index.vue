<script setup>
import { computed, ref, onMounted } from "vue";
import { useMainStore } from "@/stores/main";
import { Head } from "@inertiajs/inertia-vue3";
import {
    mdiAccountMultiple,
    mdiCartOutline,
    mdiChartTimelineVariant,
    mdiMonitorCellphone,
    mdiReload,
    mdiGithub,
    mdiChartPie,
} from "@mdi/js";
import * as chartConfig from "@/components/Charts/chart.config.js";
import LineChart from "@/components/Charts/LineChart.vue";
import SectionMain from "@/components/SectionMain.vue";
import CardBoxWidget from "@/components/CardBoxWidget.vue";
import CardBox from "@/components/CardBox.vue";
import TableSampleClients from "@/components/TableSampleClients.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import BaseButton from "@/components/BaseButton.vue";
import CardBoxTransaction from "@/components/CardBoxTransaction.vue";
import CardBoxClient from "@/components/CardBoxClient.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import SectionBannerStarOnGitHub from "@/components/SectionBannerStarOnGitHub.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseLevel from "@/components/BaseLevel.vue";
const props = defineProps({
    teachers: {
        type: Number,
        default: 0,
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
});

const days = ["Mon", "Tue", "Wed", "Thur", "Fri"];
function hasSubject(period, day) {
    if (period) {
        for (let i = 0; i < period.length; i++) {
            if (period[i].classday.includes(day)) {
                return true;
            }
        }
    }
    return false;
}

function getSubjectIndex(period, day) {
    for (let i = 0; i < period.length; i++) {
        if (period[i].classday.includes(day)) {
            return i;
        }
    }
}

const perPage = ref(2); //change this value to display tables per page.
const currentPage = ref(0);

const itemsPaginated = props.schedule.schedules
    ? computed(() =>
          props.schedule.schedules.slice(
              perPage.value * currentPage.value,
              perPage.value * (currentPage.value + 1)
          )
      )
    : null;

const numPages = props.schedule.schedules
    ? computed(() => Math.ceil(props.schedule.schedules.length / perPage.value))
    : null;

const currentPageHuman = computed(() => currentPage.value + 1);

const pagesList = numPages
    ? computed(() => {
          const pagesList = [];

          for (let i = 0; i < numPages.value; i++) {
              pagesList.push(i);
          }

          return pagesList;
      })
    : null;
</script>

<template>
    <Head title="Dashboard" />
    <SectionMain>
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

        <SectionBannerStarOnGitHub class="mt-6 mb-6" />

        <SectionTitleLineWithButton :icon="mdiChartPie" title="Trends overview">
            <BaseButton :icon="mdiReload" color="whiteDark" />
        </SectionTitleLineWithButton>

        <CardBox class="mb-6" has-table v-if="schedule.schedules">
            <h1>Conflicts: {{ schedule.conflicts }}</h1>

            <table v-for="items in itemsPaginated" :key="items.id" class="mb-6">
                <thead>
                    <tr>
                        <th class="text-center py-1" colspan="7">
                            Grade Level:
                            {{ itemsPaginated[0].gradelevel.level }}
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center py-1" colspan="7">
                            Section: {{ items.section.name }}
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center py-1" colspan="7">
                            {{ items.section.bldg_letter }}
                            {{ items.section.room_number }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="1">Period</th>
                        <th v-for="day in days">{{ day }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(period, index) in items.period" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td v-for="(day, dayIndex) in days" :key="dayIndex">
                            <template v-if="hasSubject(period, day)">
                                <div>
                                    {{
                                        period[getSubjectIndex(period, day)]
                                            .subject
                                    }}
                                </div>
                                <div>
                                    {{
                                        period[getSubjectIndex(period, day)]
                                            .teacher
                                    }}
                                </div>
                            </template>
                            <template v-else><b>RESERVED</b></template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </CardBox>
        <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
            <BaseLevel>
                <BaseButtons>
                    <BaseButton
                        v-for="page in pagesList"
                        :key="page"
                        :active="page === currentPage"
                        :label="page + 1"
                        :color="
                            page === currentPage ? 'lightDark' : 'whiteDark'
                        "
                        small
                        @click="currentPage = page"
                    />
                </BaseButtons>
                <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
            </BaseLevel>
        </div>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

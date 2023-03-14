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

const itemsPaginated = props.schedule
    ? computed(() =>
          props.schedule.schedules.slice(
              perPage.value * currentPage.value,
              perPage.value * (currentPage.value + 1)
          )
      )
    : null;

const numPages = props.schedule
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

if (numPages) {
    console.log(numPages.value);
}
const maxPageButtons = 10;
const startPage = numPages
    ? computed(() => {
          if (numPages.value <= maxPageButtons) {
              return 0;
          }
          if (currentPage.value < Math.floor(maxPageButtons / 2)) {
              return 0;
          }
          if (
              currentPage.value >=
              numPages.value - Math.floor(maxPageButtons / 2)
          ) {
              return numPages.value - maxPageButtons;
          }
          return currentPage.value - Math.floor(maxPageButtons / 2);
      })
    : null;

const endPage = numPages
    ? computed(() => Math.min(startPage.value + maxPageButtons, numPages.value))
    : null;

const adjustedStartPage = startPage
    ? computed(() => startPage.value - 1)
    : null;
const adjustedEndPage = endPage ? computed(() => endPage.value - 1) : null;

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
                                    v-if="can.edit"
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
                            <BaseButton
                                color="info"
                                :href="route('dashboard.show', timetable.id)"
                                target="_blank"
                                :icon="mdiEye"
                                small
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </CardBox>

        <CardBox class="mb-6" has-table v-if="schedule">
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

            <div
                class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800"
            >
                <BaseLevel>
                    <BaseButtons>
                        <!-- Render Previous button if current page set is not the first page set -->
                        <BaseButton
                            v-if="adjustedStartPage >= 0"
                            :key="`prev-${adjustedStartPage}-${adjustedEndPage}`"
                            :label="'Prev'"
                            :color="'lightDark'"
                            small
                            @click="currentPage = currentPage - 1"
                        />
                        <!-- Render page buttons -->
                        <BaseButton
                            v-for="page in adjustedEndPage - adjustedStartPage"
                            :key="`page-${adjustedStartPage + page}`"
                            :active="adjustedStartPage + page === currentPage"
                            :label="adjustedStartPage + page + 1"
                            :color="
                                adjustedStartPage + page === currentPage
                                    ? 'lightDark'
                                    : 'whiteDark'
                            "
                            small
                            @click="currentPage = adjustedStartPage + page"
                        />
                        <!-- Render Next button if current page set is not the last page set -->
                        <BaseButton
                            v-if="adjustedEndPage < numPages - 1"
                            :key="`next-${adjustedStartPage}-${adjustedEndPage}`"
                            :label="'Next'"
                            :color="'lightDark'"
                            small
                            @click="currentPage = currentPage + 1"
                        />
                    </BaseButtons>
                    <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
                </BaseLevel>
            </div>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
    components: { CardBoxComponentEmpty },
};
</script>

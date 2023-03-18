<script setup>
import { useLayoutStore } from "@/stores/layout.js";
import { useStyleStore } from "@/stores/style.js";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import moment from "moment";
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

    sectionModel: {
        type: Object,
        default: () => ({}),
    },

    periodModel: {
        type: Object,
        default: () => ({}),
    },
});

const styleStore = useStyleStore();

const layoutStore = useLayoutStore();
</script>

<template>
    <div
        :class="{
            dark: styleStore.darkMode,
        }"
    >
        <div
            class="pt-14 min-h-screen w-screen transition-position lg:w-auto bg-gray-50 dark:bg-slate-800 dark:text-slate-100"
        >
            <Head title="Show Timetable" />
            <SectionMain>
                <SectionTitleLineWithButton
                    :icon="mdiAccountKey"
                    :title="`${timetable.name} - created at ${moment(
                        timetable.created_at
                    ).format('MMM D YYYY')}`"
                    main
                >
                    <!-- <BaseButton
                        :route-name="route('dashboard.index')"
                        :icon="mdiArrowLeftBoldOutline"
                        label="Back"
                        color="white"
                        rounded-full
                        small
                    /> -->
                </SectionTitleLineWithButton>
                <CardBox
                    class="mb-6"
                    v-for="gradelevels in scheme"
                    :key="gradelevels"
                >
                    <table
                        v-for="(sections, index) in Object.values(
                            gradelevels
                        )[0]"
                        :key="index"
                    >
                        <thead>
                            <tr>
                                <th class="text-center py-1" colspan="7">
                                    {{ sectionModel[index - 1].name }}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center py-1" colspan="7">
                                    {{ sectionModel[index - 1].bldg_letter }} -
                                    {{ sectionModel[index - 1].room_number }}
                                </th>
                            </tr>
                            <tr>
                                <th>Period</th>
                                <th>Monday</th>
                                <th>Tuesday</th>
                                <th>Wednesday</th>
                                <th>Thursday</th>
                                <th>Friday</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="period in [1, 2, 3, 4, 5, 6, 7]"
                                :key="period"
                            >
                                <td class="truncate w-20">
                                    {{ periodModel[period - 1].timeslot.time }}
                                </td>

                                <td v-for="day in [1, 2, 3, 4, 5]" :key="day">
                                    <span
                                        v-if="
                                            sections[
                                                (foundIndex =
                                                    sections.findIndex(
                                                        (s) =>
                                                            s[2] ===
                                                            `D${day}T${period}`
                                                    ))
                                            ]
                                        "
                                    >
                                        {{ sections[foundIndex][1] }}
                                        <br />
                                        {{ sections[foundIndex][0] }}
                                    </span>
                                    <strong v-else class=""> RESERVED </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </CardBox>
            </SectionMain>
        </div>
    </div>
</template>

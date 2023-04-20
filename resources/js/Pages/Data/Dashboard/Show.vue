<script setup>
import { useLayoutStore } from "@/stores/layout.js";
import { useStyleStore } from "@/stores/style.js";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import {
    mdiAccountKey,
    mdiFileDocument,
    mdiFilterMenu,
    mdiFilterMinus,
} from "@mdi/js";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import moment from "moment";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseButton from "@/components/BaseButton.vue";
import html2pdf from "html2pdf.js";
import { reactive, computed, ref } from "vue";
import FormControl from "@/components/FormControl.vue";

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

const showFilter = ref(false);

function generatePdf() {
    const options = {
        filename: `${props.timetable.name}_timetable.pdf`,
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: {
            unit: "mm",
            orientation: "landscape",
            pdfWidth: document.getElementById("table-id").offsetWidth,
            pdfHeight: "auto",
            margin: [5, 10, 10, 10],
        },
    };

    const tables = Array.from(document.querySelectorAll("table"));
    // document.querySelectorAll("table");

    let worker = html2pdf().set(options).from(tables[0]);

    if (tables.length > 1) {
        worker = worker.toPdf(); // worker is now a jsPDF instance
        // add each element/page individually to the PDF render process
        tables.slice(1).forEach((element, index) => {
            worker = worker
                .get("pdf")
                .then((pdf) => {
                    pdf.addPage();
                })
                .from(element)
                .toContainer()
                .toCanvas()
                .toPdf();
        });
    }
    worker = worker.save();
}

const data = reactive({
    searchQuery: "",
    checkedGradeLevels: Object.keys(props.scheme),
});

const filteredScheme = computed(() => {
    const searchQuery = data.searchQuery;
    const filtered = {};
    for (const gradeLevel in props.scheme) {
        if (!data.checkedGradeLevels.includes(gradeLevel)) continue;
        filtered[gradeLevel] = props.scheme[gradeLevel];
        const filteredValues = Object.entries(filtered[gradeLevel]);
        const gIndex = filteredValues[0][0];
        // console.log(typeof Object.entries(filteredValues));
        const f = Object.entries(filteredValues[0][1]).filter(
            (sectionId, key) => {
                // console.log(sectionId[0]);
                if (!searchQuery) {
                    return true;
                }

                const regex = new RegExp(searchQuery, "i");
                let sId = null;
                // Check if searchTerm is found in any object's name property in props.sectionModel
                props.sectionModel.some((section) => {
                    if (regex.test(section.name)) sId = section.id;
                });

                if (sectionId[0] == sId) {
                    return true;
                }
            }
        );

        if (Object.keys(f).length > 0) {
            const updatedObj = Object.fromEntries(f);
            filtered[gradeLevel] = {
                ...filtered[gradeLevel],
                [gIndex]: updatedObj,
            };
        } else {
            delete filtered[gradeLevel];
        }
    }

    return filtered;
});
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
                    <BaseButtons>
                        <BaseButton
                            @click="generatePdf"
                            :icon="mdiFileDocument"
                            label="Print"
                            color=""
                            rounded-full
                        />
                        <BaseButton
                            @click="showFilter = !showFilter"
                            :icon="showFilter ? mdiFilterMinus : mdiFilterMenu"
                            color=""
                        />
                    </BaseButtons>
                </SectionTitleLineWithButton>
                <CardBox v-if="showFilter" class="mb-6">
                    <div class="flex items-center space-x-4 py-5">
                        <span> Filter by Grade level </span>
                        <div v-for="(value, key) in scheme" :key="key">
                            <input
                                type="checkbox"
                                :id="`checkbox_${key}`"
                                :value="`${key}`"
                                v-model="data.checkedGradeLevels"
                                checked
                                class="form-checkbox h-5 w-5 text-blue-600 mr-3"
                            />
                            <label :for="`checkbox_${key}`">{{
                                Object.keys(value)[0]
                            }}</label>
                        </div>
                    </div>
                    <FormControl
                        v-model="data.searchQuery"
                        type="text"
                        placeholder="Search section"
                    />
                    <div v-if="Object.keys(filteredScheme).length === 0">
                        No results found.
                    </div>
                </CardBox>

                <CardBox
                    class="mb-6"
                    v-for="gradelevels in filteredScheme"
                    :key="gradelevels"
                    id="section-main"
                >
                    <table
                        v-for="(sections, index) in Object.values(
                            gradelevels
                        )[0]"
                        :key="index"
                        id="table-id"
                    >
                        <thead>
                            <tr>
                                <th class="text-center py-1" colspan="7">
                                    {{ Object(sectionModel[index - 1]).name }}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center py-1" colspan="7">
                                    {{
                                        Object(sectionModel[index - 1])
                                            .bldg_letter
                                    }}
                                    -
                                    {{
                                        Object(sectionModel[index - 1])
                                            .room_number
                                    }}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center py-1" colspan="7">
                                    ADIVSER - {{ sections[0][0] }}
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

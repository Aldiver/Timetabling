<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey } from "@mdi/js";
import CardBoxComponentEmpty from "@/components/CardBoxComponentEmpty.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseDivider from "@/components/BaseDivider.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import { ref } from "vue";
const props = defineProps({
    timetables: {
        type: Object,
        default: () => ({}),
    },
    teachersData1: {
        type: Object,
        default: () => ({}),
    },
    teachersData2: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
});

const teachers = ref(props.teachersData1);
const form = useForm({
    search: props.filters,
});

const timetablesDropdown = Object.keys(props.timetables).map((key) => ({
    id: key,
    label: props.timetables[key].name,
}));

function filterWorkload() {
    form.get(route("workload.index"));
}

function parseLoad(jsonData) {
    return JSON.parse(jsonData);
}
</script>

<template>
    <Head title="Teachers' Workloads" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Teacher Workload"
            main
        >
            <slot />
        </SectionTitleLineWithButton>
        <CardBox class="mb-6">
            <FormField
                label="School Program"
                :class="{ 'text-red-400': form.errors.search }"
            >
                <FormControl
                    v-model="form.search"
                    :error="form.errors.search"
                    class="xl:w-1/2"
                    placeholder="Search"
                    :options="timetablesDropdown"
                    @change="filterWorkload"
                />
                <div class="text-red-400 text-sm" v-if="form.errors.search">
                    {{ form.errors.search }}
                </div>
            </FormField>
        </CardBox>

        <CardBox has-table>
            <CardBoxComponentEmpty v-if="!teachers.length" />
            <div v-else>
                <BaseButtons type="justify-start" class="m-6" no-wrap>
                    <BaseButton
                        label="Timetable 1"
                        color=""
                        rounded-full
                        @click="teachers = teachersData1"
                    />
                    <BaseButton
                        rounded-full
                        label="Timetable 2"
                        color=""
                        @click="teachers = teachersData2"
                    />
                </BaseButtons>
                <BaseDivider />
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Admin</th>
                            <th>Advisory</th>
                            <th>Teaching Load</th>
                            <th>Total Load</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(teacher, index) in teachers"
                            :key="teacher.id"
                        >
                            <td>{{ index + 1 }}</td>
                            <td>{{ teacher.teacher_name }}</td>
                            <td>
                                {{ parseLoad(teacher.load).Admin ?? "N/A" }}
                            </td>
                            <td>
                                {{ parseLoad(teacher.load).Advisory ?? "N/A" }}
                            </td>
                            <td>
                                {{ parseLoad(teacher.load).Sections.length }}
                            </td>
                            <td>
                                {{
                                    parseLoad(teacher.load).Sections.length +
                                    (parseLoad(teacher.load).Advisory ? 1 : 0) +
                                    (parseLoad(teacher.load).Admin ? 1 : 0)
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

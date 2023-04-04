<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiAccountKey, mdiArrowRightBox } from "@mdi/js";
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
import { useStyleStore } from "@/stores/style.js";
import { ref, computed, watch } from "vue";
import CardBoxComponentTitle from "@/components/CardBoxComponentTitle.vue";
const props = defineProps({
    adminLoads: {
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
    can: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    assignedTeachers: [],
    adminLoad: [],
    assignedTeachers2: [],
    adminLoad2: [],
});

const styleStore = useStyleStore();

const teachers = ref(props.teachersData1);
const searchTerm = ref("");

// const filteredTeachers = computed(() => {
//     if (!searchTerm.value) {
//         return teachers.value;
//     }
//     const regex = new RegExp(searchTerm.value, "i");
//     return teachers.value.filter((teacher) => regex.test(teacher.teacher_name));
// });

const filteredTeachers = computed(() => {
    let filtered = teachers.value.filter((teacher) => {
        if (form.assignedTeachers.includes(teacher)) {
            return false;
        }
        if (!searchTerm.value) {
            return true;
        }
        const regex = new RegExp(searchTerm.value, "i");
        return regex.test(teacher.teacher_name);
    });
    return filtered;
});

function parseLoad(jsonData) {
    return JSON.parse(jsonData);
}

const currentAdminLoad = computed(() => {
    if (form.assignedTeachers.length < props.adminLoads.length) {
        return props.adminLoads[form.assignedTeachers.length];
    } else {
        return null;
    }
});

function addTeacherToForm(teacher) {
    if (form.assignedTeachers.length < props.adminLoads.length) {
        form.assignedTeachers.push(teacher);
        form.adminLoad.push(props.adminLoads[form.assignedTeachers.length - 1]);
        const index = filteredTeachers.value.indexOf(teacher);
        if (index !== -1) {
            filteredTeachers.value.splice(index, 1);
        }
        // Remove the teacher from filteredTeachers if it matches the search term
        if (searchTerm.value) {
            const regex = new RegExp(searchTerm.value, "i");
            const searchIndex = filteredTeachers.value.findIndex((t) =>
                regex.test(t.teacher_name)
            );
            if (
                searchIndex !== -1 &&
                filteredTeachers.value[searchIndex].id === teacher.id
            ) {
                filteredTeachers.value.splice(searchIndex, 1);
            }
        }
    } else {
        alert("All admin loads have been assigned");
    }
}

function removeTeacherFromForm(index) {
    const teacher = form.assignedTeachers[index];
    form.assignedTeachers.splice(index, 1);
    form.adminLoad.splice(index, 1);

    // Check if the teacher is already in the filteredTeachers array
    const teacherExists = filteredTeachers.value.some(
        (t) => t.id === teacher.id
    );
    if (!teacherExists) {
        filteredTeachers.value.push(teacher);
    }
}

function nextTimetable() {
    form.adminLoad2 = form.adminLoad;
    form.assignedTeachers2 = form.assignedTeachers;
    form.adminLoad = [];
    form.assignedTeachers = [];
    teachers.value = props.teachersData2;
    formStep.value += 1;
}

let formStep = ref(0);

const submit = () => {
    form.post(route("workload.store"), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Assign Admin Loads" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Assign Admin Loads"
            main
        >
            <BaseButton
                v-if="
                    form.assignedTeachers.length == adminLoads.length &&
                    formStep == 0
                "
                type="button"
                color="info"
                label="Next"
                @click="nextTimetable"
            />
            <BaseButton
                v-else-if="
                    form.assignedTeachers.length == adminLoads.length &&
                    formStep == 1
                "
                type="button"
                color="info"
                label="Submit"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="submit"
            />
            <slot v-else />
        </SectionTitleLineWithButton>
        <div class="grid grid-cols-2 gap-6 flex flex-col h-screen">
            <div
                :class="
                    styleStore.darkMode
                        ? 'aside-scrollbars-[slate]'
                        : styleStore.asideScrollbarsStyle
                "
                class="flex-grow overflow-y-auto overflow-x-hidden"
            >
                <CardBox has-table>
                    <CardBoxComponentEmpty v-if="!teachers.length" />
                    <div v-else>
                        <div type="justify-start" class="m-6" no-wrap>
                            <CardBoxComponentTitle
                                :title="`Timetable ${formStep + 1}`"
                            />
                        </div>
                        <BaseDivider />
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="3">
                                        <FormControl
                                            v-model="searchTerm"
                                            type="text"
                                            placeholder="Search teachers"
                                        />
                                    </th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Total Load</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(teacher, index) in filteredTeachers"
                                    :key="teacher.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ teacher.teacher_name }}</td>

                                    <td>
                                        {{
                                            parseLoad(teacher.load).Sections
                                                .length +
                                            (parseLoad(teacher.load).Advisory
                                                ? 1
                                                : 0)
                                        }}
                                    </td>
                                    <td>
                                        <BaseButton
                                            small
                                            :icon="mdiArrowRightBox"
                                            color=""
                                            @click="addTeacherToForm(teacher)"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardBox>
            </div>
            <div
                :class="
                    styleStore.darkMode
                        ? 'aside-scrollbars-[slate]'
                        : styleStore.asideScrollbarsStyle
                "
                class="flex-grow overflow-y-auto overflow-x-hidden"
            >
                <CardBox class="min-h-full" has-table>
                    <div type="justify-start" class="m-6" no-wrap>
                        <CardBoxComponentTitle
                            :title="
                                currentAdminLoad
                                    ? currentAdminLoad.name
                                    : 'No More'
                            "
                        />
                    </div>
                    <BaseDivider />
                    <div
                        v-for="(addedTeacher, index) in form.assignedTeachers"
                        :key="addedTeacher.id"
                        class="p-3 bg-gray-100/50 dark:bg-slate-800 w-1/2 m-auto"
                    >
                        <span
                            class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
                            @click="removeTeacherFromForm(index)"
                        >
                            {{ addedTeacher.teacher_name }} -
                            {{ form.adminLoad[index].name }}
                        </span>
                    </div>
                </CardBox>
            </div>
        </div>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

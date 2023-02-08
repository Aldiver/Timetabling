<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/inertia-vue3";
import { mdiAccountKey, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import CardBox from "@/components/CardBox.vue";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import FormCheckRadioGroup from "@/components/FormCheckRadioGroup.vue";
import BaseDivider from "@/components/BaseDivider.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import CardBoxComponentTitle from "@/components/CardBoxComponentTitle.vue";
import CardBoxComponentEmpty from "@/components/CardBoxComponentEmpty.vue";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import { ref, computed, watch, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";

const errors = computed(() => usePage().props.value.errors);

const props = defineProps({
    gradelevels: {
        type: Object,
        default: () => ({}),
    },
    sections: {
        type: Object,
        default: () => ({}),
    },
    periods: {
        type: Object,
        default: () => ({}),
    },
    timeslots: {
        type: Object,
        default: () => ({}),
    },
    classdays: {
        type: Object,
        default: () => ({}),
    },
    teachers: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    school_year: "",
    levels: [],
    sections: [],
    periods: [],
    classdays: [],
    teachers: [],
});
const periodChecked = (period) => {
    return form.periods.some((obj) => obj.id === period.id);
};
const classdayChecked = (classday) => {
    return form.classdays.some((obj) => obj.id === classday.id);
};
const teacherChecked = (teacher) => {
    return form.teachers.some((obj) => obj.id === teacher.id);
};
const sectionChecked = (section) => {
    return form.sections.some((obj) => obj.id === section.id);
};

//set all data as default values
onMounted(() => {
    Object.keys(props.gradelevels).forEach((key) => {
        form.levels.push(Number(key));
    });
    Object(props.sections).forEach((section) => {
        form.sections.push(section);
    });
    Object(props.classdays).forEach((classday) => {
        form.classdays.push(classday);
    });
    Object(props.teachers).forEach((teacher) => {
        form.teachers.push(teacher);
    });
    Object(props.periods).forEach((period) => {
        form.periods.push(period);
    });
});

let formStep = ref(1);
let stepvalue = ref([
    "SCHOOLYEAR",
    "GRADELEVEL",
    "SECTION",
    "SECTION",
    "SECTION",
    "SECTION",
    "SCHEDULE",
    "TEACHER",
]);
const levelsChecked = ref(false);

//functions
const remove = (arr, cb) => {
    const newArr = [];

    arr.forEach((item) => {
        if (!cb(item)) {
            newArr.push(item);
        }
    });

    return newArr;
};

const checked = (isChecked, object, classification) => {
    if (isChecked) {
        if (classification == "sections") {
            if (!form.sections.some((obj) => obj.id === object.id)) {
                form.sections.push(object);
            }
        } else if (classification == "periods") {
            if (!form.periods.some((obj) => obj.id === object.id)) {
                form.periods.push(object);
            }
        } else if (classification == "classdays") {
            if (!form.classdays.some((obj) => obj.id === object.id)) {
                form.classdays.push(object);
            }
        } else if (classification == "teachers") {
            if (!form.teachers.some((obj) => obj.id === object.id)) {
                form.teachers.push(object);
            }
        }
    } else {
        if (classification == "sections") {
            form.sections = remove(
                form.sections,
                (row) => row.id === object.id
            );
        } else if (classification == "periods") {
            form.periods = remove(form.periods, (row) => row.id === object.id);
        } else if (classification == "classdays") {
            form.classdays = remove(
                form.classdays,
                (row) => row.id === object.id
            );
        } else if (classification == "teachers") {
            form.teachers = remove(
                form.teachers,
                (row) => row.id === object.id
            );
        }
    }
};

const submit = () => {
    form.post(route("schoolprogram.store"), {
        onSuccess: () => {
            form.reset(), (formStep.value = 1);
        },
    });
};

function nextStep() {
    Inertia.post(
        route("schoolprogram.check.form"),
        {
            stepValue: stepvalue.value[formStep.value - 1],
            school_year: form.school_year,
            levels: form.levels,
            sections: form.sections,
            periods: form.periods,
            classdays: form.classdays,
        },
        {
            onSuccess: () => {
                formStep.value++;
            },
        }
    );
}

function prevStep() {
    formStep.value--;
}

watch(
    () => form.levels.length, // use a getter like this
    (newLength, oldLength) => {
        if (newLength > oldLength) {
            stepvalue.value.splice(2, 0, "SECTION");
        } else if (newLength < oldLength) {
            stepvalue.value.splice(2, 1);
        }
    }
);
</script>

<template>
    <Head title="Add School Program" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Add School Program"
            main
        >
            <BaseButton
                :routeName="route('schoolprogram.index')"
                :icon="mdiArrowLeftBoldOutline"
                label="Back"
                color="white"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>
        <CardBox>
            <div v-if="formStep == 1">
                <CardBoxComponentTitle title="School Year" />
                <FormField
                    label="School Year"
                    help="Input School Year eg (2020 - 2021)"
                    :class="{ 'text-red-400': errors.school_year }"
                >
                    <FormControl
                        v-model="form.school_year"
                        type="text"
                        placeholder="Enter school year"
                        :error="errors.school_year"
                    >
                        <div
                            class="text-red-400 text-sm"
                            v-if="errors.school_year"
                        >
                            {{ errors.school_year }}
                        </div>
                    </FormControl>
                </FormField>
            </div>

            <div v-if="formStep == 2">
                <CardBoxComponentTitle title="Grade Level" />
                <FormField
                    label="Choose Grade level"
                    :class="{ 'text-red-400': errors.levels }"
                >
                    <FormCheckRadioGroup
                        v-model="form.levels"
                        name="sample-checkbox"
                        :options="gradelevels"
                    />
                </FormField>
                <div class="text-red-400 text-sm" v-if="errors.levels">
                    {{ errors.levels }}
                </div>
            </div>

            <div v-for="(gradelevel, index) in form.levels" :key="index">
                <div v-if="formStep == 3 + index">
                    <CardBoxComponentTitle title="Section" />
                    <div
                        v-if="form.sections.length"
                        class="p-3 bg-gray-100/50 dark:bg-slate-800 w-1/2 m-auto"
                    >
                        <span
                            v-for="section in form.sections"
                            :key="section.id"
                            class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
                        >
                            {{ form.sections.length }}
                        </span>
                    </div>

                    <table
                        class="w-1/2 m-auto"
                        :class="{ 'text-red-400 text-sm': errors.sections }"
                    >
                        <thead>
                            <tr>
                                <th></th>
                                <th>Section</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="section in sections" :key="section.id">
                                <TableCheckboxCell
                                    v-if="section.gradelevel_id == gradelevel"
                                    @checked="
                                        checked($event, section, 'sections')
                                    "
                                    :check="sectionChecked(section)"
                                />
                                <td v-if="section.gradelevel_id == gradelevel">
                                    {{ section.name }} {{ index }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div
                        class="w-1/2 m-auto text-red-400 text-sm"
                        v-if="errors.sections"
                    >
                        {{ errors.sections }}
                    </div>
                </div>
            </div>

            <div
                v-if="formStep == 3 + form.levels.length"
                class="grid grid-cols-2 gap-6 lg:grid-cols-2 mb-6"
            >
                <CardBoxComponentTitle title="Class Schedule" />
                <div />
                <CardBox>
                    <div
                        v-if="form.periods.length"
                        class="p-3 bg-gray-100/50 dark:bg-slate-800"
                    >
                        <span
                            v-for="period in form.periods"
                            :key="period.id"
                            class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
                        >
                            {{ timeslots[period.timeslot_id] }}
                        </span>
                    </div>
                    <table class="m-auto">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Time slots</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="period in periods" :key="period.id">
                                <TableCheckboxCell
                                    @checked="
                                        checked($event, period, 'periods')
                                    "
                                    :check="periodChecked(period)"
                                />
                                <td>
                                    {{ timeslots[period.timeslot_id] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </CardBox>

                <CardBox>
                    <div
                        v-if="form.classdays.length"
                        class="p-3 bg-gray-100/50 dark:bg-slate-800"
                    >
                        <span
                            v-for="classday in form.classdays"
                            :key="classday.id"
                            class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
                        >
                            {{ classday.name }}
                        </span>
                    </div>
                    <table class="m-auto">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Class days</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="classday in classdays"
                                :key="classday.id"
                            >
                                <TableCheckboxCell
                                    @checked="
                                        checked($event, classday, 'classdays')
                                    "
                                    :check="classdayChecked(classday)"
                                />
                                <td>
                                    {{ classday.name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </CardBox>
            </div>

            <div v-if="formStep == 4 + form.levels.length">
                <CardBoxComponentTitle title="Add Teachers" />
                <div
                    v-if="form.teachers.length"
                    class="p-3 bg-gray-100/50 dark:bg-slate-800"
                >
                    <span
                        v-for="teacher in form.teachers"
                        :key="teacher.id"
                        class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
                    >
                        {{ teacher.first_name }} {{ teacher.last_name }}
                    </span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Teacher Name</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="teacher in teachers" :key="teacher.id">
                            <TableCheckboxCell
                                @checked="checked($event, teacher, 'teachers')"
                                :check="teacherChecked(teacher)"
                            />
                            <td>
                                {{ teacher.first_name }} {{ teacher.last_name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <template #footer>
                <BaseDivider />
                <BaseButtons type="flex justify-end">
                    <BaseButton
                        type="button"
                        color="info"
                        label="Previous"
                        @click="prevStep"
                        v-if="formStep > 1"
                        outline
                    />
                    <BaseButton
                        type="button"
                        color="info"
                        label="Next"
                        @click="nextStep"
                        v-if="formStep < 4 + form.levels.length"
                    />
                    <BaseButton
                        v-if="formStep == 4 + form.levels.length"
                        type="button"
                        color="info"
                        label="Submit"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="submit"
                    />
                </BaseButtons>
            </template>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
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
import { ref } from "vue";

const props = defineProps({
    gradelevels: {
        type: Object,
        default: () => ({}),
    },
    sections: {
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
    gradelevels: {
        type: Object,
        default: () => ({}),
    },

    can: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    levels: [],
    sections: [],
});

const remove = (arr, cb) => {
    const newArr = [];

    arr.forEach((item) => {
        if (!cb(item)) {
            newArr.push(item);
        }
    });

    return newArr;
};

const checked = (isChecked, section) => {
    console.log("clicked");
    if (isChecked) {
        form.sections.push(section);
    } else {
        form.sections = remove(form.sections, (row) => row.id === section.id);
    }
};

const formStep = ref(1);

function nextStep() {
    formStep.value++;
}

function prevStep() {
    formStep.value--;
}
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
        <CardBox
            is-form
            @submit.prevent="form.post(route('schoolprogram.store'))"
        >
            <div v-if="formStep == 1">
                <CardBoxComponentTitle title="School Year" />
                <FormField
                    label="School Year"
                    help="Input School Year eg (2020 - 2021)"
                    :class="{ 'text-red-400': form.errors.level }"
                >
                    <FormControl
                        v-model="form.level"
                        type="text"
                        placeholder="Enter school year"
                        :error="form.errors.level"
                    >
                        <div
                            class="text-red-400 text-sm"
                            v-if="form.errors.level"
                        >
                            {{ form.errors.level }}
                        </div>
                    </FormControl>
                </FormField>
            </div>

            <div v-if="formStep == 2">
                <CardBoxComponentTitle title="Grade Level" />
                <FormField label="Choose Grade level">
                    <FormCheckRadioGroup
                        v-model="form.levels"
                        name="sample-checkbox"
                        :options="gradelevels"
                    />
                </FormField>
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
                            {{ section.name }}
                        </span>
                    </div>
                    <div>index = {{ index }}</div>

                    <table class="w-1/2 m-auto">
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
                                    @checked="checked($event, section)"
                                />
                                <td v-if="section.gradelevel_id == gradelevel">
                                    {{ section.name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                        v-if="form.sections.length"
                        class="p-3 bg-gray-100/50 dark:bg-slate-800 w-1/2 m-auto"
                    >
                        <span
                            v-for="section in form.sections"
                            :key="section.id"
                            class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
                        >
                            {{ section.name }}
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
                            <tr
                                v-for="timeslot in timeslots"
                                :key="timeslot.id"
                            >
                                <TableCheckboxCell
                                    @checked="checked($event, timeslot)"
                                />
                                <td>
                                    {{ timeslot.time }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </CardBox>

                <CardBox>
                    <div
                        v-if="form.sections.length"
                        class="p-3 bg-gray-100/50 dark:bg-slate-800 w-1/2 m-auto"
                    >
                        <span
                            v-for="section in form.sections"
                            :key="section.id"
                            class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
                        >
                            {{ section.name }}
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
                                    @checked="checked($event, classday)"
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
                    v-if="form.sections.length"
                    class="p-3 bg-gray-100/50 dark:bg-slate-800 w-1/2 m-auto"
                >
                    <span
                        v-for="teacher in form.teachers"
                        :key="teacher.id"
                        class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
                    >
                        {{ teacher.name }}
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
                                @checked="checked($event, teacher)"
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
                        type="submit"
                        color="info"
                        label="Submit"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
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

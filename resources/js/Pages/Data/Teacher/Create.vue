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

const props = defineProps({
    gradelevels: {
        type: Object,
        default: () => ({}),
    },
    departments: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    last_name: "",
    first_name: "",
    middle_name: "",
    specialization: "",
    gender: "Male",
    gradelevel: "",
    department: "",
});

const gradelevelsDropdown = Object.keys(props.gradelevels).map((key) => ({
    id: key,
    label: props.gradelevels[key],
}));

const departmentDropdown = Object.keys(props.departments).map((key) => ({
    id: key,
    label: props.departments[key],
}));
</script>

<template>
    <Head title="Add teacher" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Add teacher"
            main
        >
            <BaseButton
                :routeName="route('teacher.index')"
                :icon="mdiArrowLeftBoldOutline"
                label="Back"
                color="white"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>
        <CardBox is-form @submit.prevent="form.post(route('teacher.store'))">
            <FormField
                label="Last Name"
                :class="{ 'text-red-400': form.errors.last_name }"
            >
                <FormControl
                    v-model="form.last_name"
                    type="text"
                    placeholder="Enter Last Name"
                    :error="form.errors.last_name"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.last_name"
                    >
                        {{ form.errors.last_name }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="First Name"
                :class="{ 'text-red-400': form.errors.first_name }"
            >
                <FormControl
                    v-model="form.first_name"
                    type="text"
                    placeholder="Enter First Name"
                    :error="form.errors.first_name"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.first_name"
                    >
                        {{ form.errors.first_name }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="Middle Name"
                :class="{ 'text-red-400': form.errors.middle_name }"
            >
                <FormControl
                    v-model="form.middle_name"
                    type="text"
                    placeholder="Enter Middle Name"
                    :error="form.errors.middle_name"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.middle_name"
                    >
                        {{ form.errors.middle_name }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="Specialization"
                :class="{ 'text-red-400': form.errors.specialization }"
            >
                <FormControl
                    v-model="form.specialization"
                    type="text"
                    placeholder="Enter Specialization"
                    :error="form.errors.specialization"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.specialization"
                    >
                        {{ form.errors.specialization }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="Gender"
                :class="{ 'text-red-400': form.errors.gender }"
            >
                <FormCheckRadioGroup
                    name="gender-radio"
                    v-model="form.gender"
                    type="radio"
                    :error="form.errors.gender"
                    :options="{ Male: 'Male', Female: 'Female' }"
                />
            </FormField>

            <FormField
                label="Grade level"
                :class="{ 'text-red-400': form.errors.gradelevel }"
            >
                <FormControl
                    v-model="form.gradelevel"
                    placeholder="Grade levels"
                    :error="form.errors.gradelevel"
                    :options="gradelevelsDropdown"
                />
                <div class="text-red-400 text-sm" v-if="form.errors.gradelevel">
                    {{ form.errors.gradelevels }}
                </div>
            </FormField>

            <FormField
                label="Department"
                :class="{ 'text-red-400': form.errors.department }"
            >
                <FormControl
                    v-model="form.department"
                    placeholder="Department"
                    :error="form.errors.department"
                    :options="departmentDropdown"
                />
                <div class="text-red-400 text-sm" v-if="form.errors.department">
                    {{ form.errors.department }}
                </div>
            </FormField>

            <BaseDivider />

            <template #footer>
                <BaseButtons>
                    <BaseButton
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

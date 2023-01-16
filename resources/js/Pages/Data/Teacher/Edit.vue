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
    teacher: {
        type: Object,
        default: () => ({}),
    },
    // roles: {
    //     type: Object,
    //     default: () => ({}),
    // },
    // userHasRoles: {
    //     type: Object,
    //     default: () => ({}),
    // },
});

const form = useForm({
    _method: "put",
    teacher_id: props.teacher.teacher_id,
    last_name: props.teacher.last_name,
    first_name: props.teacher.first_name,
    middle_name: props.teacher.middle_name,
    grade_level_assigned: props.teacher.grade_level_assigned,
    special_task: props.teacher.special_task,
    image: props.teacher.image,
    email: props.teacher.email,
});
</script>

<template>
    <Head title="Update teacher" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Update teacher"
            main
        >
            <BaseButton
                :route-name="route('teacher.index')"
                :icon="mdiArrowLeftBoldOutline"
                label="Back"
                color="white"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>
        <CardBox
            is-form
            @submit.prevent="
                form.post(route('teacher.update', props.teacher.id))
            "
        >
            <FormField
                label="ID Number"
                :class="{ 'text-red-400': form.errors.teacher_id }"
            >
                <FormControl
                    v-model="form.teacher_id"
                    type="text"
                    placeholder="Enter ID Number"
                    :error="form.errors.teacher_id"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.teacher_id"
                    >
                        {{ form.errors.teacher_id }}
                    </div>
                </FormControl>
            </FormField>

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
                label="Grade Level"
                :class="{ 'text-red-400': form.errors.grade_level_assigned }"
            >
                <FormControl
                    v-model="form.grade_level_assigned"
                    type="text"
                    placeholder="Enter Grade Level"
                    :error="form.errors.grade_level_assigned"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.grade_level_assigned"
                    >
                        {{ form.errors.grade_level_assigned }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="Special Task"
                :class="{ 'text-red-400': form.errors.special_task }"
            >
                <FormControl
                    v-model="form.special_task"
                    type="text"
                    placeholder="Enter Special Task"
                    :error="form.errors.special_task"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.special_task"
                    >
                        {{ form.errors.special_task }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="Insert Image"
                :class="{ 'text-red-400': form.errors.image }"
            >
                <FormControl
                    v-model="form.image"
                    type="text"
                    placeholder="Enter Last Name"
                    :error="form.errors.image"
                >
                    <div class="text-red-400 text-sm" v-if="form.errors.image">
                        {{ form.errors.image }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="Email"
                :class="{ 'text-red-400': form.errors.email }"
            >
                <FormControl
                    v-model="form.email"
                    type="text"
                    placeholder="Enter Email"
                    :error="form.errors.email"
                >
                    <div class="text-red-400 text-sm" v-if="form.errors.email">
                        {{ form.errors.email }}
                    </div>
                </FormControl>
            </FormField>

            <BaseDivider />

            <!-- <FormField label="Roles" wrap-body>
                <FormCheckRadioGroup
                    v-model="form.roles"
                    name="roles"
                    is-column
                    :options="props.roles"
                />
            </FormField> -->

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

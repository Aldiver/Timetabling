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
    roles: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    roles: [],
});
</script>

<template>
    <Head title="Add user" />
    <SectionMain>
        <SectionTitleLineWithButton :icon="mdiAccountKey" title="Add user" main>
            <BaseButton
                :routeName="route('user.index')"
                :icon="mdiArrowLeftBoldOutline"
                label="Back"
                color="white"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>
        <CardBox is-form @submit.prevent="form.post(route('user.store'))">
            <FormField
                label="Name"
                :class="{ 'text-red-400': form.errors.name }"
            >
                <FormControl
                    v-model="form.name"
                    type="text"
                    placeholder="Enter Name"
                    :error="form.errors.name"
                >
                    <div class="text-red-400 text-sm" v-if="form.errors.name">
                        {{ form.errors.name }}
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

            <FormField
                label="Password"
                :class="{ 'text-red-400': form.errors.password }"
            >
                <FormControl
                    v-model="form.password"
                    type="password"
                    placeholder="Enter Password"
                    :error="form.errors.password"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.password"
                    >
                        {{ form.errors.password }}
                    </div>
                </FormControl>
            </FormField>

            <FormField
                label="Password Confirmation"
                :class="{ 'text-red-400': form.errors.password }"
            >
                <FormControl
                    v-model="form.password_confirmation"
                    type="password"
                    placeholder="Enter Password Confirmation"
                    :error="form.errors.password"
                >
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.password"
                    >
                        {{ form.errors.password }}
                    </div>
                </FormControl>
            </FormField>

            <BaseDivider />

            <FormField label="Roles" wrap-body>
                <FormCheckRadioGroup
                    v-model="form.roles"
                    name="roles"
                    is-column
                    :options="props.roles"
                />
            </FormField>

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

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
    period: {
        type: Number,
        default: 0,
    },
    timeslots: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    rank: props.period,
    timeslot: "",
});

const timeslotsDropdown = Object.keys(props.timeslots).map((key) => ({
    id: key,
    label: props.timeslots[key].time,
}));

const emit = defineEmits(["submit-clicked"]);

const submit = () => {
    form.post(route("period.store"), {
        onSuccess: () => {
            form.reset();
        },
    });
    emit("submit-clicked");
};
</script>

<template>
    <tr>
        <td>
            <FormControl
                v-model="form.rank"
                type="text"
                placeholder="Enter period"
                :error="form.errors.rank"
            >
                <div class="text-red-400 text-sm" v-if="form.errors.rank">
                    {{ form.errors.rank }}
                </div>
            </FormControl>
        </td>
        <!-- <CardBox is-form @submit.prevent="form.post(route('period.store'))"> -->
        <td>
            <FormControl
                v-model="form.timeslot"
                :error="form.errors.timeslot"
                :options="timeslotsDropdown"
            />
        </td>
        <td>
            <BaseButton
                @click="submit"
                label="Add"
                color="info"
                rounded-full
                outline
                class="w-full"
            />
        </td>
        <!-- <td v-for="index in classdayCount" />
    <td></td> -->
    </tr>
</template>

<script setup>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiClockTimeNineOutline, mdiTimetable } from "@mdi/js";
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
import { computed, watch } from "vue";

const props = defineProps({
    timeslot: {
        type: Array,
        default: [],
    },
    timeslot_id: {
        type: Number,
        default: null,
    },
    modelValue: {
        type: [String, Number, Boolean],
        default: null,
    },
});

const emit = defineEmits(["update:modelValue", "update"]);

const value = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const confirmCancel = (mode) => {
    form.post(route("timeslot.update", props.timeslot_id), {
        onSuccess: () => {
            form.reset();
        },
    });
    value.value = false;
    emit(mode);
};

function to24HourFormat(timeStr) {
    let [time, period] = timeStr.split(" ");
    let [hours, minutes] = time.split(":");

    hours = period === "AM" && hours === "12" ? "00" : hours;
    hours =
        period === "PM" && hours !== "12"
            ? (parseInt(hours, 10) + 12).toString()
            : hours;

    return `${hours}:${minutes}`;
}

const update = () => confirmCancel("update");

const form = useForm({
    _method: "put",
    time_from: "",
    time_to: "",
    rank: "",
});

watch(
    () => props.timeslot, // use a getter like this
    () => {
        form.time_from = to24HourFormat(props.timeslot[0]);
        form.time_to = to24HourFormat(props.timeslot[1]);
    }
);
</script>

<template>
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiTimetable"
            title="Edit Timeslot"
            main
        >
            <slot />
        </SectionTitleLineWithButton>
        <CardBox>
            <FormField
                label="Timeslots"
                help="Select a timeslot"
                :class="{ 'text-red-400': form.errors.time_to }"
            >
                <div class="xl:flex xl:items-center xl:justify-between">
                    <FormControl
                        v-model="form.time_from"
                        type="time"
                        :error="form.errors.time_from"
                        class="xl:w-1/2 xl:mb-0 mb-6 relative"
                    />
                    <div class="px-5 py-3 text-xs sm:text-base text-gray-600">
                        to
                    </div>
                    <FormControl
                        v-model="form.time_to"
                        type="time"
                        :error="form.errors.time_to"
                        class="xl:w-1/2"
                    />
                </div>
                <div class="text-red-400 text-sm" v-if="form.errors.time_to">
                    {{ form.errors.time_to }}
                </div>
                <slot />
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
                        @click="update"
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

<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { mdiTimetable, mdiClockTimeNineOutline } from "@mdi/js";
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
import { computed } from "vue";

const props = defineProps({
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
    value.value = false;
    emit(mode);
};

const save = () => confirmCancel("update");

const form = useForm({
    time_to: "",
    time_from: "",
    rank: "",
});

function setrank() {
    form.rank = timeOptions.find((x) => x.label === form.time_from)?.id || null;
}

var timeArray = [],
    current = new Date();
current.setHours(6, 0);
while (current.getHours() < 20) {
    timeArray.push(
        current.toLocaleString("en-US", {
            hour: "numeric",
            minute: "numeric",
            hour12: true,
        })
    );
    current.setMinutes(current.getMinutes() + 30);
}

let timeOptions = timeArray.map((label, index) => {
    return { id: index, label };
});
</script>

<template>
    <SectionTitleLineWithButton :icon="mdiTimetable" title="Add timeslot" main>
        <slot />
    </SectionTitleLineWithButton>
    <CardBox is-form @submit.prevent="form.post(route('timeslot.store'))">
        <FormField
            label="Timeslots"
            help="Select a timeslot"
            :class="{ 'text-red-400': form.errors.time_to }"
        >
            <div class="xl:flex xl:items-center xl:justify-between">
                <FormControl
                    v-model="form.time_from"
                    @change="setrank"
                    type="text"
                    placeholder="from"
                    :error="form.errors.time_from"
                    class="xl:w-1/2 xl:mr-5 xl:mb-0 mb-6"
                    :options="timeOptions"
                    :icon="mdiClockTimeNineOutline"
                />
                <div class="text-red-400 text-sm" v-if="form.errors.time_from">
                    {{ form.errors.time_from }}
                </div>
                <FormControl
                    v-model="form.time_to"
                    type="text"
                    placeholder="to"
                    :error="form.errors.time_to"
                    class="xl:w-1/2"
                    :options="timeOptions"
                    :icon="mdiClockTimeNineOutline"
                />
                <div class="text-red-400 text-sm" v-if="form.errors.time_to">
                    {{ form.errors.time_to }}
                </div>
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
                    @click="save"
                />
            </BaseButtons>
        </template>
    </CardBox>
</template>

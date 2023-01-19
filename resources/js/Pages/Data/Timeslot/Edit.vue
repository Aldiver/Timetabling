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
        type: Object,
        default: () => ({}),
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
    value.value = false;
    emit(mode);
};

const update = () => confirmCancel("update");
var timeString, result;

const form = useForm({
    _method: "put",
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

function setStrings(text) {
    timeString = text;
    result = timeString.split(" - ");
    // selectedFrom = timeOptions.find((x) => x.label === result[0])?.id || null;
    // selectedTo = timeOptions.find((x) => x.label === result[1])?.id || null;
}

watch(
    () => props.timeslot, // use a getter like this
    () => {
        setStrings(props.timeslot.time);
        form.time_from = result[0];
        form.time_to = result[1];
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
        <CardBox
            is-form
            @submit.prevent="
                form.post(route('timeslot.update', props.timeslot.id))
            "
        >
            <FormField
                label="Timeslots"
                help="Time range must be correct"
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
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.time_from"
                    >
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
                    <div
                        class="text-red-400 text-sm"
                        v-if="form.errors.time_to"
                    >
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
                        label="Update"
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

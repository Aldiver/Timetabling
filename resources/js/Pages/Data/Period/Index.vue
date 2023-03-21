<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import {
    mdiAccountKey,
    mdiPlus,
    mdiSquareEditOutline,
    mdiTrashCan,
    mdiAlertBoxOutline,
    mdiPlusCircleOutline,
    mdiMinusCircleOutline,
    mdiPlusMinusVariant,
} from "@mdi/js";
import FormField from "@/components/FormField.vue";
import FormControl from "@/components/FormControl.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import Pagination from "@/components/Admin/Pagination.vue";
import IconRounded from "@/components/IconRounded.vue";
import BaseIcon from "@/components/BaseIcon.vue";
import Sort from "@/components/Admin/Sort.vue";
import Create from "./Create.vue";
import { ref } from "vue";

const props = defineProps({
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
    unassignedTimeslots: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
});

const formDelete = useForm({});

const classdayCount = ref(5);
const showEditButton = ref(false);
const addRow = ref(false);

const addCol = () => {
    if (classdayCount.value >= 7) {
        return;
    }
    classdayCount.value += 1;
    showEditButton.value = false;
};

const removeCol = () => {
    classdayCount.value -= 1;
    showEditButton.value = false;
};

function showEdit() {
    showEditButton.value = true;
}

function addNewRow() {
    addRow.value = true;
}

const emit = defineEmits(["submit-clicked"]);

const submitClicked = () => {
    addRow.value = false;
};

function destroy(id) {
    if (confirm("Are you sure you want to delete?")) {
        formDelete.delete(route("period.destroy", id));
    }
}
</script>

<template>
    <Head title="Period" />
    <SectionMain>
        <SectionTitleLineWithButton :icon="mdiAccountKey" title="Period" main>
            <BaseButton
                v-if="can.delete"
                @click="addNewRow"
                :icon="mdiPlus"
                label="Add"
                color="info"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>
        <NotificationBar
            v-if="$page.props.flash.message"
            color="success"
            :icon="mdiAlertBoxOutline"
        >
            {{ $page.props.flash.message }}
        </NotificationBar>
        <CardBox class="mb-6" has-table>
            <table>
                <thead>
                    <tr>
                        <th class="before:hidden lg:w-1 whitespace-nowrap">
                            Period
                        </th>
                        <th class="text-center py-1">Timeslots</th>
                        <!-- <th v-for="index in classdayCount" :key="index">
                            {{ classdays[index] }}
                        </th>
                        <th
                            v-if="addRow"
                            class="before:hidden lg:w-1 whitespace-nowrap"
                        >
                            <IconRounded
                                v-if="!showEditButton"
                                :icon="mdiPlusMinusVariant"
                                color="light"
                                class="mr-3"
                                bg
                                @click="showEdit"
                            />
                            <BaseButtons
                                v-if="showEditButton"
                                type="justify-start lg:justify-end"
                                no-wrap
                            >
                                <BaseButton
                                    v-if="can.edit"
                                    color="danger"
                                    @click="removeCol"
                                    :icon="mdiMinusCircleOutline"
                                    small
                                />
                                <BaseButton
                                    v-if="can.delete"
                                    color="success"
                                    :icon="mdiPlusCircleOutline"
                                    small
                                    @click="addCol"
                                />
                            </BaseButtons>
                        </th> -->
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="period in periods.data" :key="period.id">
                        <td data-label="Period">
                            <span>
                                {{ period.rank }}
                            </span>
                        </td>
                        <td data-label="Timeslots" class="text-center py-1">
                            {{ timeslots[period.timeslot_id] }}
                        </td>
                        <!-- <td v-for="index in classdayCount" /> -->
                        <td class="before:hidden lg:w-1 whitespace-nowrap">
                            <!-- <BaseButton
                                    v-if="can.edit"
                                    :route-name="
                                        route('period.edit', period.id)
                                    "
                                    color="info"
                                    :icon="mdiSquareEditOutline"
                                    small
                                /> -->
                            <BaseButton
                                v-if="can.delete"
                                color="danger"
                                :icon="mdiTrashCan"
                                small
                                @click="destroy(period.id)"
                            />
                        </td>
                    </tr>
                    <Create
                        v-if="addRow"
                        :period="periods.data.length + 1"
                        :timeslots="unassignedTimeslots"
                        @submit-clicked="submitClicked"
                    />
                </tbody>
            </table>
            <div class="py-4">
                <Pagination :data="periods" />
            </div>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

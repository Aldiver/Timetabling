<script setup>
import { ref, onMounted } from "vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import {
    mdiTimetable,
    mdiPlus,
    mdiSquareEditOutline,
    mdiTrashCan,
    mdiAlertBoxOutline,
} from "@mdi/js";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionMain from "@/components/SectionMain.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/BaseButton.vue";
import CardBox from "@/components/CardBox.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import CardBoxModal from "@/components/CardBoxModal.vue";
import Pagination from "@/components/Admin/Pagination.vue";
import Edit from "./Edit.vue";
import Create from "./Create.vue";

const props = defineProps({
    timeslots: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
});

const formDelete = useForm({});

const modalEdit = ref(false);
const modalCreate = ref(false);

var data, timeslot_id;

function editClick(timeslot, id) {
    data = timeslot.split(" - ");
    timeslot_id = id;
    modalEdit.value = !modalEdit.value;
}

function destroy(id) {
    if (confirm("Are you sure you want to delete?")) {
        formDelete.delete(route("timeslot.destroy", id));
    }
}

let isMounted = ref(true);
onMounted(() => {
    setTimeout(() => {
        isMounted.value = false;
    }, 3000);
});
</script>

<template>
    <CardBoxModal v-model="modalEdit" class="mb-6" title="">
        <Edit :timeslot="data" :timeslot_id="timeslot_id" v-model="modalEdit" />
    </CardBoxModal>

    <CardBoxModal v-model="modalCreate" class="mb-6" title="">
        <Create v-model="modalCreate" />
    </CardBoxModal>

    <Head title="Timeslot" />
    <SectionMain>
        <SectionTitleLineWithButton :icon="mdiTimetable" title="Timeslot" main>
            <BaseButton
                v-if="can.delete"
                @click="modalCreate = true"
                :icon="mdiPlus"
                label="Add"
                color="info"
                rounded-full
                small
            />
            <!-- :route-name="route('timeslot.create')" -->
        </SectionTitleLineWithButton>
        <NotificationBar
            v-if="$page.props.flash.message && isMounted"
            color="success"
            :icon="mdiAlertBoxOutline"
        >
            {{ $page.props.flash.message }}
        </NotificationBar>

        <CardBox class="mb-6" has-table>
            <table>
                <thead>
                    <tr>
                        <th>Timeslot</th>
                        <th v-if="can.edit || can.delete">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="timeslot in timeslots.data" :key="timeslot.id">
                        <td data-label="Timeslot">
                            <Link
                                :href="route('timeslot.show', timeslot.id)"
                                class="no-underline hover:underline text-cyan-600 dark:text-cyan-400"
                            >
                                {{ timeslot.time }}
                            </Link>
                        </td>
                        <td
                            v-if="can.edit || can.delete"
                            class="before:hidden lg:w-1 whitespace-nowrap"
                        >
                            <BaseButtons
                                type="justify-start lg:justify-end"
                                no-wrap
                            >
                                <!-- :route-name="
                                        route('timeslot.edit', timeslot.id)
                                    " -->
                                <BaseButton
                                    v-if="can.edit"
                                    @click="
                                        editClick(timeslot.time, timeslot.id)
                                    "
                                    color="info"
                                    :icon="mdiSquareEditOutline"
                                    small
                                />
                                <BaseButton
                                    v-if="can.delete"
                                    color="danger"
                                    :icon="mdiTrashCan"
                                    small
                                    @click="destroy(timeslot.id)"
                                />
                            </BaseButtons>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="py-4">
                <Pagination :data="timeslots" />
            </div>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

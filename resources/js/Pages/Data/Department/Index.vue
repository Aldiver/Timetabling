<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import {
    mdiAccountKey,
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
import Pagination from "@/components/Admin/Pagination.vue";
import Sort from "@/components/Admin/Sort.vue";
import CardBoxModal from "@/components/CardBoxModal.vue";
import Create from "./Create.vue";
import Edit from "./Edit.vue";
import { ref } from "vue";
const props = defineProps({
    departments: {
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

const modalEdit = ref(false);
const modalCreate = ref(false);

var data;

function editClick(id) {
    data = id;
    modalEdit.value = !modalEdit.value;
}

const formDelete = useForm({});

function destroy(id) {
    if (confirm("Are you sure you want to delete?")) {
        formDelete.delete(route("department.destroy", id));
    }
}
</script>

<template>
    <CardBoxModal v-model="modalEdit" class="mb-6" title="">
        <Edit :department="data" v-model="modalEdit" />
    </CardBoxModal>

    <CardBoxModal v-model="modalCreate" class="mb-6" title="">
        <Create v-model="modalCreate" />
    </CardBoxModal>
    <Head title="Department" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Department"
            main
        >
            <BaseButton
                v-if="can.delete"
                @click="modalCreate = true"
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
                        <th>
                            <Sort label="Department" attribute="name" />
                        </th>
                        <th v-if="can.edit || can.delete">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="department in departments.data"
                        :key="department.id"
                    >
                        <td data-label="Department">
                            <Link
                                :href="route('department.show', department.id)"
                                class="no-underline hover:underline text-cyan-600 dark:text-cyan-400"
                            >
                                {{ department.name }}
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
                                <BaseButton
                                    v-if="can.edit"
                                    @click="editClick(department)"
                                    color="info"
                                    :icon="mdiSquareEditOutline"
                                    small
                                />
                                <BaseButton
                                    v-if="can.delete"
                                    color="danger"
                                    :icon="mdiTrashCan"
                                    small
                                    @click="destroy(department.id)"
                                />
                            </BaseButtons>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="py-4">
                <Pagination :data="departments" />
            </div>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

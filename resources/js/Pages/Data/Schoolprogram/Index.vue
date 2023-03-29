<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import {
    mdiAccountKey,
    mdiPlus,
    mdiSquareEditOutline,
    mdiTrashCan,
    mdiAlertBoxOutline,
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
import Sort from "@/components/Admin/Sort.vue";

const props = defineProps({
    schoolprogram: {
        type: Object,
        default: () => ({}),
    },
    gradelevels: {
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

const form = useForm({
    search: props.filters.search,
});

const formDelete = useForm({});

function destroy(id) {
    if (confirm("Are you sure you want to delete?")) {
        formDelete.delete(route("schoolprogram.destroy", id));
    }
}
</script>

<template>
    <Head title="School Program" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="School Program"
            main
        >
            <BaseButton
                v-if="can.delete"
                :route-name="route('schoolprogram.create')"
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
                        <th>ID</th>
                        <th>School Program Name</th>
                        <th>Gradelevel</th>
                        <th>Section</th>
                        <th>Teachers</th>
                        <th v-if="can.edit || can.delete">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="schoolprogramdata in schoolprogram"
                        :key="schoolprogramdata.id"
                    >
                        <td>{{ schoolprogramdata.id }}</td>
                        <td>{{ schoolprogramdata.school_year }}</td>
                        <td>{{ schoolprogramdata.gradelevels.length }}</td>
                        <td>
                            {{ schoolprogramdata.sections.length }}
                        </td>
                        <td>
                            {{ schoolprogramdata.teachers.length }}
                        </td>
                        <td
                            v-if="can.edit || can.delete"
                            class="before:hidden lg:w-1 whitespace-nowrap"
                        >
                            <BaseButton
                                v-if="can.delete"
                                color="danger"
                                :icon="mdiTrashCan"
                                small
                                @click="destroy(schoolprogramdata.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="py-4">
                <!-- <Pagination :data="gradelevels" /> -->
            </div>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

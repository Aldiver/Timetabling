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
import CardBoxModal from "@/components/CardBoxModal.vue";
import Create from "./Create.vue";
import Edit from "./Edit.vue";
import { ref } from "vue";
const props = defineProps({
    admins: {
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

const form = useForm({
    search: props.filters.search,
});

const formDelete = useForm({});

function destroy(id) {
    if (confirm("Are you sure you want to delete?")) {
        formDelete.delete(route("admin.destroy", id));
    }
}
</script>

<template>
    <CardBoxModal v-model="modalEdit" class="mb-6" title="">
        <Edit :admin="data" v-model="modalEdit" />
    </CardBoxModal>

    <CardBoxModal v-model="modalCreate" class="mb-6" title="">
        <Create v-model="modalCreate" />
    </CardBoxModal>
    <Head title="Admin" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiAccountKey"
            title="Admin Loads"
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
        <CardBox
            class="mb-6"
            has-table
            is-form
            @submit.prevent="form.get(route('admin.index'))"
        >
            <FormField>
                <div class="py-2 flex">
                    <div class="flex pl-4">
                        <FormControl
                            v-model="form.search"
                            type="text"
                            placeholder="Search"
                            :error="form.errors.name"
                        />
                        <BaseButton
                            label="Search"
                            type="submit"
                            color="info"
                            class="ml-4 inline-flex items-center px-4 py-2"
                        />
                    </div>
                </div>
            </FormField>
        </CardBox>
        <CardBox class="mb-6" has-table>
            <table>
                <thead>
                    <tr>
                        <th>
                            <Sort label="Admin" attribute="name" />
                        </th>
                        <th v-if="can.edit || can.delete">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="admin in admins.data" :key="admin.id">
                        <td data-label="Admin">
                            <Link
                                :href="route('admin.show', admin.id)"
                                class="no-underline hover:underline text-cyan-600 dark:text-cyan-400"
                            >
                                {{ admin.name }}
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
                                    @click="editClick(admin)"
                                    color="info"
                                    :icon="mdiSquareEditOutline"
                                    small
                                />
                                <BaseButton
                                    v-if="can.delete"
                                    color="danger"
                                    :icon="mdiTrashCan"
                                    small
                                    @click="destroy(admin.id)"
                                />
                            </BaseButtons>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="py-4">
                <Pagination :data="admins" />
            </div>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

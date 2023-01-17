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
    periods: {
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
                :route-name="route('period.create')"
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
            @submit.prevent="form.get(route('period.index'))"
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
                            <Sort label="Period" attribute="name" />
                        </th>
                        <th>
                            <Sort
                                label="Number of timeslots"
                                attribute="number_of_timeslots"
                            />
                        </th>
                        <th v-if="can.edit || can.delete">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="period in periods.data" :key="period.id">
                        <td data-label="Period">
                            <Link
                                :href="route('period.show', period.id)"
                                class="no-underline hover:underline text-cyan-600 dark:text-cyan-400"
                            >
                                {{ period.name }}
                            </Link>
                        </td>
                        <td data-label="Number of hours">
                            {{ period.number_of_timeslots }}
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
                                    :route-name="
                                        route('period.edit', period.id)
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
                                    @click="destroy(period.id)"
                                />
                            </BaseButtons>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="py-4">
                <!-- <Pagination :data="periods" /> -->
            </div>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

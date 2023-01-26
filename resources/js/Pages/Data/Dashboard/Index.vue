<script setup>
import { computed, ref, onMounted } from "vue";
import { useMainStore } from "@/stores/main";
import { Head } from "@inertiajs/inertia-vue3";
import {
    mdiAccountMultiple,
    mdiCartOutline,
    mdiChartTimelineVariant,
    mdiMonitorCellphone,
    mdiReload,
    mdiGithub,
    mdiChartPie,
} from "@mdi/js";
import * as chartConfig from "@/components/Charts/chart.config.js";
import LineChart from "@/components/Charts/LineChart.vue";
import SectionMain from "@/components/SectionMain.vue";
import CardBoxWidget from "@/components/CardBoxWidget.vue";
import CardBox from "@/components/CardBox.vue";
import TableSampleClients from "@/components/TableSampleClients.vue";
import NotificationBar from "@/components/NotificationBar.vue";
import BaseButton from "@/components/BaseButton.vue";
import CardBoxTransaction from "@/components/CardBoxTransaction.vue";
import CardBoxClient from "@/components/CardBoxClient.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
import SectionBannerStarOnGitHub from "@/components/SectionBannerStarOnGitHub.vue";

const props = defineProps({
    teachers: {
        type: Number,
        default: 0,
    },
    sections: {
        type: Number,
        default: 0,
    },
    departments: {
        type: Number,
        default: 0,
    },
});

const chartData = ref(null);

const fillChartData = () => {
    chartData.value = chartConfig.sampleChartData();
};

onMounted(() => {
    fillChartData();
});

const mainStore = useMainStore();

const clientBarItems = computed(() => mainStore.clients.slice(0, 4));

const transactionBarItems = computed(() => mainStore.history);
</script>

<template>
    <Head title="Dashboard" />
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiChartTimelineVariant"
            title="Overview"
            main
        >
            <slot />
        </SectionTitleLineWithButton>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
            <CardBoxWidget
                trend="Teacher Data"
                color="text-emerald-500"
                :icon="mdiAccountMultiple"
                :number="teachers"
                label="Teacher"
                to="teacher.index"
            />
            <CardBoxWidget
                trend="Setion Data"
                color="text-blue-500"
                :icon="mdiCartOutline"
                :number="sections"
                label="Sections"
                to="section.index"
            />
            <CardBoxWidget
                trend="Department Data"
                color="text-red-500"
                :icon="mdiChartTimelineVariant"
                :number="departments"
                label="Department"
                to="department.index"
            />
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="flex flex-col justify-between">
                <CardBoxTransaction
                    v-for="(transaction, index) in transactionBarItems"
                    :key="index"
                    :amount="transaction.amount"
                    :date="transaction.date"
                    :business="transaction.business"
                    :type="transaction.type"
                    :name="transaction.name"
                    :account="transaction.account"
                />
            </div>
            <div class="flex flex-col justify-between">
                <CardBoxClient
                    v-for="client in clientBarItems"
                    :key="client.id"
                    :name="client.name"
                    :login="client.login"
                    :date="client.created"
                    :progress="client.progress"
                />
            </div>
        </div>

        <SectionBannerStarOnGitHub class="mt-6 mb-6" />

        <SectionTitleLineWithButton :icon="mdiChartPie" title="Trends overview">
            <BaseButton
                :icon="mdiReload"
                color="whiteDark"
                @click="fillChartData"
            />
        </SectionTitleLineWithButton>

        <CardBox class="mb-6">
            <div>Display none if no current timetable for school year</div>
        </CardBox>
    </SectionMain>
</template>
<script>
export default {
    layout: LayoutAuthenticated,
};
</script>

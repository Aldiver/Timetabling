<script setup>
import { mdiGithub } from "@mdi/js";
import BaseButton from "@/components/BaseButton.vue";
import SectionBanner from "@/components/SectionBanner.vue";
import { gradientBgPinkRed } from "@/colors";
// :route-name="route('timetable.generate')"
const emit = defineEmits(["openModal"]);

const handleClick = () => {
    // Emit the 'openModal' event
    emit("openModal");
};
</script>

<template>
    <SectionBanner :class="gradientBgPinkRed">
        <h1 class="text-3xl text-white mb-6">
            <b>Generate New Timetable</b>
        </h1>
        <div>
            <BaseButton
                @click="handleClick"
                :icon="mdiGithub"
                label="Generate"
                target="_blank"
                rounded-full
            />
        </div>
    </SectionBanner>
</template>

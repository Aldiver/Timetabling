<script setup>
import { computed, ref } from 'vue'
import { useMainStore } from '@/stores/main'
import { mdiCheckDecagram } from '@mdi/js'
import BaseLevel from '@/components/BaseLevel.vue'
import UserAvatarCurrentUser from '@/components/UserAvatarCurrentUser.vue'
import CardBox from '@/components/CardBox.vue'
import FormCheckRadioGroup from '@/components/FormCheckRadioGroup.vue'
import PillTag from '@/components/PillTag.vue'

const props = defineProps({
  user: {
    type: Object,
    default: () => ({}),
  },
})

const userSwitchVal = ref([])
</script>

<template>
  <CardBox>
    <BaseLevel type="justify-around lg:justify-center">
      <UserAvatarCurrentUser class="lg:mx-12" />
      <div class="space-y-3 text-center md:text-left lg:mx-12">
        <div class="flex justify-center md:block">
          <FormCheckRadioGroup
            v-model="userSwitchVal"
            name="sample-switch"
            type="switch"
            :options="{ one: 'Notifications' }"
          />
        </div>
        <h1 class="text-2xl">
          Howdy, <b>{{ props.user.name }}</b>!
        </h1>
        <p>Last login: {{ props.user.last_login_at }} <br />from {{ props.user.last_login_ip }}</p>
        <div class="flex justify-center md:block">
          <PillTag
            text="Verified"
            type="info"
            :icon="mdiCheckDecagram"
            label="Verified"
          />
        </div>
      </div>
    </BaseLevel>
  </CardBox>
</template>
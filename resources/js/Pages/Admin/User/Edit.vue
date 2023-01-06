<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3"
import {
  mdiAccountKey,
  mdiArrowLeftBoldOutline
} from "@mdi/js"
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue"
import SectionMain from "@/components/SectionMain.vue"
import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue"
import CardBox from "@/components/CardBox.vue"
import FormField from '@/components/FormField.vue'
import FormControl from '@/components/FormControl.vue'
import FormCheckRadioGroup from '@/components/FormCheckRadioGroup.vue'
import BaseDivider from '@/components/BaseDivider.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'

const props = defineProps({
  user: {
    type: Object,
    default: () => ({}),
  },
  roles: {
    type: Object,
    default: () => ({}),
  },
  userHasRoles: {
    type: Object,
    default: () => ({}),
  }
})

const form = useForm({
  _method: 'put',
  name: props.user.name,
  email: props.user.email,
  password: '',
  password_confirmation: '',
  roles: props.userHasRoles
})
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Update user" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiAccountKey"
        title="Update user"
        main
      >
        <BaseButton
          :route-name="route('user.index')"
          :icon="mdiArrowLeftBoldOutline"
          label="Back"
          color="white"
          rounded-full
          small
        />
      </SectionTitleLineWithButton>
      <CardBox is-form="true"
        form
        @submit.prevent="form.post(route('user.update', props.user.id))"
      >
        <FormField
          label="Enter Name"
          :class="{ 'text-red-400': form.errors.name }"
        >
          <FormControl
            v-model="form.name"
            type="text"
            placeholder="Name"
            :error="form.errors.name"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.name">
              {{ form.errors.name }}
            </div>
          </FormControl>
        </FormField>

        <FormField
          label="Enter Email"
          :class="{ 'text-red-400': form.errors.email }"
        >
          <FormControl
            v-model="form.email"
            type="text"
            placeholder="Email"
            :error="form.errors.email"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.email">
              {{ form.errors.email }}
            </div>
          </FormControl>
        </FormField>

        <FormField
          label="Password"
          :class="{ 'text-red-400': form.errors.password }"
        >
          <FormControl
            v-model="form.password"
            type="password"
            placeholder="Enter Password"
            :error="form.errors.password"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.password">
              {{ form.errors.password }}
            </div>
          </FormControl>
        </FormField>

        <FormField
          label="Password Confirmation"
          :class="{ 'text-red-400': form.errors.password }"
        >
          <FormControl
            v-model="form.password_confirmation"
            type="password"
            placeholder="Enter Password Confirmation"
            :error="form.errors.password"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.password">
              {{ form.errors.password }}
            </div>
          </FormControl>
        </FormField>

        <BaseDivider />

        <FormField
          label="Roles"
          wrap-body
        >
          <FormCheckRadioGroup
            v-model="form.roles"
            name="roles"
            is-column
            :options="props.roles"
          />
        </FormField>

        <template #footer>
          <BaseButtons>
            <BaseButton
              type="submit"
              color="info"
              label="Submit"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            />
          </BaseButtons>
        </template>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>

<template>
  <Head title="Users" />
  <div class="flex justify-between mb-6">

    <div class="flex items-center">
      <h1 class="text-4xl font-bold">Users</h1>
      <Link v-if="can.createUser" href="/users/create" class="text-blue-500 text-sm ml-3">New User</Link>
  </div>

  <input type="text" placeholder="search" class="border px-2 rounded-lg" v-model="search">
  </div>
  <div class="px-4 sm:px-6 lg:px-8">
    <div
      class="mt-8
      flow-root">
      <div
        class="-mx-4
        -my-2
        overflow-x-auto
        sm:-mx-6
        lg:-mx-8">
        <div
          class="inline-block
          min-w-full
          py-2
          align-middle
          sm:px-6
          lg:px-8">
          <div
            class="overflow-hidden
            shadow
            ring-1
            ring-black
            ring-opacity-5
            sm:rounded-lg">
            <table
              class="min-w-full
              divide-y
              divide-gray-300">
              <tbody
                class="divide-y
                divide-gray-200
                bg-white">
                <tr v-for="user in
                  users.data"
                  :key="user.id">
                  <td
                    class="whitespace-nowrap
                    py-4
                    pl-4
                    pr-3
                    text-sm
                    font-medium
                    text-gray-900
                    sm:pl-6">{{ user.name }}</td>
                  <td
                    class="relative
                    whitespace-nowrap
                    py-4
                    pl-3
                    pr-4
                    text-right
                    text-sm
                    font-medium
                    sm:pr-6">
                    <Link
                      v-if="user.can.edit"
                      href="`/users/${user.id}/edit`"
                      class="text-indigo-600
                      hover:text-indigo-900">Edit<span
                        class="sr-only">,
                        Lindsay
                        Walton</span></Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    </div>


    <!-- Paginator -->
    <Pagination :links="users.links" />

</template>

<script setup>

import { ref, watch } from 'vue'
import Pagination from '../../Shared/Pagination.vue'
import { router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'


let props = defineProps({ 
users: Object,
filters: Object,
can: Object,
});

let search = ref(props.filters.search);

watch(search, debounce(function(value) {
      console.log('triggered');
      router.get('/users', { search: value }, { preserveState: true, replace: true });

    }, 500));

</script>

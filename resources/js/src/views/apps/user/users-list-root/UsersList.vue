<template>
  <div>

    <user-list-add-new 
      :is-add-new-user-sidebar-active.sync="isAddNewUserSidebarActive" 
      :role-options="roleOptions"
      :statu-options="statusOptions"
      @refetch-data="refetchData"
      :currentUserData.sync="currentUserData"
    />

    <!-- Filters -->
    <b-card no-body>
      <!-- <b-card-header class="pb-50">
        <h5>
          Filters
        </h5>
      </b-card-header> -->
      <b-card-body>
        <b-row>
          <b-col
            cols="12"
            md="3"
            class="mb-md-0 mb-2"
          >
            <label>Tên</label>
            <b-form-input
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              class="w-100"
              v-model="searchName"
              placeholder="Nhập họ tên"
            />
          </b-col>
          <b-col
            cols="12"
            md="3"
            class="mb-md-0 mb-2"
          >
            <label>Email</label>
            <b-form-input
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              class="w-100"
              v-model="searchEmail"
              placeholder="Nhập email"
            />
          </b-col>
          <b-col
            cols="12"
            md="3"
            class="mb-md-0 mb-2"
          >
            <label>Nhóm</label>
            <v-select
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              v-model="roleFilter"
              :options="roleOptions"
              class="w-100"
              placeholder="Chọn nhóm"
              :reduce="option => option.value"
            />
          </b-col>
          <b-col
            cols="12"
            md="3"
            class="mb-md-0 mb-2"
          >
            <label>Trạng thái</label>
            <v-select
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              v-model="statusFilter"
              :options="statusOptions"
              class="w-100"
              placeholder="Chọn trạng thái"
              :reduce="option => option.value"
            />
          </b-col>
        </b-row>
      </b-card-body>
    </b-card>
    <!-- Filters -->

    <!-- Table Container Card -->
    <b-card no-body class="mb-0">
      <div class="m-2">

        <!-- Table Top -->
        <b-row>

          <b-col cols="12" md="8">
            <div class="d-flex align-items-center justify-content-start">
              <b-button variant="primary" @click="isAddNewUserSidebarActive = true">
                <span class="text-nowrap">Add User</span>
              </b-button>
            </div>
          </b-col>

          <b-col cols="12" md="2">
            <div class="d-flex align-items-center justify-content-end">
              <b-button variant="primary" @click="refetchData">
                <span class="text-nowrap">Search</span>
              </b-button>
            </div>
          </b-col>

          <b-col cols="12" md="2">
            <div class="d-flex align-items-center justify-content-end">
              <b-button variant="primary" @click="resetData">
                <span class="text-nowrap">Xóa tìm kiếm</span>
              </b-button>
            </div>
          </b-col>
        </b-row>

      </div>

      <div class="mx-2 mb-2">
        <b-row v-if="totalUsers > 20">
          <!-- Pagination -->
          <b-col cols="12" sm="7" class="d-flex align-items-center justify-content-center justify-content-sm-end">

            <b-pagination v-model="currentPage" :total-rows="totalUsers" :per-page="perPage" first-number last-number
              class="mb-0 mt-1 mt-sm-0" prev-class="prev-item" next-class="next-item">
              <template #prev-text>
                <feather-icon icon="ChevronLeftIcon" size="18" />
              </template>
              <template #next-text>
                <feather-icon icon="ChevronRightIcon" size="18" />
              </template>
            </b-pagination>

          </b-col>

          <b-col cols="12" sm="5" class="d-flex align-items-right justify-content-right justify-content-sm-end">
            <span class="text-muted">Hiển thị từ {{ dataMeta.from }} - {{ dataMeta.to }} trong tổng số {{ dataMeta.of }}
              user</span>
          </b-col>

        </b-row>
      </div>

      <b-table ref="refUserListTable" class="position-relative" :items="fetchUsers" responsive :fields="tableColumns"
        primary-key="id" :sort-by.sync="sortBy" show-empty empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc">

        <!-- Column: User -->
        <template #cell(user)="data">
          <b-media vertical-align="center">
            <small class="text-muted">{{ data.item.name }}</small>
          </b-media>
        </template>

        <!-- Column: Role -->
        <template #cell(group_role)="data">
          <div class="text-nowrap">
            <span class="align-text-top text-capitalize">{{ data.item.group_role }}</span>
          </div>
        </template>

        <!-- Column: Status -->
        <template #cell(status)="data">
          <b-badge pill :variant="`light-${resolveUserStatusVariant(data.item.is_active)}`" class="text-capitalize">
            {{ data.item.status }}
          </b-badge>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL">

            <template #button-content>
              <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
            </template>

            <b-dropdown-item @click="getCurrentUser(data.item)">
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Edit</span>
            </b-dropdown-item>

            <b-dropdown-item @click="handleAction(data.item, 'delete')" v-b-modal.modal-no-backdrop>
              <feather-icon icon="TrashIcon" />
              <span class="align-middle ml-50">Delete</span>
            </b-dropdown-item>

            <b-dropdown-item @click="handleAction(data.item, 'block')" v-b-modal.modal-no-backdrop>
              <feather-icon icon="FileTextIcon" />
              <span class="align-middle ml-50">Block</span>
            </b-dropdown-item>
          </b-dropdown>
        </template>

      </b-table>
      <div class="mx-2 mb-2">
        <b-row v-if="totalUsers > 20">
          <!-- Pagination -->
          <b-col cols="12" sm="7" class="d-flex align-items-center justify-content-center justify-content-sm-end">

            <b-pagination v-model="currentPage" :total-rows="totalUsers" :per-page="perPage" first-number last-number
              class="mb-0 mt-1 mt-sm-0" prev-class="prev-item" next-class="next-item">
              <template #prev-text>
                <feather-icon icon="ChevronLeftIcon" size="18" />
              </template>
              <template #next-text>
                <feather-icon icon="ChevronRightIcon" size="18" />
              </template>
            </b-pagination>

          </b-col>
        </b-row>
      </div>
    </b-card>

    <user-action
    :userData="userData"
    :userAction="userAction"
    @refetch-data="refetchData"
    >
    </user-action>
  </div>
</template>

<script>
import {
  BCard,
  BRow,
  BCol,
  BFormInput,
  BButton,
  BTable,
  BMedia,
  BAvatar,
  BLink,
  BBadge,
  BDropdown,
  BDropdownItem,
  BPagination,
  BCardBody
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import store from '@/store'
import { ref, onUnmounted } from '@vue/composition-api'
import { avatarText } from '@core/utils/filter'
// import UsersListFilters from './UsersListFilters.vue'
import useUsersList from './useUsersList'
import userStoreModule from '../userStoreModule'
import UserListAddNew from './UserListAddNew.vue'
import UserAction from './UserAction.vue'

export default {
  components: {
    // UsersListFilters,
    UserListAddNew,

    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BTable,
    BMedia,
    BAvatar,
    BLink,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,

    vSelect,
    BCardBody,
    UserAction
  },
  setup() {
    const USER_APP_STORE_MODULE_NAME = 'app-user'

    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, userStoreModule)

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    const isAddNewUserSidebarActive = ref(false)

    const roleOptions = [
      { label: 'Admin', value: 'Admin' },
      { label: 'Reviewer', value: 'Reviewer' },
      { label: 'Editor', value: 'Editor' },
    ]

    const statusOptions = [
      { label: 'Active', value: 1 },
      { label: 'Inactive', value: 0 },
    ]

    const currentUserData = ref(null);
    currentUserData.value = {
      id: null,
      name: '',
      email: '',
      group_role: null,
      password: '',
      password_confirmation: '',
    }

    const getCurrentUser = (user) => {
      currentUserData.value = JSON.parse(JSON.stringify(user))
      isAddNewUserSidebarActive.value = true

      refetchData()
    }

    const userData = ref({})
    const userAction = ref('block')
    const handleAction = (user, action) => {
      userData.value = user;
      userAction.value = action;
    }

    const {
      fetchUsers,
      tableColumns,
      perPage,
      currentPage,
      totalUsers,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refUserListTable,
      refetchData,

      // UI
      resolveUserRoleVariant,
      resolveUserRoleIcon,
      resolveUserStatusVariant,

      // Extra Filters
      roleFilter,
      statusFilter,

      searchName,
      searchEmail,

      resetData,
    } = useUsersList()

    return {
      // Sidebar
      isAddNewUserSidebarActive,

      fetchUsers,
      tableColumns,
      perPage,
      currentPage,
      totalUsers,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refUserListTable,
      refetchData,

      // Filter
      avatarText,

      // UI
      resolveUserRoleVariant,
      resolveUserRoleIcon,
      resolveUserStatusVariant,

      roleOptions,
      statusOptions,

      // Extra Filters
      roleFilter,
      statusFilter,

      searchName,
      searchEmail,

      resetData,

      getCurrentUser,

      currentUserData,

      userData,
      userAction,
      handleAction,
    }
  },

}
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-select.scss';
</style>

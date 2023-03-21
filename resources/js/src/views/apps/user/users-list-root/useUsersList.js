import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useUsersList() {
  // Use toast
  const toast = useToast()

  const refUserListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'user', sortable: true },
    { key: 'email', sortable: true },
    { key: 'group_role', sortable: true },
    { key: 'status', sortable: true },
    { key: 'actions' },
  ]
  const perPage = ref(10)
  const totalUsers = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const roleFilter = ref(null)
  const statusFilter = ref(null)
  const searchName = ref('')
  const searchEmail = ref('')

  const dataMeta = computed(() => {
    const localItemsCount = refUserListTable.value ? refUserListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalUsers.value,
    }
  })

  const refetchData = () => {
    refUserListTable.value.refresh()
  }

  // watch([currentPage, perPage, searchQuery, searchName, searchEmail, , statusFilter], () => {
  watch([currentPage, perPage], () => {
    refetchData()
  })

  const resetData = () => {
    roleFilter.value = null
    statusFilter.value = null
    searchName.value = ''
    searchEmail.value = ''
    currentPage.value = 1

    refetchData()
  }

  const fetchUsers = (ctx, callback) => {
    console.log('sortBy: ' + sortBy.value)
    console.log('isSortDirDesc: ' + isSortDirDesc.value)

    store
      .dispatch('users/fetchUsers', {
        // q: searchQuery.value,
        email: searchEmail.value,
        name: searchName.value,
        perPage: perPage.value,
        page: currentPage.value,
        // sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
        role: roleFilter.value,
        status: statusFilter.value,
      })
      .then(response => {
        // store.commit('users/GET_USERS', response.data)

        const users = response.data.data
        const total = response.data.meta.total

        callback(users)
        totalUsers.value = total
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching users list',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*

  const resolveUserRoleVariant = role => {
    if (role === 'Editor') return 'info'
    if (role === 'Admin') return 'danger'
    return 'primary'
  }

  const resolveUserRoleIcon = role => {
    if (role === 'Editor') return 'Edit2Icon'
    if (role === 'Admin') return 'ServerIcon'
    return 'UserIcon'
  }

  const resolveUserStatusVariant = (is_active) => {
    if (is_active === 1) return 'success'
    return 'secondary'
  }

  return {
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

    resolveUserRoleVariant,
    resolveUserRoleIcon,
    resolveUserStatusVariant,
    refetchData,

    // Extra Filters
    roleFilter,
    statusFilter,

    searchName,
    searchEmail,

    resetData,
  }
}

import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function customerList() {
  // Use toast
  const toast = useToast()

  const refCustomerListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'customer_name', label: 'Name' },
    { key: 'email' },
    { key: 'address' },
    { key: 'tel_num'},
    { key: 'edit'},
  ]
  const perPage = ref(10)
  const totalCustomers = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const statusFilter = ref(null)
  const addressFilter = ref(null)
  const searchName = ref('')
  const searchEmail = ref('')

  const dataMeta = computed(() => {
    const localItemsCount = refCustomerListTable.value ? refCustomerListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalCustomers.value,
    }
  })

  const refetchData = () => {
    refCustomerListTable.value.refresh()
  }

  watch([currentPage, perPage], () => {
    refetchData()
  })

  const resetData = () => {
    statusFilter.value = null
    addressFilter.value = ''
    searchName.value = ''
    searchEmail.value = ''
    currentPage.value = 1

    refetchData()
  }

  const fetchCustomers = (ctx, callback) => {
    store
      .dispatch('customers/fetchCustomers', {
        email: searchEmail.value,
        customer_name: searchName.value,
        is_active: statusFilter.value,
        address: addressFilter.value,
        perPage: perPage.value,
        page: currentPage.value,
      })
      .then(response => {
        // const customers = response.data.data.map(item => ({...item, isEdit: false}));
        const customers = response.data.data.map(item => ({...item, isEdit: false}));
        const total = response.data.meta.total
        callback(customers)
        totalCustomers.value = total
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching customers list',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  const exportCustomers = (ctx, callback) => {
    store
      .dispatch('customers/exportCustomer', {
        email: searchEmail.value,
        customer_name: searchName.value,
        is_active: statusFilter.value,
        address: addressFilter.value,
      })
      .then(response => {
        const anchor = document.createElement('a');
        anchor.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(response.data);
        anchor.target = '_blank';
        anchor.download = 'customers.csv';
        anchor.click();

        toast({
          component: ToastificationContent,
          props: {
            title: 'Export successful',
            icon: 'AlertTriangleIcon',
            variant: 'success',
          },
        })
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Export Error, please try again',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }


  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*

  return {
    fetchCustomers,
    tableColumns,
    perPage,
    currentPage,
    totalCustomers,
    dataMeta,
    perPageOptions,
    sortBy,
    isSortDirDesc,
    refCustomerListTable,

    refetchData,

    // Extra Filters
    statusFilter,
    addressFilter,

    searchName,
    searchEmail,

    resetData,

    toast,

    ToastificationContent,

    exportCustomers,
  }
}

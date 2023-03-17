import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function productList() {
  // Use toast
  const toast = useToast()

  const refProductListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'id', label: '#' },
    { key: 'product_name' },
    { key: 'description' },
    { key: 'product_price'},
    { key: 'is_sales'},
    { key: 'actions'},
  ]
  const perPage = ref(10)
  const totalProducts = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const statusFilter = ref(null)
  const searchName = ref('')
  const searchPriceFrom = ref(null)
  const searchPriceTo = ref(null)

  const dataMeta = computed(() => {
    const localItemsCount = refProductListTable.value ? refProductListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalProducts.value,
    }
  })

  const refetchData = () => {
    refProductListTable.value.refresh()
  }

  watch([currentPage, perPage], () => {
    refetchData()
  })

  const resetData = () => {
    searchName.value = ''
    statusFilter.value = null
    searchPriceFrom.value = null
    searchPriceTo.value = null
    
    currentPage.value = 1

    refetchData()
  }

  const resolveProductStatusVariant = (is_sales) => {
    if (is_sales === 1) return 'success'
    return 'secondary'
  }

  const fetchProducts = (ctx, callback) => {
    store
      .dispatch('products/fetchProducts', {
        product_name: searchName.value,
        is_sales: statusFilter.value,
        product_price_from:  searchPriceFrom.value,
        product_price_to:  searchPriceTo.value,
        perPage: perPage.value,
        page: currentPage.value,
      })
      .then(response => {
        const products = response.data.data
        const total = response.data.meta.total

        callback(products)
        totalProducts.value = total
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching products list',
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
    fetchProducts,
    tableColumns,
    perPage,
    currentPage,
    totalProducts,
    dataMeta,
    perPageOptions,
    sortBy,
    isSortDirDesc,
    refProductListTable,

    refetchData,

    // Extra Filters
    statusFilter,

    searchName,
    searchPriceFrom,
    searchPriceTo,

    resetData,

    toast,

    ToastificationContent,
    resolveProductStatusVariant,

  }
}

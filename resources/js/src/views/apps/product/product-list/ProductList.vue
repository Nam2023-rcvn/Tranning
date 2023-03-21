<template>
  <div>

    <product-list-add-new 
      :is-add-new-product-sidebar-active.sync="isAddNewProductSidebarActive"
      :status-options="statusOptions" 
      @refetch-data="refetchData" 
      :currentProductData.sync="currentProductData" 
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
          <b-col cols="12" md="3" class="mb-md-0 mb-2">
            <label>Tên sản phẩm</label>
            <b-form-input :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" class="w-100" v-model="searchName"
              placeholder="Nhập tên sản phẩm" />
          </b-col>
          <b-col cols="12" md="3" class="mb-md-0 mb-2">
            <label>Trạng thái</label>
            <v-select :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" v-model="statusFilter" :options="statusOptions"
              class="w-100" placeholder="Chọn trạng thái" :reduce="option => option.value" />
          </b-col>

          <b-col cols="12" md="3" class="mb-md-0 mb-2">
            <label>Giá bán từ</label>
            <b-form-input :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" class="w-100" v-model="searchPriceFrom"
              type="number" step="0.01" min="0.00"></b-form-input>
          </b-col>
          <b-col cols="12" md="3" class="mb-md-0 mb-2">
            <label>Giá bán đến</label>
            <b-form-input :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" class="w-100" v-model="searchPriceTo"
              type="number" step="0.01" min="0.00"></b-form-input>
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
              <b-button variant="primary" @click="isAddNewProductSidebarActive = true">
                <span class="text-nowrap">Add Product</span>
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
        <b-row v-if="totalProducts > 20">
          <!-- Pagination -->
          <b-col cols="12" sm="7" class="d-flex align-items-center justify-content-center justify-content-sm-end">

            <b-pagination v-model="currentPage" :total-rows="totalProducts" :per-page="perPage" first-number last-number
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
              sản phẩm</span>
          </b-col>

        </b-row>
      </div>

      <b-table
        id="table_products"
        ref="refProductListTable" class="position-relative" :items="fetchProducts" responsive
        :fields="tableColumns" primary-key="id" :sort-by.sync="sortBy" show-empty empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >

        <!-- Column: id -->
        <template #cell(id)="data">
          <b-media vertical-align="center">
            <small class="text-muted">{{ data.item.id }}</small>
          </b-media>
        </template>

        <!-- Column: product_name -->
        <template #cell(product_name)="data">
          <div @mouseleave="mouseleaveItem(data.item.id)">
            <div 
              class="text-nowrap"
              @mouseover="mouseoverItem(data.item.id)"
              
            >
              <span class="align-text-top text-capitalize">{{ data.item.product_name }}</span>
            </div>
            <img
              v-if="isHover == data.item.id && data.item.product_image"
              class="hover-img"
              alt="img-3"
              width="90"
              :src="data.item.product_image"
            />
          </div>
        </template>

        <!-- Column: description -->
        <template #cell(description)="data">
          <span style="text-overflow: ellipsis;" class="align-text-top text-capitalize">{{ data.item.description_str }}</span>
        </template>

        <!-- Column: product_price -->
        <template #cell(product_price)="data">
          <span class="align-text-top text-capitalize">{{ '$' + data.item.product_price }}</span>
        </template>

        <!-- Column: is_sales -->
        <template #cell(is_sales)="data">
          <b-badge pill :variant="`light-${resolveProductStatusVariant(data.item.is_sales)}`" class="text-capitalize">
            {{ data.item.status }}
          </b-badge>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL">

            <template #button-content>
              <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
            </template>

            <b-dropdown-item @click="getCurrentProduct(data.item)">
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Edit</span>
            </b-dropdown-item>

            <b-dropdown-item @click="handleAction(data.item, 'delete')" v-b-modal.modal-action-products>
              <feather-icon icon="TrashIcon" />
              <span class="align-middle ml-50">Delete</span>
            </b-dropdown-item>
          </b-dropdown>
        </template>

      </b-table>
      <div class="mx-2 mb-2">
        <b-row v-if="totalProducts > 20">
          <!-- Pagination -->
          <b-col cols="12" sm="7" class="d-flex align-items-center justify-content-center justify-content-sm-end">

            <b-pagination v-model="currentPage" :total-rows="totalProducts" :per-page="perPage" first-number last-number
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

    <product-action
    :productData="productData"
    :productAction="productAction"
    @refetch-data="refetchData"
    >
    </product-action>
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
  BCardBody,
  BImg,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import store from '@/store'
import { ref, onUnmounted } from '@vue/composition-api'
import { avatarText } from '@core/utils/filter'
import productList from './ProductList'
import ProductListAddNew from './ProductListAddNew.vue'
import ProductAction from './ProductAction.vue'

export default {
  components: {
    ProductListAddNew,

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
    BImg,
    ProductAction,
  },
  data() {
    return {
      isHover : null
    }
  },
  setup() {

    const isAddNewProductSidebarActive = ref(false)

    const statusOptions = [
      { label: 'Đang bán', value: 1 },
      { label: 'Hết hàng', value: 0 },
    ]

    const currentProductData = ref(null);
    currentProductData.value = {
      id: null,
      product_name: '',
      product_price: null,
      description: '',
      is_sales: null,
      product_image: null
    }

    const getCurrentProduct = (product) => {
      currentProductData.value = JSON.parse(JSON.stringify(product))
      isAddNewProductSidebarActive.value = true

      refetchData()
    }

    const productData = ref({})
    const productAction = ref('')
    const handleAction = (product, action) => {
      productData.value = product;
      productAction.value = action;
    }

    const {
      fetchProducts,
      tableColumns,
      perPage,
      currentPage,
      totalProducts,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refProductListTable,
      refetchData,

      // UI
      resolveProductStatusVariant,

      // Extra Filters
      roleFilter,
      statusFilter,

      searchName,
      searchPriceFrom,
      searchPriceTo,

      resetData,
      toast,
      ToastificationContent,
    } = productList()

    return {
      // Sidebar
      isAddNewProductSidebarActive,

      fetchProducts,
      tableColumns,
      perPage,
      currentPage,
      totalProducts,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refProductListTable,
      refetchData,

      // Filter
      avatarText,

      // UI
      resolveProductStatusVariant,

      statusOptions,

      // Extra Filters
      roleFilter,
      statusFilter,

      searchName,
      searchPriceFrom,
      searchPriceTo,

      resetData,
      currentProductData,
      getCurrentProduct,
      toast,
      ToastificationContent,
      handleAction,
      productData,
      productAction
    }
  },
  methods: {
    hoverRow(record, index){
    },
    mouseoverItem(id){
      this.isHover = id
    },
    mouseleaveItem(id){
      this.isHover = null
    }
  }
}
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>

<style>
#table_products tr{
  height: 50px;
}
</style>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-select.scss';
</style>

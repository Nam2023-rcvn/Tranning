<template>
  <div>

    <customer-list-add-new 
      :is-add-new-customer-sidebar-active.sync="isAddNewCustomerSidebarActive" 
      :status-options="statusOptions"
      @refetch-data="refetchData"
    />

    <!-- Filters -->
    <b-card no-body>
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
            <label>Trạng thái</label>
            <v-select
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              v-model="statusFilter"
              :options="statusOptions"
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
            <label>Tên</label>
            <b-form-input
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              class="w-100"
              v-model="addressFilter"
              placeholder="Nhập họ tên"
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
          <b-col cols="3">
            <div class="d-flex align-items-left justify-content-start">
              <b-button variant="primary" @click="isAddNewCustomerSidebarActive = true">
                <span class="text-nowrap">Add Customer</span>
              </b-button>
            </div>
          </b-col>
          <b-col cols="2">
            <div class="d-flex align-items-left justify-content-start">
              <b-button variant="primary" v-b-modal.modal-customer-import>
                <span class="text-nowrap">Import</span>
              </b-button>
            </div>
          </b-col>
          <b-col cols="2">
            <div class="d-flex align-items-left justify-content-start">
              <b-button variant="primary" @click="exportCustomers">
                <span class="text-nowrap">Export</span>
              </b-button>
            </div>
          </b-col>

          <b-col cols="1"></b-col>

          <b-col cols="2">
            <div class="d-flex align-items-right justify-content-end">
              <b-button variant="primary" @click="refetchData">
                <span class="text-nowrap">Search</span>
              </b-button>
            </div>
          </b-col>

          <b-col cols="2">
            <div class="d-flex align-items-right justify-content-end">
              <b-button variant="primary" @click="resetData">
                <span class="text-nowrap">Xóa tìm kiếm</span>
              </b-button>
            </div>
          </b-col>
        </b-row>

      </div>

      <div class="mx-2 mb-2">
        <b-row>
          <!-- Pagination -->
          <b-col cols="12" sm="7" class="d-flex align-items-center justify-content-center justify-content-sm-end">

            <b-pagination v-model="currentPage" :total-rows="totalCustomers" :per-page="perPage" first-number last-number
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
              khách hàng</span>
          </b-col>

        </b-row>
      </div>

      <b-table ref="refCustomerListTable" 
        class="position-relative" 
        :items="fetchCustomers" 
        responsive :fields="tableColumns"
      >
          <template #cell(customer_name)="data">
            <ValidationProvider
              v-if="data.item.isEdit" 
              rules="required" 
              v-slot="{ validate, errors }"
              name="Name"
            >
              <b-form-input
              type="text" 
              v-model="data.item.customer_name"
              :state="errors.length > 0 ? false:null"
              autofocus
              trim
              ></b-form-input>
              
              <small class="text-danger">{{ errors[0] }}</small>
            </ValidationProvider>

            <span v-else>{{data.value}}</span>
          </template>

          <template #cell(email)="data">
            <ValidationProvider
              v-if="data.item.isEdit" 
              rules="required|email" 
              v-slot="{ validate, errors }"
              name="Email"
            >
              <b-form-input
              type="text" 
              v-model="data.item.email"
              :state="errors.length > 0 ? false:null"
              autofocus
              trim
              ></b-form-input>
              
              <small class="text-danger">{{ errors[0] }}</small>
            </ValidationProvider>

            <span v-else>{{data.value}}</span>
          </template>

          <template #cell(address)="data">
            <ValidationProvider
              v-if="data.item.isEdit" 
              rules="required" 
              v-slot="{ validate, errors }"
              name="Address"
            >
              <b-form-input
              type="text" 
              v-model="data.item.address"
              :state="errors.length > 0 ? false:null"
              autofocus
              trim
              ></b-form-input>
              
              <small class="text-danger">{{ errors[0] }}</small>
            </ValidationProvider>

            <span v-else>{{data.value}}</span>
          </template>

          <template #cell(tel_num)="data">
            <ValidationProvider
              v-if="data.item.isEdit" 
              rules="required" 
              v-slot="{ validate, errors }"
              name="Phone"
            >
              <b-form-input
              type="text" 
              v-model="data.item.tel_num"
              :state="errors.length > 0 ? false:null"
              autofocus
              trim
              ></b-form-input>
              
              <small class="text-danger">{{ errors[0] }}</small>
            </ValidationProvider>

            <span v-else>{{data.value}}</span>
          </template>

          <template #cell(edit)="data">
            <div v-if="!data.item.isEdit">
              <b-button 
                variant="primary"
                class="mr-2"
                @click="editRowHandler(data, 'edit')"
                type="submit"
              >
                Edit
              </b-button>
            </div>
            <div v-else="!data.item.isEdit">
              <b-button 
                variant="primary"
                class="mr-2"
                @click="editRowHandler(data, 'save')"
                type="submit"
                :disabled="test"
              >
                Save
              </b-button>
              <b-button 
                variant="primary"
                class="mr-2"
                @click="editRowHandler(data, 'cancel')"
                type="submit"
              >
                Cancel
              </b-button>
            </div>
            
          </template>
      </b-table>
      
      <div class="mx-2 mb-2">
        <b-row>
          <!-- Pagination -->
          <b-col cols="12" sm="7" class="d-flex align-items-center justify-content-center justify-content-sm-end">

            <b-pagination v-model="currentPage" :total-rows="totalCustomers" :per-page="perPage" first-number last-number
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

      <CustomerImport
      @refetch-data="refetchData"
      >
      </CustomerImport>
    </b-card>
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
  BForm
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import store from '@/store'
import { ref, onUnmounted } from '@vue/composition-api'
import { avatarText } from '@core/utils/filter'
import CustomerList from './CustomerList'
import CustomerListAddNew from './CustomerListAddNew.vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import CustomerImport from './CustomerImport.vue'

export default {
  components: {
    CustomerListAddNew,

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
    BForm,

    vSelect,
    BCardBody,

    ValidationProvider,
    ValidationObserver,

    CustomerImport,
  },
  data(){
    return {
      items: [],
      test: false
    }
  },
  setup() {
    const USER_APP_STORE_MODULE_NAME = 'app-user'

    const isAddNewCustomerSidebarActive = ref(false)

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
      isAddNewCustomerSidebarActive.value = true

      refetchData()
    }

    const {
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
    } = CustomerList()

    return {
      // Sidebar
      isAddNewCustomerSidebarActive,

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

      // Filter
      avatarText,


      statusOptions,

      // Extra Filters
      statusFilter,
      addressFilter,

      searchName,
      searchEmail,

      resetData,

      getCurrentUser,

      currentUserData,

      toast,
      ToastificationContent,

      exportCustomers,
    }
  },
  // const {handleSubmit} = useForm();
  // const editRow = handleSubmit((data) => {

  // })
  methods: {
    editRowHandler(data, action) {
      if(action === 'save')
      {
        var refCustomerListTable = this.$refs.refCustomerListTable
        var refCustomerListTableRow = ((refCustomerListTable.$refs)['item-rows'][data.index]).$children
        var check = true

        refCustomerListTableRow.forEach(function(element){
          if(element && element.errors.length > 0){
            check = false
          }
        })

        if(check === true){
          store.dispatch('customers/updateCustomer', data.item)
          .then(response => { 
            this.refetchData()
          })
          .catch(error => {
            console.log(error)

            this.toast({
            component: this.ToastificationContent,
            props: {
              title: 'ERROR',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
            },
          })
          })
        }
        else{
          this.toast({
            component: this.ToastificationContent,
            props: {
              title: 'Rown data is invalid',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
            },
          })
        }
      }

      data.item.isEdit = !data.item.isEdit
    },
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

<template>
  <b-sidebar
    id="add-new-user-sidebar"
    :visible="isAddNewCustomerSidebarActive"
    bg-variant="white"
    sidebar-class="sidebar-lg"
    shadow
    backdrop
    no-header
    right
    @hidden="resetForm"
    @change="(val) => $emit('update:is-add-new-customer-sidebar-active', val)"
  >
    <template #default="{ hide }">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
        <h5 v-if="customerData.id !== null" class="mb-0">
          Chỉnh sửa User
        </h5>
        <h5 v-else class="mb-0">
          Thêm khách hàng
        </h5>
        <feather-icon
          class="ml-1 cursor-pointer"
          icon="XIcon"
          size="16"
          @click="hide"
        />
      </div>

      <!-- BODY -->
      <validation-observer
        #default="{ invalid }"
        ref="refFormObserver"
      >
        <!-- Form -->
        <b-form
          class="p-2"
          @submit.prevent="onSubmit()"
          @reset.prevent="resetForm"
        >
          <!--  Name -->
          <validation-provider
            #default="{ errors }"
            name="Name"
            vid="customer_name"
            rules="required"
          >
            <b-form-group
              label="Tên"
              label-for="customer_name"
            >
              <b-form-input
                id="customer_name"
                v-model="customerData.customer_name"
                :state="errors.length > 0 ? false:null"
                name="customer_name"
                placeholder="Nhập họ tên"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <!-- email -->
          <validation-provider
            #default="{ errors }"
            name="Email"
            vid="email"
            rules="required|email"
          >
            <b-form-group
              label="Email"
              label-for="login-email"
            >
              <b-form-input
                id="login-email"
                v-model="customerData.email"
                :state="errors.length > 0 ? false:null"
                name="login-email"
                placeholder="john@example.com"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <!-- tel_num -->
          <validation-provider
            #default="{ errors }"
            name="Điện thoại"
            rules="required"
          >
            <b-form-group
              label="Điện thoại"
              label-for="tel_num"
            >
              <b-form-input
                id="tel_num"
                v-model="customerData.tel_num"
                :state="errors.length > 0 ? false:null"
                name="tel_num"
                placeholder="Điện thoại"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <validation-provider
            #default="{ errors }"
            name="Địa chỉ"
            rules="required"
          >
            <b-form-group
              label="Địa chỉ"
              label-for="address"
            >
              <b-form-input
                id="address"
                v-model="customerData.address"
                autofocus
                :state="errors.length > 0 ? false:null"
                trim
                placeholder="Địa chỉ"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <b-col
            cols="12"
            md="12"
            class="mb-md-0 mb-2"
          >
            <label>Trạng thái: TRUE</label>
          </b-col>

          <!-- Form Actions -->
          <div class="d-flex mt-2 align-items-center justify-content-start">
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              class="mr-2"
              type="button"
              variant="outline-secondary"
              @click="hide"
            >
              Hủy
            </b-button>
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              class="mr-2"
              type="submit"
            >
              Lưu
            </b-button>
          </div>

        </b-form>
      </validation-observer>
    </template>
  </b-sidebar>
</template>

<script>
import { BAlert, BSidebar, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton, BCol} from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref, toRefs, watch, computed } from '@vue/composition-api'
import { required, alphaNum, email } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import store from '@/store'
import { useInputImageRenderer } from '@core/comp-functions/forms/form-utils'

export default {
  components: {
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BButton,
    BCol,
    BAlert,

    vSelect,

    // Form Validation
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: ['isAddNewCustomerSidebarActive'],
    event: 'update:is-add-new-customer-sidebar-active',
    event: 'update:modelValue',
  },
  props: {
    isAddNewCustomerSidebarActive: {
      type: Boolean,
      required: true,
    },
    statusOptions: {
      type: Array,
    },
  },
  data() {
    return {
      required,
      alphaNum,
      email,
    }
  },
  setup(props, { emit }) {
    const blankcustomerData = {
      customer_name: '',
      email: '',
      tel_num: '',
      address: '',
    }

    const customerData = ref(JSON.parse(JSON.stringify(blankcustomerData)))
    const resetcustomerData = () => {
      customerData.value = JSON.parse(JSON.stringify(blankcustomerData))
    }

    const { refFormObserver, getValidationState, resetForm } = formValidation(resetcustomerData)

    return {
      customerData,
      refFormObserver,
      getValidationState,
      resetForm,
    }
  },
  methods: {
    onSubmit(){
      this.$refs.refFormObserver.validate().then(success => {
        if (success) {
          store.dispatch('customers/addCustomer', { data : this.customerData})
          .then(response => { 
            this.$emit('refetch-data')
            this.$emit('update:is-add-new-customer-sidebar-active', false)
          })
          .catch(error => {
            this.$refs.refFormObserver.setErrors(error.response.data.errors)
          })
        }
      })
      

    }
  }
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-select.scss';

#add-new-user-sidebar {
  .vs__dropdown-menu {
    max-height: 200px !important;
  }
}
</style>

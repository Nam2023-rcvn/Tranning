<template>
  <b-sidebar
    id="add-new-user-sidebar"
    :visible="isAddNewUserSidebarActive"
    bg-variant="white"
    sidebar-class="sidebar-lg"
    shadow
    backdrop
    no-header
    right
    @hidden="resetForm"
    @change="(val) => $emit('update:is-add-new-user-sidebar-active', val)"
  >
    <template #default="{ hide }">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
        <h5 v-if="userData.id !== null" class="mb-0">
          Chỉnh sửa User
        </h5>
        <h5 v-else class="mb-0">
          Thêm User
        </h5>
        <feather-icon
          class="ml-1 cursor-pointer"
          icon="XIcon"
          size="16"
          @click="hide"
        />
      </div>

      <!-- Alert: No item found -->
      <b-alert
        variant="danger"
        :show="userData === undefined"
      >
        <h4 class="alert-heading">
          Error fetching user data
        </h4>
        <div class="alert-body">
          No user found with this user id. Check
          <b-link
            class="alert-link"
            :to="{ name: 'apps-users-list'}"
          >
            User List
          </b-link>
          for other users.
        </div>
      </b-alert>

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
          <input type="hidden" name="id" :value="userData.id">

          <!-- Full Name -->
          <validation-provider
            #default="{ errors }"
            name="Name"
            vid="name"
            rules="required"
          >
            <b-form-group
              label="Tên"
              label-for="name"
            >
              <b-form-input
                id="name"
                v-model="userData.name"
                autofocus
                :state="errors.length > 0 ? false:null"
                name="name"
                placeholder="Nhập họ tên"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <!-- Email -->
          <validation-provider
            #default="{ errors }"
            name="email"
            rules="required|email"
            vid="email"
          >
            <b-form-group
              label="Email"
              label-for="email"
            >
              <b-form-input
                id="email"
                v-model="userData.email"
                :state="errors.length > 0 ? false:null"
                name="email"
                placeholder="Nhập email"
                />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <!-- password -->
          <validation-provider
          #default="{ errors }"
            name="password"
            rules="required"
            vid="password"
          >
            <b-form-group
              label="Mật khẩu"
              label-for="password"
            >
              <b-form-input
                v-model="userData.password"
                type="password"
                :state="errors.length > 0 ? false:null"
                placeholder="Mật khẩu"
                aria-describedby="password-help-block"
                ref="password"
                />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <!-- password_confirmation -->
          <validation-provider
          #default="{ errors }"
            name="password_confirmation"
            rules="required|confirmed:password"
            vid="password_confirmation"
          >
            <b-form-group
              label="Xác nhận"
              label-for="password_confirmation"
            >
              <b-form-input
                v-model="userData.password_confirmation"
                type="password"
                :state="errors.length > 0 ? false:null"
                placeholder="Xác mật khẩu"
                aria-describedby="password-help-block"
                ref="password"
                data-vv-as="password"
                />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <!-- User Role -->
          <validation-provider
            #default="{ errors }"
            name="group_role"
            rules="required"
            vid="group_role"
          >
            <b-form-group
              label="Nhóm"
              label-for="group_role"
              :state="errors.length > 0 ? false:null"
            >
              <v-select
                v-model="userData.group_role"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="roleOptions"
                :reduce="val => val.value"
                :clearable="false"
                input-id="user-role"
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
import { BAlert, BSidebar, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton, BCol } from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref, toRefs, watch, computed } from '@vue/composition-api'
import { required, alphaNum, email } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import store from '@/store'

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
    prop: ['isAddNewUserSidebarActive', 'currentUserData'],
    event: 'update:is-add-new-user-sidebar-active',
    event: 'update:modelValue',
  },
  props: {
    isAddNewUserSidebarActive: {
      type: Boolean,
      required: true,
    },
    roleOptions: {
      type: Array,
      required: true,
    },
    currentUserData: {
      type: Object,
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
    const blankUserData = {
      id: null,
      name: '',
      email: '',
      group_role: null,
      password: '',
      password_confirmation: '',
    }

    const userData = computed({
        get: () => {
          return props.currentUserData
        },
        set: (val) => {
          emit('update:currentUserData', val)
        },
    });

    const resetuserData = () => {
      userData.value = JSON.parse(JSON.stringify(blankUserData))
    }

    const { refFormObserver, getValidationState, resetForm } = formValidation(resetuserData)

    return {
      userData,
      // onSubmit,

      refFormObserver,
      getValidationState,
      resetForm,
    }
  },
  methods: {
    onSubmit(){
      this.$refs.refFormObserver.validate().then(success => {
        if (success) {
          if(this.userData.id !== null){
            store.dispatch('users/updateUser', { id: this.userData.id, data : this.userData})
            .then(response => { 
              this.$emit('refetch-data')
              this.$emit('update:is-add-new-user-sidebar-active', false)
            })
            .catch(error => {
              this.$refs.refFormObserver.setErrors(error.response.data.errors)
            })
          }
          else{
            store.dispatch('users/addUser', { data : this.userData})
            .then(response => { 
              this.$emit('refetch-data')
              this.$emit('update:is-add-new-user-sidebar-active', false)
            })
            .catch(error => {
              console.log('error')
              console.log(error)
              console.log(error.response)
              this.$refs.refFormObserver.setErrors(error.response.data.errors)
            })
          }
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

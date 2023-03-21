<template>
  <b-sidebar
    id="add-new-user-sidebar"
    :visible="isAddNewProductSidebarActive"
    bg-variant="white"
    sidebar-class="sidebar-lg"
    shadow
    backdrop
    no-header
    right
    @hidden="resetForm"
    @change="(val) => $emit('update:is-add-new-product-sidebar-active', val)"
    width="60%"
  >
    <template #default="{ hide }">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
        <h5 v-if="productData.id !== null" class="mb-0">
          Chỉnh sửa sản phẩm
        </h5>
        <h5 v-else class="mb-0">
          Thêm sản phẩm
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
          <input type="hidden" name="id" :value="productData.id">

          <!-- Full Name -->
          <validation-provider
            #default="{ errors }"
            name="Product Name"
            vid="product_name"
            rules="required"
          >
            <b-form-group
              label="Product Name"
              label-for="product_name"
            >
              <b-form-input
                id="product_name"
                v-model="productData.product_name"
                autofocus
                :state="errors.length > 0 ? false:null"
                trim
                placeholder="Nhập tên sản phẩm"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <!-- product price -->
          <validation-provider
          #default="{ errors }"
            name="Product Price"
            vid="product_price"
            rules="required|min_value:0.00|double"
          >
            <b-form-group
              label="Product Price"
              label-for="product_price"
            >
              <b-form-input
                id="product_price"
                v-model="productData.product_price"
                autofocus
                :state="errors.length > 0 ? false:null"
                trim
                placeholder="Nhập giá bán"
                type="number" step="0.01" min="0.00" 
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <!-- product desc -->
          <b-form-group
            label="Product description"
            label-for="description"
          >
            <div class="editor">
              <div
                id="toolbar"
                class="d-flex border-bottom-0"
              >
                <!-- Add a bold button -->
                <button class="ql-bold" />
                <button class="ql-italic" />
                <button class="ql-underline" />
                <button class="ql-align" />
                <button class="ql-link" />
              </div>
              <quill-editor
                style="height: 250px"
                id="quil-content"
                v-model="productData.description"
                :options="editorOption"
              />
            </div>
          </b-form-group>

          <!-- User Role -->
          <validation-provider
            #default="{ errors }"
            name="Trạng thái"
            vid="is_sales"
            rules=""
          >
            <b-form-group
              label="Trạng thái"
              label-for="is_sales"
              :state="errors.length > 0 ? false:null"
            >
              <v-select
                v-model="productData.is_sales"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="statusOptions"
                :reduce="val => val.value"
                :clearable="false"
                input-id="is_sales"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>

          <!-- media -->
          <validation-provider
            #default="{ errors }"
            name="Product image"
            vid="product_image"
            rules=""
          >
            <b-form-group
              label="Product image"
              label-for="product_image"
            >
              <b-media
                no-body
                vertical-align="center"
              >
                <b-media-aside
                vertical-align="center"
                >
                  <b-img
                    ref="previewEl"
                    height="110"
                    width="110"
                    vertical-align="center"
                    :src="productData.product_image !== null ? productData.product_image : imageDefault"
                  />
                </b-media-aside>
                <b-media-body>
                  <b-form-file
                    ref="refInputEl"
                    v-model="imageUpload"
                    accept=".jpg, .png, .jpeg"
                    :hidden="true"
                    plain
                    @input="inputImageRenderer"
                    :state="errors.length > 0 ? false:null"
                  />
                  
                  <b-button
                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                    variant="primary"
                    size="sm"
                    class=""
                    @click="$refs.refInputEl.$el.click()"
                  >
                    Upload
                  </b-button>

                  <b-button
                    v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                    variant="outline-secondary"
                    size="sm"
                    class=""
                    @click="resetImage()"
                  >
                    Delete
                  </b-button>
                  <small class="text-danger">{{ errors[0] }}</small>
                </b-media-body>
              </b-media>
            </b-form-group>
          </validation-provider>
          <!--/ media -->

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
import { BAlert, BSidebar, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton, BCol, BFormTextarea, BMediaBody, BMedia, BFormFile, BMediaAside, BImg } from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref, toRefs, watch, computed } from '@vue/composition-api'
import { required, alphaNum, email } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import store from '@/store'
import { quillEditor } from 'vue-quill-editor'
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
    BFormTextarea,
    BMediaBody,
    BMedia,
    BFormFile,
    BMediaAside,
    BImg,
    vSelect,

    // Form Validation
    ValidationProvider,
    ValidationObserver,

    quillEditor,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: ['isAddNewProductSidebarActive', 'currentProductData'],
    event: 'update:is-add-new-product-sidebar-active',
    event: 'update:modelValue',
  },
  props: {
    isAddNewProductSidebarActive: {
      type: Boolean,
      required: true,
    },
    statusOptions: {
      type: Array,
      required: true,
    },
    currentProductData: {
      type: Object,
    },
  },
  data() {
    return {
      required,
      alphaNum,

      imageDefault: 'https://www.portofinoselecta.com/images/joomlart/demo/default.jpg',
    }
  },
  setup(props, { emit }) {
    const productData = computed({
        get: () => {
          return props.currentProductData
        },
        set: (val) => {
          emit('update:currentProductData', val)
        },
    });

    const refInputEl = ref(null)
    const previewEl = ref(null)

    const { inputImageRenderer } = useInputImageRenderer(refInputEl, base64 => {
      previewEl.value.src = base64
      productData.value.product_image = base64
    })

    const blankProductData = {
      id: null,
      product_name: '',
      product_price: null,
      description: '',
      is_sales: null,
      product_image: null,
    }

    const editorOption = {
      modules: {
        // toolbar: '#quill-toolbar',
        toolbar: '#toolbar',
      },
      placeholder: 'Description',
    }

    const imageUpload = ref(null)

    const resetProductData = () => {
      productData.value = JSON.parse(JSON.stringify(blankProductData))
      imageUpload.value = null
    }

    const { refFormObserver, getValidationState, resetForm } = formValidation(resetProductData)

    return {
      productData,

      refFormObserver,
      getValidationState,
      resetForm,

      editorOption,

      inputImageRenderer,
      refInputEl,
      previewEl,

      imageUpload,
    }
  },
  methods: {
    onSubmit(){
      this.$refs.refFormObserver.validate().then(success => {
        if (success) {
          if(this.imageUpload){
            this.productData.product_image = this.imageUpload
          }

          if(this.productData.product_image === null) {
            this.productData.product_image = ''
          }
          
          if(this.productData.id !== null){
            store.dispatch('products/updateProduct', this.productData)
            .then(response => { 
              this.$emit('refetch-data')
              this.$emit('update:is-add-new-product-sidebar-active', false)
            })
            .catch(error => {
              this.$refs.refFormObserver.setErrors(error.response.data.errors)
            })
          }
          else{
            store.dispatch('products/addProduct', this.productData)
            .then(response => { 
              this.$emit('refetch-data')
              this.$emit('update:is-add-new-product-sidebar-active', false)
            })
            .catch(error => {
              this.$refs.refFormObserver.setErrors(error.response.data.errors)
            })

          }
        }
      })
    },
    resetImage(){
      this.$refs.refInputEl.reset()
      this.productData.product_image = null
    }
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-select.scss';
@import '~@resources/scss/vue/libs/quill.scss';

#add-new-product-sidebar {
  .vs__dropdown-menu {
    max-height: 200px !important;
  }

  // Quill Editor Style
  .quill-editor {
    .ql-container.ql-snow {
      border-bottom: 0 !important;
    }
  }
}
</style>

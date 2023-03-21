<template>
    <div>
    <b-modal 
        id="modal-action-products" 
        hide-backdrop 
        content-class="shadow" 
        @ok="handleAction"
    >
        <p class="my-2">
        Are you want <code> {{ productAction }} </code> product <code>{{ productData.product_name }}</code>
        </p>
    </b-modal>
    </div>
</template>
  
<script>
import { BButton } from 'bootstrap-vue'
import store from '@/store'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
    components: {   
        BButton
    },
    data() {
      return {
        message_errors : 'ERROR, please try again',
        message_successful : 'Successful',
      }
    },
    props: {
        productData: {
            type: Object,
        },
        productAction: {
            type: String,
            required: true,
        },
    },
    methods: {
        handleAction() {
            store.dispatch('products/deleteProducts', this.productData.id)
            .then(response => {
                this.alertSuccessful() 
                this.$emit('refetch-data')
            })
            .catch(error => {
                this.alertError()
            })
        },
        alertError(){
            this.$toast({
                component: ToastificationContent,
                props: {
                    title: this.message_errors,
                    icon: 'AlertTriangleIcon',
                    variant: 'danger',
                },
            })
        },
        alertSuccessful(){
            this.$toast({
                component: ToastificationContent,
                props: {
                    title: this.message_successful,
                    icon: 'AlertTriangleIcon',
                    variant: 'success',
                },
            })
        },
    }
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-select.scss';
</style>
  
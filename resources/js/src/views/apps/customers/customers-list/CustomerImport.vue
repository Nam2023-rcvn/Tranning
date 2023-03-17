<template>
    <div>
    <b-modal 
        id="modal-customer-import" 
        hide-backdrop 
        content-class="shadow" 
        @ok="handleOk"
    >
        <p class="my-2">
        Upload file cần import
        </p>

        <form ref="customerImPortForm" @submit.stop.prevent="handleSubmit">
            <b-form-group
            label="File upload"
            label-for="name-input"
            >
                <b-form-file
                    v-model="fileImport"
                    placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..."
                    accept=".csv"
                    :hidden="true"
                >
                </b-form-file>
            </b-form-group>
        </form>

    </b-modal>
    </div>
</template>

  
<script>
import { BButton, BFormFile, BFormGroup } from 'bootstrap-vue'
import store from '@/store'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
    components: {   
        BButton,
        BFormFile,
        BFormGroup
    },
    data() {
      return {
        message_successful : 'Successful',

        fileImport: null
      }
    },
    props: {
    },
    methods: {
        handleOk() {
            this.handleSubmit()
        },
        handleSubmit(){
            store.dispatch('customers/importCustomer', this.fileImport)
                .then(response => { 
                    console.log(response)

                    if(response.data.error){
                        response.data.error.map((value, key)=> {
                            this.alertError(value[0])
                        })
                    }
                    else{
                        this.alertSuccessful()
                    }
                    
                    this.$emit('refetch-data')
                })
                .catch(error => {
                    this.alertError()
                })
        },
        alertError(value){
            this.$toast({
                component: ToastificationContent,
                props: {
                    title: value ?? 'ERROR, please try again',
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
  
<template>
    <div>
    <b-modal 
        id="modal-no-backdrop" 
        hide-backdrop 
        content-class="shadow" 
        :title="[(userData.is_active === 0 && userAction === 'block') ? ('Un ' + userAction) : userAction]"
        @ok="handleActionUser"
    >
        <p class="my-2">
        Are you want <code> {{ (userData.is_active === 0 && userAction === 'block') ? ('Un ' + userAction) : userAction }} </code> user <code>{{ userData.name }}</code>
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
        userData: {
            type: Object,
        },
        userAction: {
            type: String,
            required: true,
        },
    },
    methods: {
        handleActionUser() {
            switch(this.userAction) {
            case 'block':
                store.dispatch('users/blockUser', this.userData.id)
                .then(response => { 
                    this.alertSuccessful()
                    this.$emit('refetch-data')
                })
                .catch(error => {
                    this.alertError()
                })
                break;
            case 'delete':
                store.dispatch('users/deleteUser', this.userData.id)
                .then(response => {
                    this.alertSuccessful() 
                    this.$emit('refetch-data')
                })
                .catch(error => {
                    this.alertError()
                })
                break;
            }
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
  
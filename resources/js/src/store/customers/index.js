import axios from '@axios'

export default {
  namespaced: true,
  state: {
    customers: {},
    customer: {},
  },
  getters: {},
  mutations: {
  },
  actions: {
    fetchCustomers(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/customers', { params: queryParams })
          .then(response => {
            // commit('users/GET_USERS', response.data)
            // console.log('actions')
            // console.log(response)
            resolve(response)
          })
          .catch(error => reject(error))
      })
    },
    addCustomer(ctx, customerData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/customers', customerData.data)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateCustomer(ctx, customerData) {
      return new Promise((resolve, reject) => {
        axios
          .put('/customers/' + customerData.id, customerData)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    importCustomer(ctx, file) {
      const formData = new FormData()
      formData.append('customers_file', file)

      return new Promise((resolve, reject) => {
        axios
          .post('/customers/import', formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    exportCustomer(ctx, file) {
      return new Promise((resolve, reject) => {
        axios
          .get('/customers/export', { customers_file: file })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}

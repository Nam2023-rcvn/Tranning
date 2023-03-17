import axios from '@axios'

export default {
  namespaced: true,
  state: {
    products: {},
    product: {},
  },
  getters: {},
  mutations: {
  },
  actions: {
    fetchProducts(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/products', { params: queryParams })
          .then(response => {
            resolve(response)
          })
          .catch(error => reject(error))
      })
    },
    deleteProducts(ctx, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete('/products/' + id)
          .then(response => {
            resolve(response)
          })
          .catch(error => reject(error))
      })
    },
    addProduct(ctx, productData) {
      const formData = new FormData()

      for (const [key, value] of Object.entries(productData)) {
        formData.append(key, productData[key])
      }

      return new Promise((resolve, reject) => {
        axios
          .post('/products', formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateProduct(ctx, productData) {
      const formData = new FormData()

      for (const [key, value] of Object.entries(productData)) {
        formData.append(key, productData[key])
      }

      return new Promise((resolve, reject) => {
        axios
          .post('/products/' + productData.id, formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
          })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}

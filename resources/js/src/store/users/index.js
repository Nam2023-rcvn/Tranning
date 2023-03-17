import axios from '@axios'

export default {
  namespaced: true,
  state: {
    users: {},
    user: {},
  },
  getters: {},
  mutations: {
    GET_USERS(state, val){
      // console.log('GET_USERS')
      // console.log(val)
      state.users = val
    }
  },
  actions: {
    fetchUsers(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/users', { params: queryParams })
          .then(response => {
            // commit('users/GET_USERS', response.data)
            // console.log('actions')
            // console.log(response)
            resolve(response)
          })
          .catch(error => reject(error))
      })
    },
    addUser(ctx, userData) {
      return new Promise((resolve, reject) => {
        axios
          .post('/users', userData.data)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateUser(ctx, userData) {
      return new Promise((resolve, reject) => {
        axios
          .put('/users/' + userData.id, userData.data)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    deleteUser(ctx, userId) {
      return new Promise((resolve, reject) => {
        axios
          .delete('/users/' + userId)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    blockUser(ctx, userId) {
      return new Promise((resolve, reject) => {
        axios
          .put('/users/' + userId + '/block')
          .then(response => {
            resolve(response)
          })
          .catch(error => {
            reject(error)
          })
      })
    },
  },
}

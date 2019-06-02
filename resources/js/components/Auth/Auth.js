export default function (Vue) {
    let data = {}
    Vue.auth = {
        setToken(token, expiration,isAdmin) {
            localStorage.setItem('token', token)
            localStorage.setItem('expiration', expiration)
            localStorage.setItem('isAdmin', isAdmin)
        },
        getToken() {
            var token = localStorage.getItem('token')
            var expiration = localStorage.getItem('expiration')
            var isAdmin = localStorage.getItem('isAdmin')
            if (!token || !expiration || !isAdmin)
                return null
            if (Date.now() > parseInt(expiration)) {
                this.destroyToken()
                return null
            } else {
                return token
            }
        },
        destroyToken() {
            localStorage.removeItem('token')
            localStorage.removeItem('isAdmin')
            localStorage.removeItem('expiration')
        },
        isAuthenticeted() {
            if (this.getToken()) {
                return true
            } else {
                return false
            }
        },
        setAuthicatedUser(obj) {
            data = obj;
        },
        getAuthicatedUser() {
            return data
        },
        isAdmin(){
            if(localStorage.getItem("isAdmin")==1){
                return true
            }
            return false
        }
    }
    Object.defineProperties(Vue.prototype, {
        $auth: {
            get: () => {
                return Vue.auth
            }
        }
    })
}

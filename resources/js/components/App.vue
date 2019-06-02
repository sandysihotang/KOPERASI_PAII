<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark unique-color-dark" style="background-color: #212731;">
            <a class="navbar-brand" href="#"><h2>KOPERASI IT DEL</h2></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4"
                    aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent-4" v-if="isAuth">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown" style="cursor: pointer;">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">{{ name }}
                            <img :src="avatar" class="avatar border" alt="">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-cyan"
                             aria-labelledby="navbarDropdownMenuLink-4">
                            <a class="dropdown-item" href="#" @click="logOut">Logout</a>
                            <router-link to="/profile">
                                <a class="dropdown-item" href="#">Profile</a>
                            </router-link>
                            <router-link to="/konfirmasi" v-show="isAdmin()">
                                <a class="dropdown-item" href="#">konfirmasi Akun</a>
                            </router-link>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
            <router-view v-on:ref="setAuthUser()"></router-view>
        </div>
    </div>
</template>

<script scoped>
    export default {
        data() {
            return {
                isAuth: null,
                name: null,
                avatar:null
            }
        },
        created() {
            this.isAuth = this.$auth.isAuthenticeted()
            if (this.$auth.isAuthenticeted()) {
                this.setAuthUser()
            }
        },
        methods: {
            setAuthUser() {
                axios('api/user', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.$auth.setAuthicatedUser(e)
                        this.name = this.$auth.getAuthicatedUser().data.name
                        this.avatar=e.data.image
                    })
            },
            logOut() {
                this.$swal({
                    title: 'Anda Yakin?',
                    text: "Log Out!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        this.$auth.destroyToken()
                        window.location.href = '/'
                    }
                })
            },
            isAdmin() {
                return Vue.auth.isAdmin()
            }
        }
    }

</script>
<style scoped>
    .avatar {
        vertical-align: middle;
        width: 35px;
        height: 35px;
        border-radius: 50%;
    }
</style>

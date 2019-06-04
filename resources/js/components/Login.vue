<template>
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div id="formFooter" class="fadeIn first">
                <h3>KOPERASI IT DEL</h3>
            </div>

            <!-- Login Form -->
            <div class="mt-2">
                <input v-model="form.email" type="email" id="login" class="fadeIn second"
                       placeholder="Email">
                <input v-model="form.password" type="password" id="password" class="fadeIn third"
                       placeholder="Password">
                <b-button class="fadeIn fourth" :disabled="isLoading"
                          @click="login">Login<br>
                    <b-spinner v-show="isLoading" label="Spinning"
                               style="font-size:20px;max-width:90%!important;"></b-spinner>
                </b-button>
            </div>
            <div class="row mb-3">
                <b-col md="7">
                    Sudah mempunyai akun?
                    <router-link to="/register">
                        <a href="#">Daftar</a>
                    </router-link>
                </b-col>
                <b-col md="5">
                    <router-link to="/forgotpassword">
                        <a href="#">Lupa Password</a>
                    </router-link>
                </b-col>
            </div>
        </div>

    </div>
</template>

<script>

    export default {
        data() {
            return {
                form: {
                    email: null,
                    password: null
                },
                isLoading: false
            }
        },
        methods: {
            login() {
                if (!this.form.email || !this.form.password) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: 'Isi semua form',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                this.isLoading = true
                axios.post('api/checkUser', {email: this.form.email})
                    .then(e => {
                        if (e.data.length > 0) {
                            this.$swal({
                                position: 'center',
                                type: 'error',
                                title: 'Silahkan tunggu Konfirmasi Administrator!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.isLoading = false
                        } else {
                            var data = {
                                client_id: 2,
                                client_secret: 'jSApTgDgGxXFYakZGuSPf1xmdOmEN8rjugpsnmHq',
                                grant_type: 'password',
                                username: this.form.email,
                                password: this.form.password
                            }
                            axios.post('oauth/token', data)
                                .then(e => {
                                    axios.get('api/user', {headers: {Authorization: `Bearer ${e.data.access_token}`}})
                                        .then(res => {
                                            this.$auth.setToken(e.data.access_token, e.data.expires_in + Date.now(), res.data.isAdmin)
                                            setTimeout(function () {
                                                    window.location.href = '/'
                                                    this.isLoading = false
                                                },
                                                3000
                                            )
                                            const Toast = this.$swal.mixin({
                                                toast: true,
                                                position: 'top-end',
                                                showConfirmButton: false,
                                                timer: 5000
                                            });
                                            Toast.fire({
                                                type: 'success',
                                                title: 'Login Success'
                                            })
                                        })
                                })
                                .catch(e => {
                                    this.isLoading = false
                                    this.$swal({
                                        position: 'center',
                                        type: 'error',
                                        title: 'Anda Tidak Terdaftar!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                })
                        }
                    })
            }
        }
    }
</script>
<style scoped>
    html {
        background-color: #56baed;
    }

    body {
        font-family: "Poppins", sans-serif;
        height: 100vh;
    }

    a {
        color: #92badd;
        display: inline-block;
        text-decoration: none;
        font-weight: 400;
    }

    h2 {
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        margin: 40px 8px 10px 8px;
        color: #cccccc;
    }

    /* STRUCTURE */

    .wrapper {
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        width: 100%;
        min-height: 100%;
        padding: 20px;
    }

    #formContent {
        -webkit-border-radius: 10px 10px 10px 10px;
        border-radius: 10px 10px 10px 10px;
        background: #fff;
        padding: 30px;
        width: 90%;
        max-width: 450px;
        position: relative;
        padding: 0px;
        -webkit-box-shadow: 0 30px 60px 0 rgba(0, 0, 0, 0.3);
        box-shadow: 0 30px 60px 0 rgba(0, 0, 0, 0.3);
        text-align: center;
    }

    #formFooter {
        background-color: #f6f6f6;
        border-bottom: 1px solid #dce8f1;
        padding: 25px;
        text-align: center;
        -webkit-border-radius: 0 0 10px 10px;
        border-radius: 10px 10px 0 0;
    }

    /* TABS */

    h2.inactive {
        color: #cccccc;
    }

    h2.active {
        color: #0d0d0d;
        border-bottom: 2px solid #5fbae9;
    }

    /* FORM TYPOGRAPHY*/

    button[type=button], button[type=submit], button[type=reset] {
        background-color: #56baed;
        border: none;
        color: white;
        padding: 15px 80px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        text-transform: uppercase;
        font-size: 13px;
        -webkit-box-shadow: 0 10px 30px 0 rgba(95, 186, 233, 0.4);
        box-shadow: 0 10px 30px 0 rgba(95, 186, 233, 0.4);
        -webkit-border-radius: 5px 5px 5px 5px;
        border-radius: 5px 5px 5px 5px;
        margin: 5px 20px 40px 20px;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        -ms-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    button[type=button]:hover, button[type=submit]:hover, button[type=reset]:hover {
        background-color: #39ace7;
    }

    button[type=button]:active, button[type=submit]:active, button[type=reset]:active {
        -moz-transform: scale(0.95);
        -webkit-transform: scale(0.95);
        -o-transform: scale(0.95);
        -ms-transform: scale(0.95);
        transform: scale(0.95);
    }

    input[type=email] {
        background-color: #f6f6f6;
        border: none;
        color: #0d0d0d;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 5px;
        width: 85%;
        border: 2px solid #f6f6f6;
        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -ms-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;
        -webkit-border-radius: 5px 5px 5px 5px;
        border-radius: 20px 20px 20px 20px;
    }

    input[type=email] {
        background-color: #f6f6f6;
        border: none;
        color: #0d0d0d;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 5px;
        width: 85%;
        border: 2px solid #f6f6f6;
        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -ms-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;
        -webkit-border-radius: 5px 5px 5px 5px;
        border-radius: 20px 20px 20px 20px;
    }

    input[type=password] {
        background-color: #f6f6f6;
        border: none;
        color: #0d0d0d;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 5px;
        width: 85%;
        border: 2px solid #f6f6f6;
        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -ms-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;
        -webkit-border-radius: 5px 5px 5px 5px;
        border-radius: 20px 20px 20px 20px;
    }

    input[type=password]:focus {
        background-color: #fff;
        border-bottom: 2px solid #5fbae9;
    }

    input[type=password]::placeholder {
        color: #cccccc;
    }

    input[type=email]:focus {
        background-color: #fff;
        border-bottom: 2px solid #5fbae9;
    }

    input[type=email]::placeholder {
        color: #cccccc;
    }

    /* ANIMATIONS */

    /* Simple CSS3 Fade-in-down Animation */
    .fadeInDown {
        -webkit-animation-name: fadeInDown;
        animation-name: fadeInDown;
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
    }

    @-webkit-keyframes fadeInDown {
        0% {
            opacity: 0;
            -webkit-transform: translate3d(0, -100%, 0);
            transform: translate3d(0, -100%, 0);
        }
        100% {
            opacity: 1;
            -webkit-transform: none;
            transform: none;
        }
    }

    @keyframes fadeInDown {
        0% {
            opacity: 0;
            -webkit-transform: translate3d(0, -100%, 0);
            transform: translate3d(0, -100%, 0);
        }
        100% {
            opacity: 1;
            -webkit-transform: none;
            transform: none;
        }
    }

    /* Simple CSS3 Fade-in Animation */
    @-webkit-keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @-moz-keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .fadeIn {
        opacity: 0;
        -webkit-animation: fadeIn ease-in 1;
        -moz-animation: fadeIn ease-in 1;
        animation: fadeIn ease-in 1;

        -webkit-animation-fill-mode: forwards;
        -moz-animation-fill-mode: forwards;
        animation-fill-mode: forwards;

        -webkit-animation-duration: 1s;
        -moz-animation-duration: 1s;
        animation-duration: 1s;
    }

    .fadeIn.first {
        -webkit-animation-delay: 0.4s;
        -moz-animation-delay: 0.4s;
        animation-delay: 0.4s;
    }

    .fadeIn.second {
        -webkit-animation-delay: 0.6s;
        -moz-animation-delay: 0.6s;
        animation-delay: 0.6s;
    }

    .fadeIn.third {
        -webkit-animation-delay: 0.8s;
        -moz-animation-delay: 0.8s;
        animation-delay: 0.8s;
    }

    .fadeIn.fourth {
        -webkit-animation-delay: 1s;
        -moz-animation-delay: 1s;
        animation-delay: 1s;
    }

    /* Simple CSS3 Fade-in Animation */
    .underlineHover:after {
        display: block;
        left: 0;
        bottom: -10px;
        width: 0;
        height: 2px;
        background-color: #56baed;
        content: "";
        transition: width 0.2s;
    }

    .underlineHover:hover {
        color: #0d0d0d;
    }

    .underlineHover:hover:after {
        width: 100%;
    }

    /* OTHERS */

    *:focus {
        outline: none;
    }

    #icon {
        width: 60%;
    }
</style>

<template>
    <div class="container">
        <div class="row">
            <form ref="images" @submit.prevent="saveProfile" method="post"
                  enctype="multipart/form-data">
                <div class="col-sm-3">
                    <div class="text-center">
                        <img v-if="formData.avatar!==null" :src="formData.avatar" class="img-circle img-thumbnail">
                        <img v-if="formData.avatar===null" :src="'./storage/Image/avatar_2x.png'" style="width:900px;"
                             class="img-circle img-thumbnail">
                        <h6>Upload Photo</h6>
                        <input type="file" @change="onImageChange" class="text-center center-block file-upload">
                    </div>
                    <hr>
                    <br>
                </div>
                <div class="col-sm-9">
                    <div class="tab-content">
                        <div class="tab-pane active" id="home">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label><h4>Nama</h4></label>
                                    <input type="text" class="form-control" v-model="formData.nama" placeholder="Nama"
                                           title="Enter your name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label><h4>Email</h4></label>
                                    <input type="email" class="form-control" name="email" :value="formData.email"
                                           placeholder="Email"
                                           title="enter your email." disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <br>
                                    <button class="btn btn-sm btn-success">Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                formData: {
                    avatar: null,
                    nama: null,
                    email: null,
                    id: null
                }
            }
        },
        methods: {
            onImageChange(e) {
                let image = e.target.files[0]
                let reader = new FileReader()
                reader.readAsDataURL(image)
                reader.onload = (e) => {
                    this.formData.avatar = e.target.result
                }
            },
            getUser() {
                axios('api/user', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.formData.nama = e.data.name
                        this.formData.email = e.data.email
                        this.formData.avatar = e.data.image
                        this.formData.id = e.data.id
                    })
            },
            saveProfile() {
                axios.post('api/profile', this.formData)
                    .then(e => {
                        this.$emit('ref')
                        const Toast = this.$swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        Toast.fire({
                            type: 'success',
                            title: 'Profile Change'
                        })
                        this.getUser()
                    })
                    .catch(e => {
                        console.log(e)
                    })
            }
        },
        created() {
            this.getUser()
        }
    }
</script>

<style scoped>

</style>

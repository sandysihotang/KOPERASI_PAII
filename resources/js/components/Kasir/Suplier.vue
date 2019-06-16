<template>
    <div class="container">
        <div class="row" v-show="!show">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <b-form @submit.prevent="tambahProduct">
                            <b-form-group>
                                <label>Nama Suplier</label>
                                <b-input-group>
                                    <b-form-select
                                        v-model="suplier"
                                        :options="options"></b-form-select>
                                </b-input-group>
                            </b-form-group>
                            <button class="float-left btn btn-primary" @click="show=true"><i
                                class="fa fa-plus"></i>Tambah Suplier
                            </button>
                            <b-button class="float-right" variant="success" type="submit"><i
                                class="fa fa-plus"></i>Tambah
                            </b-button>
                        </b-form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-show="show">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form @submit.prevent="addSuplier()">
                            <b-form-group>
                                <label>Nama Suplier</label>
                                <b-input-group>
                                    <b-input type="text" v-model="form.nama" placeholder="Nama Suplier"></b-input>
                                </b-input-group>
                            </b-form-group>
                            <b-form-group>
                                <label>No Telepon Suplier</label>
                                <b-input-group>
                                    <b-input type="text" v-model="form.telp" placeholder="No Telepon Suplier"></b-input>
                                </b-input-group>
                            </b-form-group>
                            <b-form-group>
                                <label>Alamat Suplier</label>
                                <b-input-group>
                                    <b-input type="text" v-model="form.alamat" placeholder="Alamat Suplier"></b-input>
                                </b-input-group>
                            </b-form-group>
                            <button class="float-left btn btn-primary" @click="show=false"><i
                                class="fa fa-plus"></i>Back
                            </button>
                            <b-button class="float-right" variant="success" type="submit"><i
                                class="fa fa-plus"></i>Tambah
                            </b-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                show: false,
                suplier: null,
                options: [{text: 'Select one', value: null}],
                form:{
                    nama:null,
                    telp:null,
                    alamat:null
                }
            }
        },
        methods: {
            getSuplier() {
                this.options = [{text: 'Select one', value: null}]
                axios.get('api/getSuplier', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        for (var i = 0; i < e.data.length; i++) {
                            this.options.push({text: e.data[i].nama_suplier, value: e.data[i].id})
                        }
                    })
            },
            tambahProduct(){
                if(this.suplier===null){
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        width:300,
                        title: 'Pilih Suplier!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                axios.get('api/tambahSuplier/'+this.suplier,{headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e=>{
                        console.log(e)
                    })
            },
            addSuplier() {
                if(this.form.nama===null || this.form.telp===null || this.form.alamat===null){
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        width:300,
                        title: 'Isi Semua Form',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                axios.post('api/tambahSuplier',this.form,{headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e=>{
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            width:300,
                            title: 'Berhasil Menambah Suplier',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.show=false
                        this.getSuplier()
                    })
            }
        },
        created() {
            this.getSuplier()
        }
    }
</script>

<style scoped>

</style>

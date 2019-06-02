<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <b-form v-show="show" @submit.prevent="addProduct">
                            <b-form-group>
                                <label>Kode Barang</label>
                                <b-input-group>
                                    <input type="text" class="form-control" required v-model="formBaru.kode"
                                           placeholder="Scan atau Ketik Manual"/>
                                </b-input-group>
                            </b-form-group>
                            <b-button @click="addProduct" class="float-right" variant="success"><i
                                class="fa fa-plus"></i>Tambahkan
                            </b-button>
                        </b-form>
                    </div>
                </div>
            </div>
        </div>
        <b-container>
            <div v-show="!show && !creteq" class="container">
                <center>Tambah Produk Untuk <b>{{ nama_barang }}</b></center>
                <b-form @submit.prevent="addProductTambah">
                    <b-form-group>
                        <label>Harga</label>
                        <b-input-group>
                            <CurrencyInput
                                class="form-control"
                                v-model="form.harga"
                                :currency="currency_input.currency"
                                :locale="currency_input.locale"
                                :validate-on-input="true"
                            />
                        </b-input-group>
                    </b-form-group>
                    <b-form-group>
                        <label>Jumlah</label>
                        <b-input-group>
                            <input type="number" v-model="form.jumlah" class="form-control" min="1"
                                   placeholder="Jumlah barang" required>
                        </b-input-group>
                    </b-form-group>
                    <button type="submit" class="btn btn-success pull-right"><i class="fa fa-plus"></i>Tambahkan
                    </button>
                </b-form>
            </div>
            <div v-show="!show && creteq">
                <b-container>
                    <b-form @submit.prevent="addProduct">
                        <b-row>
                            <b-col>
                                <b-form-group label="Nama Produk">
                                    <b-form-input
                                        v-model="formBaru.nama"
                                        placeholder="Masukkan Nama Produk"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                                <b-form-group label="Kategori">
                                    <b-form-select
                                        v-model="formBaru.kategori"
                                        :options="kategories">
                                    </b-form-select>
                                </b-form-group>
                            </b-col>
                            <b-col>
                                <b-form-group label="Harga">
                                    <b-form-input
                                        type="number"
                                        min="1"
                                        v-model="formBaru.harga"
                                        placeholder="Masukkan Harga"
                                        required></b-form-input>
                                </b-form-group>
                                <b-form-group label="Jumlah Stock">
                                    <b-form-input
                                        v-model="formBaru.jumlah"
                                        type="number"
                                        min="1"
                                        placeholder="Jumlah Stock"
                                        required
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <div class="float-right">
                            <b-button variant="success" @click="addProductBaru"><i class="fa fa-plus"></i> Simpan
                            </b-button>
                            <b-button variant="Danger" class="ml-2" @click="resetData">Reset</b-button>
                        </div>
                    </b-form>
                </b-container>
            </div>
        </b-container>
    </div>
</template>

<script>
    import {CurrencyInput} from 'vue-currency-input'

    export default {
        components: {CurrencyInput},
        data() {
            return {
                currency_input: {
                    locale: 'id',
                    currency: 'IDR',
                },
                form: {
                    jumlah: null,
                    id: null,
                    harga: 1
                },
                kategories: [{text: 'Select one', value: null}],
                show: true,
                creteq: false,
                nama_barang: null,
                formBaru: {
                    nama: null,
                    harga: null,
                    jumlah: null,
                    kategori: null,
                    kode: null
                }
            }
        },
        created() {
            this.getKategori()
        },
        methods: {
            addProduct() {
                if (this.formBaru.kode === null) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: 'Isi form Kode barang',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                axios.get('api/getCode/' + this.formBaru.kode, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        if (e.data.length == 0) {
                            this.show = false
                            this.creteq = true
                        } else {
                            this.form.id = e.data[0].id
                            this.nama_barang = e.data[0].nama
                            this.show = false
                            this.creteq = false
                        }
                    })
            },
            addProductTambah() {
                if (this.form.jumlah === null || this.form.id === null || this.form.harga === 0) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: 'Isi Semua Form',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                axios.post('api/addProductTambah', this.form, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                this.$emit('refresh')
                this.$nextTick(() => {
                    this.resetData()
                })
                this.forHargaBarang = false
                this.$swal({
                    position: 'center',
                    type: 'success',
                    title: 'Produk Berhasil Ditambah',
                    showConfirmButton: false,
                    timer: 1500
                })
            },
            addProductBaru() {
                if (this.formBaru.nama === null || this.formBaru.harga === null || this.formBaru.jumlah === null || this.formBaru.kategori === null || this.formBaru.kode === null) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: 'Isi Semua Form',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                axios.post('api/addProductBaru', this.formBaru, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                this.$emit('refresh')
                this.$nextTick(() => {
                    this.resetData()
                })
                this.$swal({
                    position: 'center',
                    type: 'success',
                    title: 'Produk Berhasil Ditambah',
                    showConfirmButton: false,
                    timer: 1500
                })
            },
            getKategori() {
                this.kategories = [{text: 'Select one', value: null}]
                axios.get('api/getKategori', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        for (var i = 0; i < e.data.length; i++) {
                            this.kategories.push({text: e.data[i].nama_kategori, value: e.data[i].id})
                        }
                    })
            },
            resetData() {
                this.formBaru.nama = null
                this.formBaru.harga = null
                this.formBaru.jumlah = null
                this.formBaru.kategori = null
                this.formBaru.kode = null
                this.form.jumlah = null
                this.creteq = false
                this.show = true
                this.form.id = null
                this.form.harga = null
            }
        }
    }
</script>

<style scoped>

</style>

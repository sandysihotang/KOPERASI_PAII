<template>
    <div class="container">
        <h1>Menu Transaksi</h1>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control" v-model="form_transaksi_baru.no_transaksi" disabled
                           placeholder="No Transaksi">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" v-model="form_transaksi_baru.tanggal" placeholder="Tanggal"
                           disabled>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" v-model="form_transaksi_baru.jam" placeholder="Jam"
                           disabled>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" v-model="form_transaksi_baru.user" placeholder="User"
                           disabled>
                </div>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-5">
                <b-form @submit.prevent="addProdukBaru">
                    <div class="form-group">
                        <input type="text" placeholder="Scan Barcode atau Ketik Manual"
                               v-model="form_produk_tambah.barcode" class="form-control">
                    </div>
                </b-form>
                <div class="form-group">
                    <b-row>
                        <b-col>
                            <input type="text" placeholder="Nama Barang" disabled class="form-control"
                                   v-model="form_produk_tambah.nama">
                        </b-col>
                        <b-col>
                            <input type="text" placeholder="Jumlah Stock" disabled class="form-control"
                                   :value="form_produk_tambah.jumlah_sekarang+' Buah'">
                        </b-col>
                    </b-row>
                </div>
                <div class="form-group">
                    <input type="number" min="1" placeholder="Jumlah" class="form-control"
                           v-model="form_produk_tambah.jumlah">
                </div>
                <button type="submit" class="btn btn-primary btn-sm float-right"
                        :disabled="form_produk_tambah.barcode==null || form_produk_tambah.nama==null"
                        @click="addProdukTambah">Tambahkan
                </button>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-9">
                <b-table
                    responsive
                    small
                    :items="items"
                    :fields="fields"
                    striped>
                    <template slot="harga_jual" slot-scope="row">
                        {{ row.item.harga_jual | currency }}
                    </template>
                    <template slot="total" slot-scope="row">
                        {{ row.item.harga_jual*row.item.jumlah | currency }}
                    </template>
                    <template slot="Option" slot-scope="row">
                        <button class="btn btn-light btn-outline-secondary btn-sm" @click="deleteProduk(row.item)">
                            <font-awesome-icon icon="trash"/>
                        </button>
                    </template>
                </b-table>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label><b>Total</b></label>
                    <input type="text" class="form-control form-control-sm" disabled :value="totalharga | currency">
                </div>
                <div class="form-group">
                    <label><b>Uang</b></label>
                    <CurrencyInput
                        class="form-control"
                        v-model="uang"
                        :currency="currency_input.currency"
                        :locale="currency_input.locale"
                        :validate-on-input="true"
                    />
                </div>
                <div class="form-group">
                    <label><b>Kembalian</b></label>
                    <input type="text"
                           :class="'form-control form-control-lg'+(uang-totalharga<0 || totalharga==0?' is-invalid':' is-valid')"
                           disabled :value="uang-totalharga | currency">
                </div>
            </div>
        </div>
        <div class="float-right">
            <button type="submit" class=" btn btn-primary btn-sm" :disabled="uang-totalharga<0 || totalharga==0"
                    @click="selesaiProcess">
                Selesai
            </button>
        </div>
    </div>
</template>

<script>
    import {CurrencyInput} from 'vue-currency-input'
    import moment from 'moment'

    export default {
        components: {CurrencyInput},
        data() {
            return {
                currency_input: {
                    locale: 'id',
                    currency: 'IDR',
                },
                fields: [
                    {
                        key: "code_produk",
                        sortable: true, label: "Kode Barang"
                    },
                    {
                        key: "nama",
                        sortable: true, label: "Nama Barang"
                    },
                    {
                        key: "harga_jual",
                        sortable: true, label: "Harga Satuan"
                    },
                    {
                        key: "jumlah",
                        sortable: true, label: "Jumlah Barang"
                    },
                    {
                        key: "total",
                        sortable: true, label: "Total Harga"
                    },
                    {
                        key: "Option",
                        sortable: true, label: "Opsi"
                    }
                ],
                items: [],
                state: null,
                uang: 0,
                form_transaksi_baru: {
                    no_transaksi: null,
                    tanggal: null,
                    jam: null,
                    user: null,
                    user_id: null
                },
                form_produk_tambah: {
                    jumlah_sekarang: 0,
                    barcode: null,
                    nama: null,
                    jumlah: 1,
                    id: null
                },
                totalharga: 0,
            }
        },
        methods: {
            fetchUser() {
                axios('api/user', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.form_transaksi_baru.user = e.data.name
                        this.form_transaksi_baru.user_id = e.data.id
                    })
            },
            formTransaksiBaru() {
                this.fetchUser()
                moment.lang('id')
                this.form_transaksi_baru.tanggal = this.dateNow()
                this.form_transaksi_baru.jam = this.timeNow()
                axios.get('api/kodeTransaksi', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.form_transaksi_baru.no_transaksi = 'SKU-DEL-' + e.data
                        this.fetchProduk()
                        this.totalHarga()
                    })
            },
            dateNow() {
                return moment().format('dddd, Do MMMM YYYY')
            },
            timeNow() {
                return moment().format('HH:mm')
            },
            addProdukBaru() {
                axios.get('api/getBarang/' + this.form_produk_tambah.barcode, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        if (e.data.length !== 0) {
                            this.form_produk_tambah.nama = e.data.nama
                            this.form_produk_tambah.jumlah_sekarang = e.data.jumlah_barang
                            this.form_produk_tambah.id = e.data.id
                            this.state = e.data.from
                        } else {
                            this.$swal({
                                position: 'center',
                                type: 'error',
                                title: 'Kode Barang Tidak Ditemukan',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    })
            },
            addProdukTambah() {
                if (this.form_produk_tambah.jumlah_sekarang < this.form_produk_tambah.jumlah || this.form_produk_tambah.jumlah < 1) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: 'Jumlah Barang harus valid!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                if (this.state === 1) {
                    axios.get('api/checkHargaJual/' + this.form_produk_tambah.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                        .then(e => {
                            if (e.data.length != 0) {
                                axios.post('api/tambahProdukTransaksi/' + this.state, {
                                    id_produk: this.form_produk_tambah.id,
                                    jumlah: this.form_produk_tambah.jumlah,
                                    kode: this.form_transaksi_baru.no_transaksi,
                                    user_id: this.form_transaksi_baru.user_id
                                }, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                                    .then(e => {
                                        this.form_produk_tambah = {
                                            barcode: null,
                                            nama: null,
                                            jumlah: 1,
                                            id: null,
                                            jumlah_sekarang: 0
                                        }
                                        this.fetchProduk()
                                        this.totalHarga()
                                        const Toast = this.$swal.mixin({
                                            toast: true,
                                            position: 'top-end',
                                            showConfirmButton: false,
                                            timer: 3000
                                        });
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Produk Berhasil Ditambah'
                                        })
                                    })
                            } else {
                                this.$swal({
                                    position: 'center',
                                    type: 'error',
                                    title: 'Tentukan Harga Jual Produk',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }
                        })
                }else{
                    axios.post('api/tambahProdukTransaksi/' + this.state, {
                        id_produk: this.form_produk_tambah.id,
                        jumlah: this.form_produk_tambah.jumlah,
                        kode: this.form_transaksi_baru.no_transaksi,
                        user_id: this.form_transaksi_baru.user_id
                    }, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                        .then(e => {
                            this.form_produk_tambah = {
                                barcode: null,
                                nama: null,
                                jumlah: 1,
                                id: null,
                                jumlah_sekarang: 0
                            }
                            this.fetchProduk()
                            this.totalHarga()
                            const Toast = this.$swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            Toast.fire({
                                type: 'success',
                                title: 'Produk Berhasil Ditambah'
                            })
                        })
                }
            },
            fetchProduk() {

                axios.get('api/produkTransaksi/' + this.form_transaksi_baru.no_transaksi, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.items = data))
            },
            totalHarga() {
                axios.get('api/produkTransaksi/' + this.form_transaksi_baru.no_transaksi, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.totalharga = 0
                        for (var i = 0; i < e.data.length; i++) {
                            this.totalharga += (e.data[i].harga_jual * e.data[i].jumlah)
                        }
                    })
            },
            deleteProduk(row) {
                axios.delete('api/deleteProduk/' + row.id+'/'+row.state, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        const Toast = this.$swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            type: 'success',
                            title: 'Deleted'
                        })
                        this.fetchProduk()
                        this.totalHarga()
                    })
            },
            selesaiProcess() {
                axios.post('api/selesaiTransaksi/' + this.form_transaksi_baru.no_transaksi, {total: this.totalharga}, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        const Toast = this.$swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            type: 'success',
                            title: 'Success'
                        })
                        this.uang = 0
                        this.formTransaksiBaru();
                    })
            }
        },
        created() {
            this.formTransaksiBaru()
        }
    }
</script>

<style scoped>

</style>

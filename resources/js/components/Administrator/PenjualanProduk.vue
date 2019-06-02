<template>
    <div class="container">
        <b-breadcrumb :items="items"></b-breadcrumb>
        <b-container style="background-color: #f8f9fa;">
            <h2 align="center">Detail Penjualan</h2>
            <b-row class="alert">
                <b-table
                    responsive
                    :items="allPenjualan"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage">
                    <template slot="kode_transaksi" slot-scope="row">
                        <b-row>
                            <b-col>{{ row.item.kode_transaksi }}</b-col>
                            <b-col>
                                <router-link :to="'/laporan/penjualanProduk/detail/'+row.item.id">
                                    <button class="btn btn-light btn-outline-secondary btn-sm" title="Lihat Detail">
                                        <font-awesome-icon icon="eye"/>
                                    </button>
                                </router-link>
                            </b-col>
                        </b-row>
                    </template>
                </b-table>
            </b-row>
            <b-row>
                <b-col md="2">
                    <b-dropdown :text="textOption" variant="primary" class="m-2">
                        <b-dropdown-item href="#" @click="textOption='Harian',statusWaktu=1">Harian</b-dropdown-item>
                        <b-dropdown-item href="#" @click="textOption='Bulanan',statusWaktu=2">Bulanan</b-dropdown-item>
                        <b-dropdown-item href="#" @click="textOption='Tahunan',statusWaktu=3">Tahunan</b-dropdown-item>
                    </b-dropdown>
                </b-col>
                <b-col md="10" v-if="statusWaktu===1">
                    <b-container>
                        <div class="row">
                            <div class="col-md-1">
                                Pilih tanggal:
                            </div>
                            <div class="col-md-2">
                                <input type="number" v-model="harian.hariMulai" class="form-control" min="1" max="31">
                            </div>
                            <div class="col-md-1">
                                Sampai tanggal:
                            </div>
                            <div class="col-md-2">
                                <input type="number" v-model="harian.hariSampai" class="form-control" min="1" max="31">
                            </div>
                            <div class="col-md-1">
                                Bulan:
                            </div>
                            <div class="col-md-2">
                                <input type="number" v-model="harian.Bulan" class="form-control" min="1" max="12">
                            </div>
                            <div class="col-md-1">
                                Tahun:
                            </div>
                            <div class="col-md-2">
                                <input type="number" v-model="harian.tahun" class="form-control" min="1">
                            </div>
                        </div>
                    </b-container>
                </b-col>
                <b-col md="10" v-if="statusWaktu===2">
                    <b-container>
                        <div class="row">
                            <div class="col-md-1">
                                Bulan:
                            </div>
                            <div class="col-md-2">
                                <input type="number" v-model="bulanan.bulanMulai" class="form-control" min="1" max="12">
                            </div>
                            <div class="col-md-1">
                                Sampai Bulan:
                            </div>
                            <div class="col-md-2">
                                <input type="number" v-model="bulanan.bulanSampai" class="form-control" min="1"
                                       max="12">
                            </div>

                            <div class="col-md-1">
                                Tahun:
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" v-model="bulanan.tahun" min="1">
                            </div>
                        </div>
                    </b-container>
                </b-col>
                <b-col md="10" v-if="statusWaktu===3">
                    <b-container>
                        <div class="row">
                            <div class="col-md-1">
                                Tahun:
                            </div>
                            <div class="col-md-2">
                                <input type="number" v-model="tahunan" class="form-control" min="1">
                            </div>
                        </div>
                    </b-container>
                </b-col>

            </b-row>
            <b-row>
                <b-col>
                    <button class="btn btn-success m-2" @click="downloadFileExcel">
                        Export Excel
                    </button>
                </b-col>
            </b-row>
            <b-row>
                <b-pagination
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    aria-controls="mytable">
                </b-pagination>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    import BBreadcrumb from "bootstrap-vue/src/components/breadcrumb/breadcrumb";
    import moment from 'moment'

    export default {
        components: {BBreadcrumb},
        data() {
            return {
                harian: {
                    hariMulai: null,
                    hariSampai: null,
                    Bulan: null,
                    tahun: null
                },
                bulanan: {
                    bulanMulai: null,
                    bulanSampai: null,
                    tahun: null
                },
                tahunan: null,
                statusWaktu: null,
                textOption: "Opsi",
                items: [
                    {
                        text: 'Dashboard',
                        to: {path: '/dashboard'}
                    },
                    {
                        text: 'Penjualan Produk',
                        active: true
                    }
                ],
                allPenjualan: [],
                perPage: 5,
                currentPage: 1,
                fields: [

                    {
                        key: "kode_transaksi",
                        sortable: true,
                        label: "Kode Transaksi",
                    },
                    {
                        key: "user.name",
                        sortable: true,
                        label: "Nama Kasir",
                    },
                    {
                        key: "updated_at",
                        sortable: true,
                        label: "Tanggal Transaksi",
                        formatter: value => {
                            moment.lang('id')
                            return moment(value).format('dddd, Do MMMM YYYY');
                        }
                    }
                ]
            }
        },
        methods: {
            fetchData() {
                axios.get('api/getLaporanPenjualan', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.allPenjualan = data))
            },
            getNameFile(file) {
                var n = file.split('/', 3)
                return n[2]
            },
            downloadFileExcel() {
                if (this.statusWaktu == null) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: 'Silahkan Pilih Waktu',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                } else if (this.statusWaktu === 1) {
                    if (this.harian.hariMulai == null || this.harian.hariSampai == null || this.harian.Bulan == null || this.harian.tahun == null) {
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            title: 'Silahkan Pilih waktu export',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        return
                    }

                    axios.post('api/downloadPenjualanExcel', {
                        id: this.statusWaktu,
                        hariMulai: this.harian.hariMulai,
                        hariSampai: this.harian.hariSampai,
                        bulan: this.harian.Bulan,
                        tahun: this.harian.tahun
                    }, {
                        headers: {Authorization: `Bearer ${this.$auth.getToken()}`},
                        responseType: 'arraybuffer'
                    })
                        .then(e => {
                            this.downloadFile(e, 'Penjualan' + Date.now())
                        })
                } else if (this.statusWaktu === 2) {
                    if (this.bulanan.bulanMulai == null || this.bulanan.bulanSampai == null || this.bulanan.tahun == null) {
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            title: 'Silahkan Pilih waktu export',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        return
                    }
                    axios.post('api/downloadPenjualanExcel', {
                        id: this.statusWaktu,
                        bulanMulai: this.bulanan.bulanMulai,
                        bulanSampai: this.bulanan.bulanSampai,
                        tahun: this.bulanan.tahun
                    }, {
                        headers: {Authorization: `Bearer ${this.$auth.getToken()}`},
                        responseType: 'arraybuffer'
                    })
                        .then(e => {
                            this.downloadFile(e, 'Penjualan' + Date.now())
                        })
                } else if (this.statusWaktu === 3) {
                    if (this.tahunan == null) {
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            title: 'Silahkan Pilih waktu export',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        return
                    }

                    axios.post('api/downloadPenjualanExcel', {
                        id: this.statusWaktu,
                        tahun: this.tahunan
                    }, {
                        headers: {Authorization: `Bearer ${this.$auth.getToken()}`},
                        responseType: 'arraybuffer'
                    })
                        .then(e => {
                            this.downloadFile(e, 'Penjualan' + Date.now())
                        })
                }
            },
            downloadFile(response, filename) {
                // It is necessary to create a new blob object with mime-type explicitly set
                // otherwise only Chrome works like it should
                var newBlob = new Blob([response.data], {type: 'application/excel'})

                // IE doesn't allow using a blob object directly as link href
                // instead it is necessary to use msSaveOrOpenBlob
                if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                    window.navigator.msSaveOrOpenBlob(newBlob)
                    return
                }

                // For other browsers:
                // Create a link pointing to the ObjectURL containing the blob.
                const data = window.URL.createObjectURL(newBlob)
                var link = document.createElement('a')
                link.href = data
                link.download = filename + '.xlsx'
                link.click()
                setTimeout(function () {
                    // For Firefox it is necessary to delay revoking the ObjectURL
                    window.URL.revokeObjectURL(data)
                }, 100)
            }
        },
        created() {
            this.fetchData()
        },
        computed: {
            rows() {
                return this.allPenjualan.length
            }
        }
    }
</script>

<style scoped>

</style>

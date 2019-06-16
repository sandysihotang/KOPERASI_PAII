<template>
    <div class="container">
        <b-breadcrumb :items="items"></b-breadcrumb>
        <b-container>
            <b-row class="alert" style="background-color: #f8f9fa;">
                <b-col>
                    <p><i>Nama Penitip: </i><b>{{ name}}</b></p>
                </b-col>
                <b-button v-b-modal.modal-2 variant="primary">
                    <font-awesome-icon icon="download"/>
                    Ambil Uang Titipan
                </b-button>
                <b-button v-b-modal.modal-1 class="btn btn-success">
                    <font-awesome-icon icon="plus-circle"/>
                    Tambah
                </b-button>
            </b-row>
            <b-row class="alert" style="background-color: #f8f9fa;">
                <b-table
                    id="mytable"
                    responsive
                    :items="detailPemasukan"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage">
                    <template slot="harga_barang" slot-scope="row">
                        {{ row.item.harga_barang | currency }}
                    </template>
                    <template slot="total" slot-scope="row">
                        {{ row.item.harga_barang*row.item.jumlah_barang | currency }}
                    </template>
                    <template slot="total_terjual" slot-scope="row">
                        {{ row.item.harga_barang*row.item.all_penjualan_titipan | currency }}
                    </template>
                    <template slot="code_barang" slot-scope="row">
                        <barcode v-bind:value="row.item.code_barang" :options="options">
                            Show this if the rendering fails.
                        </barcode>
                    </template>
                </b-table>
            </b-row>
            <b-row>
                <b-pagination
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    aria-controls="mytable"
                >
                </b-pagination>
            </b-row>
        </b-container>
        <b-modal id="modal-1" ref="modal1" title="Tambah Produk">
            <form_penambahan_titipan v-on:refresh="refreshData" ref="form_input"></form_penambahan_titipan>
        </b-modal>
        <b-modal id="modal-2" ref="modal1" title="Tambah Produk">
            <b-container>
                <b-row>
                    <b-form-group class="col-md-12" label="Jumlah Uang">
                        <input type="text" class="form-control" :value="hargaTitipan | currency" disabled
                               placeholder="Jumlah Uang">
                    </b-form-group>
                </b-row>
                <b-row>
                    <b-form-group class="col-md-12" label="Potongan(%)">
                        <input type="number" class="form-control" min="1" v-model="pemotongan">
                    </b-form-group>
                </b-row>
                <b-row>
                    <b-form-group class="col-md-12" label="Uang diambil Setelah Potongan">
                        <input type="text" class="form-control"
                               :value="hargaTitipan-(hargaTitipan*pemotongan/100) | currency" disabled
                               placeholder="SetelahPemotongan">
                    </b-form-group>
                </b-row>
                <b-button class="float-right" variant="success" @click="ambilTitipan()">Ambil Titipan</b-button>
            </b-container>
        </b-modal>
    </div>
</template>

<script>
    import barcode from 'vue-barcode'

    export default {
        components: {
            'barcode': barcode
        },
        data() {
            return {
                options: {
                    lineColor: '#ff7069',
                    fontSize: 32,
                    font: 'Courier',
                    width: 3,
                    height: 20,
                    marginBottom: 60,
                    format: 'MSI',
                    background: '#ccffff'
                },
                perPage: 10,
                currentPage: 1,
                id: this.$route.params.id,
                items: [
                    {
                        text: 'Dashboard',
                        to: {
                            path: '/kasir'
                        }
                    },
                    {
                        text: 'Kelola Titipan',
                        to: {
                            path: '/Kelolatitipan'
                        }
                    },
                    {
                        text: 'Detail Titipan',
                        active: true
                    }

                ],
                detailPemasukan: [],
                name: null,
                fields: [
                    {
                        key: 'nama_barang',
                        sortable: true,
                        label: "Nama Barang",
                    },
                    {
                        key: 'code_barang',
                        sortable: true,
                        label: "Kode Produk",
                    },
                    {
                        key: 'harga_barang',
                        sortable: true,
                        label: "Harga Barang",
                    },
                    {
                        key: 'jumlah_barang',
                        sortable: true,
                        label: "Saat Ini",
                    },
                    {
                        key: 'total',
                        sortable: true,
                        label: "Total",
                    },
                    {
                        key: 'all_penjualan_titipan',
                        sortable: true,
                        label: "Terjual",
                    },
                    {
                        key: 'total_terjual',
                        sortable: true,
                        label: "Total",
                    }
                ],
                hargaTitipan: 0,
                pemotongan: 1
            }
        },
        methods: {
            ambilTitipan() {
                axios.post('api/ambilTitipan/' + this.id, {potongan: this.pemotongan}, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            title: 'Barang Titipan Berhasil Diambil',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.$router.push('/Kelolatitipan')
                    })
            },
            fetchHargaTotal() {
                axios.get('api/ambilHargaTitipan/' + this.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.hargaTitipan = e.data.total
                    })
            },
            fetchDataById() {
                axios.get('api/getDetailPenitipan/' + this.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.detailPemasukan = e.data
                        for (var k in this.detailPemasukan) {
                            var sum = 0
                            for (var i in this.detailPemasukan[k].all_penjualan_titipan) {
                                sum += this.detailPemasukan[k].all_penjualan_titipan[i].jumlah
                            }
                            this.detailPemasukan[k].all_penjualan_titipan = sum
                        }
                    })
            },
            getDetailVendor() {
                axios.get('api/getDetailUserPenitip/' + this.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.name = data.nama_owner))
            },
            refreshData() {
                this.fetchDataById()
                this.getDetailVendor()
                this.fetchHargaTotal()
                this.$nextTick(() => {
                    this.$refs.modal1.hide()
                })
            },
            getname(name) {
                return name
            }
        },
        created() {
            this.refreshData()
        },
        computed: {
            rows() {
                return this.detailPemasukan.length
            }
        }
    }
</script>
<style scoped>
</style>

<template>
    <div class="container">
        <b-breadcrumb :items="items"></b-breadcrumb>
        <b-container>
            <b-row class="alert" style="background-color: #f8f9fa;">
                <b-col>
                    <p><i>Nama Vendor: </i><b>{{ detailVendor.nama_vendor}}</b></p>
                </b-col>
                <b-col>
                    <p><i>No Telp Vendor: </i><b>{{ detailVendor.no_telepon}}</b></p>
                </b-col>
                <b-col>
                    <p><i>Penerima Produk: </i><b>{{ name}}</b></p>
                </b-col>
            </b-row>
            <b-row class="alert" style="background-color: #f8f9fa;">
                <b-table
                    id="mytable"
                    responsive
                    :items="detailPemasukan"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage">
                    <template slot="harga" slot-scope="row">
                        {{ row.item.harga | currency }}
                    </template>
                    <template slot="total" slot-scope="row">
                        {{ row.item.harga*row.item.jumlah | currency }}
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
    </div>
</template>

<script>

    export default {
        data() {
            return {
                perPage: 10,
                currentPage: 1,
                id: this.$route.params.id,
                items: [
                    {
                        text: 'Dashboard',
                        to: {path: '/dashboard'}
                    },
                    {
                        text: 'Laporan',
                        to: {path: '/laporan/pemasukan'}
                    },
                    {
                        text: 'Detail Laporan',
                        active: true
                    }
                ],
                detailVendor: [],
                detailPemasukan: [],
                name: null,
                fields: [
                    {
                        key: 'get_source.nama',
                        sortable: true,
                        label: "Nama Produk",
                    },
                    {
                        key: 'harga',
                        sortable: true,
                        label: "Harga Produk",
                    },
                    {
                        key: 'jumlah',
                        sortable: true,
                        label: "Produk Masuk",
                    }
                    ,
                    {
                        key: 'total',
                        sortable: true,
                        label: "Total Harga",
                    }
                ]
            }
        },
        methods: {
            fetchDataById() {
                axios.get('api/getDetailLaporan/' + this.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.detailPemasukan = data))
            },
            getDetailVendor() {
                axios.get('api/getDetailVendor/' + this.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.detailVendor = data.data[0], this.name = data.data[0].penerima.name))
            },
            refreshData() {
                this.fetchDataById()
                this.getDetailVendor()
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

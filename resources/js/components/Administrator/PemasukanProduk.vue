<template>
    <div class="container">
        <b-breadcrumb :items="items"></b-breadcrumb>
        <b-container>
            <h2 align="center">Detail Pemasukkan</h2>
            <b-row class="alert" style="background-color: #f8f9fa;">
                <b-table
                    responsive
                    :items="allPemasukan"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage">
                    <template slot="aksi" slot-scope="row">
                        <router-link :to="'/laporan/pemasukanProduk/detail/'+row.item.id">
                            <button class="btn btn-light btn-outline-secondary btn-sm" title="Lihat Detail">
                                <font-awesome-icon icon="eye"/>
                            </button>
                        </router-link>
                        <button @click="downloadFileExcel(row.item.id)"
                                class="btn btn-light btn-outline-secondary btn-sm" title="Download File">
                            <font-awesome-icon icon="file-download"/>
                        </button>
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
    import BBreadcrumb from "bootstrap-vue/src/components/breadcrumb/breadcrumb";
    import moment from 'moment'

    export default {
        components: {BBreadcrumb},
        data() {
            return {
                items: [
                    {
                        text: 'Dashboard',
                        to: {path: '/dashboard'}
                    },
                    {
                        text: 'Pemasukan Produk',
                        active: true
                    }
                ],
                allPemasukan: [],
                perPage: 5,
                currentPage: 1,
                fields: [
                    {
                        key: "nama_vendor",
                        sortable: true,
                        label: "Nama Vendor",
                    },
                    {
                        key: "no_telepon",
                        sortable: true,
                        label: "No Telepon",
                    },
                    {
                        key: "updated_at",
                        sortable: true,
                        label: "Tanggal Masuk",
                        formatter: value => {
                            moment.lang('id')
                            return moment(value).format('dddd, Do MMMM YYYY');
                        }
                    },
                    {
                        key: "aksi",
                        label: "Aksi",
                    }
                ]
            }
        },
        methods: {
            fetchData() {
                axios.get('api/getLaporanPemasukan', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.allPemasukan = data))
            },
            getNameFile(file) {
                var n = file.split('/', 3)
                return n[2]
            },
            downloadFileExcel(id) {
                axios.get('api/downloadExcel/' + id, {
                    headers: {Authorization: `Bearer ${this.$auth.getToken()}`},
                    responseType: 'arraybuffer'
                })
                    .then(e => {
                        this.downloadFile(e, 'Pemasukan'+Date.now())
                    })
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
                return this.allPemasukan.length
            }
        }
    }
</script>

<style scoped>

</style>

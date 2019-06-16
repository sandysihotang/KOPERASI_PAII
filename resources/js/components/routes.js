import Vue from 'vue'
import VueRoute from 'vue-router'
import Login from './Login.vue'
import Register from './Register.vue'
import Dashboard from "./Administrator/Dashboard.vue";
import Inventory from "./Administrator/produk.vue";
import Form from "./Administrator/form_produk.vue";
import Pemasukan from "./Administrator/PemasukanBarang.vue";
import LaporonPemasukanProduk from './Administrator/PemasukanProduk.vue';
import DetailPemasukan from './Administrator/DetailLaporanPemasukan.vue';
import DetailPenjualan from './Administrator/DetailLaporanPenjualan.vue';
import Kasir from './Kasir/DashbordKasir.vue';
import ProdukKasir from './Kasir/produk.vue';
import Transaksi from './Kasir/Transaksi.vue';
import PenjualanProduk from './Administrator/PenjualanProduk.vue'
import KonfirmasiAkun from './Administrator/KonfirmasiAkun.vue'
import Profile from './Profile.vue'
import ForgotPassword from './ForgotPassword.vue'
import UbahPassword from './UbahPassword.vue'
import KelolaBarangTitipan from './Kasir/KelolaBarangTitipan.vue'
import PenambahanBarangPenitip from './Kasir/PenambahanBarangPenitip.vue'
import DetailPenitip from './Kasir/DetailTitipan.vue'
import PrintBarcode from './Kasir/PrintBarcode.vue'
import LaporonPenitipan from './Administrator/LaporanTitipan'
import LaporonDetailPenitipan from './Administrator/DetailLaporanTitipan.vue'
import Pembelian from './Administrator/PembelianProduk.vue'
import DashboardForAdmin from './DashboardForAdmin.vue'
import DaftarUser from './DaftarUser.vue'
import RiwayatTitipan from "./Kasir/RiwayatTitipan";
import DetailBarangTitipan from "./Kasir/DetailBarangTitipan";

Vue.use(VueRoute)

const router = new VueRoute({
    routes: [
        {
            path: '/',
            component: Login,
            meta: {
                forVisitors: true
            }
        },
        {
            path: '/ubahpassword/:id',
            component: UbahPassword,
            meta: {
                forVisitors: true
            }
        },
        {
            path: '/forgotpassword',
            component: ForgotPassword,
            meta: {
                forVisitors: true
            }
        },
        {
            path: '/register',
            component: Register,
            meta: {
                forVisitors: true,
            }
        },
        {
            path: '/profile',
            component: Profile,
            meta: {
                profile: true,
            }
        },
        {
            path: '/dashboard',
            component: Dashboard,
            meta: {
                forAuth: true,
                isAdmin: true
            }
        },
        {
            path: '/dashboardAdministrator',
            component: DashboardForAdmin,
            meta: {
                forAuth: true,
                forAdmin: true
            }
        },
        {
            path: '/daftarUser',
            component: DaftarUser,
            meta: {
                forAuth: true,
                forAdmin: true
            }
        },
        {
            path: '/konfirmasi',
            component: KonfirmasiAkun,
            meta: {
                forAuth: true,
                isAdmin: true
            }
        },
        {
            path: '/inventory',
            component: Inventory,
            meta: {
                forAuth: true,
                isAdmin: true
            }
        }, {
            path: '/laporan/penjualan',
            component: PenjualanProduk,
            meta: {
                forAuth: true,
                isAdmin: true
            }
        },
        {
            path: '/form',
            component: Form,
            meta: {
                forAuth: true
            }
        },
        {
            path: '/inventory/pemasukan',
            component: Pemasukan,
            meta: {
                forAuth: true,
                isAdmin: false
            }
        },
        {
            path: '/laporan/pemasukan',
            component: LaporonPemasukanProduk,
            meta: {
                forAuth: true,
                isAdmin: true
            }
        },
        {
            path: '/pembelianterbanyak',
            component: Pembelian,
            meta: {
                forAuth: true,
                isAdmin: true
            }
        },
        {
            path: '/laporan/penitipan',
            component: LaporonPenitipan,
            meta: {
                forAuth: true,
                isAdmin: true
            }
        },
        {
            path: '/laporan/penitipan/detail/:id',
            component: LaporonDetailPenitipan,
            meta: {
                forAuth: true,
                isAdmin: true
            }
        },
        {
            path: '/laporan/pemasukanProduk/detail/:id',
            component: DetailPemasukan,
            meta: {
                forAuth: true,
                isAdmin: true
            }
        },
        {
            path: '/laporan/penjualanProduk/detail/:id',
            component: DetailPenjualan,
            meta: {
                forAuth: true,
                isAdmin: true
            }
        },
        {
            path: '/kasir',
            component: Kasir,
            meta: {
                forAuth: true,
            }
        },
        {
            path: '/barcode/:id',
            component: PrintBarcode,
            meta: {
                forAuth: true,
            }
        },
        {
            path: '/produk',
            component: ProdukKasir,
            meta: {
                forAuth: true,
                isAdmin: false
            }
        },
        {
            path: '/transaksi',
            component: Transaksi,
            meta: {
                forAuth: true,
                isAdmin: false
            }
        },
        {
            path: '/Kelolatitipan',
            component: KelolaBarangTitipan,
            meta: {
                forAuth: true,
                isAdmin: false
            }
        },
        {
            path: '/penambahanBarangPenitip/:id',
            component: PenambahanBarangPenitip,
            meta: {
                forAuth: true,
                isAdmin: false
            }
        },
        {
            path: '/detailpenitip/:id',
            component: DetailPenitip,
            meta: {
                forAuth: true,
                isAdmin: false
            }
        },
        {
            path: '/riwayat',
            component: RiwayatTitipan,
            meta: {
                forAuth: true,
                isAdmin: false
            }
        },
        {
            path: '/detail/:id',
            component: DetailBarangTitipan,
            meta: {
                forAuth: true,
                isAdmin: false
            }
        }
    ]
})
export default router

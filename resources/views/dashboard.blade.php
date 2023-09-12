@extends('main')

@section('content')
<section class="container p-t-80">
    <div class="py-12" id="shops">
    <p><iframe src="//batchgeo.com/map/004825a9bb076b8afd87ed66f973fbac" frameborder="0" width="100%" height="550" sandbox="allow-top-navigation allow-scripts allow-popups allow-same-origin allow-modals allow-forms" style="border:1px solid #aaa;"></iframe></p>            <a type="button" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04" href="https://batchgeo.com/map/53c8a6e4382a2c1b1c7172928d15c6c9">Xem chi tiết bản đồ tại đây</a>
        </p>
            <div>
                <form class="d-flex align-items-center justify-content-center"
                    v-on:submit.prevent="fetchShops">
                    <input class="py-4 px-6"
                            placeholder="Tên cửa hàng..."
                            v-model="shopName" />
                        <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">Tìm kiếm</button>
                </form>
            </div>
            
            <div class="row isotope-grid" v-for="shop in shops"
                            :key="shop.id">
                        <div class="shadow-lg p-3 mb-5 bg-white rounded border" >
                            <!-- Block2 -->
                            <div class="block2">
                                <div class="block2-txt flex-w flex-t p-t-14">
                                    <div class="block2-txt-child1 flex-col-l ">
                                        <h3
                                        class="p-b-6"><strong>
                                            @{{ shop.name }}
                                        </strong>
                                        </h3>

                                        <span class="stext-105 cl3">
                                            Cách xa
                                        @{{ parseInt(shop.distance).toLocaleString() }} m
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
        </div>
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="https://unpkg.com/vue@3.1.1/dist/vue.global.prod.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0-alpha.1/axios.min.js" integrity="sha512-xIPqqrfvUAc/Cspuj7Bq0UtHNo/5qkdyngx6Vwt+tmbvTLDszzXM0G6c91LXmGrRx8KEPulT+AfOOez+TeVylg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            Vue.createApp({
                data() {
                    return {
                        shopName: "",
                        long: "",
                        lat: "",
                        shops: [],
                        loading: false,
                        locationErrorMessage: "",
                    }
                },
                methods: {
                    fetchShops() {
                        this.loading = true;
                        axios.get(`/nearest-dashboard`, {
                            params: {
                                shopName: this.shopName,
                                long: this.long,
                                lat: this.lat,
                            }
                        }).then(res => {
                            this.shops = res.data.shops;
                        }).finally(() => {
                            this.loading = false;
                        })
                    },

                    getLocation(closure) {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition((position) => {
                                this.long = position.coords.longitude;
                                this.lat = position.coords.latitude;
                                this.locationErrorMessage = "";

                                closure()
                            }, (error) => {
                                if (error.code === 1) {
                                    this.locationErrorMessage = "Please allow location access.";
                                }
                            });
                        } else { 
                            x.innerHTML = "Geolocation is not supported by this browser.";
                        }
                    },
                },
                mounted() {
                    this.getLocation(() => {
                        this.fetchShops();
                    });
                },
            }).mount('#shops');
        </script>
</section>
@endsection


    